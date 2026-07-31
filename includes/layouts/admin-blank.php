<?php
/**
 * Chromeless admin layout, used only by the sign-in screen.
 *
 * The full admin layout shows the section nav and a sign-out button, neither
 * of which means anything to someone who is not signed in yet - and rendering
 * them would leak the shape of the admin area to an anonymous visitor.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e(($title ?? 'Sign in') . ' · Highlights admin · AI Unit') ?></title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" type="image/x-icon" href="<?= e(asset('images/logo.gif')) ?>">
<link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="adm-body--centred">

<main class="adm-auth" id="adm-main">
  <?php if (!empty($flash)): ?>
    <div class="adm-flash adm-flash--<?= e($flash['type'] === 'success' ? 'success' : 'error') ?>" role="alert">
      <?= e($flash['message']) ?>
    </div>
  <?php endif; ?>

  <?= $content ?>
</main>

</body>
</html>
