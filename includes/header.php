<?php
/**
 * <head> partial shared by every "full site" page (via includes/layouts/app.php).
 * Expects an optional $title string in scope (set by the calling controller).
 *
 * $pageStyles is an optional list of extra stylesheet filenames under
 * assets/css/, for pages that ship their own styling on top of the shared
 * style.css. They load last so they can override it.
 */
$pageTitle = page_title($title ?? '');
$pageStyles = $pageStyles ?? [];
?>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?= e($pageTitle) ?></title>
<link rel="icon" type="image/png" href="<?= e(asset('images/favicon.png')) ?>">
<?php require __DIR__ . '/meta.php'; ?>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Lora:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
<?php foreach ($pageStyles as $pageStyle): ?>
<link rel="stylesheet" href="<?= e(asset('css/' . $pageStyle)) ?>">
<?php endforeach; ?>
<?php
/*
 * .reveal starts at opacity:0 and is only made visible when the
 * IntersectionObserver in assets/js/script.js adds .visible. With scripting
 * off nothing ever adds it, so roughly thirty blocks on the homepage - the
 * document cards, the marketplace cards, the team panels, the contact form -
 * would render permanently invisible.
 *
 * <noscript> is the exact tool for that: its contents are inert while
 * scripting is enabled, so the animation is untouched for everyone else, and
 * there is no flash of unstyled content in either direction. Doing the same
 * job with a JS-added "js" class on <html> would briefly show the content
 * before hiding it again.
 */
?>
<noscript>
  <style>.reveal{opacity:1;transform:none;}</style>
</noscript>
