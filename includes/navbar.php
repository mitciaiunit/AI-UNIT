<?php
/**
 * Sticky site navbar, shared by every page. On the homepage ($isHome = true)
 * the nav links smooth-scroll to in-page sections (data-scroll, handled by
 * assets/js/script.js). On every other page they link back to the matching
 * homepage section instead, since the section elements don't exist there.
 */
$isHome = $isHome ?? false;

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
<header class="navbar" id="navbar" role="navigation" aria-label="Main navigation">
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
    </nav>
    <div class="nav-right">
      <button class="btn-search" aria-label="Search" id="searchBtn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <span data-i18n="search">Search</span>
      </button>
      <div class="a11y-trigger-wrap" id="a11y-trigger" role="button" tabindex="0" aria-label="Accessibility Tools" aria-haspopup="dialog" aria-expanded="false" aria-controls="a11y-panel">
        <img src="<?= e(asset('images/accessibility.png')) ?>" alt="" aria-hidden="true" class="a11y-trigger-img"
             onerror="this.style.display='none';this.nextElementSibling.style.display='inline-block'" />
        <svg aria-hidden="true" style="display:none;flex-shrink:0;color:var(--blue);" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="5" r="2"/><path d="M12 22v-8"/><path d="M5 9l7-2 7 2"/><path d="M5 9l2 6h10l2-6"/>
        </svg>
        <span data-i18n="accessibility">Accessibility</span>
      </div>
      <button class="hamburger" id="hamburger" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
