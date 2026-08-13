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
 * Whether the current request reached us over HTTPS.
 *
 * Used by bootstrap.php to decide whether the session cookie may be marked
 * Secure. Getting this wrong in the "yes" direction breaks local XAMPP
 * development outright - the browser would stop sending the cookie over plain
 * http://localhost - so all three signals below are checked rather than
 * assuming a value for $_SERVER['HTTPS'].
 *
 * X-Forwarded-Proto is honoured because a government deployment is likely to
 * sit behind a TLS-terminating proxy, where it is the only remaining evidence
 * that the client used HTTPS. A forged header can only turn the Secure flag
 * ON, which locks the forger out of their own session rather than exposing
 * anyone else's.
 */
function request_is_https(): bool
{
    $https = $_SERVER['HTTPS'] ?? '';
    if ($https !== '' && strtolower((string) $https) !== 'off') {
        return true;
    }

    if ((int) ($_SERVER['SERVER_PORT'] ?? 0) === 443) {
        return true;
    }

    return strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
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
        . rtrim((string) config('site.asset_path'), '/')
        . '/' . $relative;

    // assets/ moved inside public/ when public/ became the document root, so
    // the on-disk lookup for the cache-busting mtime moved with it.
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
 * The site's absolute origin - "https://host[:port]", no trailing slash.
 *
 * Derived from the request unless APP_CANONICAL_ORIGIN is set. No production
 * domain is hardcoded: none is recorded anywhere in this repository, and
 * guessing one would put a wrong hostname into every canonical and og:url.
 *
 * Deriving it from the request is correct for a site reached at one hostname,
 * which is the case here. Set APP_CANONICAL_ORIGIN when that stops being true
 * - several hostnames pointing at the same site, or a TLS-terminating proxy
 * that changes the scheme - because then the request is no longer a reliable
 * witness to the canonical address.
 */
function site_origin(): string
{
    $configured = trim((string) config('site.canonical_origin', ''));
    if ($configured !== '') {
        return rtrim($configured, '/');
    }

    $host = (string) ($_SERVER['HTTP_HOST'] ?? '');
    if ($host === '') {
        return '';
    }

    return (request_is_https() ? 'https://' : 'http://') . $host;
}

/**
 * Absolute URL for a route, for <link rel="canonical"> and og:url.
 *
 * Built on url(), so it inherits that helper's base path and is correct both
 * at the document root and under a subdirectory - and cannot double the prefix,
 * because the prefix is applied exactly once, by url().
 *
 * Passing null uses the current request path with any query string removed:
 * tracking parameters and pagination noise must not appear in a canonical.
 */
function canonical_url(?string $path = null): string
{
    if ($path === null) {
        $requestPath = (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);

        return site_origin() . ($requestPath === '' ? '/' : $requestPath);
    }

    $url = url($path);

    /*
     * The homepage needs its trailing slash. url('/') returns the bare base -
     * "" at the document root, "/AI-UNIT" under a subdirectory - and a server
     * redirects that to the same address with a slash. A canonical that points
     * at a redirect is a canonical pointing at the wrong URL.
     */
    if ($path === '/' || $path === '') {
        $url = rtrim($url, '/') . '/';
    }

    return site_origin() . $url;
}

/**
 * Absolute URL for a file under assets/, for og:image - social scrapers do not
 * resolve relative paths.
 */
function asset_url(string $path): string
{
    return site_origin() . asset($path);
/**
 * Human-readable size of a file under assets/ (e.g. "2.3 MB"), or null if the
 * file isn't present on disk. Computed from the real file rather than typed
 * in by hand, so it can never drift out of sync when a document is replaced -
 * unlike the page counts on the same document cards, which have to be
 * hand-maintained because nothing here parses PDF contents.
 */
}
function asset_filesize(string $path): ?string
{
    $absolutePath = dirname(__DIR__, 2) . '/assets/' . ltrim($path, '/');
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
 * The DIVA chat endpoint, or an empty string when DIVA is not usable.
 *
 * Two callers need the same answer and must not be able to disagree about it:
 * includes/layouts/app.php hands the URL to the browser, and
 * includes/diva-widget.php decides whether to render the assistant as
 * available. A widget that says "unavailable" while the script still holds a
 * URL - or the reverse - is worse than either state on its own.
 *
 * A malformed value counts as unconfigured. There is nothing useful to do with
 * "yes" or "diva.example" except fail at the point where a visitor is already
 * typing a question, so it is treated the same as absent and reported honestly
 * up front.
 *
 * http is accepted as well as https: a developer running a local proxy needs
 * it, and DIVA_API_URL is set deliberately in their own .env. What no longer
 * exists is a localhost DEFAULT - see the note in config/config.php.
 */
function diva_api_url(): string
{
    $url = trim((string) config('diva.api_url', ''));
    if ($url === '') {
        return '';
    }

    $parts = parse_url($url);
    $scheme = strtolower((string) ($parts['scheme'] ?? ''));

    if (($parts['host'] ?? '') === '' || !in_array($scheme, ['http', 'https'], true)) {
        \App\Core\Logger::warning('DIVA_API_URL is set but is not a usable http(s) URL - DIVA will show as unavailable', [
            'value' => $url,
        ]);

        return '';
    }

    return $url;
}

/**
 * The current session's CSRF token, for embedding in a form's hidden field.
 */
function csrf_token(): string
{
    return \App\Core\Csrf::token();
}
