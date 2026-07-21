<?php

declare(strict_types=1);

use App\Core\Router;

require dirname(__DIR__) . '/bootstrap.php';

$router = new Router();
require dirname(__DIR__) . '/routes/web.php';

$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/public/index.php'));

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/', $basePath);
