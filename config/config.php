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
$scriptDir = PHP_SAPI === 'cli-server'
    ? ''
    : str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/public/index.php'));

// The repo root's own URL, one level above public/ - this is where the
// sibling assets/ directory is actually served from by Apache.
$repoRootUrl = rtrim((string) preg_replace('#/public$#', '', $scriptDir), '/');
$defaultBaseUrl = PHP_SAPI === 'cli-server' ? $repoRootUrl : $repoRootUrl . '/public';

return [
    'site' => [
        'name' => 'AI Unit',
        'full_name' => 'AI Unit - Ministry of ICT, Mauritius',
        'tagline' => 'Ministry of Information Technology, Communication and Innovation - Republic of Mauritius',
        // Routes are served through public/index.php, so route links need the "/public" segment.
        'base_url' => env('APP_BASE_URL', $defaultBaseUrl),
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

    // AI Lab. Booking is handled entirely by Calendly - the site stores no
    // booking data and has no booking tables.
    'ai_lab' => [
        /*
         * The unit's public Calendly scheduling link, e.g.
         *   https://calendly.com/ai-unit-mauritius/ai-lab-session
         *
         * Deliberately empty by default rather than carrying an invented URL:
         * an empty value makes the page render a clearly-marked "not yet
         * available" state, whereas a plausible-looking wrong URL would send
         * the public to somebody else's calendar. Set CALENDLY_AI_LAB_URL in
         * .env once the real link exists - no code change is needed.
         *
         * This is a public scheduling link, safe to appear in page source. No
         * Calendly API key or token belongs here or anywhere in the frontend.
         */
        'calendly_url' => env('CALENDLY_AI_LAB_URL', ''),
    ],

    'app' => [
        'env' => env('APP_ENV', 'local'),
        'debug' => filter_var(env('APP_DEBUG', 'true'), FILTER_VALIDATE_BOOLEAN),
    ],

    // Contact form notification email. Sending is off by default - until
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

    // Highlights CMS (App\Services\ImageUploadService, App\Services\AuthService).
    'highlights' => [
        // Where uploaded gallery images are written. Kept out of assets/ on
        // purpose: assets/ is version-controlled editorial content that page
        // templates reference directly, and an admin deleting a gallery item
        // must never be able to remove a file the page itself depends on.
        'upload_dir' => dirname(__DIR__) . '/uploads/highlights',
        'upload_url' => $repoRootUrl . '/uploads/highlights',
        'max_upload_bytes' => (int) env('HIGHLIGHTS_MAX_UPLOAD_BYTES', 5 * 1024 * 1024),
        // Allow-list, not a block-list: anything not named here is rejected.
        // Keys are the MIME types detected from the file's own contents by
        // finfo - never the browser-supplied Content-Type, which is trivially
        // forged - and the values are the extension each one is saved with.
        'allowed_image_types' => [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ],
    ],

    'admin' => [
        // Sign-in is dropped after this long without a request.
        'session_idle_timeout' => (int) env('ADMIN_SESSION_IDLE_TIMEOUT', 1800),
        'login_max_attempts' => (int) env('ADMIN_LOGIN_MAX_ATTEMPTS', 5),
        'login_lockout_seconds' => (int) env('ADMIN_LOGIN_LOCKOUT_SECONDS', 900),
    ],

    // Contact form spam/abuse guards (App\Services\SpamGuard).
    'contact' => [
        'min_submit_seconds' => (int) env('CONTACT_MIN_SUBMIT_SECONDS', 3),
        'rate_limit_max' => (int) env('CONTACT_RATE_LIMIT_MAX', 5),
        'rate_limit_window_seconds' => (int) env('CONTACT_RATE_LIMIT_WINDOW', 600),
    ],
];
