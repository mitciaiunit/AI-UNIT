<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Repositories\HighlightCategoryRepository;
use App\Repositories\HighlightImageRepository;

/**
 * Landing screen after sign-in: what is published, and the way in to each
 * management area.
 */
final class DashboardController extends AdminController
{
    public function index(): void
    {
        $user = $this->guard();
        if ($user === null) {
            return;
        }

        $categories = (new HighlightCategoryRepository())->all();
        $images = (new HighlightImageRepository())->all();

        $visibleCategories = array_filter($categories, static fn ($c): bool => $c->isVisible);
        $visibleImages = array_filter($images, static fn ($i): bool => $i->isVisible);

        $this->adminView('dashboard', [
            'title' => 'Dashboard',
            'section' => 'dashboard',
            'currentUser' => $user,
            'categoryCount' => count($categories),
            'visibleCategoryCount' => count($visibleCategories),
            'imageCount' => count($images),
            'visibleImageCount' => count($visibleImages),
        ]);
    }
}
