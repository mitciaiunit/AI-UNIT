<?php

declare(strict_types=1);

/**
 * Site-wide configuration. Read via the config() helper (app/Helpers/functions.php).
 * Keep every environment-specific or hardcoded-elsewhere value here.
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
        'base_url' => getenv('APP_BASE_URL') ?: ($repoRootUrl . '/public'),
        // Assets sit next to public/, not inside it, so asset links must NOT include "/public".
        'root_url' => getenv('APP_ROOT_URL') ?: $repoRootUrl,
        'asset_path' => '/assets',
        'contact_email' => 'aiunit@govmu.org',
        'contact_phone' => '(+230) 650 3000',
        'default_lang' => 'en',
    ],

    // Placeholder for the future DIVA API integration (not implemented yet).
    'diva' => [
        'api_url' => getenv('DIVA_API_URL') ?: 'http://127.0.0.1:8000/api/chat',
    ],

    'app' => [
        'env' => getenv('APP_ENV') ?: 'local',
        'debug' => filter_var(getenv('APP_DEBUG') ?: 'true', FILTER_VALIDATE_BOOLEAN),
    ],

    // Contact form notification email. Sending is off by default — until
    // EMAIL_ENABLED=true and SMTP_* are supplied, submissions are only saved
    // to the database (see App\Services\EmailService).
    'mail' => [
        'enabled' => filter_var(getenv('EMAIL_ENABLED') ?: 'false', FILTER_VALIDATE_BOOLEAN),
        'to_address' => getenv('CONTACT_EMAIL') ?: 'aiunit@govmu.org',
        'from_address' => getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@aiunit.govmu.org',
        'from_name' => getenv('MAIL_FROM_NAME') ?: 'AI Unit Website',
        'smtp' => [
            'host' => getenv('SMTP_HOST') ?: '',
            'port' => (int) (getenv('SMTP_PORT') ?: 587),
            'username' => getenv('SMTP_USERNAME') ?: '',
            'password' => getenv('SMTP_PASSWORD') ?: '',
            'encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls',
        ],
    ],

    // Contact form spam/abuse guards (App\Services\SpamGuard).
    'contact' => [
        'min_submit_seconds' => (int) (getenv('CONTACT_MIN_SUBMIT_SECONDS') ?: 3),
        'rate_limit_max' => (int) (getenv('CONTACT_RATE_LIMIT_MAX') ?: 5),
        'rate_limit_window_seconds' => (int) (getenv('CONTACT_RATE_LIMIT_WINDOW') ?: 600),
    ],
];
