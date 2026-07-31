<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\AdminUser;

/**
 * Persistence for admin_users. Lookups are by username only - there is no
 * listing method, because nothing in the app needs to enumerate accounts.
 */
final class AdminUserRepository
{
    public function findActiveByUsername(string $username): ?AdminUser
    {
        $statement = Database::connection()->prepare(
            'SELECT id, username, password_hash, display_name, is_active
             FROM admin_users
             WHERE username = :username AND is_active = 1'
        );
        $statement->execute(['username' => $username]);
        $row = $statement->fetch();

        return $row === false ? null : AdminUser::fromRow($row);
    }

    public function findActiveById(int $id): ?AdminUser
    {
        $statement = Database::connection()->prepare(
            'SELECT id, username, password_hash, display_name, is_active
             FROM admin_users
             WHERE id = :id AND is_active = 1'
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row === false ? null : AdminUser::fromRow($row);
    }

    public function touchLastLogin(int $id): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE admin_users SET last_login_at = CURRENT_TIMESTAMP WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
    }

    /** Used by tools/create-admin.php; there is no self-service registration. */
    public function create(string $username, string $passwordHash, ?string $displayName): int
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare(
            'INSERT INTO admin_users (username, password_hash, display_name)
             VALUES (:username, :password_hash, :display_name)'
        );
        $statement->execute([
            'username' => $username,
            'password_hash' => $passwordHash,
            'display_name' => $displayName,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public function updatePassword(int $id, string $passwordHash): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE admin_users SET password_hash = :password_hash WHERE id = :id'
        );
        $statement->execute(['id' => $id, 'password_hash' => $passwordHash]);
    }

    public function usernameExists(string $username): bool
    {
        $statement = Database::connection()->prepare(
            'SELECT COUNT(*) FROM admin_users WHERE username = :username'
        );
        $statement->execute(['username' => $username]);

        return (int) $statement->fetchColumn() > 0;
    }
}
