<?php

declare(strict_types=1);

/**
 * Router script for PHP's built-in server (see README, "Quick check without
 * XAMPP"). Run it with public/ as the document root:
 *
 *     php -S 127.0.0.1:5600 -t public router.php
 *
 * The built-in server does not read .htaccess, so every rule Apache applies
 * has to be reproduced here or it simply does not exist in development. Before
 * public/ became the document root, this script resolved requests against the
 * repository root and served `/.env` in full.
 *
 * Now it resolves them against public/ only. That single change is what makes
 * the private tree unreachable: app/, config/, .env and the rest are not below
 * the served directory, so there is no path that reaches them. The containment
 * check below exists to keep it that way even when the request tries to climb
 * out with "..".
 */

/** The only directory this server will ever read a file from. */
$documentRoot = realpath(__DIR__ . '/public');

if ($documentRoot === false) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "public/ is missing - the document root does not exist.\n";

    return true;
}

$path = urldecode((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));

/*
 * Dotfiles are configuration even when they sit inside public/ -
 * public/.htaccess and public/assets/.htaccess are the ones that exist today.
 * Apache denies them via <FilesMatch "^\."> in public/.htaccess; this is the
 * same rule for the development server.
 */
if (preg_match('#(^|/)\.[^/]#', $path) === 1) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "403 Forbidden\n";

    return true;
}

if ($path !== '/') {
    $candidate = realpath($documentRoot . '/' . ltrim($path, '/'));

    /*
     * realpath() resolves "..", symlinks and Windows' path separators, so the
     * prefix test below is done on the true location of the file rather than
     * on the text of the request. A path that climbed above public/ resolves
     * to something outside $documentRoot and falls through to the router,
     * which 404s it - it is never read from disk.
     *
     * The separator is appended to $documentRoot so that a sibling directory
     * whose name merely starts with "public" cannot satisfy the test.
     */
    if (
        $candidate !== false
        && is_file($candidate)
        && str_starts_with($candidate, $documentRoot . DIRECTORY_SEPARATOR)
    ) {
        // Hand the file back to the built-in server, which sets Content-Type.
        return false;
    }
}

require __DIR__ . '/public/index.php';
