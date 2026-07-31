<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Logger;
use App\Models\HighlightImage;
use App\Repositories\HighlightCategoryRepository;
use App\Repositories\HighlightImageRepository;
use App\Services\ImageUploadService;
use RuntimeException;
use Throwable;

/**
 * Manage the Highlights gallery: upload, replace, edit, reorder, hide, delete.
 */
final class ImageController extends AdminController
{
    private HighlightImageRepository $images;
    private HighlightCategoryRepository $categories;
    private ImageUploadService $uploads;

    public function __construct()
    {
        parent::__construct();
        $this->images = new HighlightImageRepository();
        $this->categories = new HighlightCategoryRepository();
        $this->uploads = new ImageUploadService();
    }

    public function index(): void
    {
        if ($this->guard() === null) {
            return;
        }

        $categories = $this->categories->all();
        $byCategory = [];
        foreach ($categories as $category) {
            $byCategory[(int) $category->id] = $this->images->allForCategory((int) $category->id);
        }

        $this->adminView('images', [
            'title' => 'Gallery',
            'section' => 'images',
            'categories' => $categories,
            'imagesByCategory' => $byCategory,
            'uploads' => $this->uploads,
        ]);
    }

    public function create(): void
    {
        if ($this->guard() === null) {
            return;
        }

        $categories = $this->categories->all();
        if ($categories === []) {
            $this->flash('error', 'Create a category before adding images.');
            redirect(url('admin/categories/new'));

            return;
        }

        $this->adminView('image-form', [
            'title' => 'Add image',
            'section' => 'images',
            'image' => null,
            'categories' => $categories,
            'uploads' => $this->uploads,
        ]);
    }

    public function edit(string $id): void
    {
        if ($this->guard() === null) {
            return;
        }

        $image = $this->images->find((int) $id);
        if ($image === null) {
            $this->flash('error', 'That image no longer exists.');
            redirect(url('admin/images'));

            return;
        }

        $this->adminView('image-form', [
            'title' => 'Edit image',
            'section' => 'images',
            'image' => $image,
            'categories' => $this->categories->all(),
            'uploads' => $this->uploads,
        ]);
    }

    public function store(): void
    {
        if ($this->guard() === null || !$this->guardCsrf()) {
            return;
        }

        $fields = $this->readFields();
        $errors = $this->validate($fields, requireFile: true);

        if ($errors !== []) {
            $this->flash('error', implode(' ', $errors));
            redirect(url('admin/images/new'));

            return;
        }

        try {
            $fileName = $this->uploads->store($_FILES['image']);
        } catch (RuntimeException $e) {
            $this->flash('error', $e->getMessage());
            redirect(url('admin/images/new'));

            return;
        }

        $image = new HighlightImage(
            null,
            $fields['category_id'],
            $fields['title'],
            $fields['caption'] === '' ? null : $fields['caption'],
            $fields['alt_text'],
            $fileName,
            $this->images->nextSortOrder($fields['category_id']),
            $fields['is_visible'],
        );

        try {
            $this->images->create($image);
            $this->flash('success', sprintf('"%s" added to the gallery.', $fields['title']));
        } catch (Throwable $e) {
            // The row failed, so the file it points at is now orphaned - remove it.
            $this->uploads->delete($fileName);
            Logger::error('Failed to save highlight image', ['error' => $e->getMessage()]);
            $this->flash('error', 'The image could not be saved.');
        }

        redirect(url('admin/images'));
    }

    public function update(string $id): void
    {
        if ($this->guard() === null || !$this->guardCsrf()) {
            return;
        }

        $imageId = (int) $id;
        $existing = $this->images->find($imageId);
        if ($existing === null) {
            $this->flash('error', 'That image no longer exists.');
            redirect(url('admin/images'));

            return;
        }

        $fields = $this->readFields();
        $errors = $this->validate($fields, requireFile: false);

        if ($errors !== []) {
            $this->flash('error', implode(' ', $errors));
            redirect(url('admin/images/' . $imageId . '/edit'));

            return;
        }

        // A new file is optional on edit; without one the existing file stays.
        $fileName = $existing->fileName;
        $replaced = false;

        if ($this->hasUpload()) {
            try {
                $fileName = $this->uploads->store($_FILES['image']);
                $replaced = true;
            } catch (RuntimeException $e) {
                $this->flash('error', $e->getMessage());
                redirect(url('admin/images/' . $imageId . '/edit'));

                return;
            }
        }

        $image = new HighlightImage(
            $imageId,
            $fields['category_id'],
            $fields['title'],
            $fields['caption'] === '' ? null : $fields['caption'],
            $fields['alt_text'],
            $fileName,
            $fields['category_id'] === $existing->categoryId
                ? $existing->sortOrder
                : $this->images->nextSortOrder($fields['category_id']),
            $fields['is_visible'],
        );

        try {
            $this->images->update($imageId, $image);

            // Only bin the old file once the row pointing at the new one is
            // committed, so a failed update never leaves a row with no file.
            if ($replaced) {
                $this->uploads->delete($existing->fileName);
            }

            $this->flash('success', sprintf('"%s" saved.', $fields['title']));
        } catch (Throwable $e) {
            if ($replaced) {
                $this->uploads->delete($fileName);
            }
            Logger::error('Failed to update highlight image', ['id' => $imageId, 'error' => $e->getMessage()]);
            $this->flash('error', 'The image could not be saved.');
        }

        redirect(url('admin/images'));
    }

