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
    <div class="nav-branding">
      <a <?= $isHome ? 'href="#hero" data-scroll="hero"' : 'href="' . e(url('/')) . '"' ?> class="nav-logo" target="_self" aria-label="AI Unit home">
        <img src="<?= e(asset('images/logo.webp')) ?>" alt="" class="nav-logo-img" width="300" height="200" decoding="async">
      </a>
      <a class="nav-ministry" href="https://mitci.govmu.org/mitci/" target="_blank" rel="noopener noreferrer">
        <span class="nav-ministry-mark">
          <img src="<?= e(asset('images/MLogo.png')) ?>" alt="" width="896" height="251" decoding="async">
        </span>
        <span class="nav-ministry-name">Ministry of Information Technology, Communication and Innovation</span>
        <span class="visually-hidden"> (opens in a new tab)</span>
      </a>
    </div>
    <div class="nav-right">
      <?php
      /**
       * AI Marketplace: a persistent primary action, not a nav link.
       *
       * Before this, the marketplace had exactly two entry points, both on the
       * homepage - the hero CTA and the card block in the middle of the page.
       * Anyone who scrolled past the hero, or who arrived on any other page,
       * had no route to it at all. It is a separate site, so it carries the
       * outbound icon, rel="noopener" and a spoken "opens in a new tab".
       *
       * Colour matches .btn-primary (the hero CTA) so both read as the same
       * destination rather than two different offers.
       */
      ?>
      <a href="https://aimarketplace.govmu.org/" class="nav-marketplace" target="_blank" rel="noopener noreferrer">
        <span data-i18n="nav_marketplace">AI Marketplace</span>
        <svg aria-hidden="true" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
          <polyline points="15 3 21 3 21 9"/>
          <line x1="10" y1="14" x2="21" y2="3"/>
        </svg>
        <span class="visually-hidden">(opens in a new tab)</span>
      </a>
      <button class="hamburger" id="hamburger" aria-controls="navLinks" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
    <nav class="nav-links" id="navLinks" aria-label="Site sections">
      <?php
      /**
       * Order is deliberate: AI Lab, AI in Action, AI Framework, Highlights,
       * About Us, Contact Us.
       *
       * AI Lab and Highlights target pages rather than homepage sections, so
       * they carry a plain href and no data-scroll. They use aria-current for
       * the highlight instead of the .active class: the scroll spy in
       * script.js reassigns .active across every .nav-link on each scroll event
       * and would strip a server-rendered one on the first wheel movement.
       */
      ?>
      <a href="<?= e(url('ai-lab')) ?>" class="nav-link" data-i18n="nav_ailab"<?= $navCurrent === 'ai-lab' ? ' aria-current="page"' : '' ?>>AI Lab</a>
      <a <?= $navTarget('action') ?> class="nav-link" data-i18n="nav_action">AI in Action</a>
      <a <?= $navTarget('strategy') ?> class="nav-link" data-i18n="nav_framework">AI Framework</a>
      <a href="<?= e(url('highlights')) ?>" class="nav-link" data-i18n="nav_highlights"<?= $navCurrent === 'highlights' ? ' aria-current="page"' : '' ?>>Highlights</a>
      <a <?= $navTarget('about-combined') ?> class="nav-link" data-i18n="nav_about">About Us</a>
      <a <?= $navTarget('contact') ?> class="nav-link" data-i18n="nav_contact">Contact Us</a>
    </nav>
  </div>
</header>
