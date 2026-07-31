<?php

declare(strict_types=1);

namespace App\Models;

/**
 * One gallery image on the Highlights page.
 *
 * `fileName` is a bare basename - never a path. Where the file actually lives
 * is App\Services\ImageUploadService's business, so a row can never address
 * anything outside the upload directory.
 *
 * @see \App\Repositories\HighlightImageRepository
 */
final class HighlightImage
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $categoryId,
        public readonly string $title,
        public readonly ?string $caption,
        public readonly string $altText,
        public readonly string $fileName,
        public readonly int $sortOrder,
        public readonly bool $isVisible,
    ) {
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (int) $row['category_id'],
            (string) $row['title'],
            $row['caption'] !== null ? (string) $row['caption'] : null,
            (string) $row['alt_text'],
            (string) $row['file_name'],
            (int) $row['sort_order'],
            (bool) $row['is_visible'],
        );
    }
}
