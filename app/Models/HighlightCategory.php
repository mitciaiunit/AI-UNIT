<?php

declare(strict_types=1);

namespace App\Models;

/**
 * A grouping on the Highlights page - "AI Unit Internship", "Rodrigues &
 * Imperial Programme", or anything staff add later.
 *
 * @see \App\Repositories\HighlightCategoryRepository
 */
final class HighlightCategory
{
    /**
     * @param list<HighlightImage> $images Populated only by the queries that
     *                                     explicitly load them; empty otherwise.
     */
    public function __construct(
        public readonly ?int $id,
        public readonly string $slug,
        public readonly string $name,
        public readonly ?string $description,
        public readonly int $sortOrder,
        public readonly bool $isVisible,
        public array $images = [],
    ) {
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (string) $row['slug'],
            (string) $row['name'],
            $row['description'] !== null ? (string) $row['description'] : null,
            (int) $row['sort_order'],
            (bool) $row['is_visible'],
        );
    }
}
