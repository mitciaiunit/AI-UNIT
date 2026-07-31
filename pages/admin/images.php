<header class="adm-page-head">
  <div>
    <h1>Gallery</h1>
    <p>Images are shown under their category on the public Highlights page.</p>
  </div>
  <a class="adm-btn adm-btn--primary" href="<?= e(url('admin/images/new')) ?>">Add image</a>
</header>

<?php if ($categories === []): ?>
  <section class="adm-card"><div class="adm-empty">Create a category before adding images.</div></section>
<?php else: ?>
  <?php foreach ($categories as $category): ?>
    <?php $images = $imagesByCategory[(int) $category->id] ?? []; ?>
    <section class="adm-card">
      <div class="adm-card__head">
        <h2><?= e($category->name) ?></h2>
        <span class="adm-badge adm-badge--<?= $category->isVisible ? 'on' : 'off' ?>"><?= $category->isVisible ? 'Category visible' : 'Category hidden' ?></span>
      </div>

      <?php if ($images === []): ?>
        <div class="adm-empty">No images in this category.</div>
      <?php else: ?>
        <div class="adm-table-wrap">
          <table class="adm-table">
            <thead>
              <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Status</th>
                <th>Order</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($images as $image): ?>
                <?php $id = (int) $image->id; ?>
                <tr>
                  <td><img class="adm-table__thumb" src="<?= e($uploads->url($image->fileName)) ?>" alt=""></td>
                  <td>
                    <div class="adm-table__title"><?= e($image->title) ?></div>
                    <div class="adm-table__sub"><?= e($image->caption ?? $image->altText) ?></div>
                  </td>
                  <td><span class="adm-badge adm-badge--<?= $image->isVisible ? 'on' : 'off' ?>"><?= $image->isVisible ? 'Visible' : 'Hidden' ?></span></td>
                  <td>
                    <div class="adm-actions">
                      <form class="adm-inline-form" method="post" action="<?= e(url('admin/images/' . $id . '/move')) ?>">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="direction" value="up">
                        <button class="adm-btn adm-btn--icon" type="submit" aria-label="Move <?= e($image->title) ?> up">↑</button>
                      </form>
                      <form class="adm-inline-form" method="post" action="<?= e(url('admin/images/' . $id . '/move')) ?>">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="direction" value="down">
                        <button class="adm-btn adm-btn--icon" type="submit" aria-label="Move <?= e($image->title) ?> down">↓</button>
                      </form>
                    </div>
                  </td>
                  <td>
                    <div class="adm-actions">
                      <a class="adm-btn" href="<?= e(url('admin/images/' . $id . '/edit')) ?>">Edit</a>
                      <form class="adm-inline-form" method="post" action="<?= e(url('admin/images/' . $id . '/visibility')) ?>">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <button class="adm-btn" type="submit"><?= $image->isVisible ? 'Hide' : 'Show' ?></button>
                      </form>
                      <form class="adm-inline-form" method="post" action="<?= e(url('admin/images/' . $id . '/delete')) ?>" onsubmit="return confirm('Delete this image?');">
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
  <?php endforeach; ?>
<?php endif; ?>
