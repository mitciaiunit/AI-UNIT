<?php
/**
 * @var string $title
 * @var string $lang
 * @var string $pdfUrl
 * @var string $downloadName
 * @var array<string,string> $s
 */
?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($title) ?></title>
  <link rel="icon" type="image/png" href="<?= e(asset('images/favicon.png')) ?>">
  <?php require __DIR__ . '/../includes/meta.php'; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; scroll-behavior: smooth; }

    :root {
      --blue:       #1A3A8F;
      --blue-mid:   #2554C7;
      --blue-light: #3b6ef0;
      --blue-pale:  #EEF2FF;

      --bg:       #F4F5F8;
      --surface:  #FFFFFF;
      --surface2: #F0F2F9;

      --text1: #0D1B3E;
      --text2: #4B5E77;
      --text3: #4B5E77;

      --border:  #DDE2EF;
      --border2: #C8CFE4;

      --radius:    12px;
      --radius-sm: 8px;
      --shadow:    0 2px 16px rgba(13,27,62,0.10);
      --shadow-lg: 0 8px 40px rgba(13,27,62,0.14);
      --trans:     0.18s ease;
    }

    body {
      font-family: 'Sora', system-ui, sans-serif;
      background: var(--bg);
      color: var(--text1);
      min-height: 100vh;
      overflow-x: hidden;
    }

    .skip-link {
      position: absolute; top: -40px; left: 14px;
      background: var(--blue); color: #fff;
      padding: 8px 16px; border-radius: 8px;
      font-size: 0.85rem; font-weight: 600;
      z-index: 9999; transition: top 0.2s;
      text-decoration: none;
    }
    .skip-link:focus { top: 14px; }

    .viewer-wrap { display: flex; flex-direction: column; height: 100vh; background: var(--bg); overflow: hidden; }

    .topbar {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 0 20px;
      height: 54px;
      display: flex; align-items: center; justify-content: space-between;
      gap: 12px; flex-shrink: 0; z-index: 10;
      box-shadow: 0 1px 4px rgba(13,27,62,0.06);
    }
    .topbar-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .back-btn {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 6px 14px;
      border: 1px solid var(--border);
      border-radius: 100px;
      font-size: 0.8rem; font-weight: 500; color: var(--text2);
      background: none; cursor: pointer; text-decoration: none;
      transition: all var(--trans); white-space: nowrap; flex-shrink: 0;
    }
    .back-btn:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-pale); }
    .divider-line { width: 1px; height: 22px; background: var(--border); flex-shrink: 0; }
    .breadcrumb {
      display: flex; align-items: center; gap: 6px;
      font-size: 0.8rem; color: var(--text3);
      overflow: hidden; white-space: nowrap;
    }
    /* Standalone page - style.css is not loaded here, so the visually-hidden
       utility it defines has to be repeated for the page h1. */
    .visually-hidden { position: absolute !important; width: 1px !important; height: 1px !important; padding: 0 !important; margin: -1px !important; overflow: hidden !important; clip: rect(0,0,0,0) !important; white-space: nowrap !important; border: 0 !important; }

    .breadcrumb strong { color: var(--text1); font-weight: 600; overflow: hidden; text-overflow: ellipsis; }
    .breadcrumb i { font-size: 12px; flex-shrink: 0; }

    .topbar-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .btn-dl {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 7px 16px;
      background: var(--blue); color: #fff;
      border: none; border-radius: 100px;
      font-size: 0.8rem; font-weight: 600;
      font-family: 'Sora', sans-serif;
      cursor: pointer; transition: all var(--trans);
      text-decoration: none; white-space: nowrap;
    }
    .btn-dl:hover { background: var(--blue-mid); transform: translateY(-1px); }
    .btn-dl i { font-size: 15px; }

    .main-area { flex: 1; display: flex; overflow: hidden; position: relative; }

    .thumb-sidebar {
      width: 0; background: var(--surface);
      border-right: 1px solid var(--border);
      overflow: hidden; transition: width 0.28s ease;
      flex-shrink: 0;
    }
    .thumb-sidebar.open { width: 156px; }
    .thumb-inner {
      width: 156px; padding: 12px 10px;
      display: flex; flex-direction: column; gap: 6px;
      overflow-y: auto; height: 100%;
    }
    .thumb-inner::-webkit-scrollbar { width: 4px; }
    .thumb-inner::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 2px; }
    .thumb-header {
      font-size: 0.65rem; font-weight: 700;
      letter-spacing: 0.1em; text-transform: uppercase;
      color: var(--text3); padding: 2px 2px 6px;
      border-bottom: 1px solid var(--border); flex-shrink: 0;
    }
    .thumb-grid { display: flex; flex-direction: column; gap: 6px; }
    .thumb-item {
      cursor: pointer; border-radius: 6px; overflow: hidden;
      border: 2px solid transparent;
      transition: all var(--trans);
      background: var(--surface2);
      position: relative; aspect-ratio: 3/4;
    }
    .thumb-item:hover { border-color: var(--blue-light); transform: translateY(-1px); }
    .thumb-item.active { border-color: var(--blue); box-shadow: 0 0 0 1px var(--blue); }
    .thumb-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .thumb-num {
      position: absolute; bottom: 3px; right: 4px;
      font-size: 0.6rem; font-weight: 700;
      background: rgba(13,27,62,0.55); color: #fff;
      padding: 1px 5px; border-radius: 3px;
    }
    .thumb-skeleton {
      width: 100%; height: 100%;
      background: linear-gradient(90deg, var(--surface2) 25%, var(--border) 50%, var(--surface2) 75%);
      background-size: 200% 100%;
      animation: shimmer 1.4s infinite;
    }
    @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

    .viewer-area {
      flex: 1; display: flex; align-items: center; justify-content: center;
      overflow: auto; padding: 32px 24px 110px;
      position: relative;
    }
    .viewer-area::-webkit-scrollbar { width: 5px; height: 5px; }
    .viewer-area::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }

    /* Covers the page-stage rather than replacing it, so toggling back
       keeps zoom/scroll/thumbnail state exactly as it was. */
    .text-view {
      display: none;
      position: absolute; inset: 0;
      background: var(--surface);
      overflow-y: auto;
      z-index: 5;
    }
    .text-view.open { display: block; }
    .text-view:focus { outline: none; }
    .tv-inner { max-width: 720px; margin: 0 auto; padding: 40px 24px 80px; }
    .tv-inner h2 { font-size: 1.05rem; font-weight: 700; color: var(--text1); margin: 28px 0 10px; }
    .tv-inner h2:first-child { margin-top: 0; }
    .tv-inner p { font-size: 1rem; line-height: 1.75; color: var(--text1); margin: 0 0 14px; }
    .tv-instructions { padding: 12px 14px; background: var(--blue-pale); border-left: 4px solid var(--blue); border-radius: 4px; }
    .tv-status { font-size: 0.9rem; color: var(--text2); margin: 0 0 14px; }

    /* The floating page-turn arrows sit above the canvas view at all times;
       hidden while the text view covers it so they don't float uselessly
       over reading content. */
    body.text-view-active .nav-btn { display: none; }

    /* Page nav, zoom and thumbnails in the bottom toolbar all act on the
       canvas view, which the text view has covered - and being
       position:fixed, the full bar would otherwise sit on top of and hide
       whatever text has scrolled underneath it. Collapsed to just the
       toggle button itself, which stays reachable so the text view can be
       closed again. */
    body.text-view-active .bottom-bar > *:not(#btnTextView) { display: none; }

    .page-stage { position: relative; display: inline-flex; align-items: center; justify-content: center; }

    .page-frame {
      background: #fff;
      box-shadow: 0 2px 8px rgba(13,27,62,0.08), 0 8px 40px rgba(13,27,62,0.13);
      border-radius: 3px; overflow: hidden;
      transition: box-shadow var(--trans);
    }
    .page-frame:hover { box-shadow: 0 4px 12px rgba(13,27,62,0.10), 0 12px 50px rgba(13,27,62,0.16); }
    .page-frame img { display: block; user-select: none; -webkit-user-drag: none; }
    .page-frame.anim-right { animation: animR 0.3s ease both; }
    .page-frame.anim-left  { animation: animL 0.3s ease both; }
    @keyframes animR {
      0%   { opacity:1; transform: translateX(0); }
      40%  { opacity:0; transform: translateX(-24px); }
      41%  { opacity:0; transform: translateX(24px); }
      100% { opacity:1; transform: translateX(0); }
    }
    @keyframes animL {
      0%   { opacity:1; transform: translateX(0); }
      40%  { opacity:0; transform: translateX(24px); }
      41%  { opacity:0; transform: translateX(-24px); }
      100% { opacity:1; transform: translateX(0); }
    }

    .loader {
      display: flex; flex-direction: column;
      align-items: center; gap: 14px;
      padding: 60px 24px; text-align: center;
    }
    .spinner {
      width: 38px; height: 38px;
      border: 3px solid var(--border2);
      border-top-color: var(--blue);
      border-radius: 50%;
      animation: spin 0.75s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loader p { font-size: 0.875rem; color: var(--text2); line-height: 1.65; max-width: 100%; }
    .loader code {
      color: var(--blue); background: var(--blue-pale);
      padding: 2px 7px; border-radius: 5px; font-size: 0.82rem;
      overflow-wrap: break-word; word-break: break-word;
    }
    .err-icon { font-size: 44px; color: #E24B4A; line-height: 1; }
    .err-title { font-size: 1rem; font-weight: 700; color: #A32D2D; }

    .nav-btn {
      position: fixed; top: 50%; z-index: 20;
      width: 46px; height: 46px; border-radius: 50%;
      background: var(--surface); border: 1px solid var(--border);
      box-shadow: 0 2px 12px rgba(13,27,62,0.12);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--text2);
      transition: all var(--trans); transform: translateY(-50%);
    }
    .nav-btn:hover { background: var(--blue); color: #fff; border-color: var(--blue); transform: translateY(-50%) scale(1.07); }
    .nav-btn:disabled { opacity: 0.25; pointer-events: none; }
    .nav-btn i { font-size: 22px; }
    .nav-btn.prev { left: 14px; }
    .nav-btn.next { right: 14px; }

    .bottom-bar {
      position: fixed; bottom: 22px;
      left: 50%; transform: translateX(-50%);
      z-index: 30;
      display: flex; align-items: center; gap: 4px;
      padding: 7px 12px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 100px;
      box-shadow: 0 4px 28px rgba(13,27,62,0.15);
      white-space: nowrap;
    }
    .bb-sep { width: 1px; height: 22px; background: var(--border); flex-shrink: 0; margin: 0 2px; }
    .bb-btn {
      width: 34px; height: 34px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      border: none; background: none;
      color: var(--text2); cursor: pointer;
      transition: all var(--trans);
    }
    .bb-btn i { font-size: 18px; pointer-events: none; }
    .bb-btn:hover { background: var(--blue-pale); color: var(--blue); }
    .bb-btn:disabled { opacity: 0.28; pointer-events: none; }
    .bb-btn.active { background: var(--blue-pale); color: var(--blue); }
    .bb-page {
      display: flex; align-items: center; gap: 5px;
      font-size: 0.8rem; color: var(--text2);
    }
    .bb-page input {
      width: 36px; text-align: center;
      border: 1px solid var(--border2);
      border-radius: 6px; padding: 3px 4px;
      font-size: 0.8rem; font-family: 'Sora', sans-serif;
      color: var(--text1); background: var(--bg);
    }
    .bb-page input:focus { outline: 2px solid var(--blue); border-color: transparent; }
    .bb-total { color: var(--text3); }
    .progress-wrap {
      width: 72px; height: 3px;
      background: var(--border); border-radius: 2px; overflow: hidden;
    }
    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--blue), var(--blue-light));
      border-radius: 2px; transition: width 0.3s ease;
    }
    .zoom-badge {
      font-size: 0.75rem; font-weight: 600; color: var(--text2);
      min-width: 38px; text-align: center;
    }

    .share-popup {
      position: fixed; bottom: 82px;
      left: 50%; transform: translateX(-50%);
      background: var(--surface); border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: 0 8px 32px rgba(13,27,62,0.14);
      padding: 16px 18px; display: none; z-index: 40; min-width: 210px;
    }
    .share-popup.open { display: block; }
    .share-popup-title {
      font-size: 0.68rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.1em;
      color: var(--text3); margin-bottom: 10px;
    }
    .share-row { display: flex; flex-direction: column; gap: 7px; }
    .share-btn {
      display: flex; align-items: center; gap: 8px;
      padding: 8px 12px; border: 1px solid var(--border);
      border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 500;
      color: var(--text2); background: none; cursor: pointer;
      transition: all var(--trans); font-family: 'Sora', sans-serif;
      text-align: left; width: 100%;
    }
    .share-btn i { font-size: 16px; color: var(--text3); }
    .share-btn:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-pale); }
    .share-btn:hover i { color: var(--blue); }

    .toast {
      position: fixed; bottom: 88px; left: 50%; transform: translateX(-50%);
      background: var(--text1); color: #fff;
      padding: 9px 20px; border-radius: 100px;
      font-size: 0.8rem; font-weight: 500;
      opacity: 0; pointer-events: none; z-index: 50;
      transition: opacity 0.2s ease;
    }
    .toast.show { opacity: 1; }

    .kbd-hints {
      position: fixed; bottom: 0; left: 0; right: 0;
      text-align: center;
      padding: 5px 20px;
      font-size: 0.68rem; color: var(--text3);
      background: var(--surface);
      border-top: 1px solid var(--border);
      display: flex; align-items: center; justify-content: center; gap: 18px;
      /* opacity:0.8 removed - it faded this text to 2.32:1 against the bar it
         sits on. Darkening --text3 alone still left it at 4.14:1, because the
         fade applies on top of the colour; the two together were the failure.
         Found by an axe-core scan during final QA (WCAG 2.1 AA 1.4.3). */
    }
    .kbd-hint { display: flex; align-items: center; gap: 4px; }
    kbd {
      padding: 1px 6px; background: var(--surface2);
      border: 1px solid var(--border2); border-radius: 4px;
      font-size: 0.65rem; font-family: monospace; color: var(--text2);
    }

    @media (max-width: 768px) {
      .breadcrumb { display: none; }
      .nav-btn { width: 38px; height: 38px; }
      .nav-btn i { font-size: 18px; }
      .nav-btn.prev { left: 6px; }
      .nav-btn.next { right: 6px; }
      .topbar { padding: 0 12px; }
      .btn-dl span { display: none; }
      .viewer-area { padding: 20px 8px 110px; }
      .kbd-hints { display: none; }
      .bottom-bar { padding: 6px 10px; gap: 2px; bottom: 10px; }
    }
    @media (max-width: 480px) {
      .bottom-bar { max-width: calc(100vw - 20px); flex-wrap: wrap; border-radius: 16px; justify-content: center; }
    }
  </style>
