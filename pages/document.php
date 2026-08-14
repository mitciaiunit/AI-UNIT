<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <title><?= e(page_title($title)) ?></title>
  <link rel="icon" type="image/png" href="<?= e(asset('images/favicon.png')) ?>" />
  <?php require __DIR__ . '/../includes/meta.php'; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <!--
    Loaded so the "View as Text" button can extract the document's real text
    layer with pdf.js's own parser, independent of whether this particular
    PDF carries accessibility tags. See pages/booklet.php for the same
    technique applied to the booklet reader. cdnjs/blob: are already allowed
    by the site's CSP (app/Core/SecurityHeaders.php) for this exact purpose.
  -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
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
      --text3:     #4B5E77;
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
    .back-btn.active { border-color: var(--blue-light); color: var(--blue); background: var(--blue-pale); }

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

    /* Text alternative to the embedded PDF - see #textViewBtn. Covers the
       iframe rather than replacing it, so toggling back keeps the iframe's
       scroll position and zoom level intact. */
    .pdf-text-view {
      display: none;
      position: absolute; inset: 0;
      background: var(--surface);
      overflow-y: auto;
    }
    .pdf-text-view.show { display: block; }
    .pdf-text-view:focus { outline: none; }
    .tv-inner { max-width: 760px; margin: 0 auto; padding: 40px 24px 80px; }
    .tv-inner h2 { font-size: 1.05rem; font-weight: 700; color: var(--text1); margin: 28px 0 10px; }
    .tv-inner h2:first-child { margin-top: 0; }
    .tv-inner p { font-size: 1rem; line-height: 1.75; color: var(--text1); margin: 0 0 14px; }
    /* Only the converted document paragraphs are justified. Interface text,
       headings and the screen-reader guidance keep their natural alignment. */
    #tvContent p { text-align: justify; text-justify: inter-word; hyphens: auto; }
    .tv-instructions { padding: 12px 14px; background: var(--blue-pale); border-left: 4px solid var(--blue); border-radius: 4px; }
    .tv-status { font-size: 0.9rem; color: var(--text2); margin: 0 0 14px; }
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
      .doc-meta-title { font-size: 0.82rem; margin: 0; font-weight: 600; line-height: 1.2; }
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
    <a href="<?= e(url('/')) ?>" class="logo-wrap" aria-label="AI Unit - Back to homepage">
      <img src="<?= e(asset('images/logo.webp')) ?>" alt="" aria-hidden="true" width="300" height="200" decoding="async" />
      <div class="logo-text">
        <span class="logo-main">AI Unit</span>
        <span class="logo-sub">Ministry of ICT · Mauritius</span>
      </div>
    </a>
    <div class="divider-line" aria-hidden="true"></div>
    <div class="doc-meta">
      <span class="doc-meta-label">Framework Library</span>
      <h1 class="doc-meta-title" id="docTitle"><?= e($title) ?></h1>
    </div>
  </div>

  <div class="topbar-right">
    <a href="<?= e(url('/')) ?>" class="back-btn" id="backBtnTop" aria-label="Back to homepage">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
        <path d="M19 12H5M12 5l-7 7 7 7"/>
      </svg>
      <span class="btn-label">Back to Homepage</span>
    </a>
    <button type="button" class="back-btn" id="textViewBtn" aria-pressed="false" aria-label="View this document as accessible text">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="9" y1="13" x2="15" y2="13"/>
        <line x1="9" y1="17" x2="15" y2="17"/>
      </svg>
      <span class="btn-label">View as Text</span>
    </button>
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

