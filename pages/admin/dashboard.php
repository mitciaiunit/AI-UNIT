<header class="adm-page-head">
  <div>
    <h1>Dashboard</h1>
    <p>Overview of the content currently managed for the public Highlights page.</p>
  </div>
  <div class="adm-actions">
    <a class="adm-btn adm-btn--primary" href="<?= e(url('admin/images/new')) ?>">Add image</a>
    <a class="adm-btn" href="<?= e(url('admin/categories/new')) ?>">Add category</a>
  </div>
</header>

<section class="adm-stats" aria-label="Highlights totals">
  <div class="adm-stat">
    <div class="adm-stat__value"><?= e((string) $categoryCount) ?></div>
    <div class="adm-stat__label">Categories</div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__value"><?= e((string) $visibleCategoryCount) ?></div>
    <div class="adm-stat__label">Visible categories</div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__value"><?= e((string) $imageCount) ?></div>
    <div class="adm-stat__label">Gallery images</div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__value"><?= e((string) $visibleImageCount) ?></div>
    <div class="adm-stat__label">Visible images</div>
  </div>
</section>

<section class="adm-grid">
  <div class="adm-card">
    <div class="adm-card__head">
      <h2>Categories</h2>
      <a class="adm-btn adm-btn--quiet" href="<?= e(url('admin/categories')) ?>">Manage</a>
    </div>
    <p class="adm-table__sub">Create reusable sections such as internships, workshops, conferences and outreach events.</p>
  </div>

  <div class="adm-card">
    <div class="adm-card__head">
      <h2>Gallery</h2>
      <a class="adm-btn adm-btn--quiet" href="<?= e(url('admin/images')) ?>">Manage</a>
    </div>
    <p class="adm-table__sub">Upload, replace, reorder, hide and caption images shown on the public page.</p>
  </div>
</section>
