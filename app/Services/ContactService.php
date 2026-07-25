<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Logger;
use App\Models\ContactMessage;
use App\Repositories\ContactMessageRepository;

/**
 * Orchestrates a contact form submission: spam checks, validation,
 * persistence, and (optionally) an email notification.
 *
 * Controller -> Service -> Repository -> Database, per the project's
 * layering convention; this is the only class that ties those steps
 * together, so the controller stays a thin HTTP adapter.
 */
final class ContactService
{
    public function __construct(
        private readonly ContactValidator $validator,
        private readonly SpamGuard $spamGuard,
        private readonly ContactMessageRepository $repository,
        private readonly EmailService $emailService,
    ) {
    }

    /**
     * @param array<string, mixed> $input Raw $_POST data.
     * @return array{success: bool, errors: array<string, string>, message: string, status: int}
     */
    public function submit(array $input, ?string $ipAddress, ?string $userAgent): array
    {
        // Bot signals get an indistinguishable "success" response and are
        // silently dropped — never saved, never emailed — so automated
        // submitters get no feedback to learn from.
        if ($this->spamGuard->isHoneypotTriggered($input) || $this->spamGuard->isTooFast($input)) {
            Logger::warning('Contact submission blocked by spam guard', ['ip' => $ipAddress]);

            return $this->successResponse();
        }

        if ($this->spamGuard->tooManyAttempts()) {
            return [
                'success' => false,
                'errors' => [],
                'message' => "You're submitting too quickly. Please wait a few minutes and try again.",
                'status' => 429,
            ];
        }

        $validated = $this->validator->validate($input);
        if ($validated['errors'] !== []) {
            return [
                'success' => false,
                'errors' => $validated['errors'],
                'message' => 'Please correct the errors below.',
                'status' => 422,
            ];
        }

        $values = $validated['values'];
        $contactMessage = new ContactMessage(
            fullName: $values['fullName'],
            email: $values['email'],
            subject: $values['subject'],
            message: $values['message'],
            ipAddress: $ipAddress,
            userAgent: $userAgent !== null ? mb_substr($userAgent, 0, 255) : null,
        );

        $id = $this->repository->save($contactMessage);
        if ($id === null) {
            return [
                'success' => false,
                'errors' => [],
                'message' => 'Something went wrong on our end. Please try again shortly, or email us directly.',
                'status' => 500,
            ];
        }

        $this->emailService->sendContactNotification($contactMessage);

        return $this->successResponse();
    }

    /**
     * @return array{success: bool, errors: array<string, string>, message: string, status: int}
     */
    private function successResponse(): array
    {
        return [
            'success' => true,
            'errors' => [],
            'message' => "Thanks for reaching out! We've received your message and will get back to you soon.",
            'status' => 200,
        ];
    }
}
