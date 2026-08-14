/* ==========================================================
   ACCESSIBILITY WIDGET - single <script> embed

   Drop this one line into any page:
     <script src="accessibility-widget.js"></script>
   It injects its own fonts, CSS, and markup, and wires itself up -
   nothing else needs to be added. The trigger button is moved into the
   page's navbar (.nav-right) if one is found, otherwise it falls back
   to floating fixed in the bottom-right corner.
   ========================================================== */
(function () {
  'use strict';
  if (window.__A11Y_WIDGET_LOADED__) return;
  window.__A11Y_WIDGET_LOADED__ = true;

  // Set right before a programmatic focus() restores focus after the panel
  // closes, so the focusin listener below can tell that apart from the user
  // actually tabbing/clicking elsewhere. Without this, closing the panel
  // (close button, backdrop, Escape) refocuses whatever had focus before it
  // opened, which the reader can't distinguish from real navigation - so it
  // interrupted the say-all and announced the refocused control instead of
  // continuing to read, making the reader look dead the moment the panel closed.
  var a11ySkipNextFocusAnnounce = false;

  // Captured synchronously so it's still available once injectAssets() runs
  // later (on DOMContentLoaded), after document.currentScript has reset.
  // Resolving the icon relative to this script's own URL (rather than a
  // hardcoded root-absolute path) keeps it correct whether the site is
  // served from a domain root or a subfolder (e.g. XAMPP's /AI-UNIT/).
  var WIDGET_SCRIPT_SRC = document.currentScript ? document.currentScript.src : '';

  function resolveAsset(relativeToScript) {
    if (!WIDGET_SCRIPT_SRC) return relativeToScript;
    try {
      return new URL(relativeToScript, WIDGET_SCRIPT_SRC).href;
    } catch (err) {
      return relativeToScript;
    }
  }

  function injectAssets() {
    var iconSrc = resolveAsset('../images/accessibility.png');
    var pre = document.createElement('link');
    pre.rel = 'preconnect';
    pre.href = 'https://fonts.googleapis.com';
    document.head.appendChild(pre);

    var font = document.createElement('link');
    font.rel = 'stylesheet';
    font.href = 'https://fonts.googleapis.com/css2?family=Lora:wght@600;700&family=Sora:wght@400;500;600;700&display=swap';
    document.head.appendChild(font);

    var style = document.createElement('style');
    style.textContent = `:root {
  --bg: #F7F8FC;
  --surface: #FFFFFF;
  --surface-2: #F0F2F9;
  --surface-3: #E8EBF5;
  --blue: #1A3A8F;
  --blue-mid: #2554C7;
  --blue-light: #D6E0FB;
  --blue-pale: #EEF2FF;
  --blue-glow: rgba(37,84,199,0.15);
  --teal: #0B7285;
  --teal-light: #C3F0FA;
  --teal-pale: #F0FDFF;
  --gold: #B45309;
  --gold-light: #FEF3C7;
  --emerald: #047857;
  --emerald-light: #D1FAE5;
  --violet: #5B21B6;
  --violet-light: #EDE9FE;
  --amber: #D97706;
  --amber-light: #FEF3C7;
  --amber-pale: #FFFBEB;
  --sage: #059669;
  --sage-light: #D1FAE5;
  --sage-pale: #ECFDF5;
  --coral: #92400E;
  --coral-light: #FEF3C7;
  --coral-pale: #FFFBEB;
  --warm-gray: #78716C;
  --warm-gray-light: #F5F5F4;
  --text-1: #0D1B3E;
  --text-2: #2D3A5A;
  --text-3: #4B5E77;
  --text-4: #6B7E96;
  --border: #DDE2EF;
  --border-2: #C8CFE4;
  --radius: 18px;
  --radius-sm: 10px;
  --trans: 0.24s cubic-bezier(0.4,0,0.2,1);
  --shadow-sm: 0 1px 4px rgba(13,27,62,0.07);
  --shadow-md: 0 4px 18px rgba(13,27,62,0.09);
  --shadow-lg: 0 12px 40px rgba(13,27,62,0.12);
  --shadow-xl: 0 24px 64px rgba(13,27,62,0.14);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Sora',sans-serif;background:var(--bg);color:var(--text-1);}

/* ==========================================================
   ACCESSIBILITY WIDGET - CSS
   Class names / IDs match accessibility.html, and are toggled
   by a11y-widget.js. Uses the design tokens from style.css
   (--blue, --teal, --radius, --shadow-md, 'Sora' / 'Lora' fonts).
   ========================================================== */

/* ---- TRIGGER BUTTON ----
   Default rule below is a fixed floating fallback for pages with no
   navbar. a11y-widget.js looks for .nav-right and, when found, moves the
   trigger there and adds .a11y-trigger-navbar, which overrides it to sit
   inline alongside the search/hamburger buttons instead of floating. */
.a11y-trigger-wrap{
  position:fixed;bottom:20px;right:20px;z-index:2001;
  display:flex;align-items:center;gap:8px;
  padding:8px 14px;border-radius:100px;
  cursor:pointer;
  color:var(--text-2);
  font-family:'Sora',sans-serif;
  font-size:0.84rem;font-weight:600;
  border:1.5px solid var(--border);
  background:var(--surface);
  box-shadow:var(--shadow-md);
  transition:all var(--trans);
}
.a11y-trigger-wrap:hover,
.a11y-trigger-wrap:focus-visible{
  border-color:var(--blue-mid);
  color:var(--blue-mid);
  background:var(--blue-pale);
}
.a11y-trigger-wrap[aria-expanded="true"]{
  border-color:var(--blue);
  background:var(--blue-pale);
  color:var(--blue);
}
.a11y-trigger-wrap.a11y-trigger-navbar{
  position:static;
  bottom:auto;right:auto;
  height:36px;padding:0 12px;
  box-shadow:none;
}
.a11y-trigger-img{width:22px;height:22px;object-fit:contain;flex-shrink:0;}
.a11y-trigger-wrap span{white-space:nowrap;}
@media(max-width:900px){.a11y-trigger-wrap span{display:none;}.a11y-trigger-wrap{padding:8px;}.a11y-trigger-wrap.a11y-trigger-navbar{padding:0 8px;}}

/* ---- PANEL SHELL ---- */
#a11y-panel{
  position:fixed;
  top:0;right:0;
  height:100vh;
  width:400px;
  max-width:92vw;
  background:var(--surface);
  box-shadow:var(--shadow-xl);
  border-left:1.5px solid var(--border);
  z-index:2000;
  overflow-y:auto;
  transform:translateX(100%);
  /* visibility is delayed on the way in (0s 0s) but instant on the way out
     (0s .3s) so the slide animation still plays, while removing the closed
     panel from the accessibility tree - see openPanel()/closePanel(), which
     toggle aria-modal in lockstep with this class. Without this, NVDA (which
     enforces aria-modal strictly) treats the still-present, merely
     off-screen panel as the active modal from first page load and hides the
     entire rest of the document, even though nothing looks wrong visually. */
  visibility:hidden;
  transition:transform 0.32s cubic-bezier(0.4,0,0.2,1), visibility 0s 0.32s;
  font-family:'Sora',sans-serif;
  padding-bottom:24px;
}
#a11y-panel.open{transform:translateX(0);visibility:visible;transition:transform 0.32s cubic-bezier(0.4,0,0.2,1), visibility 0s 0s;}
#a11y-panel::-webkit-scrollbar{width:6px;}
#a11y-panel::-webkit-scrollbar-thumb{background:var(--border-2);border-radius:4px;}

/* Backdrop - add <div id="a11y-backdrop"></div> in your JS if you want a dim overlay */
#a11y-backdrop{
  position:fixed;inset:0;
  background:rgba(13,27,62,0.35);
  z-index:1999;
  opacity:0;pointer-events:none;
  transition:opacity 0.3s ease;
}
#a11y-backdrop.show{opacity:1;pointer-events:auto;}

/* ---- HEADER ---- */
.a11y-header{
  position:sticky;top:0;z-index:2;
  display:flex;align-items:flex-start;justify-content:space-between;gap:12px;
  padding:22px 22px 18px;
  background:linear-gradient(135deg,var(--blue),var(--teal));
  color:white;
}
.a11y-header-left{display:flex;align-items:flex-start;gap:12px;}
.a11y-header-icon{
  width:40px;height:40px;border-radius:12px;flex-shrink:0;
  background:rgba(255,255,255,0.15);
  display:flex;align-items:center;justify-content:center;
}
.a11y-header h2{font-family:'Lora',serif;font-size:1.2rem;font-weight:700;color:white;margin-bottom:2px;}
.a11y-header p{font-size:0.8rem;color:rgba(255,255,255,0.82);}
.a11y-header-right{display:flex;align-items:center;gap:8px;flex-shrink:0;}
.a11y-reset{
  background:rgba(255,255,255,0.15);
  border:1px solid rgba(255,255,255,0.3);
  color:white;
  font-size:0.76rem;font-weight:700;
  padding:6px 12px;border-radius:100px;
  cursor:pointer;transition:background 0.2s;
}
.a11y-reset:hover{background:rgba(255,255,255,0.28);}
.a11y-close{
  width:32px;height:32px;border-radius:50%;
  background:rgba(255,255,255,0.15);
  border:1px solid rgba(255,255,255,0.3);
  color:white;
  display:flex;align-items:center;justify-content:center;
  cursor:pointer;font-size:1rem;transition:background 0.2s;
  flex-shrink:0;
}
.a11y-close:hover{background:rgba(255,255,255,0.28);}

/* ---- SCREEN READER BLOCK ---- */
.a11y-sr-first{
  margin:18px 22px;
  padding:18px;
  border-radius:var(--radius-sm);
  background:var(--blue-pale);
  border:1.5px solid var(--blue-light);
}
.a11y-sr-title{
  display:flex;align-items:center;gap:8px;
  font-size:0.82rem;font-weight:700;color:var(--blue);
  margin-bottom:14px;
}
.sr-main-btn{
  width:100%;
  display:flex;align-items:center;gap:12px;
  padding:12px 14px;
  background:white;
  border:1.5px solid var(--blue-light);
  border-radius:var(--radius-sm);
  cursor:pointer;
  transition:all var(--trans);
  text-align:left;
}
.sr-main-btn:hover{border-color:var(--blue-mid);box-shadow:var(--shadow-sm);}
.sr-main-btn.active{background:var(--blue);border-color:var(--blue);}
.sr-main-btn.active .sr-btn-label strong,
.sr-main-btn.active .sr-btn-label span{color:white;}
.sr-main-btn.active .sr-btn-icon{color:white;}
.sr-main-btn.paused{background:var(--amber-light);border-color:var(--amber);}
.sr-btn-icon{
  width:36px;height:36px;border-radius:50%;flex-shrink:0;
  background:var(--blue-light);color:var(--blue);
  display:flex;align-items:center;justify-content:center;
}
.sr-btn-label{display:flex;flex-direction:column;gap:2px;}
.sr-btn-label strong{font-size:0.88rem;color:var(--text-1);}
.sr-btn-label span{font-size:0.72rem;color:var(--text-3);}

.sr-kbd-hints{margin-top:12px;}
.sr-kbd-hints p{font-size:0.7rem;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:8px;}
.sr-kbd-grid{display:grid;grid-template-columns:1fr 1fr;gap:6px 12px;}
.sr-kbd-item{display:flex;align-items:center;gap:6px;font-size:0.76rem;color:var(--text-2);}
.sr-kbd-item kbd{
  font-family:monospace;font-size:0.7rem;font-weight:700;
  padding:2px 6px;border-radius:5px;
  background:white;border:1px solid var(--border-2);
  color:var(--text-2);
  box-shadow:0 1px 0 var(--border-2);
}

/* Persistent stop control for the built-in reader (Task 11b). Fixed near the
   top of the viewport rather than placed early in DOM order, since this
   widget only ever injects at the end of <body> (see boot()) - reordering
   that is out of scope ("do not refactor"). Only shown while SR.speaking, so
   it never competes for attention outside a reading session. Real visible
   text, not icon-only, per the same rule as every other control here. */
.sr-stop-floating{
  display:none;
  position:fixed;top:14px;left:50%;transform:translateX(-50%);
  z-index:100000;
  align-items:center;gap:8px;
  padding:10px 18px;
  background:var(--blue);color:white;
  border:none;border-radius:100px;
  font-family:'Sora',sans-serif;font-size:0.85rem;font-weight:700;
  cursor:pointer;
  box-shadow:0 6px 20px rgba(0,0,0,0.25);
}
.sr-stop-floating.visible{display:flex;}
.sr-stop-floating:hover{background:var(--blue-mid);}
.sr-stop-floating:focus-visible{outline:3px solid white;outline-offset:2px;}

.sr-status{
  min-height:0;
  font-size:0.76rem;color:var(--blue);font-weight:600;
  margin-top:10px;
}
.sr-status.active{
  padding:8px 10px;border-radius:8px;
  background:white;border:1px solid var(--blue-light);
}

.sr-speed-row{display:flex;align-items:center;gap:10px;margin-top:12px;}
.sr-speed-label{font-size:0.78rem;font-weight:600;color:var(--text-2);flex-shrink:0;}
.sr-speed-slider{
  flex:1;
  accent-color:var(--blue);
  cursor:pointer;
}

/* ---- QUICK PROFILES ---- */
.a11y-profiles{margin:0 22px 20px;}
.a11y-section-label{
  font-size:0.72rem;font-weight:700;color:var(--text-3);
  text-transform:uppercase;letter-spacing:0.08em;
  margin-bottom:10px;
}
.a11y-profiles-grid{
  display:grid;grid-template-columns:repeat(3,1fr);gap:8px;
}
.a11y-profile{
  display:flex;flex-direction:column;align-items:center;gap:6px;
  padding:12px 6px;
  border-radius:var(--radius-sm);
  border:1.5px solid var(--border);
  background:var(--surface);
  color:var(--text-2);
  cursor:pointer;
  transition:all var(--trans);
  font-size:0.72rem;font-weight:600;
  text-align:center;
}
.a11y-profile:hover{border-color:var(--blue-light);background:var(--blue-pale);color:var(--blue-mid);}
.a11y-profile[aria-pressed="true"]{
  border-color:var(--blue);
  background:var(--blue);
  color:white;
}

/* ---- GROUPS (shared shell for all sections below profiles) ---- */
.a11y-group{
  margin:0 22px 20px;
  padding-bottom:20px;
  border-bottom:1px solid var(--border);
}
.a11y-group:last-of-type{border-bottom:none;}
.a11y-group-title{
  display:flex;align-items:center;gap:8px;
  font-size:0.78rem;font-weight:700;color:var(--text-2);
  text-transform:uppercase;letter-spacing:0.05em;
  margin-bottom:12px;
}

/* Text size stepper */
.a11y-step-row{
  display:flex;align-items:center;justify-content:space-between;
  gap:12px;
  padding:6px;
  border-radius:100px;
  background:var(--surface-2);
  border:1.5px solid var(--border);
}
.a11y-step-btn{
  width:36px;height:36px;border-radius:50%;
  background:white;border:1.5px solid var(--border-2);
  color:var(--blue);font-size:1.1rem;font-weight:700;
  cursor:pointer;transition:all var(--trans);
  display:flex;align-items:center;justify-content:center;
}
.a11y-step-btn:hover{background:var(--blue);color:white;border-color:var(--blue);}
.a11y-step-val{font-size:0.88rem;font-weight:700;color:var(--text-1);min-width:44px;text-align:center;}

/* Colour mode row */
.a11y-color-row{
  display:grid;grid-template-columns:repeat(3,1fr);gap:8px;
  margin-bottom:14px;
}
.a11y-color-opt{
  padding:9px 6px;
  border-radius:8px;
  border:1.5px solid var(--border);
  background:var(--surface);
  color:var(--text-2);
  font-size:0.74rem;font-weight:600;
  cursor:pointer;
  transition:all var(--trans);
}
.a11y-color-opt:hover{border-color:var(--blue-light);}
.a11y-color-opt.active,
.a11y-color-opt[aria-pressed="true"]{
  background:var(--blue);
  border-color:var(--blue);
  color:white;
}

/* Toggle rows (shared by colour/reading/navigation groups) */
.a11y-controls{display:flex;flex-direction:column;gap:4px;}
.a11y-toggle-row{
  display:flex;align-items:center;justify-content:space-between;
  gap:12px;
  padding:10px 4px;
  cursor:pointer;
  border-radius:8px;
  transition:background 0.15s;
}
.a11y-toggle-row:hover{background:var(--surface-2);}
.a11y-toggle-row-left{display:flex;align-items:center;gap:10px;}
.a11y-toggle-icon{
  width:30px;height:30px;border-radius:8px;flex-shrink:0;
  background:var(--surface-2);color:var(--text-3);
  display:flex;align-items:center;justify-content:center;
}
.a11y-toggle-text{font-size:0.84rem;color:var(--text-2);font-weight:500;}

/* Toggle switch control */
.a11y-toggle-switch{
  appearance:none;-webkit-appearance:none;
  width:40px;height:24px;
  border-radius:100px;
  background:var(--border-2);
  position:relative;
  cursor:pointer;
  flex-shrink:0;
  transition:background 0.2s;
}
.a11y-toggle-switch::before{
  content:'';
  position:absolute;top:3px;left:3px;
  width:18px;height:18px;border-radius:50%;
  background:white;
  box-shadow:0 1px 3px rgba(0,0,0,0.25);
  transition:transform 0.2s;
}
.a11y-toggle-switch:checked{background:var(--blue);}
.a11y-toggle-switch:checked::before{transform:translateX(16px);}
.a11y-toggle-switch:focus-visible{outline:2px solid var(--blue-mid);outline-offset:2px;}

/* ---- KEYBOARD BAR (footer of panel) ---- */
.a11y-kbd-bar{
  margin:4px 22px 0;
  padding-top:14px;
  border-top:1px solid var(--border);
  font-size:0.72rem;
  color:var(--text-3);
  text-align:center;
}
.a11y-kbd-bar kbd{
  font-family:monospace;font-size:0.68rem;font-weight:700;
  padding:2px 6px;border-radius:5px;
  background:var(--surface-2);border:1px solid var(--border-2);
  color:var(--text-2);
  margin:0 2px;
}

/* ==========================================================
   GLOBAL EFFECTS - toggled via classes on <html> by a11y-widget.js
   ========================================================== */

/* Stop animations */
html.a11y-no-motion *,
html.a11y-no-motion *::before,
html.a11y-no-motion *::after{
  animation-duration:0.001ms !important;
  animation-iteration-count:1 !important;
  transition-duration:0.001ms !important;
  scroll-behavior:auto !important;
}

/* High contrast */
html.a11y-mode-high-contrast{filter:contrast(1.35);}
html.a11y-mode-high-contrast body{background:#fff;}

/* Dark mode: handled entirely in style.css, which redefines --bg/--surface/
   --text-* etc. under html.a11y-mode-dark so every component that already
   reads those tokens (which is nearly all of them) repaints correctly with
   no per-component rule needed here. See style.css for the token block and
   the handful of components (navbar, footer, DIVA widget, doc cards...) that
   set literal colours instead of tokens and so needed their own dark-mode
   override there. */

/* Greyscale */
html.a11y-mode-grayscale{filter:grayscale(1);}

/* Negative */
html.a11y-mode-negative{filter:invert(1) hue-rotate(180deg);}
html.a11y-mode-negative img,
html.a11y-mode-negative video{filter:invert(1) hue-rotate(180deg);}

/* Highlight links */
html.a11y-highlight-links a{
  outline:2px solid var(--amber) !important;
  background:var(--amber-light) !important;
  color:var(--coral) !important;
}

/* Hide images */
html.a11y-hide-images img,
html.a11y-hide-images video,
html.a11y-hide-images .hero-video{
  visibility:hidden !important;
}

/* Dyslexia-friendly font */
html.a11y-dyslexia body,
html.a11y-dyslexia h1,html.a11y-dyslexia h2,html.a11y-dyslexia h3{
  font-family:'Comic Sans MS','OpenDyslexic',Verdana,sans-serif !important;
}

/* Wider letter spacing */
html.a11y-wide-spacing body{letter-spacing:0.04em;word-spacing:0.12em;}

/* Bold focus outline */
html.a11y-bold-focus :focus-visible{
  outline:4px solid var(--amber) !important;
  outline-offset:3px !important;
}

/* Large cursor */
html.a11y-large-cursor,
html.a11y-large-cursor *{
  cursor:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path d="M4 4l7.07 17 2.51-7.39L21 11.07z" fill="%231A3A8F" stroke="white" stroke-width="1"/></svg>') 4 4, auto !important;
}

/* Reading guide line - top position follows cursor via a11y-widget.js */
#a11y-read-guide{
  position:fixed;
  left:0;right:0;
  height:36px;
  pointer-events:none;
  z-index:1998;
  display:none;
  border-top:2px solid var(--blue-mid);
  border-bottom:2px solid var(--blue-mid);
  background:rgba(37,84,199,0.06);
}
html.a11y-readguide-on #a11y-read-guide{display:block;}

/* Remove all styling - strips the page back to plain browser defaults
   (helpful for cognitive load: no colour, layout, or decoration to parse),
   while leaving the widget itself styled so it can still be switched off. */
html.a11y-no-style body{all:revert !important;background:#fff !important;color:#000 !important;}
html.a11y-no-style body *:not(#a11y-panel):not(#a11y-panel *):not(#a11y-trigger):not(#a11y-trigger *){
  all:revert !important;
}

/* Text size scaling - a11y-widget.js sets --a11y-font-scale on <html> */
html{ --a11y-font-scale:1; }
html[style*="--a11y-font-scale"] body{
  font-size:calc(16px * var(--a11y-font-scale));
}

/* ---- RESPONSIVE ---- */
@media(max-width:480px){
  #a11y-panel{width:100vw;}
  .a11y-profiles-grid{grid-template-columns:repeat(2,1fr);}
  .a11y-color-row{grid-template-columns:repeat(2,1fr);}
}

#a11y-sr-prompt{
  position:fixed;inset:0;z-index:2500;
  display:flex;align-items:center;justify-content:center;
  background:rgba(13,27,62,0.45);
  padding:20px;
}
#a11y-sr-prompt[hidden]{display:none;}
.a11y-sr-prompt-card{
  position:relative;
  background:var(--surface);
  border-radius:var(--radius);
  box-shadow:var(--shadow-xl);
  max-width:420px;width:100%;
  padding:28px;
  font-family:'Sora',sans-serif;
}
.a11y-sr-prompt-close{
  position:absolute;top:14px;right:14px;
  width:30px;height:30px;border-radius:50%;
  background:var(--surface-2);border:1.5px solid var(--border);
  color:var(--text-3);
  display:flex;align-items:center;justify-content:center;
  cursor:pointer;font-size:1.05rem;font-weight:700;line-height:1;padding:0;
  transition:all var(--trans);
}
.a11y-sr-prompt-close:hover{border-color:var(--blue-mid);color:var(--blue-mid);background:var(--blue-pale);}
.a11y-sr-prompt-close:focus-visible{outline:3px solid var(--blue-mid);outline-offset:2px;}
.a11y-sr-prompt-card h2{font-family:'Lora',serif;font-size:1.15rem;font-weight:700;color:var(--text-1);margin-bottom:10px;padding-right:28px;}
.a11y-sr-prompt-card p{font-size:0.88rem;color:var(--text-2);line-height:1.6;margin-bottom:20px;}
.a11y-sr-prompt-actions{display:flex;flex-direction:column;gap:10px;}
.a11y-sr-prompt-btn{
  padding:12px 18px;border-radius:100px;
  font-size:0.88rem;font-weight:600;font-family:'Sora',sans-serif;
  cursor:pointer;transition:all var(--trans);
  border:1.5px solid var(--blue);
}
.a11y-sr-prompt-btn.primary{background:var(--blue);color:white;}
.a11y-sr-prompt-btn.primary:hover{background:var(--blue-mid);border-color:var(--blue-mid);}
.a11y-sr-prompt-btn.ghost{background:white;color:var(--blue);}
.a11y-sr-prompt-btn.ghost:hover{background:var(--blue-pale);}
`;
    document.head.appendChild(style);

    document.body.insertAdjacentHTML('beforeend', `<div id="a11y-sr-prompt" role="dialog" aria-modal="true" aria-labelledby="a11y-sr-prompt-title" aria-describedby="a11y-sr-prompt-desc" hidden>
  <div class="a11y-sr-prompt-card">
    <button type="button" id="a11y-sr-prompt-close" class="a11y-sr-prompt-close" aria-label="Close">&times;</button>
    <h2 id="a11y-sr-prompt-title">Screen Reader</h2>
    <p id="a11y-sr-prompt-desc">This site has a built-in screen reader. If you already use your own assistive technology (NVDA, JAWS, VoiceOver, TalkBack, etc.), you may prefer to keep using that instead to avoid both reading at once.</p>
    <div class="a11y-sr-prompt-actions">
      <button type="button" id="a11y-sr-prompt-use" class="a11y-sr-prompt-btn primary">Use built-in screen reader</button>
      <button type="button" id="a11y-sr-prompt-own" class="a11y-sr-prompt-btn ghost">I'll use my own</button>
    </div>
  </div>
</div>

<button type="button" class="a11y-trigger-wrap" id="a11y-trigger" aria-label="Accessibility Tools" aria-haspopup="menu" aria-expanded="false" aria-controls="a11y-panel">
  <img src="${iconSrc}" alt="" aria-hidden="true" class="a11y-trigger-img"
       onerror="this.style.display='none';this.nextElementSibling.style.display='inline-block'" />
  <svg aria-hidden="true" style="display:none;flex-shrink:0;color:var(--blue);" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="12" cy="5" r="2"/><path d="M12 22v-8"/><path d="M5 9l7-2 7 2"/><path d="M5 9l2 6h10l2-6"/>
  </svg>
  <span data-i18n="accessibility">Accessibility</span>
</button>

<button type="button" id="sr-stop-floating" class="sr-stop-floating" aria-label="Stop reading aloud">
  <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="6" width="12" height="12" rx="1.5"/></svg>
  <span data-i18n="sr_stop_floating">Stop reading aloud (Alt+S)</span>
</button>

<div id="a11y-announcer" aria-live="polite" aria-atomic="true" role="status"></div>
<div id="a11y-read-guide" aria-hidden="true"></div>

<div id="a11y-panel" role="dialog" aria-label="Accessibility options">
  <div class="a11y-header">
    <div class="a11y-header-left">
      <div class="a11y-header-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
      </div>
      <div><h2 data-i18n="a11y_title">Accessibility</h2><p data-i18n="a11y_subtitle">Adjust this website to your needs</p></div>
    </div>
    <div class="a11y-header-right">
      <button class="a11y-reset" id="a11y-reset-btn" aria-label="Reset all settings" data-i18n="a11y_reset">Reset</button>
      <button class="a11y-close" id="a11y-close-btn" aria-label="Close accessibility panel">&times;</button>
    </div>
  </div>

  <div class="a11y-sr-first" role="region" aria-label="Screen Reader controls">
    <div class="a11y-sr-title">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1A3A8F" stroke-width="2.5"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
      <span data-i18n="a11y_sr_title">Screen Reader - Read this page aloud</span>
    </div>
    <button class="sr-main-btn" id="sr-read-btn" aria-label="Start reading page aloud">
      <span class="sr-btn-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
      </span>
      <span class="sr-btn-label" id="sr-read-label">
        <strong data-i18n="a11y_read_btn_label">Read page aloud</strong>
        <span data-i18n="a11y_read_btn_hint">Click to start &middot; Space to pause / resume</span>
      </span>
    </button>
    <div class="sr-kbd-hints" aria-label="Keyboard shortcuts for screen reader">
      <p data-i18n="a11y_kbd_hint">Keyboard shortcuts (while reading)</p>
      <div class="sr-kbd-grid">
        <div class="sr-kbd-item"><kbd>Alt</kbd>+<kbd>S</kbd> <span data-i18n="a11y_kbd_stop_alt">Stop (works with a screen reader running)</span></div>
        <div class="sr-kbd-item"><kbd>Space</kbd> <span data-i18n="a11y_kbd_playpause">Play / Pause</span></div>
        <div class="sr-kbd-item"><kbd>S</kbd> <span data-i18n="a11y_kbd_stop">Stop</span></div>
        <div class="sr-kbd-item"><kbd>&#8592;</kbd> <span data-i18n="a11y_kbd_slower">Slower</span></div>
        <div class="sr-kbd-item"><kbd>&#8594;</kbd> <span data-i18n="a11y_kbd_faster">Faster</span></div>
      </div>
    </div>
    <div class="sr-status" id="sr-status" role="status" aria-live="polite"></div>
    <div class="sr-speed-row">
      <span class="sr-speed-label" data-i18n="a11y_speed_label">Speed:</span>
      <input type="range" class="sr-speed-slider" id="sr-speed" min="0.5" max="2" step="0.1" value="1" aria-label="Reading speed">
      <span id="sr-speed-display" style="font-size:0.75rem;font-weight:700;color:#1A3A8F;min-width:32px;">1&times;</span>
    </div>
  </div>

  <div class="a11y-profiles">
    <div class="a11y-section-label" data-i18n="a11y_profiles_label">Quick Profiles</div>
    <div class="a11y-profiles-grid">
      <button class="a11y-profile" id="prof-vision" data-profile="vision" aria-pressed="false" title="Larger text, high contrast, clear focus"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg><span data-i18n="a11y_profile_vision">Low Vision</span></button>
      <button class="a11y-profile" id="prof-motor" data-profile="motor" aria-pressed="false" title="Big cursor, keyboard help"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg><span data-i18n="a11y_profile_motor">Motor</span></button>
      <button class="a11y-profile" id="prof-dyslexia" data-profile="dyslexia" aria-pressed="false" title="Dyslexia font, wide spacing, reading guide"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg><span data-i18n="a11y_profile_dyslexia">Dyslexia</span></button>
      <button class="a11y-profile" id="prof-cognitive" data-profile="cognitive" aria-pressed="false" title="Less movement, reading help, clear links"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><circle cx="12" cy="12" r="10"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg><span data-i18n="a11y_profile_cognitive">Cognitive</span></button>
      <button class="a11y-profile" id="prof-elderly" data-profile="elderly" aria-pressed="false" title="Large cursor, clear labels, visible links"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg><span data-i18n="a11y_profile_elderly">Senior</span></button>
    </div>
  </div>

  <div class="a11y-group">
    <div class="a11y-group-title"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg><span data-i18n="a11y_textsize">Text Size</span></div>
    <div class="a11y-step" id="step-fontsize">
      <div class="a11y-step-row">
        <button class="a11y-step-btn" id="font-dec" aria-label="Decrease text size">-</button>
        <span class="a11y-step-val" id="font-display" aria-live="polite">100%</span>
        <button class="a11y-step-btn" id="font-inc" aria-label="Increase text size">+</button>
      </div>
    </div>
  </div>

  <div class="a11y-group">
    <div class="a11y-group-title"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/></svg><span data-i18n="a11y_colour">Colour &amp; Display</span></div>
    <div class="a11y-color-block">
      <div class="a11y-color-row" role="group" aria-label="Colour mode">
        <button class="a11y-color-opt active" data-mode="normal" aria-pressed="true" data-i18n="a11y_colour_normal">Normal</button>
        <button class="a11y-color-opt" data-mode="high-contrast" aria-pressed="false" data-i18n="a11y_colour_highcontrast">High Contrast</button>
        <button class="a11y-color-opt" data-mode="dark" aria-pressed="false" data-i18n="a11y_colour_dark">Dark</button>
        <button class="a11y-color-opt" data-mode="grayscale" aria-pressed="false" data-i18n="a11y_colour_grayscale">Greyscale</button>
        <button class="a11y-color-opt" data-mode="negative" aria-pressed="false" data-i18n="a11y_colour_negative">Negative</button>
      </div>
    </div>
    <div class="a11y-controls">
      <label class="a11y-toggle-row" for="t-links" id="lbl-t-links"><div class="a11y-toggle-row-left"><span class="a11y-toggle-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></span><span class="a11y-toggle-text" data-i18n="a11y_toggle_links">Highlight Links</span></div><input type="checkbox" role="switch" class="a11y-toggle-switch" id="t-links"></label>
      <label class="a11y-toggle-row" for="t-images" id="lbl-t-images"><div class="a11y-toggle-row-left"><span class="a11y-toggle-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></span><span class="a11y-toggle-text" data-i18n="a11y_toggle_images">Hide Images</span></div><input type="checkbox" role="switch" class="a11y-toggle-switch" id="t-images"></label>
      <label class="a11y-toggle-row" for="t-motion" id="lbl-t-motion"><div class="a11y-toggle-row-left"><span class="a11y-toggle-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 9l5 3-5 3V9z"/><circle cx="12" cy="12" r="10"/><path d="M4.93 4.93l14.14 14.14"/></svg></span><span class="a11y-toggle-text" data-i18n="a11y_toggle_motion">Stop Animations</span></div><input type="checkbox" role="switch" class="a11y-toggle-switch" id="t-motion"></label>
      <label class="a11y-toggle-row" for="t-nostyle" id="lbl-t-nostyle"><div class="a11y-toggle-row-left"><span class="a11y-toggle-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg></span><span class="a11y-toggle-text" data-i18n="a11y_toggle_nostyle">Remove All Styling</span></div><input type="checkbox" role="switch" class="a11y-toggle-switch" id="t-nostyle"></label>
    </div>
  </div>

  <div class="a11y-group">
    <div class="a11y-group-title"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg><span data-i18n="a11y_reading">Reading &amp; Focus</span></div>
    <div class="a11y-controls">
      <label class="a11y-toggle-row" for="t-dyslexia" id="lbl-t-dyslexia"><div class="a11y-toggle-row-left"><span class="a11y-toggle-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7V4h16v3"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg></span><span class="a11y-toggle-text" data-i18n="a11y_toggle_dyslexia">Dyslexia-Friendly Font</span></div><input type="checkbox" role="switch" class="a11y-toggle-switch" id="t-dyslexia"></label>
      <label class="a11y-toggle-row" for="t-readguide" id="lbl-t-readguide"><div class="a11y-toggle-row-left"><span class="a11y-toggle-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h6M3 18h6"/></svg></span><span class="a11y-toggle-text" data-i18n="a11y_toggle_readguide">Reading Guide Line</span></div><input type="checkbox" role="switch" class="a11y-toggle-switch" id="t-readguide"></label>
      <label class="a11y-toggle-row" for="t-letterspacing" id="lbl-t-letterspacing"><div class="a11y-toggle-row-left"><span class="a11y-toggle-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 4v16M18 4v16M4 8h4M16 8h4M4 16h4M16 16h4"/></svg></span><span class="a11y-toggle-text" data-i18n="a11y_toggle_spacing">Wider Letter Spacing</span></div><input type="checkbox" role="switch" class="a11y-toggle-switch" id="t-letterspacing"></label>
      <label class="a11y-toggle-row" for="t-focus" id="lbl-t-focus"><div class="a11y-toggle-row-left"><span class="a11y-toggle-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg></span><span class="a11y-toggle-text" data-i18n="a11y_toggle_focus">Bold Focus Outline</span></div><input type="checkbox" role="switch" class="a11y-toggle-switch" id="t-focus"></label>
    </div>
  </div>

  <div class="a11y-group">
    <div class="a11y-group-title"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12h18M3 6h6M3 18h6"/></svg><span data-i18n="a11y_navigation">Navigation</span></div>
    <div class="a11y-controls">
      <label class="a11y-toggle-row" for="t-cursor" id="lbl-t-cursor"><div class="a11y-toggle-row-left"><span class="a11y-toggle-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4l7.07 17 2.51-7.39L21 11.07z"/></svg></span><span class="a11y-toggle-text" data-i18n="a11y_toggle_cursor">Large Mouse Cursor</span></div><input type="checkbox" role="switch" class="a11y-toggle-switch" id="t-cursor"></label>
      <label class="a11y-toggle-row" for="t-keyboard" id="lbl-t-keyboard"><div class="a11y-toggle-row-left"><span class="a11y-toggle-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><line x1="6" y1="10" x2="6" y2="10"/><line x1="10" y1="10" x2="10" y2="10"/><line x1="14" y1="10" x2="14" y2="10"/><line x1="18" y1="10" x2="18" y2="10"/><line x1="6" y1="14" x2="6" y2="14"/><line x1="18" y1="14" x2="18" y2="14"/><line x1="10" y1="14" x2="14" y2="14"/></svg></span><span class="a11y-toggle-text" data-i18n="a11y_toggle_keyboard">Show Keyboard Shortcuts</span></div><input type="checkbox" role="switch" class="a11y-toggle-switch" id="t-keyboard"></label>
    </div>
  </div>

  <div class="a11y-kbd-bar"><span data-i18n="a11y_kbd_open">Open panel:</span> <kbd>Alt</kbd> + <kbd>A</kbd> &middot; <span data-i18n="a11y_kbd_close">Close:</span> <kbd>Esc</kbd> &middot; <span data-i18n="a11y_kbd_reset">Reset:</span> <kbd>Alt</kbd> + <kbd>R</kbd></div>
</div>`);

    // Move the trigger into the navbar next to search/hamburger when one
    // is present on the page, instead of leaving it floating over content.
    var a11yTrigger = document.getElementById('a11y-trigger');
    var navRight = document.querySelector('.nav-right');
    if (a11yTrigger && navRight) {
      var hamburgerBtn = navRight.querySelector('.hamburger');
      if (hamburgerBtn) {
        navRight.insertBefore(a11yTrigger, hamburgerBtn);
      } else {
        navRight.appendChild(a11yTrigger);
      }
      a11yTrigger.classList.add('a11y-trigger-navbar');
    }
  }

  function initScreenReader() {
/* ==========================================================
   ACCESSIBILITY - SCREEN READER JS

   Depends on:
     - the HTML panel IDs in accessibility.html
     - translations object (for label text) - falls back to English
       string literals if `translations`/`currentLang` aren't defined
     - fires/listens for a custom 'aiunit-lang-changed' event
   ========================================================== */

// Flag other accessibility scripts can check so they don't wire up
// a competing/basic reader.
window.__AI_UNIT_ADVANCED_READER__ = true;

setTimeout(function () {
  try {
    // ===== FULLY FUNCTIONAL SCREEN READER =====
    const SR = {
      speaking: false,
      paused: false,
      currentIndex: 0,
      queue: [],
      voices: [],
      selectedVoice: null,
      rate: 1,
      // Language mapping:
      //   en -> en-GB  (British English)
      //   fr -> fr-FR
      //   km -> fr-FR  (Kreol Morisien: no native TTS, French is closest)
      langMap: { en: 'en-GB', fr: 'fr-FR', km: 'fr-FR' },
      preferredVoices: {
        en: ['Google UK English Female', 'Google UK English Male', 'Microsoft Hazel - English (United Kingdom)', 'Microsoft George - English (United Kingdom)', 'Daniel'],
        fr: ['Google Français', 'Microsoft Julie - French (France)', 'Microsoft Hortense - French (France)', 'Thomas'],
        km: ['Google Français', 'Microsoft Julie - French (France)', 'Microsoft Hortense - French (France)', 'Thomas']
      },
      currentUtterance: null,
      highlightEl: null,
      // A reader "session" outlives any one say-all pass. Tabbing away from
      // continuous reading ends the say-all (speaking:false) but keeps the
      // session alive, so focus announcements carry on - the same way NVDA's
      // "read all" stops at the first Tab yet the reader stays on.
      sessionActive: false,
      // True only while a focus announcement is being spoken, so the say-all
      // queue's own onend handler doesn't treat the cancelled utterance as
      // "finished, speak the next chunk" and talk over it.
      announcingFocus: false,
      // Bumped every time a new focus announcement (or a new say-all) starts.
      // A pending announcement whose token is stale is dropped, so holding
      // Tab doesn't queue up one utterance per element passed through.
      focusToken: 0
    };

    const srReadBtn = document.getElementById('sr-read-btn');
    const srStopFloating = document.getElementById('sr-stop-floating');
    const srStatus = document.getElementById('sr-status');
    const srSpeedSlider = document.getElementById('sr-speed');
    const srSpeedDisplay = document.getElementById('sr-speed-display');
    const srReadLabelStrong = srReadBtn ? srReadBtn.querySelector('.sr-btn-label strong') : null;
    const srReadLabelSpan = srReadBtn ? srReadBtn.querySelector('.sr-btn-label span') : null;
    const announcer = document.getElementById('a11y-announcer');

    // currentLang / translations are expected to exist globally (i18n.js).
    // Guard so this file still runs standalone.
    const currentLang = (typeof window.currentLang !== 'undefined') ? window.currentLang : 'en';
    const translations = (typeof window.translations !== 'undefined') ? window.translations : {};

    function announce(msg) {
      if (!announcer) return;
      announcer.textContent = '';
      requestAnimationFrame(function () { announcer.textContent = msg; });
    }

    // --- Voice Management with robust async loading ---
    // No user-facing voice picker: exactly one voice is auto-selected per
    // language (UK English for 'en' - see SR.preferredVoices/langMap above).
    function loadVoices() {
      if (!window.speechSynthesis) return;
      SR.voices = window.speechSynthesis.getVoices() || [];
      if (!SR.selectedVoice && SR.voices.length > 0) {
        autoSelectPreferredVoice();
      }
    }

    function getVoicesForLang(lang) {
      const targetLang = SR.langMap[lang] || 'en-GB';
      return SR.voices.filter(function (v) {
        return v.lang && (v.lang.startsWith(targetLang) || v.lang.replace('_', '-').startsWith(targetLang));
      });
    }

    function autoSelectPreferredVoice() {
      // For Kreol, always use French voices since no Kreol TTS exists
      const effectiveLang = currentLang === 'km' ? 'fr' : currentLang;
      const prefs = SR.preferredVoices[effectiveLang] || SR.preferredVoices['en'];
      for (let i = 0; i < prefs.length; i++) {
        const match = SR.voices.find(function (v) { return v.name === prefs[i]; });
        if (match) {
          SR.selectedVoice = match;
          return;
        }
      }
      // Fallback: first available voice for the effective language locale
      const langVoices = getVoicesForLang(effectiveLang);
      if (langVoices.length > 0) {
        SR.selectedVoice = langVoices[0];
      }
    }

    // Robust voice loading: handles Chrome async, Firefox sync, Safari edge cases
    function initVoices() {
      if (!window.speechSynthesis) return;
      loadVoices();
      if (speechSynthesis.onvoiceschanged !== undefined) {
        speechSynthesis.onvoiceschanged = loadVoices;
      }
      let attempts = 0;
      const interval = setInterval(function () {
        if (SR.voices.length > 0 || attempts >= 20) {
          clearInterval(interval);
          return;
        }
        loadVoices();
        attempts++;
      }, 250);
    }
    initVoices();

    srSpeedSlider && srSpeedSlider.addEventListener('input', function () {
      SR.rate = parseFloat(srSpeedSlider.value);
      if (srSpeedDisplay) srSpeedDisplay.textContent = SR.rate + '×';
    });

    // --- Text Extraction ---
    // Widget/UI chrome that should never end up in the read-aloud queue,
    // regardless of its current visibility state.
    // #a11y-panel/#a11y-trigger: clicking the trigger to open or close the
    // panel - or clicking the panel's own close button - moves DOM focus
    // onto that button on mousedown, before the click handler (closePanel(),
    // togglePanel()) even runs. That focus change reached the focusin
    // listener below like any other, and speakFocus()'s "tabbing away ends
    // the say-all" rule silenced continuous reading just from operating the
    // widget's own toggle - the reported "reader goes quiet the moment you
    // close the panel" bug. Same principle as the other entries here: the
    // reader's own controls must not interrupt or narrate themselves.
    var A11Y_READER_SKIP_SELECTOR = '.video-modal, .diva-panel, #divaWidget, #a11y-panel, #a11y-trigger, #a11y-announcer, #a11y-read-guide, #a11y-sr-prompt, #sr-speed-display, .simple-toggle, .listen-offer, .page-contents';

    // getComputedStyle(el) only ever reflects el's own specified/computed
    // style - display:none (or visibility/opacity/hidden/aria-hidden) on an
    // ANCESTOR does not cascade into a descendant's own computed values, so
    // checking only node.parentElement (as this used to) lets fully hidden
    // content - inactive team-bio tabs (display:none), collapsed framework
    // accordions (max-height:0 + overflow:hidden, no display/visibility
    // change at all), the closed mobile nav - pass the filter and get read
    // aloud anyway, in an order matching nothing on screen. Walk every
    // ancestor up to <body> instead.
    function isHiddenOrCollapsed(el) {
      for (var node = el; node && node !== document.body; node = node.parentElement) {
        if (node.hidden || node.getAttribute('aria-hidden') === 'true') return true;
        var style = window.getComputedStyle(node);
        if (style.display === 'none' || style.visibility === 'hidden') return true;
        // .reveal is this site's scroll-triggered fade-in (script.js's
        // IntersectionObserver adds .visible the first time an element
        // scrolls into view - see assets/js/script.js). It starts every
        // section at opacity:0 by design, one-way, regardless of whether a
        // sighted user has scrolled there yet; treating that as "hidden"
        // here would make the reader silently skip most of the page for
        // anyone who hits "Read page aloud" without scrolling first, which
        // is a normal, common pattern for screen-reader users. Real,
        // permanently-hidden content never carries this class, so it's
        // excluded from the opacity check rather than the check being
        // dropped entirely.
        if (style.opacity === '0' && !node.classList.contains('reveal')) return true;
        // Collapsed accordion: content is there and would scroll into view,
        // but overflow:hidden + a collapsed max-height/height clips it to
        // nothing (dimension-body etc. use max-height:0, not display:none).
        if (style.overflow === 'hidden' && node.clientHeight === 0 && node.scrollHeight > 0) return true;
      }
      return false;
    }

    // `root` limits extraction to one subtree (the skip link's target, say)
    // instead of the whole page. Defaults to <body> for a normal say-all.
    function getReadableText(root) {
      const walker = document.createTreeWalker(
        root || document.body,
        NodeFilter.SHOW_TEXT,
        function (node) {
          const parent = node.parentElement;
          if (!parent) return NodeFilter.FILTER_REJECT;
          const tag = parent.tagName.toLowerCase();
          if (['script', 'style', 'noscript', 'iframe', 'canvas', 'svg'].includes(tag)) return NodeFilter.FILTER_REJECT;
          if (parent.closest && parent.closest(A11Y_READER_SKIP_SELECTOR)) return NodeFilter.FILTER_REJECT;
          if (isHiddenOrCollapsed(parent)) return NodeFilter.FILTER_REJECT;
          const text = node.textContent.trim();
          if (!text) return NodeFilter.FILTER_REJECT;
          return NodeFilter.FILTER_ACCEPT;
        }
      );

      const chunks = [];
      const processedContainers = new Set();
      let node;
      while (node = walker.nextNode()) {
        const parent = node.parentElement;
        let text = node.textContent.replace(/\s+/g, ' ').trim();
        if (!parent || !text) continue;

        const meaningfulContainer = parent.closest('h1, h2, h3, p, li, button, a, label, figcaption, td') || parent;
        if (processedContainers.has(meaningfulContainer)) continue;
        processedContainers.add(meaningfulContainer);

        // Look past inline wrappers (e.g. a <span> label inside a <button>)
        // to find the actual interactive/heading/list ancestor, so labels
        // like "Accessibility" still get announced as "Button." instead of
        // being missed just because the text itself sits in a <span>.
        const headingEl = parent.closest('h1, h2, h3');
        const interactiveEl = parent.closest('button, a, [role="button"], [role="link"]');
        const toggleLabelEl = parent.closest('label');
        const toggleInput = toggleLabelEl ? toggleLabelEl.querySelector('input[type="checkbox"]') : null;
        const listEl = parent.closest('li');

        let prefix = '';
        let suffix = '';
        if (headingEl) {
          prefix = headingEl.tagName.toLowerCase() === 'h3' ? 'Subheading. ' : 'Heading. ';
        } else if (interactiveEl) {
          const isLink = interactiveEl.tagName.toLowerCase() === 'a' || interactiveEl.getAttribute('role') === 'link';
          prefix = isLink ? 'Link. ' : 'Button. ';
          const popup = interactiveEl.getAttribute('aria-haspopup');
          if (popup && popup !== 'false') {
            suffix += ', opens a ' + popup + '. ';
          }
          const pressed = interactiveEl.getAttribute('aria-pressed');
          if (pressed === 'true') suffix += ', selected. ';
          else if (pressed === 'false') suffix += ', not selected. ';
        } else if (toggleInput) {
          // Accessibility panel toggle rows: <label><span>Highlight Links</span>...<input type="checkbox"></label>
          prefix = 'Toggle. ';
          suffix = ', ' + (toggleInput.checked ? 'on' : 'off') + '. ';
        } else if (parent.classList.contains('sr-speed-label')) {
          // "Speed:" label next to the reading-speed <input type="range">.
          const slider = document.getElementById('sr-speed');
          prefix = 'Slider. ';
          suffix = slider ? ' ' + parseFloat(slider.value).toFixed(1) + '×. ' : '';
        } else if (listEl) {
          prefix = 'List item. ';
        }

        chunks.push({ text: prefix + text + suffix, element: meaningfulContainer });
      }
      return chunks;
    }

    function chunkText(text, maxLen) {
      maxLen = maxLen || 180;
      if (text.length <= maxLen) return [text];
      const sentences = text.match(/[^.!?]+[.!?]+|[^.!?]+$/g) || [text];
      const chunks = [];
      let current = '';
      sentences.forEach(function (s) {
        s = s.trim();
        if (!s) return;
        if ((current + ' ' + s).length <= maxLen) {
          current = current ? current + ' ' + s : s;
        } else {
          if (current) chunks.push(current);
          current = s;
        }
      });
      if (current) chunks.push(current);
      return chunks.length ? chunks : [text];
    }

    // --- Speech Control ---
    function createUtterance(text) {
      const u = new SpeechSynthesisUtterance(text);
      // For Kreol, always use French voice since no Kreol TTS exists
      const effectiveLang = currentLang === 'km' ? 'fr' : currentLang;
      const targetLang = SR.langMap[effectiveLang] || 'en-GB';
      u.lang = targetLang;
      u.rate = SR.rate;
      u.pitch = 1;
      u.volume = 1;
      if (SR.selectedVoice) {
        u.voice = SR.selectedVoice;
        u.lang = SR.selectedVoice.lang;
      }
      return u;
    }

    function removeHighlight() {
      if (SR.highlightEl) {
        SR.highlightEl.style.outline = '';
        SR.highlightEl.style.backgroundColor = '';
        SR.highlightEl = null;
      }
    }

    function highlightElement(el) {
      removeHighlight();
      if (!el) return;
      SR.highlightEl = el;
      el.style.outline = '3px solid #1A3A8F';
      el.style.backgroundColor = 'rgba(26,58,143,0.08)';
      el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function updateSRStatus(msg) {
      if (srStatus) {
        srStatus.textContent = msg;
        srStatus.classList.add('active');
      }
    }

    function clearSRStatus() {
      if (srStatus) {
        srStatus.textContent = '';
        srStatus.classList.remove('active');
      }
    }

    function updateReadButton(state) {
      if (!srReadBtn) return;
      const icon = srReadBtn.querySelector('.sr-btn-icon svg');
      if (state === 'playing') {
        srReadBtn.classList.add('active');
        srReadBtn.classList.remove('paused');
        if (srReadLabelStrong) srReadLabelStrong.textContent = translations[currentLang] && translations[currentLang]['a11y_read_btn_label'] ? translations[currentLang]['a11y_read_btn_label'] : 'Reading…';
        if (srReadLabelSpan) srReadLabelSpan.textContent = 'Press Space to pause';
        srReadBtn.setAttribute('aria-label', 'Reading page aloud, press Space to pause');
        if (icon) icon.innerHTML = '<rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/>';
      } else if (state === 'paused') {
        srReadBtn.classList.remove('active');
        srReadBtn.classList.add('paused');
        if (srReadLabelStrong) srReadLabelStrong.textContent = 'Paused';
        if (srReadLabelSpan) srReadLabelSpan.textContent = 'Press Space to resume';
        srReadBtn.setAttribute('aria-label', 'Paused, press Space to resume reading page aloud');
        if (icon) icon.innerHTML = '<polygon points="5 3 19 12 5 21 5 3"/>';
      } else {
        srReadBtn.classList.remove('active', 'paused');
        if (srReadLabelStrong) srReadLabelStrong.textContent = translations[currentLang] && translations[currentLang]['a11y_read_btn_label'] ? translations[currentLang]['a11y_read_btn_label'] : 'Read page aloud';
        if (srReadLabelSpan) srReadLabelSpan.textContent = translations[currentLang] && translations[currentLang]['a11y_read_btn_hint'] ? translations[currentLang]['a11y_read_btn_hint'] : 'Click to start · Space to pause / resume';
        srReadBtn.setAttribute('aria-label', 'Start reading page aloud');
        if (icon) icon.innerHTML = '<polygon points="5 3 19 12 5 21 5 3"/>';
      }
    }

    function speakNext() {
      if (!window.speechSynthesis) return;
      if (SR.currentIndex >= SR.queue.length) {
        stopReading();
        updateSRStatus('Finished reading page');
        setTimeout(clearSRStatus, 3000);
        return;
      }
      const item = SR.queue[SR.currentIndex];
      const u = createUtterance(item.text);
      // Snapshot the session token so this chunk's handlers can tell whether
      // a focus announcement has superseded them.
      const token = SR.focusToken;
      SR.currentUtterance = u;

      u.onstart = function () {
        SR.speaking = true;
        SR.paused = false;
        highlightElement(item.element);
        updateReadButton('playing');
      };

      u.onend = function () {
        // A focus announcement interrupted us: cancel() fires this handler,
        // and advancing/continuing here is exactly what made the old reader
        // talk over the announcement and resume from its previous position.
        if (SR.announcingFocus || token !== SR.focusToken) return;
        SR.currentIndex++;
        if (SR.speaking && !SR.paused) {
          speakNext();
        }
      };

      u.onerror = function (e) {
        if (e.error === 'canceled' || e.error === 'interrupted') return;
        if (SR.announcingFocus || token !== SR.focusToken) return;
        console.warn('Speech error:', e.error);
        SR.currentIndex++;
        if (SR.speaking) speakNext();
      };

      window.speechSynthesis.speak(u);
    }

    function startReading(root) {
      if (!window.speechSynthesis) {
        announce('Speech synthesis is not supported in your browser');
        return;
      }
      window.speechSynthesis.cancel();
      // Invalidate any in-flight focus announcement so its onend can't
      // restart the queue we're about to build.
      SR.focusToken++;
      SR.announcingFocus = false;
      SR.sessionActive = true;
      removeHighlight();
      SR.queue = [];
      // Spoken once, only at the start of a fresh full-page read (not on
      // skip-link continuations, which pass a root) - people relying only on
      // this built-in reader never see the on-screen shortcut hints, so the
      // stop command has to be spoken, not just displayed.
      if (!root) {
        SR.queue.push({ text: 'Reading page aloud. Press Alt plus S, or Escape, to stop.', element: null });
      }
      const chunks = getReadableText(root);
      chunks.forEach(function (c) {
        const subChunks = chunkText(c.text, 200);
        subChunks.forEach(function (sc) {
          SR.queue.push({ text: sc, element: c.element });
        });
      });
      if (SR.queue.length === 0) {
        updateSRStatus('No readable content found');
        setTimeout(clearSRStatus, 3000);
        return;
      }
      SR.currentIndex = 0;
      SR.speaking = true;
      SR.paused = false;
      updateSRStatus('Reading page aloud');
      announce('Reading page aloud. Press Alt+S or Escape to stop.');
      if (srStopFloating) srStopFloating.classList.add('visible');
      speakNext();
    }

    function pauseReading() {
      if (!window.speechSynthesis) return;
      window.speechSynthesis.pause();
      SR.paused = true;
      updateReadButton('paused');
      updateSRStatus('Paused. Press Space to resume.');
      announce('Paused. Press Space to resume.');
    }

    function resumeReading() {
      if (!window.speechSynthesis) return;
      window.speechSynthesis.resume();
      SR.paused = false;
      updateReadButton('playing');
      updateSRStatus('Resuming…');
      announce('Resuming reading.');
    }

    function stopReading() {
      if (!window.speechSynthesis) return;
      window.speechSynthesis.cancel();
      SR.speaking = false;
      SR.paused = false;
      // An explicit stop (Alt+S, Escape, the floating Stop button) is the
      // user asking for silence altogether - end the session so focus
      // announcements stop too, rather than leaving the reader chattering
      // at every Tab after they thought they'd switched it off.
      SR.sessionActive = false;
      SR.focusToken++;
      SR.announcingFocus = false;
      SR.currentIndex = 0;
      SR.currentUtterance = null;
      removeHighlight();
      updateReadButton('stopped');
      clearSRStatus();
      announce('Stopped reading.');
      if (srStopFloating) srStopFloating.classList.remove('visible');
    }

    function toggleReading() {
      if (!SR.speaking) {
        startReading();
      } else if (SR.paused) {
        resumeReading();
      } else {
        pauseReading();
      }
    }

    srReadBtn && srReadBtn.addEventListener('click', toggleReading);
    srStopFloating && srStopFloating.addEventListener('click', stopReading);

    // --- Focus announcements -------------------------------------------
    // Without this the reader only ever did a say-all: once started it read
    // the page top-to-bottom and ignored the keyboard entirely, so tabbing
    // through the navbar produced no announcement of the focused control
    // (the reported "keeps reading continuously instead of announcing the
    // content that receives focus"). A real screen reader interrupts itself
    // and announces whatever just took focus; that's what this does.

    // The accessible name of a focused element, preferring the same sources
    // an AT would consult, in the same order.
    function accessibleName(el) {
      if (!el || el === document.body) return '';
      var labelledby = el.getAttribute('aria-labelledby');
      if (labelledby) {
        var named = labelledby.split(/\s+/).map(function (id) {
          var ref = document.getElementById(id);
          return ref ? ref.textContent.replace(/\s+/g, ' ').trim() : '';
        }).filter(Boolean).join(' ');
        if (named) return named;
      }
      var label = el.getAttribute('aria-label');
      if (label && label.trim()) return label.trim();
      if (el.id) {
        var forLabel = document.querySelector('label[for="' + el.id + '"]');
        if (forLabel) {
          var forText = forLabel.textContent.replace(/\s+/g, ' ').trim();
          if (forText) return forText;
        }
      }
      var wrapping = el.closest && el.closest('label');
      if (wrapping) {
        var wrapText = wrapping.textContent.replace(/\s+/g, ' ').trim();
        if (wrapText) return wrapText;
      }
      var text = (el.textContent || '').replace(/\s+/g, ' ').trim();
      if (text) return text;
      // Image-only controls: fall back to the inner <img>'s alt text.
      var img = el.querySelector && el.querySelector('img[alt]');
      if (img && img.alt.trim()) return img.alt.trim();
      return el.getAttribute('title') || el.getAttribute('placeholder') || '';
    }

    // Role + state, phrased the way the say-all path already phrases them so
    // the two sound like one reader rather than two.
    function focusDescription(el) {
      var tag = el.tagName ? el.tagName.toLowerCase() : '';
      var role = el.getAttribute('role');
      var name = accessibleName(el);
      var prefix = '';
      var suffix = '';

      if (tag === 'a' || role === 'link') prefix = 'Link. ';
      else if (tag === 'button' || role === 'button') prefix = 'Button. ';
      else if (tag === 'select') prefix = 'Combo box. ';
      else if (tag === 'textarea') prefix = 'Edit box. ';
      else if (tag === 'input') {
        var type = (el.getAttribute('type') || 'text').toLowerCase();
        if (type === 'checkbox') {
          prefix = 'Check box. ';
          suffix = ', ' + (el.checked ? 'checked' : 'not checked') + '. ';
        } else if (type === 'radio') {
          prefix = 'Radio button. ';
          suffix = ', ' + (el.checked ? 'selected' : 'not selected') + '. ';
        } else if (type === 'range') {
          prefix = 'Slider. ';
          suffix = ', ' + el.value + '. ';
        } else if (type === 'submit' || type === 'button') {
          prefix = 'Button. ';
        } else {
          prefix = 'Edit box. ';
          if (el.value) suffix = ', contains ' + el.value + '. ';
        }
      } else if (/^h[1-6]$/.test(tag)) {
        prefix = tag === 'h3' ? 'Subheading. ' : 'Heading. ';
      } else if (tag === 'main' || role === 'main') {
        // The skip link's landing spot. Announce the landmark, then let the
        // caller read the content itself.
        prefix = 'Main content. ';
      }

      var popup = el.getAttribute('aria-haspopup');
      if (popup && popup !== 'false') suffix += ', opens a ' + popup + '. ';
      var expanded = el.getAttribute('aria-expanded');
      if (expanded === 'true') suffix += ', expanded. ';
      else if (expanded === 'false') suffix += ', collapsed. ';
      var pressed = el.getAttribute('aria-pressed');
      if (pressed === 'true') suffix += ', selected. ';
      else if (pressed === 'false') suffix += ', not selected. ';
      if (el.disabled || el.getAttribute('aria-disabled') === 'true') suffix += ', unavailable. ';

      var described = el.getAttribute('aria-describedby');
      if (described) {
        var desc = described.split(/\s+/).map(function (id) {
          var ref = document.getElementById(id);
          return ref ? ref.textContent.replace(/\s+/g, ' ').trim() : '';
        }).filter(Boolean).join(' ');
        if (desc) suffix += ' ' + desc + '. ';
      }

      return (prefix + name + suffix).trim();
    }

    // Speak a focus announcement immediately, interrupting whatever is being
    // said. Deliberately does NOT go through SR.queue: the queue is the
    // say-all, and mixing the two is what made the old reader carry on from
    // its previous position instead of following the user.
    function speakFocus(text, opts) {
      if (!window.speechSynthesis || !text) return;
      opts = opts || {};
      var token = ++SR.focusToken;
      // Cancelling fires onend/onerror for the current utterance; the token
      // check in those handlers is what keeps this from being mistaken for
      // "chunk finished, carry on".
      SR.announcingFocus = true;
      // Tabbing away ends the say-all pass (a real screen reader's "read all"
      // stops at the first keystroke), so the button must not keep claiming
      // it's reading - and the stale highlight has to come off the element
      // the say-all had reached.
      var wasReading = SR.speaking;
      SR.speaking = false;
      SR.paused = false;
      window.speechSynthesis.cancel();
      if (wasReading && !opts.thenReadRoot) {
        removeHighlight();
        updateReadButton('stopped');
        clearSRStatus();
        if (srStopFloating) srStopFloating.classList.remove('visible');
      }

      var u = createUtterance(text);
      u.onend = function () {
        if (token !== SR.focusToken) return;
        SR.announcingFocus = false;
        // After announcing the landmark the skip link jumped to, continue
        // reading that region so activating the skip link actually starts
        // reading from the main content.
        if (opts.thenReadRoot) startReading(opts.thenReadRoot);
      };
      u.onerror = function () {
        if (token !== SR.focusToken) return;
        SR.announcingFocus = false;
      };
      SR.currentUtterance = u;
      window.speechSynthesis.speak(u);
    }

    // Only announce focus while a reader session is running - otherwise the
    // page would start talking at a sighted keyboard user who never asked
    // for speech.
    document.addEventListener('focusin', function (e) {
      if (a11ySkipNextFocusAnnounce) {
        a11ySkipNextFocusAnnounce = false;
        return;
      }
      if (!SR.sessionActive) return;
      var el = e.target;
      if (!el || el === document.body) return;
      // The reader's own controls are excluded from the say-all; keep them
      // out of focus announcements too, so operating Stop/Pause/speed
      // doesn't make the reader narrate itself.
      if (el.closest && el.closest(A11Y_READER_SKIP_SELECTOR)) return;
      // The skip link handler below owns this case; it needs to read the
      // whole region, not just announce the landmark.
      if (el.dataset && el.dataset.a11ySkipTarget === 'pending') return;
      var text = focusDescription(el);
      if (text) speakFocus(text);
    });

    // --- Skip link ------------------------------------------------------
    // A bare href="#main-content" moves the visual viewport (and, with the
    // tabindex="-1" the templates already carry, DOM focus), but the built-in
    // reader was not listening, so a say-all in progress carried on from
    // wherever it had reached - the reported bug. Move focus explicitly and
    // restart reading from the target.
    function focusSkipTarget(target) {
      if (!target) return;
      // Programmatic focus needs a focusable target. pages/highlights.php's
      // <main> has no tabindex, so add one rather than depend on every
      // template remembering it.
      if (!target.hasAttribute('tabindex')) target.setAttribute('tabindex', '-1');
      target.dataset.a11ySkipTarget = 'pending';
      target.focus({ preventScroll: true });
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      delete target.dataset.a11ySkipTarget;

      if (SR.sessionActive) {
        // Announce the landmark, then read on from here.
        var name = focusDescription(target) || 'Main content.';
        speakFocus(name.split('.')[0] + '.', { thenReadRoot: target });
      }
    }

    document.addEventListener('click', function (e) {
      var link = e.target.closest && e.target.closest('a.skip-link[href^="#"]');
      if (!link) return;
      var id = link.getAttribute('href').slice(1);
      var target = document.getElementById(id);
      if (!target) return;
      e.preventDefault();
      focusSkipTarget(target);
    });

    // Keyboard activation of a link fires click in every current browser, but
    // Enter on an <a> is the path screen-reader users actually take - keep an
    // explicit handler so the behaviour doesn't depend on that equivalence.
    document.addEventListener('keydown', function (e) {
      if (e.key !== 'Enter') return;
      var link = e.target.closest && e.target.closest('a.skip-link[href^="#"]');
      if (!link) return;
      var target = document.getElementById(link.getAttribute('href').slice(1));
      if (!target) return;
      e.preventDefault();
      focusSkipTarget(target);
    });

    // Global keyboard shortcuts for screen reader
    document.addEventListener('keydown', function (e) {
      // Alt+S and Escape are handled before the input-field guard below:
      // they're the only way to stop the built-in reader that survives
      // NVDA/JAWS browse mode (see Task 11 - bare keys like the 's' below
      // are swallowed by those screen readers' own quick-navigation keys,
      // so a blind user running their own AT had no way to silence this
      // reader once started). Alt-modified keys pass through browse mode,
      // same reasoning as the existing Alt+A panel shortcut.
      // Gated on sessionActive, not SR.speaking: once focus announcements
      // exist, tabbing away ends the say-all pass (speaking:false) while the
      // reader is still on and still talking at every focus change. Keyed on
      // `speaking` alone, Alt+S and Escape would go dead in exactly that
      // state - leaving no way to silence it, which is the situation these
      // two shortcuts exist to prevent.
      if (e.altKey && (e.key === 's' || e.key === 'S') && (SR.speaking || SR.sessionActive)) {
        e.preventDefault();
        stopReading();
        return;
      }
      if (e.key === 'Escape' && (SR.speaking || SR.sessionActive)) {
        e.preventDefault();
        stopReading();
        return;
      }
      if (!SR.speaking && e.target && (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT' || e.target.isContentEditable)) return;
      if (e.code === 'Space' && SR.speaking) {
        e.preventDefault();
        toggleReading();
      }
      if (e.key === 's' || e.key === 'S') {
        if (SR.speaking) {
          e.preventDefault();
          stopReading();
        }
      }
      if (e.key === 'ArrowRight' && SR.speaking) {
        e.preventDefault();
        SR.rate = Math.min(2, SR.rate + 0.1);
        if (srSpeedSlider) srSpeedSlider.value = SR.rate;
        if (srSpeedDisplay) srSpeedDisplay.textContent = SR.rate.toFixed(1) + '×';
        updateSRStatus('Speed: ' + SR.rate.toFixed(1) + '×');
      }
      if (e.key === 'ArrowLeft' && SR.speaking) {
        e.preventDefault();
        SR.rate = Math.max(0.5, SR.rate - 0.1);
        if (srSpeedSlider) srSpeedSlider.value = SR.rate;
        if (srSpeedDisplay) srSpeedDisplay.textContent = SR.rate.toFixed(1) + '×';
        updateSRStatus('Speed: ' + SR.rate.toFixed(1) + '×');
      }
    });

    // Re-select the voice when language changes
    window.addEventListener('aiunit-lang-changed', function () {
      autoSelectPreferredVoice();
      // When switching to Kreol, ensure French voice is selected
      if (currentLang === 'km') {
        const frenchVoices = getVoicesForLang('fr');
        if (frenchVoices.length > 0 && !SR.selectedVoice) {
          SR.selectedVoice = frenchVoices[0];
        }
      }
      if (SR.speaking) {
        stopReading();
        setTimeout(startReading, 300);
      }
    });

    // ===== END SCREEN READER =====
    // Panel controls (font size, toggles, colour modes, profiles, reset,
    // persistence, focus trap) live in a11y-widget.js
  } catch (err) {
    console.error('Accessibility toolbar error:', err);
  }
}, 100);
  }

  function initWidget() {
/* ==========================================================
   ACCESSIBILITY WIDGET - PANEL CONTROLS

   Wires up everything in accessibility.html / accessibility.css
   that isn't the screen reader (that part lives in
   accessibility-screen-reader.js):
     - open/close panel (trigger, close button, backdrop, Esc, Alt+A)
     - focus trap while the panel is open
     - text size stepper (80%-200%, 10% steps)
     - colour mode (normal / high-contrast / dark / grayscale / negative)
     - feature toggles (links, images, motion, dyslexia, read guide,
       letter spacing, focus outline, cursor, keyboard shortcuts class)
     - quick profiles (vision, motor, dyslexia, cognitive, elderly)
     - reset button (also Alt+R, from anywhere on the page)
     - persistence via localStorage
   ========================================================== */

const html = document.documentElement;
const STORAGE_KEY = 'a11y-settings';

const trigger = document.getElementById('a11y-trigger');
const panel = document.getElementById('a11y-panel');
const closeBtn = document.getElementById('a11y-close-btn');
const resetBtn = document.getElementById('a11y-reset-btn');
const announcer = document.getElementById('a11y-announcer');
const readGuide = document.getElementById('a11y-read-guide');

const fontDec = document.getElementById('font-dec');
const fontInc = document.getElementById('font-inc');
const fontDisplay = document.getElementById('font-display');

const colorButtons = Array.from(document.querySelectorAll('.a11y-color-opt'));
const profileButtons = Array.from(document.querySelectorAll('.a11y-profile'));

if (!panel || !trigger) return; // widget markup not present on this page

// accessibility.css styles #a11y-backdrop but leaves creating it to the
// JS (per the "add <div id="a11y-backdrop"></div> in your JS" comment).
const backdrop = document.createElement('div');
backdrop.id = 'a11y-backdrop';
document.body.appendChild(backdrop);

const TOGGLE_CLASS = {
  links: 'a11y-highlight-links',
  images: 'a11y-hide-images',
  motion: 'a11y-no-motion',
  dyslexia: 'a11y-dyslexia',
  readguide: 'a11y-readguide-on',
  letterspacing: 'a11y-wide-spacing',
  focus: 'a11y-bold-focus',
  cursor: 'a11y-large-cursor',
  keyboard: 'a11y-show-kbd-hints',
  nostyle: 'a11y-no-style'
};
const TOGGLE_IDS = {
  links: 't-links',
  images: 't-images',
  motion: 't-motion',
  dyslexia: 't-dyslexia',
  readguide: 't-readguide',
  letterspacing: 't-letterspacing',
  focus: 't-focus',
  cursor: 't-cursor',
  keyboard: 't-keyboard',
  nostyle: 't-nostyle'
};
const MODE_CLASS_PREFIX = 'a11y-mode-';
const FONT_MIN = 0.8, FONT_MAX = 2.0, FONT_STEP = 0.1;

// Each profile is a bundle of settings applied together, and reverted
// together if the profile button is clicked again while active.
const PROFILES = {
  vision: { fontScale: 1.2, colorMode: 'high-contrast', toggles: { focus: true } },
  motor: { toggles: { cursor: true, keyboard: true } },
  dyslexia: { toggles: { dyslexia: true, letterspacing: true, readguide: true } },
  cognitive: { toggles: { motion: true, readguide: true, links: true } },
  elderly: { fontScale: 1.2, toggles: { cursor: true, links: true } }
};

function defaultState() {
  return {
    fontScale: 1,
    colorMode: 'normal',
    toggles: { links: false, images: false, motion: false, dyslexia: false, readguide: false, letterspacing: false, focus: false, cursor: false, keyboard: false, nostyle: false },
    profiles: { vision: false, motor: false, dyslexia: false, cognitive: false, elderly: false }
  };
}

let state = loadState();

function loadState() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    if (!raw) return defaultState();
    const parsed = JSON.parse(raw);
    const merged = defaultState();
    merged.fontScale = typeof parsed.fontScale === 'number' ? parsed.fontScale : 1;
    merged.colorMode = parsed.colorMode || 'normal';
    Object.assign(merged.toggles, parsed.toggles || {});
    Object.assign(merged.profiles, parsed.profiles || {});
    return merged;
  } catch (err) {
    return defaultState();
  }
}

function saveState() {
  try {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
  } catch (err) {
    // localStorage unavailable (private mode, quota, etc.) - settings just won't persist
  }
}

function announce(msg) {
  if (!announcer) return;
  announcer.textContent = '';
  requestAnimationFrame(function () { announcer.textContent = msg; });
}

// --- Text size ---
function applyFontScale(scale) {
  state.fontScale = scale;
  html.style.setProperty('--a11y-font-scale', scale);
  if (fontDisplay) fontDisplay.textContent = Math.round(scale * 100) + '%';
  if (fontDec) fontDec.disabled = scale <= FONT_MIN;
  if (fontInc) fontInc.disabled = scale >= FONT_MAX;
}

fontDec && fontDec.addEventListener('click', function () {
  const next = Math.max(FONT_MIN, Math.round((state.fontScale - FONT_STEP) * 10) / 10);
  applyFontScale(next);
  saveState();
  announce('Text size ' + Math.round(next * 100) + '%');
});
fontInc && fontInc.addEventListener('click', function () {
  const next = Math.min(FONT_MAX, Math.round((state.fontScale + FONT_STEP) * 10) / 10);
  applyFontScale(next);
  saveState();
  announce('Text size ' + Math.round(next * 100) + '%');
});

// --- Colour mode ---
function applyColorMode(mode) {
  state.colorMode = mode;
  colorButtons.forEach(function (btn) {
    const isActive = btn.dataset.mode === mode;
    btn.classList.toggle('active', isActive);
    btn.setAttribute('aria-pressed', String(isActive));
    const cls = MODE_CLASS_PREFIX + btn.dataset.mode;
    if (btn.dataset.mode !== 'normal') html.classList.toggle(cls, isActive);
  });
}

colorButtons.forEach(function (btn) {
  btn.addEventListener('click', function () {
    applyColorMode(btn.dataset.mode);
    saveState();
    announce(btn.textContent.trim() + ' mode enabled');
  });
});

// --- Feature toggles ---
function applyToggle(key, value) {
  state.toggles[key] = value;
  const cls = TOGGLE_CLASS[key];
  if (cls) html.classList.toggle(cls, value);
  const input = document.getElementById(TOGGLE_IDS[key]);
  if (input) input.checked = value;
  if (key === 'readguide') {
    if (value) document.addEventListener('mousemove', moveReadGuide);
    else document.removeEventListener('mousemove', moveReadGuide);
  }
}

function moveReadGuide(e) {
  if (!readGuide) return;
  readGuide.style.top = (e.clientY - 18) + 'px';
}

Object.keys(TOGGLE_IDS).forEach(function (key) {
  const input = document.getElementById(TOGGLE_IDS[key]);
  if (!input) return;
  input.addEventListener('change', function () {
    applyToggle(key, input.checked);
    saveState();
    const label = document.getElementById('lbl-' + TOGGLE_IDS[key]);
    const text = label ? label.querySelector('.a11y-toggle-text').textContent : key;
    announce(text + (input.checked ? ' enabled' : ' disabled'));
  });
});

// --- Quick profiles ---
profileButtons.forEach(function (btn) {
  const name = btn.dataset.profile;
  const def = PROFILES[name];
  if (!def) return;
  btn.addEventListener('click', function () {
    const isActive = state.profiles[name];
    if (!isActive) {
      if (def.fontScale) applyFontScale(def.fontScale);
      if (def.colorMode) applyColorMode(def.colorMode);
      Object.keys(def.toggles || {}).forEach(function (k) { applyToggle(k, true); });
      state.profiles[name] = true;
    } else {
      if (def.fontScale) applyFontScale(1);
      if (def.colorMode) applyColorMode('normal');
      Object.keys(def.toggles || {}).forEach(function (k) { applyToggle(k, false); });
      state.profiles[name] = false;
    }
    btn.setAttribute('aria-pressed', String(state.profiles[name]));
    saveState();
    const label = btn.querySelector('span').textContent;
    announce(label + ' profile ' + (state.profiles[name] ? 'applied' : 'removed'));
  });
});

// --- Reset ---
resetBtn && resetBtn.addEventListener('click', function () {
  state = defaultState();
  render();
  saveState();
  try { localStorage.removeItem('a11y-sr-prompt-choice'); } catch (err) {}
  announce('All accessibility settings have been reset');
});

// --- Full render (used on load and on reset) ---
function render() {
  applyFontScale(state.fontScale);
  applyColorMode(state.colorMode);
  Object.keys(state.toggles).forEach(function (key) { applyToggle(key, state.toggles[key]); });
  profileButtons.forEach(function (btn) {
    const name = btn.dataset.profile;
    btn.setAttribute('aria-pressed', String(!!state.profiles[name]));
  });
}

render();

// --- Panel open/close + focus trap ---
let lastFocused = null;

function getFocusable() {
  return Array.from(panel.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'))
    .filter(function (el) { return el.offsetParent !== null; });
}

function onPanelKeydown(e) {
  if (e.key === 'Escape') {
    e.preventDefault();
    closePanel();
    return;
  }
  if (e.key !== 'Tab') return;
  const focusable = getFocusable();
  if (!focusable.length) return;
  const first = focusable[0];
  const last = focusable[focusable.length - 1];
  if (e.shiftKey && document.activeElement === first) {
    e.preventDefault();
    last.focus();
  } else if (!e.shiftKey && document.activeElement === last) {
    e.preventDefault();
    first.focus();
  }
}

function openPanel() {
  lastFocused = document.activeElement;
  panel.classList.add('open');
  // aria-modal is only true while the panel is actually open and its focus
  // trap (onPanelKeydown, below) is active. Left on permanently, screen
  // readers that enforce it strictly (NVDA) treat the panel as an
  // always-active modal and hide the rest of the document from first load.
  panel.setAttribute('aria-modal', 'true');
  backdrop.classList.add('show');
  trigger.setAttribute('aria-expanded', 'true');
  document.addEventListener('keydown', onPanelKeydown);
  if (closeBtn) closeBtn.focus();
}

function closePanel() {
  panel.classList.remove('open');
  panel.removeAttribute('aria-modal');
  backdrop.classList.remove('show');
  trigger.setAttribute('aria-expanded', 'false');
  document.removeEventListener('keydown', onPanelKeydown);
  if (lastFocused && typeof lastFocused.focus === 'function') {
    // See a11ySkipNextFocusAnnounce's declaration up top: this focus() is
    // restoring where the user already was, not moving them somewhere new,
    // so it must not be read as "user tabbed away" and cut off the say-all.
    a11ySkipNextFocusAnnounce = true;
    lastFocused.focus();
  }
}

function togglePanel() {
  if (panel.classList.contains('open')) closePanel();
  else openPanel();
}

trigger.addEventListener('click', togglePanel);
closeBtn && closeBtn.addEventListener('click', closePanel);
backdrop.addEventListener('click', closePanel);

document.addEventListener('keydown', function (e) {
  if (e.altKey && (e.key === 'a' || e.key === 'A')) {
    e.preventDefault();
    togglePanel();
  }
  if (e.altKey && (e.key === 'r' || e.key === 'R')) {
    e.preventDefault();
    resetBtn && resetBtn.click();
  }
});

// The first-visit choice prompt has been removed in favour of explicit,
// user-controlled controls: the navbar toggle for simple language and the
// built-in reader controls in the Accessibility panel.
  }

  function boot() {
    injectAssets();
    initScreenReader();
    initWidget();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
