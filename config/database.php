<?php

declare(strict_types=1);

/**
 * PDO connection factory. Values default to a stock XAMPP/EasyPHP MySQL install
 * (root user, empty password, localhost) and can be overridden with environment
 * variables for other setups.
 */

return [
    'driver' => 'mysql',
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => getenv('DB_PORT') ?: '3306',
    'database' => getenv('DB_NAME') ?: 'ai_unit',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
    'charset' => 'utf8mb4',
];