<!-- VIEWER - takes all remaining height -->
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

  <!--
   * Screen-reader alternative to the embedded viewer above, built by
   * extracting the PDF's own text layer with pdf.js - independent of
   * whether this document carries the accessibility tags NVDA relies on to
   * navigate a PDF opened directly (see #textViewBtn's click handler below).
   *
   * Focus goes to #tvHeading, not this wrapper, when the panel opens.
   * Focusing a plain <div> announces nothing - NVDA doesn't read a
   * container's descendants just because focus landed on it, so a screen
   * reader user would hear silence and have no reason to go looking further
   * in. A heading has real text, so focusing it is actually announced, and
   * it gives the user a natural point to arrow down (or Say All) from.
  -->
  <div class="pdf-text-view" id="pdfTextView">
    <div class="tv-inner">
      <h2 id="tvHeading" tabindex="-1" aria-describedby="tvInstructions">Text version of this document</h2>
      <p class="tv-instructions" id="tvInstructions">Screen reader help: Press Down Arrow to read line by line, or NVDA+Down Arrow to read continuously. Tab moves only between buttons and links.</p>
      <p class="tv-status" id="tvStatus" role="status" aria-live="polite"></p>
      <div id="tvContent"></div>
    </div>
  </div>
</main>

<!-- FOOTER BAR -->
<footer class="footer-bar" role="contentinfo">
  <span>AI Unit · Ministry of ICT, Mauritius</span>
  <span class="footer-dot" aria-hidden="true"></span>
  <a href="<?= e(url('/')) ?>" id="backBtnFooter">Back to Homepage</a>
  <span class="footer-dot" aria-hidden="true"></span>
  <span id="footerDocName"><?= e($title) ?></span>
</footer>

<script>
(function () {
  // Use real browser back navigation so the homepage restores scroll
  // position instead of reloading fresh. Falls back to the href (plain
  // navigation to "/") when there's no same-origin page to return to,
  // e.g. the document link was opened directly or shared.
  function goBack(e) {
    const cameFromSite = document.referrer && new URL(document.referrer).origin === window.location.origin;
    if (cameFromSite && window.history.length > 1) {
      e.preventDefault();
      window.history.back();
    }
  }
  document.getElementById('backBtnTop').addEventListener('click', goBack);
  document.getElementById('backBtnFooter').addEventListener('click', goBack);
})();
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
(function () {
  const PDF_SRC = <?= json_encode($docUrl) ?>;
  const btn = document.getElementById('textViewBtn');
  const panel = document.getElementById('pdfTextView');
  const tvHeading = document.getElementById('tvHeading');
  const tvStatus = document.getElementById('tvStatus');
  const tvContent = document.getElementById('tvContent');
  const embed = document.getElementById('pdfEmbed');
  const fallback = document.getElementById('pdfFallback');
  let extracted = false;
  let extracting = false;
  let initialTextPromise = null;

  /*
   * Groups pdf.js's flat list of positioned text fragments into lines (by
   * y-coordinate) and then into paragraphs (by flagging line-to-line gaps
   * noticeably larger than the page's typical single-line gap). This is a
   * best-effort reconstruction, not a real structure - an untagged PDF
   * carries no paragraph/heading information at all, so a multi-column
   * layout can still interleave. It is still far more useful to NVDA than
   * the alternative of no extractable text whatsoever.
   */
  function groupTextItems(items) {
    if (!items.length) return [];
    const Y_EPS = 2;
    const lines = [];
    let curLine = [];
    let curY = items[0].transform[5];
    for (const item of items) {
      const y = item.transform[5];
      if (Math.abs(y - curY) > Y_EPS && curLine.length) {
        lines.push({ y: curY, text: curLine.join('').trim() });
        curLine = [];
      }
      curLine.push(item.str);
      curY = y;
      if (item.hasEOL) {
        lines.push({ y: curY, text: curLine.join('').trim() });
        curLine = [];
      }
    }
    if (curLine.length) lines.push({ y: curY, text: curLine.join('').trim() });

    const nonEmpty = lines.filter(l => l.text);
    if (!nonEmpty.length) return [];

    const gaps = [];
    for (let i = 1; i < nonEmpty.length; i++) gaps.push(Math.abs(nonEmpty[i - 1].y - nonEmpty[i].y));
    gaps.sort((a, b) => a - b);
    const typicalGap = gaps.length ? gaps[Math.floor(gaps.length / 2)] : 0;

    const paragraphs = [nonEmpty[0].text];
    for (let i = 1; i < nonEmpty.length; i++) {
      const gap = Math.abs(nonEmpty[i - 1].y - nonEmpty[i].y);
      if (typicalGap > 0 && gap > typicalGap * 1.6) {
        paragraphs.push(nonEmpty[i].text);
      } else {
        const last = paragraphs.length - 1;
        const joiner = /[-‐-―]$/.test(paragraphs[last]) ? '' : ' ';
        paragraphs[last] += joiner + nonEmpty[i].text;
      }
    }
    return paragraphs;
  }

  async function appendTextPage(pdf, pageNum) {
    const page = await pdf.getPage(pageNum);
    const content = await page.getTextContent();
    const paragraphs = groupTextItems(content.items);
    const section = document.createElement('section');
    const h = document.createElement('h2');
    h.textContent = 'Page ' + pageNum;
    section.appendChild(h);
    if (paragraphs.length) {
      paragraphs.forEach(text => {
        const p = document.createElement('p');
        p.textContent = text;
        section.appendChild(p);
      });
    } else {
      const p = document.createElement('p');
      p.textContent = 'No extractable text on this page - it may be an image.';
      section.appendChild(p);
    }
    tvContent.appendChild(section);
  }

  /*
   * Do not make a screen-reader user wait for an entire 60 MB document to be
   * parsed. The first page is added before this promise resolves, so focus can
   * land on real DOM text straight away; later pages are appended quietly in
   * order while the user reads. Waiting for every page here made NVDA appear
   * to have nothing to read on the larger framework documents.
   */
  function extractText() {
    if (extracted) return Promise.resolve();
    if (initialTextPromise) return initialTextPromise;

    extracting = true;
    tvStatus.textContent = 'Extracting text from the document…';
    tvContent.replaceChildren();

    initialTextPromise = (async () => {
      try {
        if (typeof pdfjsLib === 'undefined') throw new Error('pdf.js failed to load');
        pdfjsLib.GlobalWorkerOptions.workerSrc =
          'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        const pdf = await pdfjsLib.getDocument(PDF_SRC).promise;

        await appendTextPage(pdf, 1);
        const remainingPages = Array.from({ length: pdf.numPages - 1 }, (_, index) => index + 2);
        if (!remainingPages.length) {
          extracted = true;
          extracting = false;
          tvStatus.textContent = 'Text version ready.';
          return;
        }

        tvStatus.textContent = 'Text version ready. Loading the remaining pages…';
        (async () => {
          try {
            for (const pageNum of remainingPages) await appendTextPage(pdf, pageNum);
            extracted = true;
            tvStatus.textContent = 'All text pages are ready.';
          } catch (err) {
            tvStatus.textContent = 'Some pages could not be converted to text.';
            console.error('PDF text extraction failed:', err);
          } finally {
            extracting = false;
          }
        })();
      } catch (err) {
        extracting = false;
        tvStatus.textContent = "Sorry, the text could not be extracted from this document. Please use the Download PDF button instead.";
        console.error('PDF text extraction failed:', err);
      }
    })();

    return initialTextPromise;
  }

  btn.addEventListener('click', async () => {
    const open = !panel.classList.contains('show');
    panel.classList.toggle('show', open);
    btn.classList.toggle('active', open);
    btn.setAttribute('aria-pressed', String(open));
    embed.setAttribute('aria-hidden', String(open));
    if (fallback) fallback.setAttribute('aria-hidden', String(open));
    if (open) {
      // extractText() fills #tvContent while nothing has focus yet - the
      // aria-live #tvStatus text ("Extracting…") is announced on its own
      // regardless of focus, so the user isn't left with no feedback while
      // it runs. Focus only moves to the heading afterwards, once every
      // page's text is actually in the DOM: focusing it earlier announces
      // "Text version of this document" before the content behind it
      // exists, so a screen reader user who immediately arrows down or
      // presses Say All - the natural next move - reaches the end of an
      // empty panel and never finds out the real text was still loading.
      await extractText();
      // Do not move focus into a panel the visitor closed while extraction was
      // in progress.
      if (panel.classList.contains('show')) tvHeading.focus();
    }
  });
})();
</script>
<script src="<?= e(asset('js/accessibility-widget.js')) ?>"></script>

</body>
</html>
