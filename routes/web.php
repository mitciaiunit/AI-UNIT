<?php

declare(strict_types=1);

/**
 * Route table. $router is provided by public/index.php.
 *
 * @var \App\Core\Router $router
 */

use App\Controllers\BookletController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ImageController;
use App\Controllers\ContactController;
use App\Controllers\DocumentController;
use App\Controllers\HighlightsController;
use App\Controllers\PageController;
use App\Controllers\VideoController;

$router->get('/', [PageController::class, 'home']);
$router->get('/home', [PageController::class, 'home']);
$router->get('/privacy-policy', [PageController::class, 'privacyPolicy']);
$router->get('/disclaimer', [PageController::class, 'disclaimer']);
$router->get('/cookie-policy', [PageController::class, 'cookiePolicy']);
$router->get('/accessibility', [PageController::class, 'accessibility']);
$router->get('/ai-lab', [PageController::class, 'aiLab']);
$router->get('/highlights', [HighlightsController::class, 'index']);

/*
 * The page was called Student Corner until this branch. The old URL stays
 * alive as a permanent redirect so links already shared - or indexed - land
 * on the renamed page instead of a 404.
 */
$router->get('/student-corner', static function (): void {
    http_response_code(301);
    header('Location: ' . url('highlights'));
});

$router->get('/admin', [DashboardController::class, 'index']);
$router->get('/admin/login', [AuthController::class, 'showLogin']);
$router->post('/admin/login', [AuthController::class, 'login']);
$router->post('/admin/logout', [AuthController::class, 'logout']);

$router->get('/admin/categories', [CategoryController::class, 'index']);
$router->get('/admin/categories/new', [CategoryController::class, 'create']);
$router->post('/admin/categories', [CategoryController::class, 'store']);
$router->get('/admin/categories/{id}/edit', [CategoryController::class, 'edit']);
$router->post('/admin/categories/{id}', [CategoryController::class, 'update']);
$router->post('/admin/categories/{id}/delete', [CategoryController::class, 'delete']);
$router->post('/admin/categories/{id}/visibility', [CategoryController::class, 'toggleVisibility']);
$router->post('/admin/categories/{id}/move', [CategoryController::class, 'move']);

$router->get('/admin/images', [ImageController::class, 'index']);
$router->get('/admin/images/new', [ImageController::class, 'create']);
$router->post('/admin/images', [ImageController::class, 'store']);
$router->get('/admin/images/{id}/edit', [ImageController::class, 'edit']);
$router->post('/admin/images/{id}', [ImageController::class, 'update']);
$router->post('/admin/images/{id}/delete', [ImageController::class, 'delete']);
$router->post('/admin/images/{id}/visibility', [ImageController::class, 'toggleVisibility']);
$router->post('/admin/images/{id}/move', [ImageController::class, 'move']);

$router->post('/contact', [ContactController::class, 'submit']);

$router->get('/document/{slug}', [DocumentController::class, 'show']);

$router->get('/video/{id}', [VideoController::class, 'show']);

$router->get('/booklet/{slug}', [BookletController::class, 'show']);
