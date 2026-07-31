<?php
/**
 * Sticky site navbar, shared by every page. On the homepage ($isHome = true)
 * the nav links smooth-scroll to in-page sections (data-scroll, handled by
 * assets/js/script.js). On every other page they link back to the matching
 * homepage section instead, since the section elements don't exist there.
 */
$isHome = $isHome ?? false;

/**
 * Slug of the standalone page currently being viewed, set by the controller.
 * Used to mark that page's nav link as current; empty on section-based pages,
 * where the highlight is driven by the scroll spy in assets/js/script.js.
 */
$navCurrent = $navCurrent ?? '';

/**
 * Build an href + optional data-scroll attribute for a nav link that targets
 * homepage section $sectionId.
 */
$navTarget = static function (string $sectionId) use ($isHome): string {
    if ($isHome) {
        return 'href="#' . $sectionId . '" data-scroll="' . $sectionId . '"';
    }

    return 'href="' . e(url('/') . '#' . $sectionId) . '"';
};
?>
<header class="navbar" id="navbar">
  <div class="nav-inner">
    <a <?= $isHome ? 'href="#hero" data-scroll="hero"' : 'href="' . e(url('/')) . '"' ?> class="nav-logo" aria-label="AI Unit Home">
      <img src="<?= e(asset('images/logo.gif')) ?>" alt="AI Unit - Ministry of ICT, Mauritius" class="nav-logo-img">
      <div class="logo-text-wrap">
        <span class="logo-main">AI Unit</span>
        <span class="logo-sub">Ministry of ICT · Mauritius</span>
      </div>
    </a>
    <nav class="nav-links" id="navLinks" aria-label="Site sections">
      <a <?= $navTarget('action') ?> class="nav-link" data-i18n="nav_action">AI in Action</a>
      <a <?= $navTarget('strategy') ?> class="nav-link" data-i18n="nav_framework">AI Framework</a>
      <a <?= $navTarget('about-combined') ?> class="nav-link" data-i18n="nav_about">About Us</a>
      <a <?= $navTarget('contact') ?> class="nav-link" data-i18n="nav_contact">Contact Us</a>
      <?php
      /**
       * Unlike its neighbours this targets a page rather than a homepage
       * section, so it carries a plain href and no data-scroll. aria-current is
       * used for the highlight instead of the .active class: the scroll spy in
       * script.js reassigns .active across every .nav-link on each scroll event
       * and would strip a server-rendered one on the first wheel movement.
       */
      ?>
      <a href="<?= e(url('highlights')) ?>" class="nav-link" data-i18n="nav_highlights"<?= $navCurrent === 'highlights' ? ' aria-current="page"' : '' ?>>Highlights</a>
    </nav>
    <div class="nav-right">
      <button class="hamburger" id="hamburger" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
