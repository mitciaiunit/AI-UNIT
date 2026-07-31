<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Logger;
use App\Models\AdminUser;
use App\Repositories\AdminUserRepository;

/**
 * Session-based sign-in for the admin area.
 *
 * Deliberately small: no roles, no registration, no password reset. It exists
 * so the Highlights upload endpoints are not open to the public.
 *
 * It is written against "an authenticated admin", not against Highlights, so a
 * future protected section only has to extend App\Controllers\Admin\AdminController
 * - nothing here needs to change.
 */
final class AuthService
{
    private const SESSION_USER_ID = 'admin_user_id';
    private const SESSION_LAST_SEEN = 'admin_last_seen';
    private const SESSION_ATTEMPTS = 'admin_login_attempts';
    private const SESSION_LOCKED_UNTIL = 'admin_locked_until';

    public function __construct(
        private readonly AdminUserRepository $users = new AdminUserRepository(),
    ) {
    }

    /**
     * @return bool True when the credentials were accepted and the session is
     *              now signed in.
     */
    public function attempt(string $username, string $password): bool
    {
        if ($this->isLockedOut()) {
            return false;
        }

        $user = $this->users->findActiveByUsername($username);

        /*
         * password_verify() runs even when no such user exists, against a
         * throwaway hash, so that a wrong username and a wrong password take
         * the same time to reject. Returning early on an unknown username
         * would let an attacker enumerate valid ones by timing.
         */
        $hash = $user?->passwordHash ?? '$2y$12$usqQTgcHtqBB9nRdCiOAeuQoQhVMLuLSPuqTvyj7A9ExVeAxdCTNC';
        $verified = password_verify($password, $hash);

        if ($user === null || !$verified) {
            $this->recordFailure($username);

            return false;
        }

        // Rehash if the cost factor or algorithm default has since changed.
        if (password_needs_rehash($user->passwordHash, PASSWORD_DEFAULT)) {
            $this->users->updatePassword((int) $user->id, password_hash($password, PASSWORD_DEFAULT));
        }

        $this->startSessionFor($user);
        $this->users->touchLastLogin((int) $user->id);
        Logger::info('Admin signed in', ['username' => $user->username]);

        return true;
    }

    /**
     * The signed-in admin, or null. Also enforces the idle timeout, so every
     * guarded request re-checks it.
     */
    public function user(): ?AdminUser
    {
        $id = $_SESSION[self::SESSION_USER_ID] ?? null;
        if (!is_int($id)) {
            return null;
        }

        $lastSeen = (int) ($_SESSION[self::SESSION_LAST_SEEN] ?? 0);
        $timeout = (int) config('admin.session_idle_timeout');

        if ($timeout > 0 && $lastSeen > 0 && (time() - $lastSeen) > $timeout) {
            $this->logout();

            return null;
        }

        // The account may have been deactivated since sign-in.
        $user = $this->users->findActiveById($id);
        if ($user === null) {
            $this->logout();

            return null;
        }

        $_SESSION[self::SESSION_LAST_SEEN] = time();

        return $user;
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function logout(): void
    {
        unset(
            $_SESSION[self::SESSION_USER_ID],
            $_SESSION[self::SESSION_LAST_SEEN]
        );

        // Drop the old session id so a captured one cannot be replayed.
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public function isLockedOut(): bool
    {
        $until = (int) ($_SESSION[self::SESSION_LOCKED_UNTIL] ?? 0);

        return $until > time();
    }

    public function lockoutSecondsRemaining(): int
    {
        return max(0, (int) ($_SESSION[self::SESSION_LOCKED_UNTIL] ?? 0) - time());
    }

    private function startSessionFor(AdminUser $user): void
    {
        // Fresh id on privilege change - the standard defence against an
        // attacker fixing the session id before sign-in.
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        $_SESSION[self::SESSION_USER_ID] = (int) $user->id;
        $_SESSION[self::SESSION_LAST_SEEN] = time();
        unset($_SESSION[self::SESSION_ATTEMPTS], $_SESSION[self::SESSION_LOCKED_UNTIL]);
    }

    private function recordFailure(string $username): void
    {
        $attempts = (int) ($_SESSION[self::SESSION_ATTEMPTS] ?? 0) + 1;
        $_SESSION[self::SESSION_ATTEMPTS] = $attempts;

        $max = (int) config('admin.login_max_attempts');
        if ($max > 0 && $attempts >= $max) {
            $_SESSION[self::SESSION_LOCKED_UNTIL] = time() + (int) config('admin.login_lockout_seconds');
            $_SESSION[self::SESSION_ATTEMPTS] = 0;
            Logger::error('Admin login locked out after repeated failures', ['username' => $username]);

            return;
        }

        Logger::info('Admin login failed', ['username' => $username, 'attempt' => $attempts]);
    }
}
