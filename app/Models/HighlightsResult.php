<?php

declare(strict_types=1);

namespace App\Models;

/**
 * The outcome of loading the Highlights galleries, not just the data.
 *
 * The service used to return a plain array, which made "the database is
 * unreachable" and "the CMS has nothing published" the same value: an empty
 * one. The view could only test `!== []`, so a database outage silently
 * removed the entire gallery section and looked like deleted content. Carrying
 * the status alongside the data is what lets the page respond differently to
 * the two.
 */
final class HighlightsResult
{
    /** Loaded from the database. */
    public const STATUS_OK = 'ok';

    /** Database unreachable; serving the last known-good copy. */
    public const STATUS_STALE = 'stale';

    /** Database unreachable and no cached copy to fall back on. */
    public const STATUS_UNAVAILABLE = 'unavailable';

    /**
     * @param list<HighlightCategory> $categories
     */
    private function __construct(
        public readonly array $categories,
        public readonly string $status,
    ) {
    }

    /**
     * @param list<HighlightCategory> $categories
     */
    public static function ok(array $categories): self
    {
        return new self($categories, self::STATUS_OK);
    }

    /**
     * @param list<HighlightCategory> $categories
     */
    public static function stale(array $categories): self
    {
        return new self($categories, self::STATUS_STALE);
    }

    public static function unavailable(): self
    {
        return new self([], self::STATUS_UNAVAILABLE);
    }

    public function hasCategories(): bool
    {
        return $this->categories !== [];
    }

    /** True when there is nothing to show *and* the reason is a failure. */
    public function isUnavailable(): bool
    {
        return $this->status === self::STATUS_UNAVAILABLE;
    }

    /** True when the data shown came from cache rather than the database. */
    public function isStale(): bool
    {
        return $this->status === self::STATUS_STALE;
    }
}