</head>
<body>

<a class="skip-link" href="#viewerArea"><?= e($s['skipLink']) ?></a>

<div class="viewer-wrap">

  <!-- TOP BAR -->
  <header class="topbar" role="banner">
    <div class="topbar-left">
      <a href="<?= e(url('/')) ?>" class="back-btn" id="backBtn">
        <i class="ti ti-arrow-left" style="font-size:14px;" aria-hidden="true"></i>
        <?= e($s['backBtn']) ?>
      </a>
      <div class="divider-line" aria-hidden="true"></div>
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <span><?= e($s['breadcrumbCategory']) ?></span>
        <i class="ti ti-chevron-right" aria-hidden="true"></i>
        <strong><?= e($s['breadcrumbTitle']) ?></strong>
      </nav>
    </div>
    <div class="topbar-right">
      <a href="<?= e($pdfUrl) ?>" download="<?= e($downloadName) ?>" class="btn-dl" aria-label="<?= e($s['downloadAria']) ?>">
        <i class="ti ti-download" aria-hidden="true"></i>
        <span><?= e($s['downloadLabel']) ?></span>
      </a>
    </div>
  </header>

  <?php
  /*
   * The booklet's title appears on screen only inside the breadcrumb, and a
   * breadcrumb item is not a heading - nor is it always present, since
   * .breadcrumb is display:none on narrow screens. A visually-hidden h1
   * carries the real title (the same string the breadcrumb shows) so the page
   * has one meaningful heading at every width, without altering the top bar.
   *
   * Clipped rather than display:none, which would remove it from the
   * accessibility tree - the opposite of the point.
   */
  ?>
  <h1 class="visually-hidden"><?= e($s['breadcrumbTitle']) ?></h1>

  <!-- MAIN AREA -->
  <div class="main-area">

    <!-- THUMBNAIL SIDEBAR -->
    <aside class="thumb-sidebar" id="thumbSidebar" aria-label="<?= e($s['thumbSidebarAria']) ?>">
      <div class="thumb-inner">
        <div class="thumb-header"><?= e($s['allPages']) ?></div>
        <div class="thumb-grid" id="thumbGrid" role="list"></div>
      </div>
    </aside>

    <!-- VIEWER -->
    <main class="viewer-area" id="viewerArea" role="main" aria-label="<?= e($s['viewerAria']) ?>">
      <div class="page-stage" id="pageStage">
        <div class="loader" id="loader" aria-live="polite">
          <div class="spinner" aria-hidden="true"></div>
          <p><?= e($s['loadingText']) ?><br><br>
             <?= e($s['loadingHint']) ?><br>
             <code><?= e($pdfUrl) ?></code></p>
        </div>
      </div>

      <!--
       * Screen-reader alternative to the page-by-page canvas images above.
       * Each page there is rendered to a <canvas> and shown as a plain <img>
       * (see showPage() in the script below) - there is no text in the DOM
       * for NVDA to read, only the fixed "Page N" alt text. This panel is
       * built from the PDF's own text layer via pdf.js's getTextContent(),
       * independent of that.
       *
       * Focus goes to #tvHeading, not this wrapper, when the panel opens.
       * Focusing a plain <div> announces nothing - NVDA doesn't read a
       * container's descendants just because focus landed on it, so a
       * screen reader user would hear silence and have no reason to look
       * further in. A heading has real text, so focusing it is actually
       * announced, and it gives a natural point to arrow down from.
      -->
      <div class="text-view" id="textView">
        <div class="tv-inner">
          <h2 id="tvHeading" tabindex="-1" aria-describedby="tvInstructions"><?= e($s['textViewHeading']) ?></h2>
          <p class="tv-instructions" id="tvInstructions"><?= e($s['textViewInstructions']) ?></p>
          <p class="tv-status" id="tvStatus" role="status" aria-live="polite"></p>
          <div id="tvContent"></div>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- SIDE NAV ARROWS -->
