<!-- ACCESSIBILITY TOOLBAR -->
<div id="a11y-announcer" aria-live="polite" aria-atomic="true" role="status"></div>
<div id="a11y-read-guide" aria-hidden="true"></div>

<div id="a11y-panel" role="dialog" aria-modal="false" aria-label="Accessibility options">
  <div class="a11y-header">
    <div class="a11y-header-left">
      <div class="a11y-header-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
      </div>
      <div><h2 data-i18n="a11y_title">Accessibility</h2><p data-i18n="a11y_subtitle">Adjust this website to your needs</p></div>
    </div>
    <div class="a11y-header-right">
      <button class="a11y-reset" id="a11y-reset-btn" aria-label="Reset all settings" data-i18n="a11y_reset">Reset</button>
      <button class="a11y-close" id="a11y-close-btn" aria-label="Close">✕</button>
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
        <span data-i18n="a11y_read_btn_hint">Click to start · Space to pause / resume</span>
      </span>
    </button>
    <div class="sr-kbd-hints" aria-label="Keyboard shortcuts for screen reader">
      <p data-i18n="a11y_kbd_hint">Keyboard shortcuts (while reading)</p>
      <div class="sr-kbd-grid">
        <div class="sr-kbd-item"><kbd>Space</kbd> <span data-i18n="a11y_kbd_playpause">Play / Pause</span></div>
        <div class="sr-kbd-item"><kbd>S</kbd> <span data-i18n="a11y_kbd_stop">Stop</span></div>
        <div class="sr-kbd-item"><kbd>←</kbd> <span data-i18n="a11y_kbd_slower">Slower</span></div>
        <div class="sr-kbd-item"><kbd>→</kbd> <span data-i18n="a11y_kbd_faster">Faster</span></div>
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
      <span id="sr-speed-display" style="font-size:0.75rem;font-weight:700;color:#1A3A8F;min-width:32px;">1×</span>
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

  <div class="a11y-kbd-bar"><span data-i18n="a11y_kbd_open">Open panel:</span> <kbd>Alt</kbd> + <kbd>A</kbd> · <span data-i18n="a11y_kbd_close">Close:</span> <kbd>Esc</kbd></div>
</div>
