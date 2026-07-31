<?php
/**
 * Master layout for every "full site" page (home, privacy-policy, disclaimer,
 * cookie-policy, accessibility, student-corner). Wraps the page's $content with
 * the shared header, navbar, footer, cookie banner, video modal, and DIVA
 * widget - the components the task asks to be de-duplicated into single
 * shared includes. Accessibility tooling is provided by the third-party
 * accessibility-widget.js (self-injecting; no markup include needed).
 */
$isHome = $isHome ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require __DIR__ . '/../header.php'; ?>
</head>

<body>

<?php require __DIR__ . '/../skip-link.php'; ?>

<div id="simple-announcer" class="visually-hidden" aria-live="polite" aria-atomic="true"></div>

<div class="listen-offer" role="region" aria-label="Listen to this page">
  <button type="button" class="listen-offer-btn" id="listen-page" aria-pressed="false">
    <span data-i18n="listen_page">Listen to this page</span>
  </button>
</div>

<?php require __DIR__ . '/../navbar.php'; ?>

<?= $content ?>

<?php require __DIR__ . '/../footer.php'; ?>

<?php require __DIR__ . '/../video-modal.php'; ?>

<?php require __DIR__ . '/../diva-widget.php'; ?>

<?php
/**
 * Runtime config handed to assets/js/script.js. Keeps environment-specific
 * values (install sub-path, DIVA endpoint) out of the JavaScript file so it
 * stays a static, cacheable asset that works regardless of where the site is
 * installed. Must be emitted before script.js loads.
 */
$jsConfig = [
    'assetBase' => rtrim((string) config('site.root_url'), '/') . rtrim((string) config('site.asset_path'), '/'),
    'divaApiUrl' => (string) config('diva.api_url'),
];
?>
<script>window.AI_UNIT = <?= json_encode($jsConfig, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>;</script>
<script src="<?= e(asset('js/script.js')) ?>"></script>
<script src="<?= e(asset('js/accessibility-widget.js')) ?>"></script>
<?php
/**
 * Optional per-page scripts (filenames under assets/js/), for pages that ship
 * behaviour of their own. Deferred so they never block parsing, and loaded
 * after the shared scripts so the shared ones are already in place.
 */
foreach ($pageScripts ?? [] as $pageScript): ?>
<script src="<?= e(asset('js/' . $pageScript)) ?>" defer></script>
<?php endforeach; ?>

</body></html>
