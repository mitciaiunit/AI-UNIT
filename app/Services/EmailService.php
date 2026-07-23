<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Logger;
use App\Models\ContactMessage;

/**
 * Contact form notification emails.
 *
 * Sending is entirely config-driven (see config('mail.*')) and off by
 * default: while EMAIL_ENABLED is false, submissions are only saved to the
 * database and this class does nothing. When it's turned on, this uses
 * PHP's built-in mail() as a dependency-free default transport — the SMTP
 * settings are already read from config so a future real SMTP client can
 * be dropped in here without changing any calling code.
 */
final class EmailService
{
    public function __construct(
        private readonly bool $enabled,
        private readonly string $toAddress,
        private readonly string $fromAddress,
        private readonly string $fromName,
    ) {
    }

    /**
     * Best-effort notification — failures are logged, not thrown, since a
     * failed notification email should never fail the whole submission
     * (the message is already saved by the time this runs).
     */
    public function sendContactNotification(ContactMessage $contactMessage): bool
    {
        if (!$this->enabled) {
            return false;
        }

        $subject = 'New contact form message' . ($contactMessage->subject !== null ? ': ' . $contactMessage->subject : '');

        $body = "You have a new message from the AI Unit website contact form.\n\n"
            . "Name: {$contactMessage->fullName}\n"
            . "Email: {$contactMessage->email}\n"
            . 'Topic: ' . ($contactMessage->subject ?? 'N/A') . "\n\n"
            . "Message:\n{$contactMessage->message}\n";

        $headers = implode("\r\n", [
            sprintf('From: %s <%s>', $this->fromName, $this->fromAddress),
            'Reply-To: ' . $contactMessage->email,
            'Content-Type: text/plain; charset=UTF-8',
        ]);

        $sent = @mail($this->toAddress, $subject, $body, $headers);

        if (!$sent) {
            Logger::warning('Contact notification email failed to send', ['to' => $this->toAddress]);
        }

        return $sent;
    }
}
