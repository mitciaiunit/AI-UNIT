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
// fatal error — a deployment that sets real environment variables directly
// (e.g. Apache's SetEnv) instead of shipping a .env file keeps working.
// Dotenv never overwrites a variable that's already set, so real
// server-level env vars always take precedence over .env either way.
//
// safeLoad() only guards against a *missing* file — a malformed one (e.g. an
// unquoted value containing a space) still throws, and a typo in .env
// should never be able to take the whole site down. The try/catch below is
// that second safety net: on a parse error, every config/*.php fallback
// default still applies, exactly as if .env were absent.
if (class_exists(\Dotenv\Dotenv::class) && is_file(__DIR__ . '/.env')) {
    try {
        \Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();
    } catch (\Throwable $e) {
        \App\Core\Logger::error('Failed to parse .env — falling back to config defaults', ['error' => $e->getMessage()]);
    }
}

require __DIR__ . '/app/Helpers/functions.php';

$config = require __DIR__ . '/config/config.php';

error_reporting(E_ALL);
ini_set('display_errors', $config['app']['debug'] ? '1' : '0');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
