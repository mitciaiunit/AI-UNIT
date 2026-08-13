<?php

declare(strict_types=1);

/**
 * Small set of global helpers shared by every view/controller. Loaded once
 * from bootstrap.php.
 */

/**
 * Read an environment variable, checking $_ENV, $_SERVER, and getenv() in
 * that order. Covers both phpdotenv's default behaviour (populates $_ENV
 * and $_SERVER from .env, see bootstrap.php) and variables set directly by
 * the web server (e.g. Apache's SetEnv), without one clobbering the other -
 * a real server-set value always wins over .env, since Dotenv::safeLoad()
 * never overwrites a variable that's already set.
 *
 * An empty string is treated the same as "not set" and falls back to
 * $default, matching how every config/*.php file already treated a blank
 * getenv() result before this helper existed.
 */
function env(string $key, mixed $default = null): mixed
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

    if ($value === false || $value === null || $value === '') {
        return $default;
    }

    return $value;
}

/**
 * Fetch a config value using dot notation, e.g. config('site.name').
 */
function config(string $key, mixed $default = null): mixed
{
    static $store = null;

    if ($store === null) {
        $store = require dirname(__DIR__, 2) . '/config/config.php';
    }

    $value = $store;
    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

/**
 * Build an absolute URL to a file under assets/ (e.g. asset('css/style.css')).
 *
 * Appends a `?v=<mtime>` cache-busting query string when the file exists on
 * disk, so browsers pick up changes to style.css/script.js immediately
 * instead of serving a stale cached copy after a deploy.
 */
function asset(string $path): string
{
    $relative = ltrim($path, '/');

    $url = rtrim((string) config('site.base_url'), '/')
        . '/assets/' . $relative;

    $absolutePath = dirname(__DIR__, 2) . '/public/assets/' . $relative;
    $mtime = @filemtime($absolutePath);

    return $mtime !== false ? $url . '?v=' . $mtime : $url;
}
/**
 * Build an absolute URL to an application route (e.g. url('video/1')).
 */
function url(string $path = ''): string
{
    $base = rtrim((string) config('site.base_url'), '/');
    $path = ltrim($path, '/');

    return $path === '' ? ($base === '' ? '/' : $base) : $base . '/' . $path;
}

/**
 * Human-readable size of a file under assets/ (e.g. "2.3 MB"), or null if the
 * file isn't present on disk. Computed from the real file rather than typed
 * in by hand, so it can never drift out of sync when a document is replaced -
 * unlike the page counts on the same document cards, which have to be
 * hand-maintained because nothing here parses PDF contents.
 */
function asset_filesize(string $path): ?string
{
    $absolutePath = dirname(__DIR__, 2) . '/public/assets/' . ltrim($path, '/');
    $bytes = @filesize($absolutePath);

    if ($bytes === false) {
        return null;
    }

    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    $size = (float) $bytes;

    while ($size >= 1024 && $i < count($units) - 1) {
        $size /= 1024;
        $i++;
    }

    $decimals = $i === 0 ? 0 : 1;

    return number_format($size, $decimals) . ' ' . $units[$i];
}

/**
 * Compose a <title> value consistent with the original pages' convention:
 * "{Page Title} - AI Unit, Ministry of ICT, Mauritius".
 */
function page_title(string $title = ''): string
{
    $full = (string) config('site.full_name');

    return $title === '' ? $full : $title . ' - ' . $full;
}

/**
 * Reserved for future use - not called anywhere yet.
 */
function redirect(string $path): never
{
    $location = preg_match('#^https?://#i', $path) === 1 || str_starts_with($path, '/')
        ? $path
        : url($path);
    header('Location: ' . $location);
    exit;
}

/**
 * htmlspecialchars shorthand for escaping dynamic values in templates.
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * The current session's CSRF token, for embedding in a form's hidden field.
 */
function csrf_token(): string
{
    return \App\Core\Csrf::token();
}