<button class="nav-btn prev" id="btnPrev" disabled aria-label="<?= e($s['prevPage']) ?>" title="<?= e($s['prevPage']) ?> (←)">
  <i class="ti ti-chevron-left" aria-hidden="true"></i>
</button>
<button class="nav-btn next" id="btnNext" disabled aria-label="<?= e($s['nextPage']) ?>" title="<?= e($s['nextPage']) ?> (→)">
  <i class="ti ti-chevron-right" aria-hidden="true"></i>
</button>

<!-- BOTTOM TOOLBAR -->
<div class="bottom-bar" role="toolbar" aria-label="Reader controls">
  <button class="bb-btn" id="bbFirst" disabled title="<?= e($s['firstPage']) ?>" aria-label="<?= e($s['firstPage']) ?>">
    <i class="ti ti-player-skip-back" aria-hidden="true"></i>
  </button>
  <button class="bb-btn" id="bbPrev" disabled aria-label="<?= e($s['prevPage']) ?>">
    <i class="ti ti-chevron-left" aria-hidden="true"></i>
  </button>

  <div class="bb-sep" aria-hidden="true"></div>

  <div class="bb-page">
    <input type="number" id="pageInput" value="1" min="1" aria-label="Current page number" />
    <span class="bb-total" id="totalDisp" aria-live="polite">/ -</span>
  </div>
  <div class="progress-wrap" title="Reading progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" id="progressBar">
    <div class="progress-fill" id="progressFill" style="width:0%"></div>
  </div>

  <div class="bb-sep" aria-hidden="true"></div>

  <button class="bb-btn" id="bbNext" disabled aria-label="<?= e($s['nextPage']) ?>">
    <i class="ti ti-chevron-right" aria-hidden="true"></i>
  </button>
  <button class="bb-btn" id="bbLast" disabled title="<?= e($s['lastPage']) ?>" aria-label="<?= e($s['lastPage']) ?>">
    <i class="ti ti-player-skip-forward" aria-hidden="true"></i>
  </button>

  <div class="bb-sep" aria-hidden="true"></div>

  <button class="bb-btn" id="btnThumbs" title="<?= e($s['toggleThumbs']) ?>" aria-pressed="false" aria-label="<?= e($s['toggleThumbs']) ?>">
    <i class="ti ti-layout-grid" aria-hidden="true"></i>
  </button>
  <button class="bb-btn" id="btnTextView" title="<?= e($s['textViewLabel']) ?>" aria-pressed="false" aria-label="<?= e($s['textViewLabel']) ?>">
    <i class="ti ti-file-text" aria-hidden="true"></i>
  </button>

  <div class="bb-sep" aria-hidden="true"></div>

  <button class="bb-btn" id="btnZoomOut" title="<?= e($s['zoomOut']) ?> (-)" aria-label="<?= e($s['zoomOut']) ?>">
    <i class="ti ti-zoom-out" aria-hidden="true"></i>
  </button>
  <span class="zoom-badge" id="zoomBadge" aria-live="polite" aria-label="Zoom level">100%</span>
  <button class="bb-btn" id="btnZoomIn" title="<?= e($s['zoomIn']) ?> (+)" aria-label="<?= e($s['zoomIn']) ?>">
    <i class="ti ti-zoom-in" aria-hidden="true"></i>
  </button>
  <button class="bb-btn" id="btnFit" title="<?= e($s['fitScreen']) ?>" aria-label="<?= e($s['fitScreen']) ?>">
    <i class="ti ti-maximize" aria-hidden="true"></i>
  </button>

  <div class="bb-sep" aria-hidden="true"></div>

  <button class="bb-btn" id="btnShare" title="<?= e($s['share']) ?>" aria-label="<?= e($s['share']) ?>" aria-haspopup="true" aria-expanded="false">
    <i class="ti ti-share" aria-hidden="true"></i>
  </button>
  <button class="bb-btn" id="btnFullscreen" title="<?= e($s['fullscreen']) ?> (F)" aria-label="<?= e($s['fullscreen']) ?>">
    <i class="ti ti-arrows-maximize" aria-hidden="true"></i>
  </button>
