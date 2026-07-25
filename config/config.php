<?php

declare(strict_types=1);

/**
 * Site-wide configuration. Read via the config() helper (app/Helpers/functions.php).
 * Keep every environment-specific or hardcoded-elsewhere value here.
 *
 * Values come from the environment via the env() helper (app/Helpers/functions.php),
 * which is populated from .env (see bootstrap.php) and/or real server-set
 * environment variables. See .env.example for every variable this file reads.
 */

// e.g. "/AI-UNIT/public" when the whole repo sits under htdocs and is
// reached via http://localhost/AI-UNIT/public/ (the front controller's URL).
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/public/index.php'));

// The repo root's own URL, one level above public/ — this is where the
// sibling assets/ directory is actually served from by Apache.
$repoRootUrl = rtrim((string) preg_replace('#/public$#', '', $scriptDir), '/');

return [
    'site' => [
        'name' => 'AI Unit',
        'full_name' => 'AI Unit - Ministry of ICT, Mauritius',
        'tagline' => 'Ministry of Information Technology, Communication and Innovation - Republic of Mauritius',
        // Routes are served through public/index.php, so route links need the "/public" segment.
        'base_url' => env('APP_BASE_URL', $repoRootUrl . '/public'),
        // Assets sit next to public/, not inside it, so asset links must NOT include "/public".
        'root_url' => env('APP_ROOT_URL', $repoRootUrl),
        'asset_path' => '/assets',
        'contact_email' => env('CONTACT_EMAIL', 'aiunit@govmu.org'),
        'contact_phone' => '(+230) 650 3000',
        'default_lang' => 'en',
    ],

    // Placeholder for the future DIVA API integration (not implemented yet).
    'diva' => [
        'api_url' => env('DIVA_API_URL', 'http://127.0.0.1:8000/api/chat'),
    ],

    'app' => [
        'env' => env('APP_ENV', 'local'),
        'debug' => filter_var(env('APP_DEBUG', 'true'), FILTER_VALIDATE_BOOLEAN),
    ],

    // Contact form notification email. Sending is off by default — until
    // EMAIL_ENABLED=true and SMTP_* are supplied, submissions are only saved
    // to the database (see App\Services\EmailService).
    'mail' => [
        'enabled' => filter_var(env('EMAIL_ENABLED', 'false'), FILTER_VALIDATE_BOOLEAN),
        'to_address' => env('CONTACT_EMAIL', 'aiunit@govmu.org'),
        'from_address' => env('MAIL_FROM_ADDRESS', 'no-reply@aiunit.govmu.org'),
        'from_name' => env('MAIL_FROM_NAME', 'AI Unit Website'),
        'smtp' => [
            'host' => env('SMTP_HOST', ''),
            'port' => (int) env('SMTP_PORT', 587),
            'username' => env('SMTP_USERNAME', ''),
            'password' => env('SMTP_PASSWORD', ''),
            'encryption' => env('SMTP_ENCRYPTION', 'tls'),
        ],
    ],

    // Contact form spam/abuse guards (App\Services\SpamGuard).
    'contact' => [
        'min_submit_seconds' => (int) env('CONTACT_MIN_SUBMIT_SECONDS', 3),
        'rate_limit_max' => (int) env('CONTACT_RATE_LIMIT_MAX', 5),
        'rate_limit_window_seconds' => (int) env('CONTACT_RATE_LIMIT_WINDOW', 600),
    ],
];
