<?php

declare(strict_types=1);

namespace App\Models;

/**
 * A validated, ready-to-persist contact form submission.
 *
 * Constructing one implies the data has already passed through
 * App\Services\ContactValidator — this class does not validate.
 */
final class ContactMessage
{
    public function __construct(
        public readonly string $fullName,
        public readonly string $email,
        public readonly ?string $subject,
        public readonly string $message,
        public readonly ?string $ipAddress,
        public readonly ?string $userAgent,
    ) {
    }
}
