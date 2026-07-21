<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <title><?= e(page_title($title)) ?></title>
  <link rel="icon" type="image/x-icon" href="<?= e(asset('images/logo.gif')) ?>" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --blue:      #1A3A8F;
      --blue-mid:  #2554C7;
      --blue-pale: #EEF2FF;
      --blue-light:#D6E0FB;
      --bg:        #F4F5F8;
      --surface:   #FFFFFF;
      --text1:     #0D1B3E;
      --text2:     #4B5E77;
      --text3:     #8896B0;
      --border:    #DDE2EF;
      --border2:   #C8CFE4;
      --trans:     0.18s ease;
      --topbar-h:  58px;
      --footer-h:  40px;
    }

    html {
      font-size: 16px;
      height: -webkit-fill-available;
      height: 100%;
    }

    body {
      font-family: 'Sora', system-ui, sans-serif;
      background: var(--bg);
      color: var(--text1);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      min-height: 100dvh;
      min-height: -webkit-fill-available;
      overflow: hidden;
    }

    .skip-link {
      position: absolute; top: -40px; left: 14px;
      background: var(--blue); color: #fff;
      padding: 8px 16px; border-radius: 8px;
      font-size: 0.85rem; font-weight: 600;
      z-index: 9999; transition: top 0.2s; text-decoration: none;
    }
    .skip-link:focus { top: 14px; }

    .topbar {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 0 20px;
      height: var(--topbar-h);
      flex: 0 0 var(--topbar-h);
      display: flex; align-items: center; justify-content: space-between;
      gap: 12px;
      z-index: 10;
      box-shadow: 0 1px 6px rgba(13,27,62,0.07);
      padding-left: max(20px, env(safe-area-inset-left));
      padding-right: max(20px, env(safe-area-inset-right));
    }

    .topbar-left {
      display: flex; align-items: center; gap: 14px;
      min-width: 0; flex: 1; overflow: hidden;
    }

    .logo-wrap {
      display: flex; align-items: center; gap: 10px; flex-shrink: 0;
      text-decoration: none; color: inherit;
    }
    .logo-wrap img { width: 40px; height: 40px; object-fit: contain; }
    .logo-text { display: flex; flex-direction: column; line-height: 1.1; }
    .logo-main { font-size: 0.95rem; font-weight: 700; color: var(--text1); }
    .logo-sub  { font-size: 0.68rem; font-weight: 500; color: var(--text3); }

    .divider-line { width: 1px; height: 24px; background: var(--border); flex-shrink: 0; }

    .doc-meta { display: flex; flex-direction: column; gap: 1px; min-width: 0; overflow: hidden; }
    .doc-meta-label {
      font-size: 0.68rem; font-weight: 600; letter-spacing: 0.08em;
      text-transform: uppercase; color: var(--text3);
    }
    .doc-meta-title {
      font-size: 0.88rem; font-weight: 600; color: var(--text1);
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .topbar-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

    .back-btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      padding: 7px 16px; min-height: 40px;
      border: 1px solid var(--border); border-radius: 100px;
      font-size: 0.8rem; font-weight: 500; color: var(--text2);
      background: none; cursor: pointer; text-decoration: none;
      transition: all var(--trans); white-space: nowrap;
      font-family: 'Sora', sans-serif;
    }
    .back-btn:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-pale); }

    .btn-dl {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      padding: 8px 18px; min-height: 40px;
      background: var(--blue); color: #fff;
      border: none; border-radius: 100px;
      font-size: 0.8rem; font-weight: 600;
      font-family: 'Sora', sans-serif;
      cursor: pointer; transition: all var(--trans);
      text-decoration: none; white-space: nowrap;
    }
    .btn-dl:hover { background: var(--blue-mid); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(26,58,143,0.28); }

    .viewer-main {
      flex: 1 1 0;
      min-height: 0;
      position: relative;
      background: #525659;
      display: flex;
      flex-direction: column;
    }

    #pdfEmbed {
      flex: 1 1 0;
      min-height: 0;
      width: 100%;
      height: 100%;
      border: none;
      display: block;
    }

    .pdf-fallback {
      display: none;
      flex-direction: column; align-items: center; justify-content: center;
      gap: 20px; padding: 48px 24px; text-align: center;
      background: var(--bg);
      position: absolute; inset: 0;
    }
    .pdf-fallback.show { display: flex; }
    .fallback-icon { color: var(--blue); opacity: 0.5; }
    .fallback-title { font-size: 1.1rem; font-weight: 700; color: var(--text1); }
    .fallback-desc { font-size: 0.9rem; color: var(--text2); line-height: 1.7; max-width: 480px; }
    .fallback-actions {
      display: flex; gap: 10px; flex-wrap: wrap;
      justify-content: center; margin-top: 8px;
    }

    .btn-new-tab {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 8px 18px; min-height: 40px;
      background: transparent; color: var(--blue);
      border: 1.5px solid var(--blue-light); border-radius: 100px;
      font-size: 0.8rem; font-weight: 600;
      font-family: 'Sora', sans-serif;
      cursor: pointer; text-decoration: none;
      transition: all var(--trans);
    }
    .btn-new-tab:hover { background: var(--blue-pale); border-color: var(--blue); }

    .footer-bar {
      background: var(--surface);
      border-top: 1px solid var(--border);
      padding: 6px 20px;
      flex: 0 0 auto;
      display: flex; align-items: center; justify-content: center;
      flex-wrap: wrap; gap: 6px 20px;
      font-size: 0.72rem; color: var(--text3);
      text-align: center;
      padding-bottom: max(6px, env(safe-area-inset-bottom));
      padding-left: max(20px, env(safe-area-inset-left));
      padding-right: max(20px, env(safe-area-inset-right));
    }
    .footer-bar a { color: var(--blue-mid); text-decoration: none; }
    .footer-bar a:hover { text-decoration: underline; }
    .footer-dot { width: 3px; height: 3px; border-radius: 50%; background: var(--border2); flex-shrink: 0; }

    @media (max-width: 900px) {
      .logo-sub    { display: none; }
      .doc-meta-label { display: none; }
      .doc-meta-title { font-size: 0.82rem; }
    }

    @media (max-width: 640px) {
      :root { --topbar-h: 52px; }
      .topbar { padding: 0 12px; gap: 8px; }
      .topbar-left { gap: 10px; }
      .logo-text { display: none; }
      .logo-wrap img { width: 34px; height: 34px; }
      .divider-line { display: none; }
      .doc-meta { display: none; }
      .back-btn .btn-label,
      .btn-dl .btn-label { display: none; }
      .back-btn { padding: 0; width: 40px; height: 40px; border-radius: 50%; }
      .btn-dl   { padding: 0; width: 40px; height: 40px; border-radius: 50%; }
      .footer-dot { display: none; }
      .footer-bar { flex-direction: column; gap: 3px; font-size: 0.68rem; padding-top: 5px; }
    }

    @media (max-width: 400px) {
      :root { --topbar-h: 48px; }
      .topbar { padding: 0 10px; gap: 6px; }
      .logo-wrap img { width: 30px; height: 30px; }
      .fallback-title { font-size: 0.95rem; }
      .fallback-desc  { font-size: 0.8rem; }
      .fallback-actions { flex-direction: column; align-items: stretch; }
      .fallback-actions .btn-dl,
      .fallback-actions .btn-new-tab { justify-content: center; width: 100%; }
    }

    @media (max-height: 480px) and (orientation: landscape) {
      :root { --topbar-h: 44px; }
      .footer-bar { display: none; }
    }

    @media (min-width: 1400px) {
      .topbar { padding: 0 32px; }
      .back-btn, .btn-dl { font-size: 0.85rem; padding: 9px 22px; }
    }

    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after { transition: none !important; animation: none !important; }
    }
  </style>
