<?php
/**
 * <head> partial shared by every "full site" page (via includes/layouts/app.php).
 * Expects an optional $title string in scope (set by the calling controller).
 */
$pageTitle = page_title($title ?? '');
?>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?= e($pageTitle) ?></title>
<link rel="icon" type="image/x-icon" href="<?= e(asset('images/logo.gif')) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Lora:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet" />
<base target="_blank">
<link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
