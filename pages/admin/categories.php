<header class="adm-page-head">
  <div>
    <h1>Categories</h1>
    <p>These sections appear on the public Highlights page when visible and when they contain visible images.</p>
  </div>
  <a class="adm-btn adm-btn--primary" href="<?= e(url('admin/categories/new')) ?>">Add category</a>
</header>

<section class="adm-card">
  <?php if ($categories === []): ?>
    <div class="adm-empty">No categories yet.</div>
  <?php else: ?>
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Images</th>
            <th>Status</th>
            <th>Order</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $category): ?>
            <?php $id = (int) $category->id; ?>
            <tr>
              <td>
                <div class="adm-table__title"><?= e($category->name) ?></div>
                <?php if ($category->description !== null && $category->description !== ''): ?>
                  <div class="adm-table__sub"><?= e($category->description) ?></div>
                <?php endif; ?>
              </td>
              <td><?= e($category->slug) ?></td>
              <td><?= e((string) ($counts[$id] ?? 0)) ?></td>
              <td><span class="adm-badge adm-badge--<?= $category->isVisible ? 'on' : 'off' ?>"><?= $category->isVisible ? 'Visible' : 'Hidden' ?></span></td>
              <td>
                <div class="adm-actions">
                  <form class="adm-inline-form" method="post" action="<?= e(url('admin/categories/' . $id . '/move')) ?>">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="direction" value="up">
                    <button class="adm-btn adm-btn--icon" type="submit" aria-label="Move <?= e($category->name) ?> up">↑</button>
                  </form>
                  <form class="adm-inline-form" method="post" action="<?= e(url('admin/categories/' . $id . '/move')) ?>">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="direction" value="down">
                    <button class="adm-btn adm-btn--icon" type="submit" aria-label="Move <?= e($category->name) ?> down">↓</button>
                  </form>
                </div>
              </td>
              <td>
                <div class="adm-actions">
                  <a class="adm-btn" href="<?= e(url('admin/categories/' . $id . '/edit')) ?>">Edit</a>
                  <form class="adm-inline-form" method="post" action="<?= e(url('admin/categories/' . $id . '/visibility')) ?>">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <button class="adm-btn" type="submit"><?= $category->isVisible ? 'Hide' : 'Show' ?></button>
                  </form>
                  <form class="adm-inline-form" method="post" action="<?= e(url('admin/categories/' . $id . '/delete')) ?>" onsubmit="return confirm('Delete this category?');">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <button class="adm-btn adm-btn--danger" type="submit">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>