</div>

<!-- SHARE POPUP -->
<div class="share-popup" id="sharePopup" role="dialog" aria-label="<?= e($s['sharePopupAria']) ?>">
  <div class="share-popup-title"><?= e($s['sharePopupTitle']) ?></div>
  <div class="share-row">
    <button class="share-btn" id="shareCopyLink">
      <i class="ti ti-link" aria-hidden="true"></i> <?= e($s['copyLink']) ?>
    </button>
    <button class="share-btn" id="shareDl">
      <i class="ti ti-download" aria-hidden="true"></i> <?= e($s['shareDownload']) ?>
    </button>
    <button class="share-btn" id="sharePrint">
      <i class="ti ti-printer" aria-hidden="true"></i> <?= e($s['print']) ?>
    </button>
  </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast" role="status" aria-live="polite"></div>

<!-- KEYBOARD HINTS -->
<div class="kbd-hints" aria-hidden="true">
  <span class="kbd-hint"><kbd>←</kbd><kbd>→</kbd> <?= e($s['kbdNavigate']) ?></span>
  <span class="kbd-hint"><kbd>+</kbd><kbd>-</kbd> <?= e($s['kbdZoom']) ?></span>
  <span class="kbd-hint"><kbd>F</kbd> <?= e($s['kbdFullscreen']) ?></span>
  <span class="kbd-hint"><kbd>Home</kbd> <?= e($s['kbdFirstPage']) ?></span>
  <span class="kbd-hint"><kbd>End</kbd> <?= e($s['kbdLastPage']) ?></span>
