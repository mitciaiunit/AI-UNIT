/* ==========================================================
   ACCESSIBILITY WIDGET - single <script> embed

   Drop this one line into any page:
     <script src="accessibility-widget.js"></script>
   It injects its own fonts, CSS, and markup, and wires itself up -
   nothing else needs to be added. The trigger button floats fixed
   in the bottom-right corner.
   ========================================================== */
(function () {
  'use strict';
  if (window.__A11Y_WIDGET_LOADED__) return;
  window.__A11Y_WIDGET_LOADED__ = true;

  function injectAssets() {
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

/* ---- TRIGGER BUTTON (lives in navbar .nav-right) ---- */
.a11y-trigger-wrap{
  position:fixed;bottom:20px;right:20px;z-index:2001;
  display:flex;align-items:center;gap:8px;
  padding:8px 14px;border-radius:100px;
  cursor:pointer;
  color:var(--text-2);
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
.a11y-trigger-img{width:22px;height:22px;object-fit:contain;flex-shrink:0;}
.a11y-trigger-wrap span{white-space:nowrap;}
@media(max-width:900px){.a11y-trigger-wrap span{display:none;}.a11y-trigger-wrap{padding:8px;}}

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
  transition:transform 0.32s cubic-bezier(0.4,0,0.2,1);
  font-family:'Sora',sans-serif;
  padding-bottom:24px;
}
#a11y-panel.open{transform:translateX(0);}
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

.sr-status{
  min-height:0;
  font-size:0.76rem;color:var(--blue);font-weight:600;
  margin-top:10px;
}
.sr-status.active{
  padding:8px 10px;border-radius:8px;
  background:white;border:1px solid var(--blue-light);
}

.sr-voice-row{margin-top:12px;}
.sr-voice-select{
  width:100%;
  padding:9px 12px;
  border-radius:8px;
  border:1.5px solid var(--border);
  background:white;
  font-size:0.82rem;
  color:var(--text-1);
  font-family:'Sora',sans-serif;
}
.sr-voice-select:focus{outline:none;border-color:var(--blue-mid);}

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

/* Dark mode */
html.a11y-mode-dark body{background:#12161f;color:#e8ecf5;}
html.a11y-mode-dark .section,
html.a11y-mode-dark .card,
html.a11y-mode-dark .doc-card,
html.a11y-mode-dark .action-card{background:#1b212e;color:#e8ecf5;}

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
  background:var(--surface);
  border-radius:var(--radius);
  box-shadow:var(--shadow-xl);
  max-width:420px;width:100%;
  padding:28px;
  font-family:'Sora',sans-serif;
}
.a11y-sr-prompt-card h2{font-family:'Lora',serif;font-size:1.15rem;font-weight:700;color:var(--text-1);margin-bottom:10px;}
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
    <h2 id="a11y-sr-prompt-title">Screen Reader</h2>
    <p id="a11y-sr-prompt-desc">This site has a built-in screen reader. If you already use your own assistive technology (NVDA, JAWS, VoiceOver, TalkBack, etc.), you may prefer to keep using that instead to avoid both reading at once.</p>
    <div class="a11y-sr-prompt-actions">
      <button type="button" id="a11y-sr-prompt-use" class="a11y-sr-prompt-btn primary">Use built-in screen reader</button>
      <button type="button" id="a11y-sr-prompt-own" class="a11y-sr-prompt-btn ghost">I'll use my own</button>
    </div>
  </div>
</div>

<div class="a11y-trigger-wrap" id="a11y-trigger" role="button" tabindex="0" aria-label="Accessibility Tools" aria-haspopup="dialog" aria-expanded="false" aria-controls="a11y-panel">
  <img src="/image/accessibility.png" alt="" aria-hidden="true" class="a11y-trigger-img"
       onerror="this.style.display='none';this.nextElementSibling.style.display='inline-block'" />
  <svg aria-hidden="true" style="display:none;flex-shrink:0;color:var(--blue);" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="12" cy="5" r="2"/><path d="M12 22v-8"/><path d="M5 9l7-2 7 2"/><path d="M5 9l2 6h10l2-6"/>
  </svg>
  <span data-i18n="accessibility">Accessibility</span>
</div>

<div id="a11y-announcer" aria-live="polite" aria-atomic="true" role="status"></div>
<div id="a11y-read-guide" aria-hidden="true"></div>

<div id="a11y-panel" role="dialog" aria-modal="true" aria-label="Accessibility options">
  <div class="a11y-header">
    <div class="a11y-header-left">
      <div class="a11y-header-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
      </div>
      <div><h2 data-i18n="a11y_title">Accessibility</h2><p data-i18n="a11y_subtitle">Adjust this website to your needs</p></div>
    </div>
    <div class="a11y-header-right">
      <button class="a11y-reset" id="a11y-reset-btn" aria-label="Reset all settings" data-i18n="a11y_reset">Reset</button>
      <button class="a11y-close" id="a11y-close-btn" aria-label="Close">&times;</button>
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
        <div class="sr-kbd-item"><kbd>Space</kbd> <span data-i18n="a11y_kbd_playpause">Play / Pause</span></div>
        <div class="sr-kbd-item"><kbd>S</kbd> <span data-i18n="a11y_kbd_stop">Stop</span></div>
        <div class="sr-kbd-item"><kbd>&#8592;</kbd> <span data-i18n="a11y_kbd_slower">Slower</span></div>
        <div class="sr-kbd-item"><kbd>&#8594;</kbd> <span data-i18n="a11y_kbd_faster">Faster</span></div>
      </div>
    </div>
    <div class="sr-status" id="sr-status" role="status" aria-live="polite"></div>
    <div class="sr-voice-row">
      <select class="sr-voice-select" id="sr-voice-select" aria-label="Choose reading voice">
        <option value="" data-i18n="a11y_voice_default">Default voice</option>
      </select>
    </div>
    <div class="sr-speed-row">
      <span class="sr-speed-label" data-i18n="a11y_speed_label">Speed:</span>
      <input type="range" class="sr-speed-slider" id="sr-speed" min="0.5" max="2" step="0.1" value="1" aria-label="Reading speed" aria-valuemin="0.5" aria-valuemax="2" aria-valuenow="1">
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

  <div class="a11y-kbd-bar"><span data-i18n="a11y_kbd_open">Open panel:</span> <kbd>Alt</kbd> + <kbd>A</kbd> &middot; <span data-i18n="a11y_kbd_close">Close:</span> <kbd>Esc</kbd></div>
</div>`);
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
      highlightEl: null
    };

    const srReadBtn = document.getElementById('sr-read-btn');
    const srStatus = document.getElementById('sr-status');
    const srVoiceSelect = document.getElementById('sr-voice-select');
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
    function loadVoices() {
      if (!window.speechSynthesis) return;
      SR.voices = window.speechSynthesis.getVoices() || [];
      populateVoiceSelect();
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
          if (srVoiceSelect) srVoiceSelect.value = match.name;
          return;
        }
      }
      // Fallback: first available voice for the effective language locale
      const langVoices = getVoicesForLang(effectiveLang);
      if (langVoices.length > 0) {
        SR.selectedVoice = langVoices[0];
        if (srVoiceSelect) srVoiceSelect.value = langVoices[0].name;
      }
    }

    function populateVoiceSelect() {
      if (!srVoiceSelect) return;
      const currentVal = srVoiceSelect.value;
      const defaultText = translations[currentLang] && translations[currentLang]['a11y_voice_default'] ? translations[currentLang]['a11y_voice_default'] : 'Default voice';
      srVoiceSelect.innerHTML = '<option value="">' + defaultText + '</option>';
      // For Kreol, show French voices since no Kreol TTS exists
      const effectiveLang = currentLang === 'km' ? 'fr' : currentLang;
      const langVoices = getVoicesForLang(effectiveLang);
      // Sort: preferred voices first, then alphabetically
      const prefs = SR.preferredVoices[effectiveLang] || [];
      langVoices.sort(function (a, b) {
        const aIdx = prefs.indexOf(a.name);
        const bIdx = prefs.indexOf(b.name);
        if (aIdx !== -1 && bIdx !== -1) return aIdx - bIdx;
        if (aIdx !== -1) return -1;
        if (bIdx !== -1) return 1;
        return a.name.localeCompare(b.name);
      });
      langVoices.forEach(function (v) {
        const opt = document.createElement('option');
        opt.value = v.name;
        var label = v.name;
        if (prefs.indexOf(v.name) !== -1) label += ' ★';
        if (currentLang === 'km') label += ' (French - Kreol fallback)';
        opt.textContent = label + ' (' + v.lang + ')';
        srVoiceSelect.appendChild(opt);
      });
      if (currentVal) {
        const exists = Array.from(srVoiceSelect.options).some(function (o) { return o.value === currentVal; });
        if (exists) srVoiceSelect.value = currentVal;
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

    srVoiceSelect && srVoiceSelect.addEventListener('change', function () {
      const name = srVoiceSelect.value;
      SR.selectedVoice = name ? SR.voices.find(function (v) { return v.name === name; }) : null;
      announce(name ? 'Voice changed to ' + name : 'Using default voice');
    });

    srSpeedSlider && srSpeedSlider.addEventListener('input', function () {
      SR.rate = parseFloat(srSpeedSlider.value);
      if (srSpeedDisplay) srSpeedDisplay.textContent = SR.rate + '×';
    });

    // --- Text Extraction ---
    function getReadableText() {
      const walker = document.createTreeWalker(
        document.body,
        NodeFilter.SHOW_TEXT,
        function (node) {
          const parent = node.parentElement;
          if (!parent) return NodeFilter.FILTER_REJECT;
          const style = window.getComputedStyle(parent);
          if (style.display === 'none' || style.visibility === 'hidden' || style.opacity === '0') return NodeFilter.FILTER_REJECT;
          const tag = parent.tagName.toLowerCase();
          if (['script', 'style', 'noscript', 'iframe', 'canvas'].includes(tag)) return NodeFilter.FILTER_REJECT;
          if (parent.closest && parent.closest('.video-modal, .diva-panel, #divaWidget, #a11y-announcer, #a11y-read-guide, #a11y-sr-prompt')) return NodeFilter.FILTER_REJECT;
          const text = node.textContent.trim();
          if (!text) return NodeFilter.FILTER_REJECT;
          return NodeFilter.FILTER_ACCEPT;
        }
      );

      const chunks = [];
      let node;
      while (node = walker.nextNode()) {
        const parent = node.parentElement;
        const tag = parent ? parent.tagName.toLowerCase() : '';
        let text = node.textContent.trim();
        if (!text) continue;

        let prefix = '';
        if (tag === 'h1' || tag === 'h2') prefix = 'Heading. ';
        else if (tag === 'h3') prefix = 'Subheading. ';
        else if (tag === 'li') prefix = 'List item. ';
        else if (tag === 'button') prefix = 'Button. ';
        else if (tag === 'a') prefix = 'Link. ';

        if (text.length > 0) {
          chunks.push({ text: prefix + text, element: parent });
        }
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
        if (icon) icon.innerHTML = '<rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/>';
      } else if (state === 'paused') {
        srReadBtn.classList.remove('active');
        srReadBtn.classList.add('paused');
        if (srReadLabelStrong) srReadLabelStrong.textContent = 'Paused';
        if (srReadLabelSpan) srReadLabelSpan.textContent = 'Press Space to resume';
        if (icon) icon.innerHTML = '<polygon points="5 3 19 12 5 21 5 3"/>';
      } else {
        srReadBtn.classList.remove('active', 'paused');
        if (srReadLabelStrong) srReadLabelStrong.textContent = translations[currentLang] && translations[currentLang]['a11y_read_btn_label'] ? translations[currentLang]['a11y_read_btn_label'] : 'Read page aloud';
        if (srReadLabelSpan) srReadLabelSpan.textContent = translations[currentLang] && translations[currentLang]['a11y_read_btn_hint'] ? translations[currentLang]['a11y_read_btn_hint'] : 'Click to start · Space to pause / resume';
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
      SR.currentUtterance = u;

      u.onstart = function () {
        SR.speaking = true;
        SR.paused = false;
        highlightElement(item.element);
        updateReadButton('playing');
        updateSRStatus('Reading: ' + item.text.substring(0, 60) + (item.text.length > 60 ? '…' : ''));
      };

      u.onend = function () {
        SR.currentIndex++;
        if (SR.speaking && !SR.paused) {
          speakNext();
        }
      };

      u.onerror = function (e) {
        if (e.error === 'canceled' || e.error === 'interrupted') return;
        console.warn('Speech error:', e.error);
        SR.currentIndex++;
        if (SR.speaking) speakNext();
      };

      window.speechSynthesis.speak(u);
    }

    function startReading() {
      if (!window.speechSynthesis) {
        announce('Speech synthesis is not supported in your browser');
        return;
      }
      window.speechSynthesis.cancel();
      removeHighlight();
      SR.queue = [];
      const chunks = getReadableText();
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
      speakNext();
    }

    function pauseReading() {
      if (!window.speechSynthesis) return;
      window.speechSynthesis.pause();
      SR.paused = true;
      updateReadButton('paused');
      updateSRStatus('Paused. Press Space to resume.');
    }

    function resumeReading() {
      if (!window.speechSynthesis) return;
      window.speechSynthesis.resume();
      SR.paused = false;
      updateReadButton('playing');
      updateSRStatus('Resuming…');
    }

    function stopReading() {
      if (!window.speechSynthesis) return;
      window.speechSynthesis.cancel();
      SR.speaking = false;
      SR.paused = false;
      SR.currentIndex = 0;
      SR.currentUtterance = null;
      removeHighlight();
      updateReadButton('stopped');
      clearSRStatus();
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

    // Global keyboard shortcuts for screen reader
    document.addEventListener('keydown', function (e) {
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

    // Re-populate voices when language changes
    window.addEventListener('aiunit-lang-changed', function () {
      populateVoiceSelect();
      autoSelectPreferredVoice();
      // When switching to Kreol, ensure French voice is selected
      if (currentLang === 'km') {
        const frenchVoices = getVoicesForLang('fr');
        if (frenchVoices.length > 0 && !SR.selectedVoice) {
          SR.selectedVoice = frenchVoices[0];
          if (srVoiceSelect) srVoiceSelect.value = frenchVoices[0].name;
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
     - reset button
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
  backdrop.classList.add('show');
  trigger.setAttribute('aria-expanded', 'true');
  document.addEventListener('keydown', onPanelKeydown);
  if (closeBtn) closeBtn.focus();
}

function closePanel() {
  panel.classList.remove('open');
  backdrop.classList.remove('show');
  trigger.setAttribute('aria-expanded', 'false');
  document.removeEventListener('keydown', onPanelKeydown);
  if (lastFocused && typeof lastFocused.focus === 'function') lastFocused.focus();
}

function togglePanel() {
  if (panel.classList.contains('open')) closePanel();
  else openPanel();
}

trigger.addEventListener('click', togglePanel);
trigger.addEventListener('keydown', function (e) {
  if (e.key === 'Enter' || e.key === ' ') {
    e.preventDefault();
    togglePanel();
  }
});
closeBtn && closeBtn.addEventListener('click', closePanel);
backdrop.addEventListener('click', closePanel);

document.addEventListener('keydown', function (e) {
  if (e.altKey && (e.key === 'a' || e.key === 'A')) {
    e.preventDefault();
    togglePanel();
  }
});

// --- First-visit screen reader choice prompt ---
const SR_PROMPT_KEY = 'a11y-sr-prompt-choice';
const srPrompt = document.getElementById('a11y-sr-prompt');
const srPromptUseBtn = document.getElementById('a11y-sr-prompt-use');
const srPromptOwnBtn = document.getElementById('a11y-sr-prompt-own');

function dismissSRPrompt(choice) {
  if (!srPrompt) return;
  srPrompt.remove();
  document.removeEventListener('keydown', onSRPromptKeydown);
  try { localStorage.setItem(SR_PROMPT_KEY, choice); } catch (err) {}
}

function onSRPromptKeydown(e) {
  if (e.key === 'Escape') {
    e.preventDefault();
    dismissSRPrompt('own');
    return;
  }
  if (e.key !== 'Tab') return;
  const focusable = [srPromptUseBtn, srPromptOwnBtn].filter(Boolean);
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

if (srPrompt) {
  let priorChoice = null;
  try { priorChoice = localStorage.getItem(SR_PROMPT_KEY); } catch (err) {}
  if (!priorChoice) {
    srPrompt.hidden = false;
    document.addEventListener('keydown', onSRPromptKeydown);
    if (srPromptUseBtn) srPromptUseBtn.focus();
  } else {
    // Already answered on a prior visit - remove it outright so it can
    // never end up in the screen reader's read-aloud queue.
    srPrompt.remove();
  }
  srPromptUseBtn && srPromptUseBtn.addEventListener('click', function () {
    dismissSRPrompt('builtin');
    openPanel();
    // accessibility-screen-reader.js wires its own click listener inside a
    // setTimeout(..., 100) after DOMContentLoaded, so wait past that
    // before simulating the click, or it fires before anything is listening.
    setTimeout(function () {
      const readBtn = document.getElementById('sr-read-btn');
      if (readBtn) readBtn.click();
    }, 200);
  });
  srPromptOwnBtn && srPromptOwnBtn.addEventListener('click', function () {
    dismissSRPrompt('own');
  });
}
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
