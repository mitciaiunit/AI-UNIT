<?php

declare(strict_types=1);

/**
 * Small set of global helpers shared by every view/controller. Loaded once
 * from bootstrap.php.
 */

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
 */
function asset(string $path): string
{
    return rtrim((string) config('site.root_url'), '/')
        . rtrim((string) config('site.asset_path'), '/')
        . '/' . ltrim($path, '/');
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
 * Compose a <title> value consistent with the original pages' convention:
 * "{Page Title} — AI Unit, Ministry of ICT, Mauritius".
 */
function page_title(string $title = ''): string
{
    $full = (string) config('site.full_name');

    return $title === '' ? $full : $title . ' — ' . $full;
}

/**
 * Reserved for future use (contact form, etc.) — not called anywhere yet.
 */
function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

/**
 * htmlspecialchars shorthand for escaping dynamic values in templates.
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
