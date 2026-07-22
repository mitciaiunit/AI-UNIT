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

require __DIR__ . '/app/Helpers/functions.php';

$config = require __DIR__ . '/config/config.php';

error_reporting(E_ALL);
ini_set('display_errors', $config['app']['debug'] ? '1' : '0');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
