<?php
/**
 * Master layout for every "full site" page (home, privacy-policy, disclaimer,
 * cookie-policy, accessibility). Wraps the page's $content with the shared
 * header, navbar, footer, cookie banner, search modal, video modal, DIVA
 * widget, and accessibility panel — the components the task asks to be
 * de-duplicated into single shared includes.
 */
$isHome = $isHome ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require __DIR__ . '/../header.php'; ?>
</head>

<body>

<?php require __DIR__ . '/../cookie-banner.php'; ?>

<?php require __DIR__ . '/../navbar.php'; ?>

<?= $content ?>

<?php require __DIR__ . '/../footer.php'; ?>

<?php require __DIR__ . '/../search-modal.php'; ?>

<?php require __DIR__ . '/../video-modal.php'; ?>

<?php require __DIR__ . '/../diva-widget.php'; ?>

<?php require __DIR__ . '/../a11y-panel.php'; ?>

<script src="<?= e(asset('js/script.js')) ?>"></script>

</body></html>
