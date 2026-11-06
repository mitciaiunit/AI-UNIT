<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Logger;
use App\Core\View;
use App\Repositories\ContactMessageRepository;
use App\Services\ContactService;
use App\Services\ContactValidator;
use App\Services\EmailService;
use App\Services\SpamGuard;
use Throwable;

/**
 * HTTP adapter for the contact form: reads the request, checks CSRF, hands
 * everything else to ContactService, and renders the result. No business logic
 * or SQL lives here.
 *
 * The form posts two ways and each needs a different response:
 *
 *   - assets/js/script.js sends X-Requested-With and expects JSON
 *   - the same <form method="post" action="/contact"> submitted with
 *     JavaScript disabled expects a page
 *
 * Answering both with JSON left a visitor without JavaScript looking at a raw
 * JSON document as their confirmation screen, so the response is negotiated on
 * that header rather than assumed.
 */
final class ContactController extends Controller
{
    public function submit(): void
    {
        if (!Csrf::isValid($_POST['csrf_token'] ?? null)) {
            $this->respond(
                false,
                [],
                'Your session has expired. Please refresh the page and try again.',
                403,
            );

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
            $this->respond(
                false,
                [],
                'Something went wrong on our end. Please try again shortly, or email us directly.',
                500,
            );

            return;
        }

        $this->respond(
            (bool) $result['success'],
            (array) $result['errors'],
            (string) $result['message'],
            (int) $result['status'],
        );
    }

    /**
     * Sends the outcome as JSON to a scripted submission, or as a rendered page
     * to a plain form post.
     *
     * @param array<string, string> $errors
     */
    private function respond(bool $success, array $errors, string $message, int $status): void
    {
        if ($this->expectsJson()) {
            $this->jsonResponse([
                'success' => $success,
                'errors' => $errors,
                'message' => $message,
            ], $status);

            return;
        }

        http_response_code($status);
        View::render('contact-result', [
            'success' => $success,
            'errors' => $errors,
            'message' => $message,
            'noindex' => true,
        ], 'app');
    }

    /**
     * True for the fetch in assets/js/script.js, which sets this header
     * explicitly. Deliberately not sniffing Accept: a browser form post sends
     * an Accept list containing star/star, so treating that as an API request
     * is exactly the bug this replaces.
     */
    private function expectsJson(): bool
    {
        return strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest';
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
                (array) config('mail.smtp'),
            ),
        );
    }
}
