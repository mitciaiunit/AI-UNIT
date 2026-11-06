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
$router->get('/ai4ai', [PageController::class, 'ai4ai']);
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
/*
 * The Innovation Lab page was published at /ai-innovation-lab on the previous
 * site, and that URL has already gone out in printed correspondence. It stays
 * alive as a permanent redirect so those references land on the renamed page
 * instead of a 404. url() is used rather than a hardcoded path so the target
 * follows APP_BASE_URL when the site moves to its public hostname.
 *
 * The trailing-slash form is covered without a second route: Router::dispatch()
 * rtrims the path before matching, so /ai-innovation-lab/ resolves here too.
 */
$router->get('/ai-innovation-lab', static function (): void {
    http_response_code(301);
    header('Location: ' . url('ai-lab'));
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


// Keep the published short playbook URL working, including its trailing slash.
$router->get('/playbook', static function (): void {
    http_response_code(301);
    header('Location: ' . url('document/playbook'));
});


// Exact replacements for published URLs from the previous website.
// Unknown paths still return 404; these aliases do not expose WordPress endpoints.
$legacyPages = [
    '/a-blueprint-for-mauritius' => 'document/blueprint',
    '/ai-strategy' => 'document/aistrategy',
    '/fair-guidelines' => 'document/fairguidelines',
    '/about-us' => '#about-combined',
    '/contact-us' => '#contact',
    '/ai-framework' => '#strategy',
    '/marketplace' => '#marketplace',
    '/projects' => '#action',
    '/ai-for-all' => '#action',
    '/ai-for-all-english-version' => 'booklet/aie',
    '/violence-numerique-contre-les-enfants' => 'booklet/livret',
];
foreach ($legacyPages as $oldPath => $destination) {
    $router->get($oldPath, static function () use ($destination): void {
        http_response_code(301);
        header('Location: ' . url($destination));
    });
}

$legacyDocuments = [
    '/wp-content/uploads/2026/04/aistrategy.pdf' => 'documents/aistrategy.pdf',
    '/wp-content/uploads/2026/04/fairguidelines.pdf' => 'documents/fairguidelines.pdf',
    '/blueprint/blueprint.pdf' => 'documents/blueprint.pdf',
];
foreach ($legacyDocuments as $oldPath => $destination) {
    $router->get($oldPath, static function () use ($destination): void {
        http_response_code(301);
        header('Location: ' . asset($destination));
    });
}
