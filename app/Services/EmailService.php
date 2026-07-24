<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Logger;
use App\Models\ContactMessage;
use PHPMailer\PHPMailer\PHPMailer;
use Throwable;

/**
 * Contact form notification emails, sent over real SMTP via PHPMailer.
 *
 * Sending is entirely config-driven (see config('mail.*')) and off by
 * default: while EMAIL_ENABLED is false, submissions are only saved to the
 * database and this class does nothing but log that it was skipped.
 *
 * PHP's built-in mail() was tried first and dropped: on top of being
 * disabled by default, it has no way to authenticate with a real mail
 * provider and gives no diagnostic detail beyond a bare true/false, which
 * made "why didn't the email arrive" impossible to answer from the logs.
 * PHPMailer's SMTP transport talks directly to the configured mail server
 * with proper authentication and TLS/SSL, and throws a descriptive
 * exception (connection refused, auth rejected, etc.) that we can log.
 */
final class EmailService
{
    /**
     * @param array{host: string, port: int, username: string, password: string, encryption: string} $smtp
     */
    public function __construct(
        private readonly bool $enabled,
        private readonly string $toAddress,
        private readonly string $fromAddress,
        private readonly string $fromName,
        private readonly array $smtp,
    ) {
    }

    /**
     * Best-effort notification — failures are logged, not thrown, since a
     * failed notification email should never fail the whole submission
     * (the message is already saved to the database by the time this runs).
     */
    public function sendContactNotification(ContactMessage $contactMessage): bool
    {
        if (!$this->enabled) {
            Logger::info('Contact notification email skipped (EMAIL_ENABLED is false)', [
                'to' => $this->toAddress,
            ]);

            return false;
        }

        $mailer = new PHPMailer(true);

        try {
            $this->configureTransport($mailer);

            $mailer->setFrom($this->fromAddress, $this->fromName);
            $mailer->addAddress($this->toAddress);
            $mailer->addReplyTo($contactMessage->email, $contactMessage->fullName);

            $mailer->Subject = 'New contact form message'
                . ($contactMessage->subject !== null ? ': ' . $contactMessage->subject : '');
            $mailer->isHTML(false);
            $mailer->CharSet = PHPMailer::CHARSET_UTF8;
            $mailer->Body = $this->buildBody($contactMessage);

            $mailer->send();

            Logger::info('Contact notification email sent', [
                'to' => $this->toAddress,
                'reply_to' => $contactMessage->email,
                'smtp_host' => $this->smtp['host'],
            ]);

            return true;
        } catch (Throwable $e) {
            // $mailer->ErrorInfo carries PHPMailer's own diagnostic text
            // (e.g. "SMTP connect() failed", "SMTP Error: Could not
            // authenticate.") — that's the useful part for debugging
            // delivery problems, so it's logged ahead of the exception
            // message, which is often just a generic wrapper string.
            Logger::error('Contact notification email failed to send', [
                'to' => $this->toAddress,
                'smtp_host' => $this->smtp['host'],
                'smtp_port' => $this->smtp['port'],
                'error' => $mailer->ErrorInfo !== '' ? $mailer->ErrorInfo : $e->getMessage(),
            ]);

            return false;
        }
    }

    private function configureTransport(PHPMailer $mailer): void
    {
        $mailer->isSMTP();
        $mailer->Host = $this->smtp['host'];
        $mailer->Port = $this->smtp['port'];

        $mailer->SMTPAuth = $this->smtp['username'] !== '';
        if ($mailer->SMTPAuth) {
            $mailer->Username = $this->smtp['username'];
            $mailer->Password = $this->smtp['password'];
        }

        $mailer->SMTPSecure = match ($this->smtp['encryption']) {
            'ssl' => PHPMailer::ENCRYPTION_SMTPS,
            'tls' => PHPMailer::ENCRYPTION_STARTTLS,
            default => '',
        };
        if ($mailer->SMTPSecure === '') {
            $mailer->SMTPAutoTLS = false;
        }
    }

    private function buildBody(ContactMessage $contactMessage): string
    {
        return "You have a new message from the AI Unit website contact form.\n\n"
            . "Name: {$contactMessage->fullName}\n"
            . "Email: {$contactMessage->email}\n"
            . 'Topic: ' . ($contactMessage->subject ?? 'N/A') . "\n\n"
            . "Message:\n{$contactMessage->message}\n";
    }
}
