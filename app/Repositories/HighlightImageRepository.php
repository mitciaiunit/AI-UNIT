<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\HighlightImage;
use Throwable;

/**
 * Persistence for highlight_images. The only place that writes SQL for this
 * table; every value is bound, never interpolated.
 */
final class HighlightImageRepository
{
    private const COLUMNS = 'id, category_id, title, caption, alt_text, file_name, sort_order, is_visible';

    /**
     * Every visible image of every visible category, in display order.
     *
     * Fetched in one query and grouped in PHP rather than one query per
     * category, so adding categories never adds round trips.
     *
     * @return array<int, list<HighlightImage>> Keyed by category id.
     */
    public function visibleGroupedByCategory(): array
    {
        $sql = 'SELECT i.' . str_replace(', ', ', i.', self::COLUMNS) . '
                FROM highlight_images i
                INNER JOIN highlight_categories c ON c.id = i.category_id
                WHERE i.is_visible = 1 AND c.is_visible = 1
                ORDER BY c.sort_order ASC, i.sort_order ASC, i.id ASC';

        $grouped = [];
        foreach (Database::connection()->query($sql)->fetchAll() as $row) {
            $grouped[(int) $row['category_id']][] = HighlightImage::fromRow($row);
        }

        return $grouped;
    }

    /**
     * @return list<HighlightImage>
     */
    public function allForCategory(int $categoryId): array
    {
        $statement = Database::connection()->prepare(
            'SELECT ' . self::COLUMNS . ' FROM highlight_images
             WHERE category_id = :category_id
             ORDER BY sort_order ASC, id ASC'
        );
        $statement->execute(['category_id' => $categoryId]);

        return array_map(
            static fn (array $row): HighlightImage => HighlightImage::fromRow($row),
            $statement->fetchAll()
        );
    }

    /**
     * @return list<HighlightImage>
     */
    public function all(): array
    {
        $sql = 'SELECT i.' . str_replace(', ', ', i.', self::COLUMNS) . '
                FROM highlight_images i
                INNER JOIN highlight_categories c ON c.id = i.category_id
                ORDER BY c.sort_order ASC, i.sort_order ASC, i.id ASC';

        return array_map(
            static fn (array $row): HighlightImage => HighlightImage::fromRow($row),
            Database::connection()->query($sql)->fetchAll()
        );
    }

    public function find(int $id): ?HighlightImage
    {
        $statement = Database::connection()->prepare(
            'SELECT ' . self::COLUMNS . ' FROM highlight_images WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row === false ? null : HighlightImage::fromRow($row);
    }

    public function countForCategory(int $categoryId): int
    {
        $statement = Database::connection()->prepare(
            'SELECT COUNT(*) FROM highlight_images WHERE category_id = :category_id'
        );
        $statement->execute(['category_id' => $categoryId]);

        return (int) $statement->fetchColumn();
    }

    public function create(HighlightImage $image): int
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare(
            'INSERT INTO highlight_images
                (category_id, title, caption, alt_text, file_name, sort_order, is_visible)
             VALUES
                (:category_id, :title, :caption, :alt_text, :file_name, :sort_order, :is_visible)'
        );
        $statement->execute([
            'category_id' => $image->categoryId,
            'title' => $image->title,
            'caption' => $image->caption,
            'alt_text' => $image->altText,
            'file_name' => $image->fileName,
            'sort_order' => $image->sortOrder,
            'is_visible' => $image->isVisible ? 1 : 0,
        ]);

        return (int) $pdo->lastInsertId();
    }

    /** Updates the metadata; the file itself is replaced separately. */
    public function update(int $id, HighlightImage $image): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE highlight_images
             SET category_id = :category_id, title = :title, caption = :caption,
                 alt_text = :alt_text, file_name = :file_name,
                 sort_order = :sort_order, is_visible = :is_visible
             WHERE id = :id'
        );
        $statement->execute([
            'id' => $id,
            'category_id' => $image->categoryId,
            'title' => $image->title,
            'caption' => $image->caption,
            'alt_text' => $image->altText,
            'file_name' => $image->fileName,
            'sort_order' => $image->sortOrder,
            'is_visible' => $image->isVisible ? 1 : 0,
        ]);
    }

    public function delete(int $id): void
    {
        $statement = Database::connection()->prepare('DELETE FROM highlight_images WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    public function setVisibility(int $id, bool $visible): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE highlight_images SET is_visible = :is_visible WHERE id = :id'
        );
        $statement->execute(['id' => $id, 'is_visible' => $visible ? 1 : 0]);
    }

    /**
     * Moves an image one place up or down within its own category by swapping
     * sort_order with its neighbour, in a transaction so the pair cannot end
     * up sharing a position.
     */
    public function move(int $id, int $direction): void
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();

        try {
            $current = $this->find($id);
            if ($current === null) {
                $pdo->rollBack();

                return;
            }

            $comparison = $direction < 0 ? '<' : '>';
            $order = $direction < 0 ? 'DESC' : 'ASC';

            $statement = $pdo->prepare(
                "SELECT id, sort_order FROM highlight_images
                 WHERE category_id = :category_id AND sort_order {$comparison} :sort_order
                 ORDER BY sort_order {$order} LIMIT 1"
            );
            $statement->execute([
                'category_id' => $current->categoryId,
                'sort_order' => $current->sortOrder,
            ]);
            $neighbour = $statement->fetch();

            if ($neighbour === false) {
                $pdo->rollBack();

                return;
            }

            $swap = $pdo->prepare('UPDATE highlight_images SET sort_order = :sort_order WHERE id = :id');
            $swap->execute(['id' => $id, 'sort_order' => (int) $neighbour['sort_order']]);
            $swap->execute(['id' => (int) $neighbour['id'], 'sort_order' => $current->sortOrder]);

            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();

            throw $e;
        }
    }

    public function nextSortOrder(int $categoryId): int
    {
        $statement = Database::connection()->prepare(
            'SELECT COALESCE(MAX(sort_order), 0) + 1 FROM highlight_images WHERE category_id = :category_id'
        );
        $statement->execute(['category_id' => $categoryId]);

        return (int) $statement->fetchColumn();
    }
}