</head>
<body>

<a class="skip-link" href="#pdfEmbed">Skip to document</a>

<!-- TOP BAR -->
<header class="topbar" role="banner">
  <div class="topbar-left">
    <a href="<?= e(url('/')) ?>" class="logo-wrap" aria-label="AI Unit — Back to homepage">
      <img src="<?= e(asset('images/logo.gif')) ?>" alt="" aria-hidden="true" />
      <div class="logo-text">
        <span class="logo-main">AI Unit</span>
        <span class="logo-sub">Ministry of ICT · Mauritius</span>
      </div>
    </a>
    <div class="divider-line" aria-hidden="true"></div>
    <div class="doc-meta">
      <span class="doc-meta-label">Framework Library</span>
      <span class="doc-meta-title" id="docTitle"><?= e($title) ?></span>
    </div>
  </div>

  <div class="topbar-right">
    <a href="<?= e(url('/')) ?>" class="back-btn" aria-label="Back to homepage">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
        <path d="M19 12H5M12 5l-7 7 7 7"/>
      </svg>
      <span class="btn-label">Back to Homepage</span>
    </a>
    <a href="<?= e($docUrl) ?>" class="btn-dl" id="dlBtn" download="<?= e($downloadName) ?>" aria-label="Download <?= e($title) ?>">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
        <polyline points="7 10 12 15 17 10"/>
        <line x1="12" y1="15" x2="12" y2="3"/>
      </svg>
      <span class="btn-label">Download PDF</span>
    </a>
  </div>
