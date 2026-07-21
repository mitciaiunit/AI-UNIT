<?php

declare(strict_types=1);

/**
 * Route table. $router is provided by public/index.php.
 *
 * @var \App\Core\Router $router
 */

use App\Controllers\BookletController;
use App\Controllers\DocumentController;
use App\Controllers\PageController;
use App\Controllers\VideoController;

$router->get('/', [PageController::class, 'home']);
$router->get('/home', [PageController::class, 'home']);
$router->get('/privacy-policy', [PageController::class, 'privacyPolicy']);
$router->get('/disclaimer', [PageController::class, 'disclaimer']);
$router->get('/cookie-policy', [PageController::class, 'cookiePolicy']);
$router->get('/accessibility', [PageController::class, 'accessibility']);

$router->get('/document/{slug}', [DocumentController::class, 'show']);

$router->get('/video/{id}', [VideoController::class, 'show']);

$router->get('/booklet/{slug}', [BookletController::class, 'show']);
