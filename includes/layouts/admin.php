<?php
/**
 * Layout for the signed-in admin screens.
 *
 * Deliberately not the public app layout: the admin area has no navbar, no
 * DIVA widget, no accessibility toolbar and no site JavaScript. Keeping them
 * out means an editor's screen cannot be broken by a change to the public
 * site, and none of the public page weight is loaded to edit a caption.
 *
 * Expects $content, $title, $section, $currentUser and $flash in scope.
 */
$section = $section ?? '';
$navItems = [
    'dashboard' => ['label' => 'Dashboard', 'path' => 'admin'],
    'categories' => ['label' => 'Categories', 'path' => 'admin/categories'],
    'images' => ['label' => 'Gallery', 'path' => 'admin/images'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e(($title ?? 'Admin') . ' · Highlights admin · AI Unit') ?></title>
<?php /* Admin pages must never be indexed, even if the URL leaks. */ ?>
<?php $noindex = true; require __DIR__ . '/../meta.php'; ?>
<link rel="icon" type="image/png" href="<?= e(asset('images/favicon.png')) ?>">
<link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body>

<a class="adm-skip" href="#adm-main">Skip to main content</a>

<header class="adm-topbar">
  <div class="adm-topbar__inner">
    <a class="adm-brand" href="<?= e(url('admin')) ?>">
      <img src="<?= e(asset('images/favicon.png')) ?>" alt="" width="34" height="34" decoding="async">
      <span>
        <strong>Highlights admin</strong>
        <small>AI Unit</small>
      </span>
    </a>

    <nav class="adm-nav" aria-label="Admin sections">
      <?php foreach ($navItems as $key => $item): ?>
        <a href="<?= e(url($item['path'])) ?>" class="adm-nav__link"<?= $section === $key ? ' aria-current="page"' : '' ?>>
          <?= e($item['label']) ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="adm-user">
      <a class="adm-user__view" href="<?= e(url('highlights')) ?>" target="_blank" rel="noopener">View site ↗</a>
      <?php if (!empty($currentUser)): ?>
        <span class="adm-user__name"><?= e($currentUser->label()) ?></span>
        <form method="post" action="<?= e(url('admin/logout')) ?>" class="adm-user__form">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <button type="submit" class="adm-btn adm-btn--quiet">Sign out</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</header>

<main class="adm-main" id="adm-main">
  <div class="adm-shell">
    <?php if (!empty($flash)): ?>
      <?php /* role=alert so a screen reader announces the result of the action just taken. */ ?>
      <div class="adm-flash adm-flash--<?= e($flash['type'] === 'success' ? 'success' : 'error') ?>" role="alert">
        <?= e($flash['message']) ?>
      </div>
    <?php endif; ?>

    <?= $content ?>
  </div>
</main>

<footer class="adm-footer">
  <div class="adm-shell">
    Highlights content management · AI Unit, Ministry of Information Technology, Communication and Innovation
  </div>
</footer>

</body>
</html>
