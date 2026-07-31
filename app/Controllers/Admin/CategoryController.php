<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Logger;
use App\Models\HighlightCategory;
use App\Repositories\HighlightCategoryRepository;
use App\Repositories\HighlightImageRepository;
use Throwable;

/**
 * Manage the groupings shown on the Highlights page.
 */
final class CategoryController extends AdminController
{
    private HighlightCategoryRepository $categories;
    private HighlightImageRepository $images;

    public function __construct()
    {
        parent::__construct();
        $this->categories = new HighlightCategoryRepository();
        $this->images = new HighlightImageRepository();
    }

    public function index(): void
    {
        if ($this->guard() === null) {
            return;
        }

        $categories = $this->categories->all();

        // Image counts drive both the listing and the delete guard.
        $counts = [];
        foreach ($categories as $category) {
            $counts[(int) $category->id] = $this->images->countForCategory((int) $category->id);
        }

        $this->adminView('categories', [
            'title' => 'Categories',
            'section' => 'categories',
            'categories' => $categories,
            'counts' => $counts,
        ]);
    }

    public function create(): void
    {
        if ($this->guard() === null) {
            return;
        }

        $this->adminView('category-form', [
            'title' => 'New category',
            'section' => 'categories',
            'category' => null,
        ]);
    }

    public function edit(string $id): void
    {
        if ($this->guard() === null) {
            return;
        }

        $category = $this->categories->find((int) $id);
        if ($category === null) {
            $this->flash('error', 'That category no longer exists.');
            redirect(url('admin/categories'));

            return;
        }

        $this->adminView('category-form', [
            'title' => 'Edit category',
            'section' => 'categories',
            'category' => $category,
        ]);
    }

    public function store(): void
    {
        if ($this->guard() === null || !$this->guardCsrf()) {
            return;
        }

        $this->save(null);
    }

    public function update(string $id): void
    {
        if ($this->guard() === null || !$this->guardCsrf()) {
            return;
        }

        $this->save((int) $id);
    }

    public function delete(string $id): void
    {
        if ($this->guard() === null || !$this->guardCsrf()) {
            return;
        }

        $categoryId = (int) $id;
        $inUse = $this->images->countForCategory($categoryId);

        /*
         * Checked here as well as enforced by the foreign key. The constraint
         * is the real guarantee; this exists so a non-technical admin gets a
         * sentence explaining what to do instead of a database error.
         */
        if ($inUse > 0) {
            $this->flash('error', sprintf(
                'That category still holds %d image(s). Move or delete them first.',
                $inUse
            ));
            redirect(url('admin/categories'));

            return;
        }

        try {
            $this->categories->delete($categoryId);
            $this->flash('success', 'Category deleted.');
        } catch (Throwable $e) {
            Logger::error('Failed to delete highlight category', ['id' => $categoryId, 'error' => $e->getMessage()]);
            $this->flash('error', 'The category could not be deleted.');
        }

        redirect(url('admin/categories'));
    }

    public function toggleVisibility(string $id): void
    {
        if ($this->guard() === null || !$this->guardCsrf()) {
            return;
        }

        $category = $this->categories->find((int) $id);
        if ($category === null) {
            redirect(url('admin/categories'));

            return;
        }

        $this->categories->setVisibility((int) $id, !$category->isVisible);
        $this->flash('success', $category->isVisible
            ? sprintf('"%s" is now hidden from the website.', $category->name)
            : sprintf('"%s" is now visible on the website.', $category->name));

        redirect(url('admin/categories'));
    }

    public function move(string $id): void
    {
        if ($this->guard() === null || !$this->guardCsrf()) {
            return;
        }

        $direction = $this->input('direction') === 'up' ? -1 : 1;

        try {
            $this->categories->move((int) $id, $direction);
        } catch (Throwable $e) {
            Logger::error('Failed to reorder highlight category', ['id' => $id, 'error' => $e->getMessage()]);
            $this->flash('error', 'The category could not be moved.');
        }

        redirect(url('admin/categories'));
    }

    /** Shared create/update path. $id is null when creating. */
    private function save(?int $id): void
    {
        $name = $this->input('name');
        $description = $this->input('description');
        $slug = $this->input('slug');
        $isVisible = $this->checkbox('is_visible');

        $errors = [];

        if ($name === '') {
            $errors[] = 'A name is required.';
        } elseif (mb_strlen($name) > 150) {
            $errors[] = 'The name must be 150 characters or fewer.';
        }

        if (mb_strlen($description) > 2000) {
            $errors[] = 'The description must be 2000 characters or fewer.';
        }

        $slug = $slug === '' ? $this->slugify($name) : $this->slugify($slug);
        if ($slug === '') {
            $errors[] = 'The name must contain at least one letter or number.';
        } elseif ($this->categories->slugExists($slug, $id)) {
            $errors[] = 'Another category already uses that name.';
        }

        if ($errors !== []) {
            $this->flash('error', implode(' ', $errors));
            redirect($id === null ? url('admin/categories/new') : url('admin/categories/' . $id . '/edit'));

            return;
        }

        $category = new HighlightCategory(
            $id,
            $slug,
            $name,
            $description === '' ? null : $description,
            $id === null ? $this->categories->nextSortOrder() : $this->categories->find($id)?->sortOrder ?? 0,
            $isVisible,
        );

        try {
            if ($id === null) {
                $this->categories->create($category);
                $this->flash('success', sprintf('Category "%s" created.', $name));
            } else {
                $this->categories->update($id, $category);
                $this->flash('success', sprintf('Category "%s" saved.', $name));
            }
        } catch (Throwable $e) {
            Logger::error('Failed to save highlight category', ['id' => $id, 'error' => $e->getMessage()]);
            $this->flash('error', 'The category could not be saved.');
        }

        redirect(url('admin/categories'));
    }

    /** "Rodrigues & Imperial Programme" -> "rodrigues-imperial-programme". */
    private function slugify(string $value): string
    {
        $slug = mb_strtolower(trim($value));
        $slug = (string) preg_replace('/[^\p{L}\p{N}]+/u', '-', $slug);

        return trim($slug, '-');
    }
}
