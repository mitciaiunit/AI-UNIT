/* ===========================================================================
   AI Lab - Calendly embed loader
   ---------------------------------------------------------------------------
   Loaded only on /ai-lab (see $pageScripts in PageController::aiLab).

   The page is fully usable before this file runs: the "Open the booking
   calendar on Calendly" link is real markup and points at the same scheduling
   page the embed shows. This script is an enhancement that replaces that
   round-trip with an inline calendar, and it deliberately never removes the
   link until an embed has actually rendered.

   Nothing here handles booking data. Calendly owns the entire transaction; the
   only value this file touches is the public scheduling URL, read from a data
   attribute the server rendered and validated.
   ======================================================================== */

(function () {
  'use strict';

  var WIDGET_SRC = 'https://assets.calendly.com/assets/external/widget.js';

  /* How long to wait for Calendly to paint an iframe before giving up and
     leaving the fallback link in place. Generous, because a slow connection
     should not cost someone the inline calendar - but bounded, so a silently
     blocked script does not leave the container empty forever. */
  var EMBED_TIMEOUT_MS = 8000;

  var container = document.getElementById('calendlyEmbed');
  if (!container) return;

  var url = container.getAttribute('data-calendly-url');
  if (!url) return;

  /* The server only ever writes an https://calendly.com URL here, but this is
     the point where a string becomes a script's input, so it is checked again
     rather than assumed. */
  var parsed;
  try {
    parsed = new URL(url, window.location.href);
  } catch (e) {
    return;
  }
  if (parsed.protocol !== 'https:') return;
  if (parsed.hostname !== 'calendly.com' && !/\.calendly\.com$/.test(parsed.hostname)) return;

  /** Marks the embed as live, which hides the fallback link via CSS. */
  function markLoaded() {
    container.classList.add('is-loaded');
  }

  /* Calendly gives no "rendered" callback, so success is observed: the widget
     injects an <iframe> into the container. */
  function watchForIframe() {
    if (container.querySelector('iframe')) {
      markLoaded();
      return;
    }

    var settled = false;

    var observer = new MutationObserver(function () {
      if (settled) return;
      if (container.querySelector('iframe')) {
        settled = true;
        observer.disconnect();

        /* The iframe exists but is still blank at this point. Naming it now
           means assistive technology announces something meaningful if it
           reaches the frame before Calendly's own title is applied; if Calendly
           sets its own, that simply wins. */
        var frame = container.querySelector('iframe');
        if (frame && !frame.getAttribute('title')) {
          frame.setAttribute('title', 'AI Lab booking calendar');
        }

        markLoaded();
      }
    });

    observer.observe(container, { childList: true, subtree: true });

    window.setTimeout(function () {
      if (settled) return;
      settled = true;
      observer.disconnect();
      // No iframe: leave the fallback link visible. Nothing else to do.
    }, EMBED_TIMEOUT_MS);
  }

  function initWidget() {
    if (!window.Calendly || typeof window.Calendly.initInlineWidget !== 'function') {
      return; // Script loaded but did not expose its API - keep the fallback.
    }

    watchForIframe();

    try {
      window.Calendly.initInlineWidget({
        url: parsed.href,
        parentElement: container
      });
    } catch (e) {
      // Any failure here leaves the fallback link in place, which is the
      // working path anyway.
    }
  }

  // Already present (e.g. a cached script or a second init) - just use it.
  if (window.Calendly && typeof window.Calendly.initInlineWidget === 'function') {
    initWidget();
    return;
  }

  var script = document.createElement('script');
  script.src = WIDGET_SRC;
  script.async = true;
  script.onload = initWidget;
  script.onerror = function () {
    /* Blocked, offline, or Calendly unreachable. The fallback link is already
       on the page and remains the route to the same calendar, so there is
       nothing to clean up and nothing to tell the visitor. */
  };

  document.head.appendChild(script);
})();