</div>

<script>
(function () {
  // Use real browser back navigation so the homepage restores scroll
  // position instead of reloading fresh. Falls back to the href (plain
  // navigation to "/") when there's no same-origin page to return to,
  // e.g. the booklet link was opened directly or shared.
  document.getElementById('backBtn').addEventListener('click', (e) => {
    const cameFromSite = document.referrer && new URL(document.referrer).origin === window.location.origin;
    if (cameFromSite && window.history.length > 1) {
      e.preventDefault();
      window.history.back();
    }
  });
})();
(function () {
  pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

  let pdf       = null;
  let total     = 0;
  let cur       = 1;
  let zoom      = 1.0;
  let sidebar   = false;
  let rendering = false;
  let cache     = {};
  let textCache      = {};
  let textViewOpen   = false;
  let fullTextLoaded = false;
  let textExtracting = false;
  let initialTextPromise = null;
  /*
   * init() below sets `pdf` once pdfjsLib.getDocument() resolves - which, on
   * a slow connection or CDN fetch of pdf.worker.min.js, can take a real
   * couple of seconds. #btnTextView is clickable from the moment the page
   * loads, not just once that finishes, so ensureFullTextLoaded() needs a
   * way to wait for `pdf` rather than bail out silently if it's clicked
   * first - which is exactly what used to happen: a bare `if (!pdf) return`
   * with no retry, so a click that landed early did nothing at all, ever,
   * with no error and no status update.
   */
  let resolvePdfReady, rejectPdfReady;
  const pdfReady = new Promise((resolve, reject) => { resolvePdfReady = resolve; rejectPdfReady = reject; });
  // Nothing awaits pdfReady at all when the PDF loads (or fails) before the
  // text view is ever opened - without this, that's an unhandled promise
  // rejection logged to the console on every ordinary load failure.
  pdfReady.catch(() => {});

  const PDF_SRC = <?= json_encode($pdfUrl) ?>;
  const S = <?= json_encode($s, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;

  const loader       = document.getElementById('loader');
  const pageStage    = document.getElementById('pageStage');
  const thumbGrid    = document.getElementById('thumbGrid');
  const totalDisp    = document.getElementById('totalDisp');
  const pageInput    = document.getElementById('pageInput');
  const progressFill = document.getElementById('progressFill');
  const progressBar  = document.getElementById('progressBar');
  const zoomBadge    = document.getElementById('zoomBadge');
  const thumbSidebar = document.getElementById('thumbSidebar');
  const btnPrev      = document.getElementById('btnPrev');
  const btnNext      = document.getElementById('btnNext');
  const sharePopup   = document.getElementById('sharePopup');
  const toast        = document.getElementById('toast');
  const btnTextView  = document.getElementById('btnTextView');
  const textView     = document.getElementById('textView');
  const tvHeading    = document.getElementById('tvHeading');
  const tvStatus     = document.getElementById('tvStatus');
  const tvContent    = document.getElementById('tvContent');

  async function renderPage(pageNum, scale) {
    const key = pageNum + '_' + scale.toFixed(2);
    if (cache[key]) return cache[key];
    const page = await pdf.getPage(pageNum);
    const vp   = page.getViewport({ scale });
    const canvas = document.createElement('canvas');
    canvas.width  = vp.width;
    canvas.height = vp.height;
    await page.render({ canvasContext: canvas.getContext('2d'), viewport: vp }).promise;
    const url = canvas.toDataURL('image/jpeg', 0.92);
    cache[key] = url;
    return url;
  }

  async function calcScale() {
    const va   = document.getElementById('viewerArea');
    const aw   = va.clientWidth  - 56;
    const ah   = va.clientHeight - 56;
    const page = await pdf.getPage(1);
    const vp   = page.getViewport({ scale: 1 });
    const fit  = Math.min(aw / vp.width, ah / vp.height);
    return fit * zoom;
  }

  /*
   * Groups pdf.js's flat list of positioned text fragments into lines (by
   * y-coordinate) and then into paragraphs (by flagging line-to-line gaps
   * noticeably larger than the page's typical single-line gap). Best-effort
   * reconstruction, not real structure - an untagged PDF carries no
   * paragraph/heading information, so a multi-column layout can still
   * interleave. Still far more useful to NVDA than no extractable text.
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

  async function extractPageText(pageNum) {
    if (textCache[pageNum]) return textCache[pageNum];
    const page = await pdf.getPage(pageNum);
    const content = await page.getTextContent();
    const paragraphs = groupTextItems(content.items);
    textCache[pageNum] = paragraphs;
    return paragraphs;
  }

  async function appendTextPage(pageNum) {
    const paragraphs = await extractPageText(pageNum);
    const section = document.createElement('section');
    const h = document.createElement('h2');
    h.textContent = S.pageHeadingPrefix + ' ' + pageNum;
    section.appendChild(h);
    if (paragraphs.length) {
      paragraphs.forEach(text => {
        const p = document.createElement('p');
        p.textContent = text;
        section.appendChild(p);
      });
    } else {
      const p = document.createElement('p');
      p.textContent = S.textViewPageEmpty;
      section.appendChild(p);
    }
    tvContent.appendChild(section);
  }

  /* Make the page the visitor was viewing readable first. The remaining
     booklet pages keep loading after focus reaches the first real heading. */
  function ensureFullTextLoaded() {
    if (fullTextLoaded) return Promise.resolve();
    if (initialTextPromise) return initialTextPromise;

    textExtracting = true;
    tvStatus.textContent = S.textViewLoading;
    tvContent.replaceChildren();

    initialTextPromise = (async () => {
      try {
        // Waits rather than bailing out if the button was clicked before
        // init() finished loading the PDF - see pdfReady above.
        await pdfReady;
        await appendTextPage(cur);
        const remainingPages = Array.from({ length: total }, (_, index) => index + 1)
          .filter(pageNum => pageNum !== cur);
        if (!remainingPages.length) {
          fullTextLoaded = true;
          textExtracting = false;
          tvStatus.textContent = S.textViewReady;
          return;
        }

        tvStatus.textContent = S.textViewReadyLoading;
        (async () => {
          try {
            for (const pageNum of remainingPages) await appendTextPage(pageNum);
            fullTextLoaded = true;
            tvStatus.textContent = S.textViewReady;
          } catch (err) {
            tvStatus.textContent = S.textViewPartialError;
            console.error('Booklet text extraction failed:', err);
          } finally {
            textExtracting = false;
          }
        })();
      } catch (err) {
        textExtracting = false;
        tvStatus.textContent = S.textViewError;
        console.error('Booklet text extraction failed:', err);
      }
    })();

    return initialTextPromise;
  }

  async function showPage(pageNum, dir) {
    if (!pdf) return;
    rendering = true;

    const scale  = await calcScale();
    const dpr    = window.devicePixelRatio || 1;
    const url    = await renderPage(pageNum, scale * dpr);

    const frame = document.createElement('div');
    frame.className = 'page-frame' + (dir ? ' anim-' + dir : '');

    const img = document.createElement('img');
    img.src  = url;
    const vp = (await pdf.getPage(pageNum)).getViewport({ scale });
    img.style.width  = Math.round(vp.width)  + 'px';
    img.style.height = Math.round(vp.height) + 'px';
    // Decorative: the real content is available to assistive tech through
    // #btnTextView's extracted text view (see extractPageText() below), so
    // this raster picture doesn't need its own competing description.
    img.alt = '';
    img.setAttribute('aria-hidden', 'true');

    frame.appendChild(img);
    pageStage.innerHTML = '';
    pageStage.appendChild(frame);

    updateUI();
    preloadNear(pageNum);
    rendering = false;
  }

  async function preloadNear(n) {
    const scale = await calcScale();
    const dpr   = window.devicePixelRatio || 1;
    for (let i = Math.max(1, n - 1); i <= Math.min(total, n + 2); i++) {
      renderPage(i, scale * dpr).catch(() => {});
    }
  }

  function updateUI() {
    pageInput.value       = cur;
    totalDisp.textContent = '/ ' + total;
    zoomBadge.textContent = Math.round(zoom * 100) + '%';

    const pct = total > 1 ? ((cur - 1) / (total - 1)) * 100 : 100;
    progressFill.style.width = pct + '%';
    progressBar.setAttribute('aria-valuenow', Math.round(pct));

    const isFirst = cur <= 1;
    const isLast  = cur >= total;
    btnPrev.disabled = isFirst;
    btnNext.disabled = isLast;
    document.getElementById('bbFirst').disabled = isFirst;
    document.getElementById('bbPrev').disabled  = isFirst;
    document.getElementById('bbNext').disabled  = isLast;
    document.getElementById('bbLast').disabled  = isLast;

    document.querySelectorAll('.thumb-item').forEach(t => {
      const active = parseInt(t.dataset.p) === cur;
      t.classList.toggle('active', active);
      if (active) t.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    });
  }

  function goTo(n, dir) {
    if (!pdf) return;
    const target = Math.max(1, Math.min(n, total));
    if (target === cur && !dir) return;
    const direction = dir || (target > cur ? 'right' : 'left');
    cur = target;
    showPage(cur, direction);
  }

  async function buildThumbs() {
    thumbGrid.innerHTML = '';
    for (let i = 1; i <= total; i++) {
      const item = document.createElement('div');
      item.className = 'thumb-item' + (i === 1 ? ' active' : '');
      item.dataset.p = i;
      item.setAttribute('role', 'listitem');
      item.setAttribute('tabindex', '0');
      item.setAttribute('aria-label', 'Page ' + i);

      const skeleton = document.createElement('div');
      skeleton.className = 'thumb-skeleton';
      item.appendChild(skeleton);

      const num = document.createElement('div');
      num.className = 'thumb-num';
      num.textContent = i;
      item.appendChild(num);

      thumbGrid.appendChild(item);
      item.addEventListener('click', () => goTo(i));
      item.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); goTo(i); }
      });

      renderPage(i, 0.35 * (window.devicePixelRatio || 1)).then(url => {
        const img = document.createElement('img');
        img.src = url; img.alt = '';
        img.setAttribute('aria-hidden', 'true');
        skeleton.replaceWith(img);
      }).catch(() => { skeleton.remove(); });
    }
  }

  async function init() {
    try {
      pdf   = await pdfjsLib.getDocument(PDF_SRC).promise;
      total = pdf.numPages;
      pageInput.max = total;
      loader.style.display = 'none';
      resolvePdfReady();
      await showPage(1, null);
      buildThumbs();
    } catch (err) {
      rejectPdfReady(err);
      loader.innerHTML =
        '<i class="ti ti-alert-circle err-icon" aria-hidden="true"></i>' +
        '<p class="err-title">' + S.errorTitle + '</p>' +
        '<p>' + S.errorHint + ' <code>' + PDF_SRC + '</code> ' +
        S.errorHint2 + '<br><br>' +
        S.errorDownloadHint + '</p>';
    }
  }

  /* ── Event listeners ── */
  btnPrev.addEventListener('click', () => goTo(cur - 1, 'left'));
  btnNext.addEventListener('click', () => goTo(cur + 1, 'right'));
  document.getElementById('bbFirst').addEventListener('click', () => goTo(1, 'left'));
  document.getElementById('bbPrev').addEventListener('click',  () => goTo(cur - 1, 'left'));
  document.getElementById('bbNext').addEventListener('click',  () => goTo(cur + 1, 'right'));
  document.getElementById('bbLast').addEventListener('click',  () => goTo(total, 'right'));

  pageInput.addEventListener('keydown', e => { if (e.key === 'Enter') goTo(parseInt(e.target.value) || 1); });
  pageInput.addEventListener('blur',    e => goTo(parseInt(e.target.value) || 1));

  document.getElementById('btnZoomIn').addEventListener('click', () => {
    zoom = Math.min(3.0, zoom + 0.2); cache = {}; showPage(cur);
  });
  document.getElementById('btnZoomOut').addEventListener('click', () => {
    zoom = Math.max(0.4, zoom - 0.2); cache = {}; showPage(cur);
  });
  document.getElementById('btnFit').addEventListener('click', () => {
    zoom = 1.0; cache = {}; showPage(cur);
  });

  document.getElementById('btnThumbs').addEventListener('click', function () {
    sidebar = !sidebar;
    thumbSidebar.classList.toggle('open', sidebar);
    this.classList.toggle('active', sidebar);
    this.setAttribute('aria-pressed', sidebar);
    setTimeout(() => { cache = {}; showPage(cur); }, 300);
  });

  btnTextView.addEventListener('click', async function () {
    textViewOpen = !textViewOpen;
    this.classList.toggle('active', textViewOpen);
    this.setAttribute('aria-pressed', textViewOpen);
    textView.classList.toggle('open', textViewOpen);
    document.body.classList.toggle('text-view-active', textViewOpen);
    // pageStage stays in the DOM (only visually covered) so it has to be
    // pulled from the accessibility tree explicitly, or NVDA's Browse Mode
    // can still reach the decorative canvas image behind the text panel.
    pageStage.setAttribute('aria-hidden', textViewOpen ? 'true' : 'false');
    if (textViewOpen) {
      // ensureFullTextLoaded() fills #tvContent while nothing has focus yet
      // - the aria-live #tvStatus text ("Extracting…") is announced on its
      // own regardless of focus, so the user isn't left with no feedback
      // while it runs. Focus only moves to the heading afterwards, once
      // every page's text is actually in the DOM: focusing it earlier
      // announces "Text version of this booklet" before the content behind
      // it exists, so a screen reader user who immediately arrows down or
      // presses Say All - the natural next move - reaches the end of an
      // empty panel and never finds out the real text was still loading.
      await ensureFullTextLoaded();
      if (textViewOpen) tvHeading.focus();
    }
  });

  document.getElementById('btnFullscreen').addEventListener('click', () => {
    if (!document.fullscreenElement) {
      document.documentElement.requestFullscreen().catch(() => {});
    } else {
      document.exitFullscreen();
    }
  });
  document.addEventListener('fullscreenchange', () => {
    const btn = document.getElementById('btnFullscreen');
    const isFs = !!document.fullscreenElement;
    btn.title = isFs ? S.fullscreen + ' (F)' : S.fullscreen + ' (F)';
    btn.querySelector('i').className = isFs ? 'ti ti-arrows-minimize' : 'ti ti-arrows-maximize';
    if (!isFs) setTimeout(() => { cache = {}; showPage(cur); }, 120);
  });

  document.getElementById('btnShare').addEventListener('click', function (e) {
    e.stopPropagation();
    const open = sharePopup.classList.toggle('open');
    this.setAttribute('aria-expanded', open);
  });
  document.addEventListener('click', () => {
    sharePopup.classList.remove('open');
    document.getElementById('btnShare').setAttribute('aria-expanded', 'false');
  });
  sharePopup.addEventListener('click', e => e.stopPropagation());

  document.getElementById('shareCopyLink').addEventListener('click', () => {
    navigator.clipboard.writeText(window.location.href)
      .then(() => showToast(S.linkCopied))
      .catch(() => showToast(S.linkCopyFailed));
    sharePopup.classList.remove('open');
  });
  document.getElementById('shareDl').addEventListener('click', () => {
    const a = document.createElement('a');
    a.href = PDF_SRC; a.download = <?= json_encode($downloadName) ?>; a.click();
    sharePopup.classList.remove('open');
  });
  document.getElementById('sharePrint').addEventListener('click', () => {
    window.print();
    sharePopup.classList.remove('open');
  });

  let toastTimer;
  function showToast(msg) {
    toast.textContent = msg;
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('show'), 2200);
  }

  document.addEventListener('keydown', e => {
    if (e.target.tagName === 'INPUT') return;
    // These are page-turn/zoom shortcuts for the visual canvas view; while
    // the text view is open they'd only fight with normal reading/scrolling
    // (e.g. ArrowDown) for no visible effect, so they're switched off.
    if (textViewOpen) return;
    switch (e.key) {
      case 'ArrowRight': case 'ArrowDown': case 'PageDown': goTo(cur + 1, 'right'); break;
      case 'ArrowLeft':  case 'ArrowUp':   case 'PageUp':   goTo(cur - 1, 'left');  break;
      case 'Home': goTo(1, 'left');      break;
      case 'End':  goTo(total, 'right'); break;
      case '+': case '=': zoom = Math.min(3, zoom + 0.2); cache = {}; showPage(cur); break;
      case '-': case '_': zoom = Math.max(0.4, zoom - 0.2); cache = {}; showPage(cur); break;
      case 'f': case 'F': document.getElementById('btnFullscreen').click(); break;
      case 'Escape':
        sharePopup.classList.remove('open');
        document.getElementById('btnShare').setAttribute('aria-expanded', 'false');
        break;
    }
  });

  let touchStartX = 0;
  const viewerArea = document.getElementById('viewerArea');
  viewerArea.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
  viewerArea.addEventListener('touchend', e => {
    const dx = e.changedTouches[0].screenX - touchStartX;
    if (Math.abs(dx) > 50) dx < 0 ? goTo(cur + 1, 'right') : goTo(cur - 1, 'left');
  }, { passive: true });

  viewerArea.addEventListener('wheel', e => {
    if (e.ctrlKey || e.metaKey) {
      e.preventDefault();
      zoom = Math.max(0.4, Math.min(3, zoom + (e.deltaY < 0 ? 0.15 : -0.15)));
      cache = {}; showPage(cur);
    }
  }, { passive: false });

  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => { cache = {}; showPage(cur); }, 260);
  });

  init();
})();
</script>
<script src="<?= e(asset('js/accessibility-widget.js')) ?>"></script>

</body>
</html>
