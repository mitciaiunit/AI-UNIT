<?php

declare(strict_types=1);

/**
 * PDO connection factory. Values default to a stock XAMPP/EasyPHP MySQL install
 * (root user, empty password, localhost) and can be overridden via .env or
 * real environment variables — see .env.example.
 *
 * The DATABASE_* names are the current convention (see .env.example); the
 * older DB_* names are still honoured as a fallback so a deployment that
 * already set those directly (e.g. via Apache's SetEnv) keeps working
 * without needing to be updated at the same time as this file.
 */

return [
    'driver' => 'mysql',
    'host' => env('DATABASE_HOST', env('DB_HOST', '127.0.0.1')),
    'port' => env('DATABASE_PORT', env('DB_PORT', '3306')),
    'database' => env('DATABASE_NAME', env('DB_NAME', 'ai_unit')),
    'username' => env('DATABASE_USERNAME', env('DB_USER', 'root')),
    'password' => env('DATABASE_PASSWORD', env('DB_PASS', '')),
    'charset' => 'utf8mb4',
];
