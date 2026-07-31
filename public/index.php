<?php

declare(strict_types=1);

use App\Core\Router;

require dirname(__DIR__) . '/bootstrap.php';

$router = new Router();
require dirname(__DIR__) . '/routes/web.php';

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestPath = parse_url($requestUri, PHP_URL_PATH) ?: '/';
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/public/index.php');

if (PHP_SAPI === 'cli-server') {
    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $requestUri, '');

    return;
}

/*
 * The front controller is reachable two ways, and each leaves a different
 * prefix in front of the route:
 *
 *   /AI-UNIT/public/booklet/aim             mod_rewrite hides index.php
 *   /AI-UNIT/public/index.php/booklet/aim   index.php addressed directly
 *
 * SCRIPT_NAME is "/AI-UNIT/public/index.php" either way, so the prefix to strip
 * is the script itself when the URL names it and the directory containing it
 * otherwise. Deriving it from the request keeps one route table valid for both
 * forms, rather than registering "/index.php/..." duplicates.
 */
$addressesScriptDirectly = $requestPath === $scriptName
    || str_starts_with($requestPath, $scriptName . '/');

$basePath = $addressesScriptDirectly ? $scriptName : dirname($scriptName);

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $requestUri, $basePath);
