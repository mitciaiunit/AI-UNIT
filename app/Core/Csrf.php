<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Session-bound CSRF token generation and validation.
 *
 * The token is created once per session and reused across requests/forms
 * during that session (it does not rotate per submission), which keeps a
 * single-page form like the homepage contact form working without needing
 * to refresh the token after each submit.
 */
final class Csrf
{
    private const SESSION_KEY = 'csrf_token';

    public static function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public static function isValid(?string $token): bool
    {
        if (!is_string($token) || $token === '' || empty($_SESSION[self::SESSION_KEY])) {
            return false;
        }

        return hash_equals($_SESSION[self::SESSION_KEY], $token);
    }
}
