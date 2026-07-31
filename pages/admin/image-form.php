<?php
$isEdit = $image !== null;
?>
<header class="adm-page-head">
  <div>
    <h1><?= $isEdit ? 'Edit image' : 'Add image' ?></h1>
    <p>Titles, captions and alt text are shown or used by the public Highlights gallery.</p>
  </div>
</header>

<section class="adm-card">
  <?php if ($isEdit): ?>
    <div class="adm-preview">
      <img src="<?= e($uploads->url($image->fileName)) ?>" alt="">
      <div class="adm-preview__meta">
        <strong>Current image</strong><br>
        Upload a replacement only when the picture itself needs to change.
      </div>
    </div>
  <?php endif; ?>

  <form class="adm-form" method="post" enctype="multipart/form-data" action="<?= e($isEdit ? url('admin/images/' . (int) $image->id) : url('admin/images')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <div class="adm-field">
      <label for="category_id">Category <span class="adm-required">*</span></label>
      <select id="category_id" name="category_id" required>
        <option value="">Choose a category</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?= e((string) $category->id) ?>"<?= $isEdit && $image->categoryId === (int) $category->id ? ' selected' : '' ?>>
            <?= e($category->name) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="adm-field">
      <label for="image">Image <?= $isEdit ? '' : '<span class="adm-required">*</span>' ?></label>
      <p class="adm-field__hint">Accepted formats: JPG, PNG, WebP and GIF. Maximum size: <?= e((string) round((int) config('highlights.max_upload_bytes') / 1024 / 1024, 1)) ?> MB.</p>
      <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif"<?= $isEdit ? '' : ' required' ?>>
    </div>

    <div class="adm-field">
      <label for="title">Title <span class="adm-required">*</span></label>
      <input type="text" id="title" name="title" maxlength="200" required value="<?= e($image?->title) ?>">
    </div>

    <div class="adm-field">
      <label for="caption">Caption</label>
      <textarea id="caption" name="caption" maxlength="500"><?= e($image?->caption) ?></textarea>
    </div>

    <div class="adm-field">
      <label for="alt_text">Alt text <span class="adm-required">*</span></label>
      <textarea id="alt_text" name="alt_text" maxlength="500" required><?= e($image?->altText) ?></textarea>
    </div>

    <div class="adm-check">
      <input type="checkbox" id="is_visible" name="is_visible" value="1"<?= (!$isEdit || $image->isVisible) ? ' checked' : '' ?>>
      <label for="is_visible">Visible on the website</label>
    </div>

    <div class="adm-form__actions">
      <button class="adm-btn adm-btn--primary" type="submit">Save image</button>
      <a class="adm-btn" href="<?= e(url('admin/images')) ?>">Cancel</a>
    </div>
  </form>
</section>
