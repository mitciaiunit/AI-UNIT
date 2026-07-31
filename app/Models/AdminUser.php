<?php

declare(strict_types=1);

namespace App\Models;

/**
 * A member of staff who may sign in to the admin area.
 *
 * The password hash is carried here because App\Services\AuthService needs it
 * for password_verify(); nothing outside that service should read it, and it
 * is never passed to a view.
 */
final class AdminUser
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $username,
        public readonly string $passwordHash,
        public readonly ?string $displayName,
        public readonly bool $isActive,
    ) {
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (string) $row['username'],
            (string) $row['password_hash'],
            $row['display_name'] !== null ? (string) $row['display_name'] : null,
            (bool) $row['is_active'],
        );
    }

    public function label(): string
    {
        return $this->displayName ?? $this->username;
    }
}
