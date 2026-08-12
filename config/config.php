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

/*
 * The URL prefix of public/, which is the entire browser-facing surface of the
 * site: the front controller, assets/ and uploads/ all live under it, and
 * nothing else in the repository is reachable at all.
 *
 * Derived from the request rather than configured, so one codebase serves both
 * supported deployments without editing anything:
 *
 *   DocumentRoot = <repo>/public   SCRIPT_NAME "/index.php"
 *                                  -> "" , so URLs are "/highlights"
 *   htdocs/AI-UNIT -> <repo>       SCRIPT_NAME "/AI-UNIT/public/index.php"
 *                                  -> "/AI-UNIT/public"
 *
 * dirname() of a top-level script returns "/", which rtrim reduces to "" so
 * concatenation cannot produce a doubled slash.
 *
 * The cli-server branch is not cosmetic. Under `php -S ... router.php` the
 * built-in server reports SCRIPT_NAME as the requested path, so dirname() of a
 * nested route like "/video/1" would wrongly yield "/video"; the prefix is
 * always empty there because the server is rooted at public/ (-t public).
 */
$publicUrl = PHP_SAPI === 'cli-server'
    ? ''
    : rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/');

return [
    'site' => [
        'name' => 'AI Unit',
        'full_name' => 'AI Unit - Ministry of ICT, Mauritius',
        'tagline' => 'Ministry of Information Technology, Communication and Innovation - Republic of Mauritius',
        /*
         * The one URL base for everything the browser sees - routes, assets
         * and uploads alike. Set APP_BASE_URL only when auto-detection cannot
         * work, e.g. behind a reverse proxy that rewrites the path.
         *
         * There used to be a second key, root_url, pointing one level above
         * public/ because assets/ lived outside it. Assets are inside public/
         * now, so the two bases collapsed into this one; APP_ROOT_URL is gone
         * from .env.example with it.
         */
        'base_url' => env('APP_BASE_URL', $publicUrl),
        /*
         * Absolute origin ("https://example.govmu.org") used for canonical and
         * Open Graph URLs, which have to be absolute.
         *
         * Blank means "work it out from the request", which is right while the
         * site answers on one hostname. Set it once the production hostname is
         * fixed, or if the site is reachable at more than one - a canonical is
         * only useful if it names ONE address, and a request cannot know which
         * of several is the preferred one.
         *
         * Deliberately empty rather than carrying a plausible government
         * domain: no approved hostname is recorded anywhere in this repository,
         * and a guessed one would be published in every page's metadata.
         */
        'canonical_origin' => env('APP_CANONICAL_ORIGIN', ''),
        'asset_path' => '/assets',
        'contact_email' => env('CONTACT_EMAIL', 'aiunit@govmu.org'),
        'contact_phone' => '(+230) 650 3000',
        'default_lang' => 'en',
    ],

    'diva' => [
        /*
         * The chat backend DIVA posts to - the AI Unit's proxy, set as
         * DIVA_API_URL in .env.
         *
         * Empty by default, never a loopback address. The previous default was
         * a local development URL, which is a safe-looking value that fails in
         * the worst possible way: a deployment shipped without a .env would
         * render that URL into the public page, and every visitor's browser
         * would post their conversation to a port on their OWN machine. Nobody
         * would see an error on the server, because no request ever reached it.
         *
         * Empty means "not configured", which the site now states plainly
         * instead of guessing - see diva_api_url() in app/Helpers/functions.php
         * and includes/diva-widget.php. A developer running a local proxy sets
         * DIVA_API_URL explicitly; the address belongs in their .env, not in a
         * default that ships to production.
         */
        'api_url' => env('DIVA_API_URL', ''),
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
        /*
         * Defaults to FALSE, so the unsafe state is the one you have to ask
         * for. A production deployment that ships without a .env - or with one
         * that omits this key - shows a blank page and logs the error instead
         * of printing the stack trace, file paths and configuration values
         * that PHP's error output includes.
         *
         * Local development sets APP_DEBUG=true explicitly in .env (see
         * .env.example). Errors are written to storage/logs/php-error.log
         * either way; see bootstrap.php.
         */
        'debug' => filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN),
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
        //
        // Both now sit under public/, which is the only tree Apache serves.
        // The database stores a bare filename (highlight_images.file_name),
        // never a URL, so relocating this directory needs no data migration -
        // see App\Services\ImageUploadService::url().
        'upload_dir' => dirname(__DIR__) . '/public/uploads/highlights',
        'upload_url' => $publicUrl . '/uploads/highlights',
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
