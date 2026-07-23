<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Logger;
use App\Repositories\ContactMessageRepository;
use App\Services\ContactService;
use App\Services\ContactValidator;
use App\Services\EmailService;
use App\Services\SpamGuard;
use Throwable;

/**
 * HTTP adapter for the contact form: reads the request, checks CSRF, hands
 * everything else to ContactService, and renders the result as JSON. No
 * business logic or SQL lives here.
 */
final class ContactController extends Controller
{
    public function submit(): void
    {
        if (!Csrf::isValid($_POST['csrf_token'] ?? null)) {
            $this->jsonResponse([
                'success' => false,
                'errors' => [],
                'message' => 'Your session has expired. Please refresh the page and try again.',
            ], 403);

            return;
        }

        try {
            $result = $this->service()->submit(
                $_POST,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null,
            );
        } catch (Throwable $e) {
            Logger::error('Unhandled contact form error', ['error' => $e->getMessage()]);
            $this->jsonResponse([
                'success' => false,
                'errors' => [],
                'message' => 'Something went wrong on our end. Please try again shortly, or email us directly.',
            ], 500);

            return;
        }

        $this->jsonResponse([
            'success' => $result['success'],
            'errors' => $result['errors'],
            'message' => $result['message'],
        ], $result['status']);
    }

    private function service(): ContactService
    {
        $contactConfig = config('contact');

        return new ContactService(
            new ContactValidator(),
            new SpamGuard(
                (int) $contactConfig['min_submit_seconds'],
                (int) $contactConfig['rate_limit_max'],
                (int) $contactConfig['rate_limit_window_seconds'],
            ),
            new ContactMessageRepository(),
            new EmailService(
                (bool) config('mail.enabled'),
                (string) config('mail.to_address'),
                (string) config('mail.from_address'),
                (string) config('mail.from_name'),
            ),
        );
    }
}
