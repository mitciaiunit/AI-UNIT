<?php

declare(strict_types=1);

/**
 * Application bootstrap: autoloading, error reporting, session, and helpers.
 * Required once by public/index.php before the router dispatches.
 */

spl_autoload_register(static function (string $class): void {
    if (!str_starts_with($class, 'App\\')) {
        return;
    }

    $relative = substr($class, strlen('App\\'));
    $path = __DIR__ . '/app/' . str_replace('\\', '/', $relative) . '.php';

    if (is_file($path)) {
        require $path;
    }
});

// Composer is used only for third-party libraries (currently PHPMailer for
// SMTP mail delivery, and phpdotenv below). The app's own App\ classes are
// still autoloaded above, with no Composer/PSR-4 involvement.
$composerAutoload = __DIR__ . '/vendor/autoload.php';
if (is_file($composerAutoload)) {
    require $composerAutoload;
}

// Load .env into $_ENV/$_SERVER, if both the library and the file are
// present. safeLoad() (rather than load()) means a missing .env is not a
// fatal error - a deployment that sets real environment variables directly
// (e.g. Apache's SetEnv) instead of shipping a .env file keeps working.
// Dotenv never overwrites a variable that's already set, so real
// server-level env vars always take precedence over .env either way.
//
// safeLoad() only guards against a *missing* file - a malformed one (e.g. an
// unquoted value containing a space) still throws, and a typo in .env
// should never be able to take the whole site down. The try/catch below is
// that second safety net: on a parse error, every config/*.php fallback
// default still applies, exactly as if .env were absent.
if (class_exists(\Dotenv\Dotenv::class) && is_file(__DIR__ . '/.env')) {
    try {
        \Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();
    } catch (\Throwable $e) {
        \App\Core\Logger::error('Failed to parse .env - falling back to config defaults', ['error' => $e->getMessage()]);
    }
}

require __DIR__ . '/app/Helpers/functions.php';

$config = require __DIR__ . '/config/config.php';

error_reporting(E_ALL);
ini_set('display_errors', $config['app']['debug'] ? '1' : '0');

// Errors must still be recorded when they are no longer shown - otherwise
// switching APP_DEBUG off (now the default) would make failures silent rather
// than private. storage/ is denied to the browser by its own .htaccess.
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/storage/logs/php-error.log');

\App\Core\SecurityHeaders::send();

if (session_status() === PHP_SESSION_NONE) {
    /*
     * Session cookie hardening. Set before session_start(), which is the only
     * point at which these take effect.
     *
     * use_strict_mode makes PHP reject a session ID it did not issue itself,
     * which closes session fixation - without it, an attacker can pick the ID
     * and hand it to a victim. use_only_cookies stops PHP falling back to a
     * session ID in the query string, where it would leak through referers,
     * logs and shared links.
     */
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    session_set_cookie_params([
        // Expires with the browser session.
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        /*
         * Only mark the cookie Secure when the request actually arrived over
         * HTTPS. Hardcoding true would stop the admin login working on XAMPP
         * over plain http://localhost, because the browser would refuse to
         * send the cookie back at all.
         */
        'secure' => request_is_https(),
        // Not readable from JavaScript, so an XSS cannot exfiltrate the session.
        'httponly' => true,
        /*
         * Lax rather than Strict: the site links out to Calendly and the AI
         * Marketplace, and Strict would drop the session on the way back,
         * silently signing an admin out. Lax still blocks the cross-site POST
         * that CSRF depends on, and App\Core\Csrf remains the primary defence.
         */
        'samesite' => 'Lax',
    ]);

    session_start();
}
