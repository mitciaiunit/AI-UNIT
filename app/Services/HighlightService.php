<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Logger;
use App\Models\HighlightCategory;
use App\Repositories\HighlightCategoryRepository;
use App\Repositories\HighlightImageRepository;
use Throwable;

/**
 * Read model for the public Highlights page.
 *
 * Returns only what should be published: visible categories, each carrying its
 * visible images in display order. The view iterates whatever comes back, so a
 * category added in the admin area appears with no code change.
 */
final class HighlightService
{
    public function __construct(
        private readonly HighlightCategoryRepository $categories = new HighlightCategoryRepository(),
        private readonly HighlightImageRepository $images = new HighlightImageRepository(),
        private readonly ImageUploadService $uploads = new ImageUploadService(),
    ) {
    }

    /**
     * Visible categories with their visible images attached. Categories with
     * no images are dropped - an empty heading is worse than no heading.
     *
     * A database failure returns an empty list rather than propagating: the
     * gallery is one section of a long editorial page, and the rest of it
     * should still render if the database is unreachable.
     *
     * @return list<HighlightCategory>
     */
    public function publishedCategories(): array
    {
        try {
            $categories = $this->categories->allVisible();
            $imagesByCategory = $this->images->visibleGroupedByCategory();
        } catch (Throwable $e) {
            Logger::error('Failed to load highlights', ['error' => $e->getMessage()]);

            return [];
        }

        $result = [];
        foreach ($categories as $category) {
            $images = $imagesByCategory[(int) $category->id] ?? [];
            if ($images === []) {
                continue;
            }

            $category->images = $images;
            $result[] = $category;
        }

        return $result;
    }

    /** Public URL for a gallery image. */
    public function imageUrl(string $fileName): string
    {
        return $this->uploads->url($fileName);
    }
}
