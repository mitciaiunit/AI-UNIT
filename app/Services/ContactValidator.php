<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Validates and normalises raw contact form input.
 *
 * Field requirements mirror the current frontend exactly: name, email and
 * message are required; subject is an optional topic picker (no asterisk,
 * not enforced client-side), so it stays optional here too.
 */
final class ContactValidator
{
    private const MAX_NAME_LENGTH = 150;
    private const MAX_EMAIL_LENGTH = 255;
    private const MAX_SUBJECT_LENGTH = 150;
    private const MAX_MESSAGE_LENGTH = 5000;

    /**
     * @param array<string, mixed> $input Raw $_POST-style data.
     * @return array{values: array{fullName: string, email: string, subject: ?string, message: string}, errors: array<string, string>}
     */
    public function validate(array $input): array
    {
        $fullName = $this->trimmed($input['name'] ?? '');
        $email = $this->trimmed($input['email'] ?? '');
        $subject = $this->trimmed($input['subject'] ?? '');
        $message = $this->trimmed($input['message'] ?? '');

        $errors = [];

        if ($fullName === '') {
            $errors['name'] = 'Please enter your name.';
        } elseif (mb_strlen($fullName) > self::MAX_NAME_LENGTH) {
            $errors['name'] = 'Name is too long (maximum ' . self::MAX_NAME_LENGTH . ' characters).';
        }

        if ($email === '') {
            $errors['email'] = 'Please enter your email address.';
        } elseif (mb_strlen($email) > self::MAX_EMAIL_LENGTH) {
            $errors['email'] = 'Email address is too long.';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if ($subject !== '' && mb_strlen($subject) > self::MAX_SUBJECT_LENGTH) {
            $errors['subject'] = 'Topic is too long.';
        }

        if ($message === '') {
            $errors['message'] = 'Please enter a message.';
        } elseif (mb_strlen($message) > self::MAX_MESSAGE_LENGTH) {
            $errors['message'] = 'Message is too long (maximum ' . self::MAX_MESSAGE_LENGTH . ' characters).';
        }

        return [
            'values' => [
                'fullName' => $fullName,
                'email' => $email,
                'subject' => $subject === '' ? null : $subject,
                'message' => $message,
            ],
            'errors' => $errors,
        ];
    }

    /**
     * Trims whitespace and strips control characters (defends against
     * excessively long / null-byte-laden input); does not strip HTML,
     * since the correct place to guard against XSS is at output time via
     * the existing e() escaping helper, not by mangling stored input.
     */
    private function trimmed(mixed $value): string
    {
        if (!is_string($value)) {
            return '';
        }

        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $value) ?? $value;

        return trim($value);
    }
}
