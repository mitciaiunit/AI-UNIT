/* Loaded only by pages/student-corner.php (see includes/layouts/app.php's
   $pageScripts). Ported unchanged from the supplied standalone page.

   Two of its five modules intentionally no-op here: `initNav` and the scroll-spy
   half of `initHeader` looked for the standalone page's own #siteNav / #navToggle
   / .site-nav__link, which the shared AI-UNIT navbar replaces. Both guard for
   missing markup and exit quietly, so the reading-progress bar, scroll reveal,
   timeline rail and lightbox all continue to run. */
/* ===========================================================================
   Students & Internships - AI Unit Internship Case Study
   Vanilla JavaScript, no dependencies, no build step.
   ---------------------------------------------------------------------------
   Each module is self-contained: if its markup is absent it exits quietly,
   and every enhancement degrades to working HTML without JavaScript.

   Contents
     00. Shared utilities
     01. Mobile navigation
     02. Header state, scroll spy & reading progress
     03. Scroll reveal (fade-up, fade-left/right, image reveal)
     04. Timeline progress rail
     05. Gallery lightbox
   ======================================================================== */

(function () {
  'use strict';

  /* =========================================================================
     00. Shared utilities
     ====================================================================== */

  var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
  var $$ = function (sel, ctx) {
    return Array.prototype.slice.call((ctx || document).querySelectorAll(sel));
  };

  var motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

  /** Read the preference each time - it can change mid-session. */
  function prefersReducedMotion() { return motionQuery.matches; }

  /** Run a callback at most once per animation frame. */
  function rafThrottle(fn) {
    var queued = false;
    return function () {
      if (queued) return;
      queued = true;
      window.requestAnimationFrame(function () {
        queued = false;
        fn();
      });
    };
  }

  function clamp(value, min, max) {
    return Math.min(Math.max(value, min), max);
  }

  var FOCUSABLE = [
    'a[href]', 'button:not([disabled])', 'input:not([disabled])',
    'select:not([disabled])', 'textarea:not([disabled])',
    '[tabindex]:not([tabindex="-1"])'
  ].join(',');

  /** Keep Tab focus inside `container` while an overlay is open. */
  function trapFocus(container, event) {
    if (event.key !== 'Tab') return;

    var items = $$(FOCUSABLE, container).filter(function (el) {
      return el.offsetParent !== null || el === document.activeElement;
    });
    if (!items.length) return;

    var first = items[0];
    var last = items[items.length - 1];

    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  }

  /* Scroll locking: swap the scrollbar for padding so opening an overlay
     does not shift the page sideways. */
  function lockScroll() {
    var gap = window.innerWidth - document.documentElement.clientWidth;
    if (gap > 0) document.body.style.paddingRight = gap + 'px';
    document.body.classList.add('is-locked');
  }

  function unlockScroll() {
    document.body.style.paddingRight = '';
    document.body.classList.remove('is-locked');
  }


  /* =========================================================================
     01. Mobile navigation
     ====================================================================== */

  (function initNav() {
    var toggle = $('#navToggle');
    var nav = $('#siteNav');
    if (!toggle || !nav) return;

    function setOpen(open) {
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      nav.classList.toggle('is-open', open);
    }

    toggle.addEventListener('click', function () {
      setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    // Choosing a destination closes the sheet.
    nav.addEventListener('click', function (event) {
      if (event.target.closest('a')) setOpen(false);
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
        setOpen(false);
        toggle.focus();
      }
    });

    // Clicking away dismisses an open sheet.
    document.addEventListener('click', function (event) {
      if (toggle.getAttribute('aria-expanded') !== 'true') return;
      if (nav.contains(event.target) || toggle.contains(event.target)) return;
      setOpen(false);
    });

    // Returning to the desktop layout must not leave the sheet stuck open.
    var wide = window.matchMedia('(min-width: 901px)');
    if (typeof wide.addEventListener === 'function') {
      wide.addEventListener('change', function (event) {
        if (event.matches) setOpen(false);
      });
    }
  })();


  /* =========================================================================
     02. Header state, scroll spy & reading progress
     ====================================================================== */

  (function initHeader() {
    var header = $('#siteHeader');
    var progressBar = $('#scrollProgressBar');
    var links = $$('.site-nav__link');

    var sections = links
      .map(function (link) {
        var href = link.getAttribute('href');
        return href && href.charAt(0) === '#' ? document.getElementById(href.slice(1)) : null;
      })
      .filter(Boolean);

    var update = rafThrottle(function () {
      var y = window.scrollY || window.pageYOffset;

      if (header) header.classList.toggle('is-scrolled', y > 12);

      // Reading progress across the whole document.
      if (progressBar) {
        var scrollable = document.documentElement.scrollHeight - window.innerHeight;
        var ratio = scrollable > 0 ? clamp(y / scrollable, 0, 1) : 0;
        progressBar.style.width = (ratio * 100).toFixed(2) + '%';
      }

      if (!sections.length) return;

      // The current section is the last one whose top has crossed a reading
      // line set a third of the way down the viewport.
      var line = y + window.innerHeight * 0.34;
      var activeId = null;

      sections.forEach(function (section) {
        if (section.offsetTop <= line) activeId = section.id;
      });

      // At the very bottom, always highlight the final section.
      if (window.innerHeight + y >= document.body.scrollHeight - 4) {
        activeId = sections[sections.length - 1].id;
      }

      links.forEach(function (link) {
        link.classList.toggle('is-active', link.getAttribute('href') === '#' + activeId);
      });
    });

    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update, { passive: true });
    update();
  })();


  /* =========================================================================
     03. Scroll reveal
     Adding `.is-revealed` also drives the nested image reveal and the
     timeline marker fill, both handled in CSS.
     ====================================================================== */

  (function initReveal() {
    var items = $$('[data-reveal]');
    if (!items.length) return;

    // Without IntersectionObserver, or with reduced motion, show everything.
    if (!('IntersectionObserver' in window) || prefersReducedMotion()) {
      items.forEach(function (el) { el.classList.add('is-revealed'); });
      return;
    }

    var revealedAny = false;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;

        var delay = entry.target.getAttribute('data-reveal-delay');
        if (delay) entry.target.style.setProperty('--reveal-delay', delay + 'ms');

        entry.target.classList.add('is-revealed');
        revealedAny = true;
        observer.unobserve(entry.target); // reveal once, then stop watching
      });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.1 });

    items.forEach(function (el) { observer.observe(el); });

    // Safety net: the page starts with these blocks at opacity 0, so if the
    // observer has not fired at all shortly after load, show everything
    // rather than leave the visitor looking at a blank page.
    window.setTimeout(function () {
      if (revealedAny) return;
      observer.disconnect();
      items.forEach(function (el) { el.classList.add('is-revealed'); });
    }, 1500);
  })();


  /* =========================================================================
     04. Timeline progress rail
     Fills the vertical rail in step with how far the reader has travelled.
     ====================================================================== */

  (function initTimeline() {
    var timeline = $('.timeline');
    if (!timeline) return;

    var update = rafThrottle(function () {
      var rect = timeline.getBoundingClientRect();
      var line = window.innerHeight * 0.55;
      var progress = clamp((line - rect.top) / rect.height, 0, 1);
      timeline.style.setProperty('--timeline-progress', (progress * 100).toFixed(2) + '%');
    });

    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update, { passive: true });
    update();
  })();


  /* =========================================================================
     05. Gallery lightbox
     ====================================================================== */

  (function initLightbox() {
    var lightbox = $('#lightbox');
    var imageEl = $('#lightboxImage');
    var captionEl = $('#lightboxCaption');
    var triggers = $$('[data-lightbox]');
    if (!lightbox || !imageEl || !captionEl || !triggers.length) return;

    var dialog = $('.lightbox__dialog', lightbox);
    var counterEl = $('.lightbox__counter', lightbox);
    var closeBtn = $('[data-close-lightbox]', dialog);
    var lastFocused = null;
    var index = 0;

    // Snapshot the gallery once; each entry drives one lightbox view.
    var slides = triggers.map(function (trigger) {
      var img = $('img', trigger);
      var title = $('.gallery__caption strong', trigger);
      var detail = $('.gallery__caption span', trigger);
      return {
        src: img ? img.getAttribute('src') : '',
        alt: img ? img.getAttribute('alt') : '',
        caption: [
          title ? title.textContent.trim() : '',
          detail ? detail.textContent.trim() : ''
        ].filter(Boolean).join(' - ')
      };
    });

    function show(next) {
      index = (next + slides.length) % slides.length; // wraps both directions
      var slide = slides[index];
      imageEl.setAttribute('src', slide.src);
      imageEl.setAttribute('alt', slide.alt);
      captionEl.textContent = slide.caption;
      if (counterEl) counterEl.textContent = (index + 1) + ' of ' + slides.length;
    }

    function openLightbox(startIndex, trigger) {
      lastFocused = trigger;
      show(startIndex);
      lightbox.hidden = false;
      lockScroll();
      if (closeBtn) closeBtn.focus();
    }

    function closeLightbox() {
      if (lightbox.hidden) return;
      lightbox.hidden = true;
      unlockScroll();
      if (lastFocused) lastFocused.focus();
      lastFocused = null;
    }

    triggers.forEach(function (trigger, i) {
      trigger.addEventListener('click', function () { openLightbox(i, trigger); });
    });

    $$('[data-close-lightbox]', lightbox).forEach(function (el) {
      el.addEventListener('click', closeLightbox);
    });

    var prev = $('[data-lightbox-prev]', lightbox);
    var next = $('[data-lightbox-next]', lightbox);
    if (prev) prev.addEventListener('click', function () { show(index - 1); });
    if (next) next.addEventListener('click', function () { show(index + 1); });

    document.addEventListener('keydown', function (event) {
      if (lightbox.hidden) return;

      switch (event.key) {
        case 'Escape':
          closeLightbox();
          break;
        case 'ArrowLeft':
          event.preventDefault();
          show(index - 1);
          break;
        case 'ArrowRight':
          event.preventDefault();
          show(index + 1);
          break;
        default:
          trapFocus(dialog, event);
      }
    });
  })();

})();