</header>

<!-- VIEWER — takes all remaining height -->
<main class="viewer-main" role="main">
  <iframe
    id="pdfEmbed"
    title="PDF Document Viewer"
    aria-label="Document viewer"
    src="<?= e($docUrl) ?>"
    allowfullscreen
  ></iframe>

  <!-- Fallback shown if PDF cannot be embedded -->
  <div class="pdf-fallback" id="pdfFallback" role="alert">
    <svg class="fallback-icon" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
      <polyline points="14 2 14 8 20 8"/>
      <line x1="12" y1="18" x2="12" y2="12"/>
      <line x1="9" y1="15" x2="15" y2="15"/>
    </svg>
    <p class="fallback-title">Document preview unavailable</p>
    <p class="fallback-desc">Your browser may be blocking the inline PDF viewer. Use one of the options below to access the document.</p>
    <div class="fallback-actions">
      <a href="<?= e($docUrl) ?>" class="btn-dl" id="fallbackDl" download="<?= e($downloadName) ?>">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7 10 12 15 17 10"/>
          <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Download PDF
      </a>
      <a href="<?= e($docUrl) ?>" class="btn-new-tab" id="fallbackOpen" target="_blank" rel="noopener">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
          <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
          <polyline points="15 3 21 3 21 9"/>
          <line x1="10" y1="14" x2="21" y2="3"/>
        </svg>
        Open in new tab
      </a>
    </div>
  </div>
</main>

<!-- FOOTER BAR -->
<footer class="footer-bar" role="contentinfo">
  <span>AI Unit · Ministry of ICT, Mauritius</span>
  <span class="footer-dot" aria-hidden="true"></span>
  <a href="<?= e(url('/')) ?>">Back to Homepage</a>
  <span class="footer-dot" aria-hidden="true"></span>
  <span id="footerDocName"><?= e($title) ?></span>
</footer>

<script>
(function () {
  const embed = document.getElementById('pdfEmbed');
  let loaded = false;
  embed.addEventListener('load', () => { loaded = true; });
  embed.addEventListener('error', () => {
    embed.style.display = 'none';
    document.getElementById('pdfFallback').classList.add('show');
  });
  /* If the inline viewer hasn't fired a load event after a few seconds, assume it's blocked. */
  setTimeout(() => {
    if (!loaded) {
      embed.style.display = 'none';
      document.getElementById('pdfFallback').classList.add('show');
    }
  }, 4000);
})();
</script>

</body>
</html>
