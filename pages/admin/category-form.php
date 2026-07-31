<?php
$isEdit = $category !== null;
?>
<header class="adm-page-head">
  <div>
    <h1><?= $isEdit ? 'Edit category' : 'New category' ?></h1>
    <p>Categories group gallery images on the public Highlights page.</p>
  </div>
</header>

<section class="adm-card">
  <form class="adm-form" method="post" action="<?= e($isEdit ? url('admin/categories/' . (int) $category->id) : url('admin/categories')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <div class="adm-field">
      <label for="name">Name <span class="adm-required">*</span></label>
      <input type="text" id="name" name="name" maxlength="150" required value="<?= e($category?->name) ?>">
    </div>

    <div class="adm-field">
      <label for="slug">Slug</label>
      <p class="adm-field__hint">Leave blank to generate it from the name.</p>
      <input type="text" id="slug" name="slug" maxlength="120" value="<?= e($category?->slug) ?>">
    </div>

    <div class="adm-field">
      <label for="description">Description</label>
      <textarea id="description" name="description" maxlength="2000"><?= e($category?->description) ?></textarea>
    </div>

    <div class="adm-check">
      <input type="checkbox" id="is_visible" name="is_visible" value="1"<?= (!$isEdit || $category->isVisible) ? ' checked' : '' ?>>
      <label for="is_visible">Visible on the website</label>
    </div>

    <div class="adm-form__actions">
      <button class="adm-btn adm-btn--primary" type="submit">Save category</button>
      <a class="adm-btn" href="<?= e(url('admin/categories')) ?>">Cancel</a>
    </div>
  </form>
</section>