    public function delete(string $id): void
    {
        if ($this->guard() === null || !$this->guardCsrf()) {
            return;
        }

        $image = $this->images->find((int) $id);
        if ($image === null) {
            redirect(url('admin/images'));

            return;
        }

        try {
            $this->images->delete((int) $id);
            $this->uploads->delete($image->fileName);
            $this->flash('success', sprintf('"%s" deleted.', $image->title));
        } catch (Throwable $e) {
            Logger::error('Failed to delete highlight image', ['id' => $id, 'error' => $e->getMessage()]);
            $this->flash('error', 'The image could not be deleted.');
        }

        redirect(url('admin/images'));
    }

    public function toggleVisibility(string $id): void
    {
        if ($this->guard() === null || !$this->guardCsrf()) {
            return;
        }

        $image = $this->images->find((int) $id);
        if ($image === null) {
            redirect(url('admin/images'));

            return;
        }

        $this->images->setVisibility((int) $id, !$image->isVisible);
        $this->flash('success', $image->isVisible
            ? sprintf('"%s" is now hidden from the website.', $image->title)
            : sprintf('"%s" is now visible on the website.', $image->title));

        redirect(url('admin/images'));
    }

    public function move(string $id): void
    {
        if ($this->guard() === null || !$this->guardCsrf()) {
            return;
        }

        $direction = $this->input('direction') === 'up' ? -1 : 1;

        try {
            $this->images->move((int) $id, $direction);
        } catch (Throwable $e) {
            Logger::error('Failed to reorder highlight image', ['id' => $id, 'error' => $e->getMessage()]);
            $this->flash('error', 'The image could not be moved.');
        }

        redirect(url('admin/images'));
    }

    /**
     * @return array{category_id: int, title: string, caption: string, alt_text: string, is_visible: bool}
     */
    private function readFields(): array
    {
        return [
            'category_id' => (int) $this->input('category_id', '0'),
            'title' => $this->input('title'),
            'caption' => $this->input('caption'),
            'alt_text' => $this->input('alt_text'),
            'is_visible' => $this->checkbox('is_visible'),
        ];
    }

    /**
     * @param array{category_id: int, title: string, caption: string, alt_text: string, is_visible: bool} $fields
     * @return list<string>
     */
    private function validate(array $fields, bool $requireFile): array
    {
        $errors = [];

        if ($fields['title'] === '') {
            $errors[] = 'A title is required.';
        } elseif (mb_strlen($fields['title']) > 200) {
            $errors[] = 'The title must be 200 characters or fewer.';
        }

        if ($fields['alt_text'] === '') {
            $errors[] = 'Alt text is required so screen reader users can understand the image.';
        } elseif (mb_strlen($fields['alt_text']) > 500) {
            $errors[] = 'The alt text must be 500 characters or fewer.';
        }

        if (mb_strlen($fields['caption']) > 500) {
            $errors[] = 'The caption must be 500 characters or fewer.';
        }

        // Confirms the category exists rather than trusting the posted id,
        // which would otherwise fail later as a foreign key error.
        if ($fields['category_id'] <= 0 || $this->categories->find($fields['category_id']) === null) {
            $errors[] = 'Please choose a category.';
        }

        if ($requireFile && !$this->hasUpload()) {
            $errors[] = 'Please choose an image to upload.';
        }

        return $errors;
    }

    private function hasUpload(): bool
    {
        return isset($_FILES['image']['error'])
            && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE;
    }
}
