<?php
/**
 * Page metadata: description, canonical, robots and Open Graph.
 *
 * One partial for the whole site. It is included by includes/header.php (the
 * shared layout) and by the three standalone views that carry their own <head>
 * - document, booklet and video - so those cannot quietly fall behind the
 * pages that use the layout.
 *
 * Everything is driven by variables the controller already passes into the
 * view, alongside $title. Nothing here needs a per-page copy of the tag list:
 *
 *   $title          page title, without the site suffix (existing convention)
 *   $description    one sentence describing THIS page - required for a public
 *                   page; a shared fallback is a wasted search result
 *   $canonicalPath  route path this page should be indexed as, when it differs
 *                   from the requested one (/home canonicalises to /)
 *   $ogType         "website" (default) or "article"
 *   $ogImage        asset path for a page-specific card; defaults to the
 *                   site's own social image
 *   $noindex        true for anything that must stay out of search results
 */

$noindex = $noindex ?? false;

/*
 * Admin screens and error pages stop here. They get the robots directive and
 * nothing else: a canonical or an og:url for a page that must never be indexed
 * or shared is at best noise, and at worst an invitation to crawl it.
 */
if ($noindex) {
    echo '<meta name="robots" content="noindex, nofollow">' . "\n";

    return;
}

$title = $title ?? '';
$description = trim((string) ($description ?? ''));
$ogType = $ogType ?? 'website';

$canonical = canonical_url($canonicalPath ?? null);

// Falls back to the site name so the card is never blank, but every public
// route passes a real title.
$ogTitle = $title !== '' ? $title . ' - ' . config('site.name') : (string) config('site.full_name');

$ogImagePath = $ogImage ?? 'images/og-image.png';
$ogImageUrl = asset_url($ogImagePath);
$ogImageFile = dirname(__DIR__) . '/public/assets/' . ltrim($ogImagePath, '/');
$ogImageSize = @getimagesize($ogImageFile);
?>
<?php if ($description !== ''): ?>
<meta name="description" content="<?= e($description) ?>">
<?php endif; ?>
<link rel="canonical" href="<?= e($canonical) ?>">
<meta property="og:site_name" content="<?= e((string) config('site.name')) ?>">
<meta property="og:locale" content="en_GB">
<meta property="og:type" content="<?= e($ogType) ?>">
<meta property="og:title" content="<?= e($ogTitle) ?>">
<?php if ($description !== ''): ?>
<meta property="og:description" content="<?= e($description) ?>">
<?php endif; ?>
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e($ogImageUrl) ?>">
<?php if ($ogImageSize !== false): ?>
<meta property="og:image:width" content="<?= e((string) $ogImageSize[0]) ?>">
<meta property="og:image:height" content="<?= e((string) $ogImageSize[1]) ?>">
<meta property="og:image:type" content="<?= e($ogImageSize['mime']) ?>">
<?php endif; ?>
<meta property="og:image:alt" content="AI Unit - Ministry of Information Technology, Communication and Innovation, Republic of Mauritius">
