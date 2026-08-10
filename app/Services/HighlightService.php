<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Logger;
use App\Models\HighlightCategory;
use App\Models\HighlightsResult;
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
        private readonly HighlightsCache $cache = new HighlightsCache(),
    ) {
    }

    /**
     * The published galleries, together with how they were obtained.
     *
     * Order of preference:
     *   1. the database - the source of truth whenever it answers;
     *   2. the last known-good copy, if the database cannot be reached;
     *   3. nothing, with a status the page can explain to a visitor.
     *
     * The exception is never allowed to escape. The gallery is one section of
     * a long editorial page, and an outage should cost the visitor that
     * section - not the whole page, and not a stack trace.
     */
    public function published(): HighlightsResult
    {
        try {
            $categories = $this->categories->allVisible();
            $imagesByCategory = $this->images->visibleGroupedByCategory();
        } catch (Throwable $e) {
            Logger::error('Failed to load highlights', ['error' => $e->getMessage()]);

            $cached = $this->cache->load();
            if ($cached !== null) {
                Logger::info('Serving highlights from cache', ['categories' => count($cached)]);

                return HighlightsResult::stale($cached);
            }

            return HighlightsResult::unavailable();
        }

        $result = [];
        foreach ($categories as $category) {
            // Categories with no visible images are dropped - an empty heading
            // is worse than no heading.
            $images = $imagesByCategory[(int) $category->id] ?? [];
            if ($images === []) {
                continue;
            }

            $category->images = $images;
            $result[] = $category;
        }

        /*
         * Refresh the mirror on every successful read, including when the
         * result is legitimately empty of *some* category - what is written is
         * always exactly what was just published.
         */
        $this->cache->store($result);

        return HighlightsResult::ok($result);
    }

    /**
     * @deprecated Use published(), which distinguishes "nothing published"
     *             from "the database is unreachable". Kept so any other
     *             caller keeps working.
     *
     * @return list<HighlightCategory>
     */
    public function publishedCategories(): array
    {
        return $this->published()->categories;
    }

    /** Public URL for a gallery image. */
    public function imageUrl(string $fileName): string
    {
        return $this->uploads->url($fileName);
    }
}
