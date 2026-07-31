<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\HighlightCategory;
use PDO;

/**
 * Persistence for highlight_categories. The only place that writes SQL for
 * this table; every value is bound, never interpolated.
 */
final class HighlightCategoryRepository
{
    /**
     * @return list<HighlightCategory>
     */
    public function all(): array
    {
        $sql = 'SELECT id, slug, name, description, sort_order, is_visible
                FROM highlight_categories
                ORDER BY sort_order ASC, name ASC';

        return $this->hydrateAll(Database::connection()->query($sql)->fetchAll());
    }

    /**
     * @return list<HighlightCategory>
     */
    public function allVisible(): array
    {
        $sql = 'SELECT id, slug, name, description, sort_order, is_visible
                FROM highlight_categories
                WHERE is_visible = 1
                ORDER BY sort_order ASC, name ASC';

        return $this->hydrateAll(Database::connection()->query($sql)->fetchAll());
    }

    public function find(int $id): ?HighlightCategory
    {
        $statement = Database::connection()->prepare(
            'SELECT id, slug, name, description, sort_order, is_visible
             FROM highlight_categories WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row === false ? null : HighlightCategory::fromRow($row);
    }

    /**
     * @param int|null $exceptId Ignore this row - used when editing, so a
     *                           category does not collide with itself.
     */
    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM highlight_categories WHERE slug = :slug';
        $params = ['slug' => $slug];

        if ($exceptId !== null) {
            $sql .= ' AND id <> :except_id';
            $params['except_id'] = $exceptId;
        }

        $statement = Database::connection()->prepare($sql);
        $statement->execute($params);

        return (int) $statement->fetchColumn() > 0;
    }

    public function create(HighlightCategory $category): int
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare(
            'INSERT INTO highlight_categories (slug, name, description, sort_order, is_visible)
             VALUES (:slug, :name, :description, :sort_order, :is_visible)'
        );
        $statement->execute([
            'slug' => $category->slug,
            'name' => $category->name,
            'description' => $category->description,
            'sort_order' => $category->sortOrder,
            'is_visible' => $category->isVisible ? 1 : 0,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public function update(int $id, HighlightCategory $category): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE highlight_categories
             SET slug = :slug, name = :name, description = :description,
                 sort_order = :sort_order, is_visible = :is_visible
             WHERE id = :id'
        );
        $statement->execute([
            'id' => $id,
            'slug' => $category->slug,
            'name' => $category->name,
            'description' => $category->description,
            'sort_order' => $category->sortOrder,
            'is_visible' => $category->isVisible ? 1 : 0,
        ]);
    }

    public function delete(int $id): void
    {
        $statement = Database::connection()->prepare('DELETE FROM highlight_categories WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    public function setVisibility(int $id, bool $visible): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE highlight_categories SET is_visible = :is_visible WHERE id = :id'
        );
        $statement->execute(['id' => $id, 'is_visible' => $visible ? 1 : 0]);
    }

    /**
     * Moves a category one place up or down by swapping sort_order with its
     * neighbour, inside a transaction so the pair can never end up sharing a
     * position if the second statement fails.
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
                "SELECT id, sort_order FROM highlight_categories
                 WHERE sort_order {$comparison} :sort_order
                 ORDER BY sort_order {$order} LIMIT 1"
            );
            $statement->execute(['sort_order' => $current->sortOrder]);
            $neighbour = $statement->fetch();

            if ($neighbour === false) {
                $pdo->rollBack();

                return;
            }

            $swap = $pdo->prepare('UPDATE highlight_categories SET sort_order = :sort_order WHERE id = :id');
            $swap->execute(['id' => $id, 'sort_order' => (int) $neighbour['sort_order']]);
            $swap->execute(['id' => (int) $neighbour['id'], 'sort_order' => $current->sortOrder]);

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();

            throw $e;
        }
    }

    public function nextSortOrder(): int
    {
        $value = Database::connection()
            ->query('SELECT COALESCE(MAX(sort_order), 0) + 1 FROM highlight_categories')
            ->fetchColumn();

        return (int) $value;
    }

    /**
     * @param list<array<string, mixed>> $rows
     * @return list<HighlightCategory>
     */
    private function hydrateAll(array $rows): array
    {
        return array_map(
            static fn (array $row): HighlightCategory => HighlightCategory::fromRow($row),
            $rows
        );
    }
}
