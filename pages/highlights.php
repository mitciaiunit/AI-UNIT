<?php
/**
 * Highlights - a case study of the AI Unit internship: the aim.govmu.org
 * revamp, the DIVA assistant and the WCAG 2.2 accessibility toolbar.
 *
 * Everything is wrapped in `.sc`, which is the scoping root for
 * assets/css/highlights.css. That stylesheet carries its own design
 * system (Inter, a blue/ink token ramp, its own element resets); confining it
 * to this subtree is what stops it restyling the shared navbar, footer and
 * DIVA widget that the same layout renders around it.
 *
 * The standalone page's own header and footer are intentionally absent - the
 * project's shared navbar and footer take their place, per the reuse of
 * existing components.
 */
?>
<div class="sc no-js" id="scRoot">
<?php /* Flip the flag immediately, so the reveal styles only apply when the
         JavaScript that undoes them is present. */ ?>
<script>document.getElementById('scRoot').classList.replace('no-js', 'js');</script>

    <div class="scroll-progress" aria-hidden="true"><span id="scrollProgressBar"></span></div>

    <svg class="sprite" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">
      <!-- Interface -->
      <symbol id="i-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></symbol>
      <symbol id="i-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></symbol>
      <symbol id="i-arrow-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m-6-7 7 7-7 7"/></symbol>
      <symbol id="i-arrow-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5m6-7-7 7 7 7"/></symbol>
      <symbol id="i-arrow-down" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14m-7-6 7 7 7-7"/></symbol>
      <symbol id="i-expand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6M9 21H3v-6M21 3l-7.5 7.5M3 21l7.5-7.5"/></symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 13 4 4L19 7"/></symbol>

      <!-- Themes / concepts -->
      <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M15.6 20.8v-1.9a3.8 3.8 0 0 0-3.8-3.8H6.2a3.8 3.8 0 0 0-3.8 3.8v1.9"/><circle cx="9" cy="7.4" r="3.8"/><path d="M21.6 20.8v-1.9a3.8 3.8 0 0 0-2.9-3.7M15.8 3.8a3.8 3.8 0 0 1 0 7.2"/></symbol>
      <symbol id="i-layers" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="m12 2.8 9 4.8-9 4.8-9-4.8 9-4.8z"/><path d="m3 12.4 9 4.8 9-4.8M3 16.9l9 4.8 9-4.8"/></symbol>
      <symbol id="i-spark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="m11 2.8 1.9 5.1 5.1 1.9-5.1 1.9-1.9 5.1-1.9-5.1L4 9.8l5.1-1.9L11 2.8z"/><path d="m18.4 15.2.9 2.3 2.3.9-2.3.9-.9 2.3-.9-2.3-2.3-.9 2.3-.9.9-2.3z"/></symbol>
      <symbol id="i-building" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V6.4a1.4 1.4 0 0 1 1.4-1.4h6.2A1.4 1.4 0 0 1 13 6.4V21"/><path d="M13 10.6h5.6A1.4 1.4 0 0 1 20 12v9M2.6 21h18.8M7 9h2.6M7 13h2.6M7 17h2.6M16 14.6h1.4M16 18h1.4"/></symbol>
      <symbol id="i-globe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.2"/><path d="M2.8 12h18.4M12 2.8c2.3 2.5 3.6 5.8 3.6 9.2s-1.3 6.7-3.6 9.2c-2.3-2.5-3.6-5.8-3.6-9.2S9.7 5.3 12 2.8z"/></symbol>
      <symbol id="i-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 12.4a8 8 0 0 1-8.6 8 9 9 0 0 1-3.6-.8L3.2 21.2l1.6-5.2a8 8 0 0 1-.8-3.6 8 8 0 0 1 8-8.6 8 8 0 0 1 8.8 8.6z"/><path d="M8.6 11.9h.01M12 11.9h.01M15.4 11.9h.01"/></symbol>
      <symbol id="i-mic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="2.4" width="6" height="11.4" rx="3"/><path d="M5.2 11.4a6.8 6.8 0 0 0 13.6 0M12 18.2v3.4"/></symbol>
      <symbol id="i-volume" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4.6 6.4 8.4H3v7.2h3.4L11 19.4z"/><path d="M15.6 9a4.2 4.2 0 0 1 0 6M18.4 6.2a8 8 0 0 1 0 11.6"/></symbol>
      <symbol id="i-keyboard" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2.4" y="6" width="19.2" height="12" rx="2.2"/><path d="M6 9.4h.01M9.4 9.4h.01M12.8 9.4h.01M16.2 9.4h.01M6 12.8h.01M9.4 12.8h.01M12.8 12.8h.01M16.2 12.8h.01M8 15.6h8"/></symbol>
      <symbol id="i-contrast" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.2"/><path d="M12 2.8v18.4a9.2 9.2 0 0 0 0-18.4z" fill="currentColor" stroke="none"/></symbol>
      <symbol id="i-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2.2 12S5.8 5.4 12 5.4 21.8 12 21.8 12 18.2 18.6 12 18.6 2.2 12 2.2 12z"/><circle cx="12" cy="12" r="3.2"/></symbol>
      <symbol id="i-a11y" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.2"/><circle cx="12" cy="7" r="1.5" fill="currentColor" stroke="none"/><path d="M7.4 10.2h9.2M12 10.6v3.6m0 0-2.6 5.2M12 14.2l2.6 5.2"/></symbol>
      <symbol id="i-tag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0l-7-7V3.4h10.2l6.8 6.8a2 2 0 0 1 0 3.2z"/><path d="M7.6 7.6h.01"/></symbol>

      <!-- Journey -->
      <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.6" cy="10.6" r="6.6"/><path d="m15.6 15.6 4.8 4.8"/></symbol>
      <symbol id="i-clipboard" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4.4h6v2.2H9z"/><path d="M15 5.5h2.2A1.8 1.8 0 0 1 19 7.3v12a1.8 1.8 0 0 1-1.8 1.8H6.8A1.8 1.8 0 0 1 5 19.3v-12a1.8 1.8 0 0 1 1.8-1.8H9"/><path d="M8.6 11.4h6.8M8.6 15h4.4"/></symbol>
      <symbol id="i-code" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="m8.2 7.4-4.6 4.6 4.6 4.6M15.8 7.4l4.6 4.6-4.6 4.6M13.4 4.2l-2.8 15.6"/></symbol>
      <symbol id="i-check-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21.2 11.2V12a9.2 9.2 0 1 1-5.4-8.4"/><path d="m8.6 11.4 3.2 3.2 9-9.2"/></symbol>
      <symbol id="i-wand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20 15.6 8.4M13.4 6.2l4.4 4.4"/><path d="M17.4 3.2l.7 1.9 1.9.7-1.9.7-.7 1.9-.7-1.9-1.9-.7 1.9-.7.7-1.9zM7 3.6l.5 1.3 1.3.5-1.3.5L7 7.2l-.5-1.3-1.3-.5 1.3-.5L7 3.6z"/></symbol>
      <symbol id="i-rocket" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M13.4 3.6c3.4 1 5.8 4 6.6 7.6-2.6 3.4-6.2 5.4-9.2 6L7 13.6c1-3.2 3.2-7.2 6.4-10z"/><path d="M9.4 15.2 6 18.6M8.8 10.4 4.4 12l2.6 1.6M13.6 15.2 12 19.6l1.6-.6"/><circle cx="14.6" cy="9.4" r="1.6"/></symbol>

      <!-- Technologies -->
      <symbol id="i-brackets" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M9.4 4.2 4 12l5.4 7.8M14.6 4.2 20 12l-5.4 7.8"/></symbol>
      <symbol id="i-brush" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9.4 14.6 3.2 20.8"/><path d="M14 3.2 20.8 10l-6.2 6.2a2.9 2.9 0 0 1-4.1 0l-2.7-2.7a2.9 2.9 0 0 1 0-4.1L14 3.2z"/></symbol>
      <symbol id="i-braces" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M8.4 3.2h-1a2 2 0 0 0-2 2v4a2 2 0 0 1-2 2 2 2 0 0 1 2 2v4a2 2 0 0 0 2 2h1M15.6 3.2h1a2 2 0 0 1 2 2v4a2 2 0 0 0 2 2 2 2 0 0 0-2 2v4a2 2 0 0 1-2 2h-1"/></symbol>
      <symbol id="i-server" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3.2" y="4" width="17.6" height="6.2" rx="1.8"/><rect x="3.2" y="13.8" width="17.6" height="6.2" rx="1.8"/><path d="M6.8 7.1h.01M6.8 16.9h.01"/></symbol>
      <symbol id="i-terminal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="m4.4 17 5.4-5.4-5.4-5.4M12.4 18.4h7.2"/></symbol>
      <symbol id="i-branch" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6.4 8.2v7.6"/><circle cx="6.4" cy="5" r="2.8"/><circle cx="6.4" cy="19" r="2.8"/><circle cx="17.6" cy="5" r="2.8"/><path d="M17.6 7.8a9 9 0 0 1-9 9"/></symbol>
      <symbol id="i-repo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4.4 18.6a2.4 2.4 0 0 1 2.4-2.4h12.8"/><path d="M6.8 2.8h12.8v18.4H6.8a2.4 2.4 0 0 1-2.4-2.4V5.2a2.4 2.4 0 0 1 2.4-2.4z"/></symbol>
      <symbol id="i-board" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2.8" y="4.4" width="18.4" height="15.2" rx="2.2"/><path d="M8.9 4.4v15.2M15.1 4.4v15.2"/></symbol>
      <symbol id="i-devices" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2.6" y="4.4" width="12.8" height="9.6" rx="1.8"/><path d="M6.4 18h6M9 14v4"/><rect x="17" y="9.6" width="4.4" height="9.8" rx="1.4"/></symbol>
      <symbol id="i-chip" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="5.2" y="5.2" width="13.6" height="13.6" rx="2.4"/><rect x="9.4" y="9.4" width="5.2" height="5.2" rx="1.2"/><path d="M9.4 2.4v2.8M14.6 2.4v2.8M9.4 18.8v2.8M14.6 18.8v2.8M2.4 9.4h2.8M2.4 14.6h2.8M18.8 9.4h2.8M18.8 14.6h2.8"/></symbol>
      <symbol id="i-database" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5.4" rx="7.8" ry="3"/><path d="M4.2 5.4v13.2c0 1.7 3.5 3 7.8 3s7.8-1.3 7.8-3V5.4"/><path d="M4.2 12c0 1.7 3.5 3 7.8 3s7.8-1.3 7.8-3"/></symbol>
      <symbol id="i-layout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2.8" y="4.4" width="18.4" height="15.2" rx="2.2"/><path d="M2.8 9.4h18.4M9.2 9.4v10.2"/></symbol>
    </svg>

  <main id="main-content" tabindex="-1">

      <!-- =================================================================
           1. Hero
           ================================================================= -->
      <section class="hero" id="top" aria-labelledby="hero-title">
        <div class="hero__wash" aria-hidden="true"></div>

        <div class="shell">
          <div class="hero__head">
            <p class="kicker" data-reveal>
              <span class="kicker__dot" aria-hidden="true"></span>
              AI Unit &middot; Ministry of ICT
            </p>

            <h1 class="hero__title" id="hero-title" data-reveal data-reveal-delay="60">
              Students &amp; Internships
            </h1>

            <p class="hero__lead" data-reveal data-reveal-delay="120">
              Over a ten-week industrial attachment at the AI Unit, hosted at the Mauritius
              Emerging Technologies Council in Ebene, a team of university interns redesigned and
              rebuilt aim.govmu.org, the official government portal for artificial intelligence in
              Mauritius. The work spanned front-end development, WCAG accessibility, speech synthesis and the integration of the DIVA chatbot. This page is a
              record of what was built.
            </p>
          </div>
        </div>

        <!-- Main hero image -->
        <div class="shell shell--wide">
          <figure class="hero__figure" data-reveal data-reveal-delay="180">
            <div class="reveal-image">
              <img src="<?= e(asset('images/highlights/team.jpg')) ?>" width="1280" height="720"
                   alt="The internship cohort of eight students standing together in the AI Unit office."
                   loading="eager" decoding="async" fetchpriority="high">
            </div>
            <figcaption>The intern team at the AI Unit, METC, during the May to July 2026 attachment</figcaption>
          </figure>
        </div>

        <a class="scroll-cue" href="#overview" target="_self" data-reveal data-reveal-delay="240">
          <span class="scroll-cue__label">Scroll to explore</span>
          <span class="scroll-cue__rail" aria-hidden="true"><span class="scroll-cue__dot"></span></span>
          <span class="sr-only">Go to the internship overview</span>
        </a>
      </section>

      <!-- =================================================================
           2. Internship overview
           ================================================================= -->
      <section class="section" id="overview" aria-labelledby="overview-title">
        <div class="shell">
          <div class="duo">
            <div class="duo__media" data-reveal>
              <figure class="framed">
                <div class="reveal-image">
                  <img src="<?= e(asset('images/highlights/team2.jpg')) ?>" width="1280" height="960"
                       alt="An intern presenting a diagram titled The Big Picture to colleagues seated around a meeting-room table."
                       loading="lazy" decoding="async">
                </div>
                <figcaption>Walking the team through the big picture: the front end and the planned FastAPI and PostgreSQL back end</figcaption>
              </figure>
            </div>

            <div class="duo__body" data-reveal data-reveal-delay="100">
              <p class="eyebrow">The internship</p>
              <h2 class="h2" id="overview-title">A live national portal, built by interns</h2>
              <p class="lede">
                The AI Unit had no permanent development staff, so its active projects were carried
                forward by the intern team. The placement began with a single intern analysing the
                existing portal and setting up the project; by the later weeks it had grown into a
                team of several interns from different Universities working in one shared codebase.
              </p>

              <ul class="ticks" role="list">
                <li>
                  <span class="ticks__icon" aria-hidden="true"><svg class="icon"><use href="#i-users"></use></svg></span>
                  <div>
                    <h3>Collaboration</h3>
                    <p>A flat, collaborative structure with the mentor as the single source of technical guidance, and work coordinated through a shared Kanban board.</p>
                  </div>
                </li>
                <li>
                  <span class="ticks__icon" aria-hidden="true"><svg class="icon"><use href="#i-layers"></use></svg></span>
                  <div>
                    <h3>Teamwork</h3>
                    <p>New interns were onboarded onto the existing code and workflow, features were divided across the team, and pieces like the PDF-to-audio converter were built and integrated by different interns working together.</p>
                  </div>
                </li>
                <li>
                  <span class="ticks__icon" aria-hidden="true"><svg class="icon"><use href="#i-code"></use></svg></span>
                  <div>
                    <h3>Learning by doing</h3>
                    <p>WCAG standards, the Web Speech API and Jira were all learned through self-directed research and then applied directly to the portal, with the mentor guiding rather than handing over solutions.</p>
                  </div>
                </li>
                <li>
                  <span class="ticks__icon" aria-hidden="true"><svg class="icon"><use href="#i-building"></use></svg></span>
                  <div>
                    <h3>Government digital projects</h3>
                    <p>The portal serves a national audience, and the work was presented to the Ministry of Technology and to the Electoral Commission of Mauritius during the attachment.</p>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- =================================================================
           3. Project one - AI Unit Website Redesign
           (the section id stays "revamp": it is the in-page anchor target,
           and renaming it would break any link already shared)
           ================================================================= -->
      <section class="section section--tint project" id="revamp" aria-labelledby="revamp-title">
        <div class="shell">
          <header class="project__head" data-reveal>
            <span class="project__index" aria-hidden="true">01</span>
            <div>
              <p class="eyebrow">Project one</p>
              <h2 class="h2 h2--display" id="revamp-title">AI Unit Website Redesign</h2>
              <p class="lede lede--wide">
                aim.govmu.org is the official Mauritian government portal for artificial
                intelligence, covering news, policies, events and resources.
              </p>
            </div>
          </header>

          <!-- Headline screenshot in a browser frame -->
          <figure class="browser browser--narrow" data-reveal data-reveal-delay="80">
            <div class="browser__frame">
              <div class="browser__bar" aria-hidden="true">
                <span class="browser__dots"><i></i><i></i><i></i></span>
                <span class="browser__url">aim.govmu.org / framework-library</span>
              </div>
              <div class="reveal-image">
                <img src="<?= e(asset('images/highlights/framework.png')) ?>" width="745" height="701"
                     alt="The rebuilt Framework Library page, showing cards for the Digital Transformation Blueprint and Mauritius' First National AI Strategy, each with download, view online and listen options."
                     loading="lazy" decoding="async">
              </div>
            </div>
            <figcaption>
              The rebuilt Framework Library. The Listen buttons are the PDF-to-audio feature: the
              AI Strategy, FAIR Guidelines, Digital Blueprint and AI Playbook can be read aloud.
            </figcaption>
          </figure>

          <!-- Analysis → planning → build -->
          <div class="duo duo--reverse duo--tight">
            <div class="duo__body" data-reveal>
              <h3 class="h3">From analysis to wireframe to build</h3>
              <p>
                The work began with a full review of the existing portal, guided by the AI Unit's
                aspirations for its redevelopment. That analysis fed a detailed wireframe covering
                the home page, the navigation structure, the key content sections and the placement
                of interactive features such as the accessibility tool, and the chatbot. Once the 
  			  wireframe was reviewed and approved by the mentor, front-end development began.
              </p>

              <ol class="steps" role="list">
                <li data-reveal data-reveal-delay="40"><span>01</span><div><h4>Analysing the existing site</h4><p>Review of aim.govmu.org: styling, mobile-friendliness, accessibility gaps and missing features.</p></div></li>
                <li data-reveal data-reveal-delay="80"><span>02</span><div><h4>Wireframing</h4><p>A detailed wireframe of the home page, navigation and feature placement, approved before any code.</p></div></li>
                <li data-reveal data-reveal-delay="120"><span>03</span><div><h4>Modern UI/UX</h4><p>A consistent design language built on typography, colour schemes and spacing.</p></div></li>
                <li data-reveal data-reveal-delay="160"><span>04</span><div><h4>Responsive development</h4><p>A fully responsive layout built with CSS Flexbox and Grid.</p></div></li>
              </ol>
            </div>

            <div class="duo__media" data-reveal data-reveal-delay="60">
              <div class="spec-cards">
                <article class="spec">
                  <span class="spec__icon" aria-hidden="true"><svg class="icon"><use href="#i-layout"></use></svg></span>
                  <h4>Frontend</h4>
                  <p>Built with HTML5, CSS3 and JavaScript (ES6+) without any front-end framework, so the codebase stays easy to maintain for future developers.</p>
                </article>
                <article class="spec">
                  <span class="spec__icon" aria-hidden="true"><svg class="icon"><use href="#i-server"></use></svg></span>
                  <h4>Back-end planning</h4>
                  <p>Python with FastAPI and PostgreSQL was agreed as the back-end stack, with API design patterns and database schema research under way at the end of the attachment.</p>
                </article>
                <article class="spec">
                  <span class="spec__icon" aria-hidden="true"><svg class="icon"><use href="#i-devices"></use></svg></span>
                  <h4>Responsive design</h4>
                  <p>A fully responsive layout using CSS Flexbox and Grid, with a consistent scheme of typography, colour and spacing.</p>
                </article>
              </div>
            </div>
          </div>

          <!-- Agile working -->
          <div class="duo duo--tight">
            <div class="duo__media" data-reveal>
              <figure class="framed">
                <div class="reveal-image">
                  <img src="<?= e(asset('images/highlights/kanban.jpg')) ?>" width="1280" height="664"
                       alt="A physical Kanban whiteboard with columns for Backlog, In Progress, Test, Blocked and Done, filled with printed task cards."
                       loading="lazy" decoding="async">
                </div>
                <figcaption>The physical Kanban board that came first, before the workflow moved to Jira</figcaption>
              </figure>
            </div>

            <div class="duo__body" data-reveal data-reveal-delay="100">
              <h3 class="h3">Kanban, Jira and a shared repository</h3>
              <p>
                Project management for the portal team was run on a Kanban workflow, introduced in
                Week 2 after guidance from the mentor on Agile practice. It began as a physical
                board at the workplace and, at the mentor's recommendation, moved to a digital Jira
                board that the whole intern team adopted as it grew. Tasks were grouped into Epics
                and Sub-Epics matching the portal's features.
              </p>
              <p>
                GitHub served as the team's version control platform, with a shared repository that
                let all the interns work concurrently on different features without conflicts in
                the code.
              </p>

              <ul class="chips" role="list">
                <li>Backlog</li>
                <li>In progress</li>
                <li>Test</li>
                <li>Blocked</li>
                <li>Done</li>
              </ul>

              <ul class="mini-facts" role="list">
                <li><span>Method</span><strong>Agile, Kanban workflow</strong></li>
                <li><span>Tracking</span><strong>Jira board, Epics and Sub-Epics</strong></li>
                <li><span>Version control</span><strong>Git and GitHub, shared repository</strong></li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- =================================================================
           4. Project two - DIVA
           ================================================================= -->
      <section class="section project" id="diva" aria-labelledby="diva-title">
        <div class="shell">
          <header class="project__head" data-reveal>
            <span class="project__index" aria-hidden="true">02</span>
            <div>
              <p class="eyebrow">Project two</p>
              <h2 class="h2 h2--display" id="diva-title">DIVA - Digital Interactive Virtual Assistant</h2>
              <p class="lede lede--wide">
                DIVA is an AI-powered assistant integrated into the portal to answer questions
                about artificial intelligence in Mauritius, the content of the portal and
                government AI resources. It is a prototype, grounded in four key documents: the
                Digital Transformation Blueprint, the AI Strategy, the FAIR Guidelines and the
                AI Playbook.
              </p>
            </div>
          </header>

          <!-- Alternating block A: entry point -->
          <div class="duo duo--tight">
            <div class="duo__media" data-reveal>
              <figure class="framed framed--pad">
                <div class="reveal-image">
                  <img src="<?= e(asset('images/highlights/DIVA1.png')) ?>" width="693" height="253"
                       alt="The Meet DIVA introduction card, describing the assistant as answering questions based on four key documents, with a Chat with DIVA button."
                       loading="lazy" decoding="async">
                </div>
                <figcaption>The invitation to start a conversation, placed alongside the documents themselves</figcaption>
              </figure>
            </div>

            <div class="duo__body" data-reveal data-reveal-delay="100">
              <h3 class="h3">Two ways into the conversation</h3>
              <p>
                Users reach DIVA through two entry points. A dedicated section of the portal
                introduces the assistant and opens it with a Chat with DIVA button, and a floating
                chatbot widget stays available across the portal, so a conversation can be started
                or continued from any page without leaving the current content.
              </p>
              <ul class="feature-rows" role="list">
                <li><span aria-hidden="true"><svg class="icon"><use href="#i-chip"></use></svg></span><div><h4>AI assistant</h4><p>Answers questions on AI in Mauritius, the portal's content and government AI resources, drawing on the four published documents.</p></div></li>
                <li><span aria-hidden="true"><svg class="icon"><use href="#i-code"></use></svg></span><div><h4>Integration work</h4><p>The first integration attempt failed against the portal's Content Security Policy. The conflict was diagnosed through browser developer tools and resolved with a CSP configuration that permits the required cross-origin calls while keeping the portal secure.</p></div></li>
              </ul>
            </div>
          </div>

          <!-- Alternating block B: the conversation -->
          <div class="duo duo--reverse duo--tight">
            <div class="duo__media" data-reveal>
              <figure class="framed framed--pad">
                <div class="reveal-image">
                  <img src="<?= e(asset('images/highlights/DIVA2.png')) ?>" width="827" height="747"
                       alt="The DIVA chat window showing a detailed answer about the Digital Transformation Blueprint, with read-aloud and copy controls and a question box offering typed or spoken input."
                       loading="lazy" decoding="async">
                </div>
                <figcaption>The conversation view, with read-aloud, copy and voice input</figcaption>
              </figure>
            </div>

            <div class="duo__body" data-reveal data-reveal-delay="100">
              <h3 class="h3">Speech synthesis: a portal that reads aloud</h3>
              <p>
                The Web Speech Synthesis API, a native browser API that turns text into speech
                without any third-party software, was integrated into the portal. Activated with a single control, it reads the content of DIVA's response aloud.
              </p>

              <ul class="feature-rows" role="list">
                <li><span aria-hidden="true"><svg class="icon"><use href="#i-chat"></use></svg></span><div><h4>Conversational interface</h4><p>Questions can be typed or spoken, answers can be read aloud or copied, and the conversation can be cleared and restarted at any point.</p></div></li>
                <li><span aria-hidden="true"><svg class="icon"><use href="#i-mic"></use></svg></span><div><h4>Accessibility</h4><p>Reading page content aloud supports users with visual impairments and lower literacy levels, in keeping with the WCAG principles behind the portal.</p></div></li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- =================================================================
           5. Project three - Accessibility
           ================================================================= -->
      <section class="section section--tint project" id="accessibility" aria-labelledby="a11y-title">
        <div class="shell">
          <header class="project__head" data-reveal>
            <span class="project__index" aria-hidden="true">03</span>
            <div>
              <p class="eyebrow">Project three</p>
              <h2 class="h2 h2--display" id="a11y-title">Accessibility</h2>
              <p class="lede lede--wide">
                In Week 3 the mentor raised the bar: the portal had to meet WCAG 2.2, the
                international guidelines for making web content usable by people with visual,
                hearing, physical, cognitive and other disabilities. Beyond meeting the guidelines,
                the interns built an accessibility toolbar into the portal itself, designed on the
                principle of user choice: each visitor decides how they want to use the site.
              </p>
            </div>
          </header>

          <!-- The three panel screenshots -->
          <div class="panels">
            <figure class="panel" data-reveal>
              <div class="reveal-image">
                <img src="<?= e(asset('images/highlights/Accessibility1.png')) ?>" width="535" height="797"
                     alt="The accessibility panel's screen reader section, offering read page aloud with play, pause and speed keyboard shortcuts, a voice selector, and quick profiles for low vision, motor, dyslexia, cognitive and senior needs."
                     loading="lazy" decoding="async">
              </div>
              <figcaption>
                <h3>Screen reader &amp; quick profiles</h3>
                <p>A built-in screen reader reads the page in natural segments while highlighting the current element and scrolling it into view. Space pauses, S stops, arrow keys change the speed, and a slider and voice list give finer control. Five quick profiles, for low vision, motor, dyslexia, cognitive and senior needs, apply a set of adjustments in one click.</p>
              </figcaption>
            </figure>

            <figure class="panel" data-reveal data-reveal-delay="80">
              <div class="reveal-image">
                <img src="<?= e(asset('images/highlights/Accessibility2.png')) ?>" width="530" height="642"
                     alt="The accessibility panel's text size control and colour and display options, including normal, high contrast, dark, greyscale and negative modes, with toggles for highlight links, hide images and stop animations."
                     loading="lazy" decoding="async">
              </div>
              <figcaption>
                <h3>Text size &amp; colour</h3>
                <p>Base text size scales between 80% and 150% without breaking the page layout. Five display modes, Normal, High Contrast, Dark, Greyscale and Negative, cover different contrast needs, alongside toggles to highlight links, hide images and stop animations.</p>
              </figcaption>
            </figure>

            <figure class="panel" data-reveal data-reveal-delay="160">
              <div class="reveal-image">
                <img src="<?= e(asset('images/highlights/Accessibility3.png')) ?>" width="537" height="797"
                     alt="The accessibility panel's reading and focus options - dyslexia-friendly font, reading guide line, wider letter spacing and bold focus outline - plus navigation aids and the Alt plus A keyboard shortcut to open the panel."
                     loading="lazy" decoding="async">
              </div>
              <figcaption>
                <h3>Reading, focus &amp; navigation</h3>
                <p>A dyslexia-friendly font toggle, a reading guide that follows the cursor, wider letter spacing and a bold focus outline for keyboard navigation, plus a large mouse pointer for users with motor difficulties and a keyboard shortcut guide. The panel itself opens with <kbd>Alt</kbd> + <kbd>A</kbd>.</p>
              </figcaption>
            </figure>
          </div>

          <!-- The principles behind it -->
          <div class="a11y-principles">
            <div class="a11y-principles__intro" data-reveal>
              <h3 class="h3">The standards behind the switches</h3>
              <p>
                Meeting WCAG 2.2 meant independent research into the A, AA and AAA conformance
                levels, testing the portal with browser-based accessibility evaluation tools, and
                reworking front-end code that had already been written. It was a practical lesson
                in building accessibility in from the start rather than retrofitting it.
              </p>
            </div>

            <ul class="principle-grid" role="list">
              <li class="principle" data-reveal>
                <span class="principle__icon" aria-hidden="true"><svg class="icon"><use href="#i-check-circle"></use></svg></span>
                <h4>WCAG 2.2</h4>
                <p>The portal was tested against the guidelines with browser-based evaluation tools, and the required adjustments were identified and applied across the front-end code.</p>
              </li>
              <li class="principle" data-reveal data-reveal-delay="60">
                <span class="principle__icon" aria-hidden="true"><svg class="icon"><use href="#i-contrast"></use></svg></span>
                <h4>Colour contrast</h4>
                <p>Contrast ratios in the existing front end were reviewed and improved to meet the guidelines, with a High Contrast display mode available for users who need more.</p>
              </li>
              <li class="principle" data-reveal data-reveal-delay="120">
                <span class="principle__icon" aria-hidden="true"><svg class="icon"><use href="#i-keyboard"></use></svg></span>
                <h4>Keyboard navigation</h4>
                <p>Keyboard navigation support across the portal, a bold focus outline to keep the current position visible, and a shortcut guide for fully mouseless use.</p>
              </li>
              <li class="principle" data-reveal>
                <span class="principle__icon" aria-hidden="true"><svg class="icon"><use href="#i-volume"></use></svg></span>
                <h4>Screen readers</h4>
                <p>The toolbar announces its state changes through an ARIA live region, keeping external screen readers such as NVDA and JAWS in step with what is on screen.</p>
              </li>
              <li class="principle" data-reveal data-reveal-delay="60">
                <span class="principle__icon" aria-hidden="true"><svg class="icon"><use href="#i-tag"></use></svg></span>
                <h4>ARIA labels</h4>
                <p>ARIA labels and text alternatives for non-text content were added throughout the front end so that assistive technology can name and describe every control.</p>
              </li>
              <li class="principle" data-reveal data-reveal-delay="120">
                <span class="principle__icon" aria-hidden="true"><svg class="icon"><use href="#i-a11y"></use></svg></span>
                <h4>Saved preferences</h4>
                <p>Every setting is stored in the browser's local storage, so a returning visitor's preferences are restored automatically with no account needed.</p>
              </li>
            </ul>
          </div>
        </div>
      </section>

      <!-- =================================================================
           6. Development journey
           ================================================================= -->
      <section class="section" id="journey" aria-labelledby="journey-title">
        <div class="shell">
          <header class="section__head section__head--center" data-reveal>
            <p class="eyebrow">The process</p>
            <h2 class="h2" id="journey-title">Development journey</h2>
            <p class="lede">
              The same six stages carried every piece of work from an open question to something
              ready to hand over.
            </p>
          </header>
        </div>

        <!-- Wide feature: the multilingual video player -->
        <div class="shell shell--wide">
          <figure class="browser browser--wide" data-reveal>
            <div class="browser__frame">
              <div class="browser__bar" aria-hidden="true">
                <span class="browser__dots"><i></i><i></i><i></i></span>
                <span class="browser__url">aim.govmu.org / child-safety / video-1</span>
              </div>
              <div class="reveal-image">
                <img src="<?= e(asset('images/highlights/VIDEO1.png')) ?>" width="1896" height="900"
                     alt="The Child Safety Series video player showing an animated film with French captions, an English and French language switch, and a captions toggle with a language selector."
                     loading="lazy" decoding="async">
              </div>
            </div>
            <figcaption>
              The video captioning feature: audio transcribed by hand into timed WebVTT files,
              surfaced through the HTML5 track element, with captions available in English and
              French. The interface presenting the videos and captions was also built by the interns.
            </figcaption>
          </figure>
        </div>

        <div class="shell">
          <ol class="timeline" role="list">
            <li class="timeline__item" data-reveal>
              <div class="timeline__marker" aria-hidden="true"><svg class="icon"><use href="#i-search"></use></svg></div>
              <div class="timeline__card">
                <p class="timeline__step">Stage 01</p>
                <h3>Research</h3>
                <p>Analysis of the existing aim.govmu.org site, plus self-directed research into WCAG 2.2, the Web Speech API and open data.</p>
              </div>
            </li>
            <li class="timeline__item" data-reveal>
              <div class="timeline__marker" aria-hidden="true"><svg class="icon"><use href="#i-clipboard"></use></svg></div>
              <div class="timeline__card">
                <p class="timeline__step">Stage 02</p>
                <h3>Planning</h3>
                <p>A wireframe of the home page, navigation and feature placement, approved by the mentor, alongside the Kanban board that would organise the work.</p>
              </div>
            </li>
            <li class="timeline__item" data-reveal>
              <div class="timeline__marker" aria-hidden="true"><svg class="icon"><use href="#i-code"></use></svg></div>
              <div class="timeline__card">
                <p class="timeline__step">Stage 03</p>
                <h3>Development</h3>
                <p>Front-end build in HTML5, CSS3 and JavaScript, followed by the accessibility toolbar, speech synthesis, DIVA, video captions and PDF-to-audio.</p>
              </div>
            </li>
            <li class="timeline__item" data-reveal>
              <div class="timeline__marker" aria-hidden="true"><svg class="icon"><use href="#i-check-circle"></use></svg></div>
              <div class="timeline__card">
                <p class="timeline__step">Stage 04</p>
                <h3>Testing</h3>
                <p>The portal was checked against WCAG 2.2 with browser-based evaluation tools, and integration problems, like the chatbot's CSP conflict, were diagnosed through browser developer tools.</p>
              </div>
            </li>
            <li class="timeline__item" data-reveal>
              <div class="timeline__marker" aria-hidden="true"><svg class="icon"><use href="#i-wand"></use></svg></div>
              <div class="timeline__card">
                <p class="timeline__step">Stage 05</p>
                <h3>Refinement</h3>
                <p>Early front-end code was reworked to meet the WCAG requirement, and the speech engine gained a voice selection algorithm to align voices with the chosen language.</p>
              </div>
            </li>
            <li class="timeline__item" data-reveal>
              <div class="timeline__marker" aria-hidden="true"><svg class="icon"><use href="#i-rocket"></use></svg></div>
              <div class="timeline__card">
                <p class="timeline__step">Stage 06</p>
                <h3>Deployment preparation</h3>
                <p>The portal was presented to the Ministry of Technology, back-end planning began, and the code structure was documented so incoming interns could continue the work.</p>
              </div>
            </li>
          </ol>

          <figure class="closing-figure" data-reveal>
            <div class="reveal-image">
              <img src="<?= e(asset('images/highlights/Mitci.jpg')) ?>" width="1280" height="960"
                   alt="Interns and a member of the unit standing in the Ministry of Information Technology, Communication and Innovation reception, beside a plaque reading Transform Mauritius into a thriving, smart and inclusive digital society."
                   loading="lazy" decoding="async">
            </div>
            <figcaption>
              At the Ministry of Information Technology, Communication and Innovation, where the
              portal was presented.
            </figcaption>
          </figure>
        </div>
      </section>

      <!-- =================================================================
           7. Gallery
           ================================================================= -->
      <?php
      /**
       * Gallery - the database-driven part of this page.
       *
       * One block per visible category, in the order staff set. Nothing about
       * the categories is hardcoded, so adding "Workshops" or "Outreach" in
       * the admin area publishes it here with no code change; the markup below
       * is the same figure/button/overlay structure the three original
       * hardcoded items used, so the styling and lightbox are untouched.
       *
       * $categories, $highlights and $highlightService come from
       * HighlightsController.
       *
       * $highlights->isUnavailable() is what stops a database outage from
       * silently deleting this whole section: an empty $categories used to mean
       * both "nothing is published" and "the database is down", and the section
       * simply vanished. Now the two are told apart - nothing published still
       * renders nothing (correct: there is genuinely no gallery), while an
       * outage keeps the section and explains itself.
       *
       * The reveal delay repeats every third item because the CSS staggers a
       * row of three; carrying it on past the first row would leave later rows
       * waiting almost a second before appearing.
       */
      $categories = $categories ?? [];
      $highlightsUnavailable = isset($highlights) && $highlights->isUnavailable();
      ?>
      <?php if ($categories !== [] || $highlightsUnavailable): ?>
        <section class="section section--tint" id="gallery" aria-labelledby="gallery-title">
          <div class="shell">
            <header class="section__head" data-reveal>
              <p class="eyebrow">Gallery</p>
              <h2 class="h2" id="gallery-title">Moments from our programmes</h2>
            </header>

            <?php if ($highlightsUnavailable): ?>
              <?php
              /*
               * role="status" rather than "alert": this is information, not an
               * emergency, and it is present on first paint - alert would make
               * a screen reader interrupt whatever it was doing to say so.
               */
              ?>
              <p class="gallery-notice" role="status">
                Our programme photographs are temporarily unavailable. Please check back shortly.
              </p>
            <?php endif; ?>

            <?php foreach ($categories as $category): ?>
              <div class="gallery-group">
                <h3 class="gallery-group__title" id="gallery-cat-<?= e((string) $category->id) ?>" data-reveal>
                  <?= e($category->name) ?>
                </h3>
                <?php if ($category->description !== null && $category->description !== ''): ?>
                  <p class="gallery-group__intro" data-reveal><?= e($category->description) ?></p>
                <?php endif; ?>

                <div class="gallery" role="group" aria-labelledby="gallery-cat-<?= e((string) $category->id) ?>">
                  <?php foreach ($category->images as $index => $image): ?>
                    <figure class="gallery__item" data-reveal<?= $index % 3 !== 0 ? ' data-reveal-delay="' . (($index % 3) * 80) . '"' : '' ?>>
                      <button class="gallery__trigger" type="button" data-lightbox="<?= e((string) $image->id) ?>">
                        <img src="<?= e($highlightService->imageUrl($image->fileName)) ?>"
                             alt="<?= e($image->altText) ?>"
                             loading="lazy" decoding="async">
                        <span class="gallery__overlay">
                          <span class="gallery__caption">
                            <strong><?= e($image->title) ?></strong>
                            <?php if ($image->caption !== null && $image->caption !== ''): ?>
                              <span><?= e($image->caption) ?></span>
                            <?php endif; ?>
                          </span>
                          <span class="gallery__zoom" aria-hidden="true"><svg class="icon"><use href="#i-expand"></use></svg></span>
                        </span>
                        <span class="sr-only">View larger: <?= e($image->title) ?></span>
                      </button>
                    </figure>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>

      <!-- =================================================================
           8. Skills & technologies
           ================================================================= -->
      <section class="section" id="skills" aria-labelledby="skills-title">
        <div class="shell">
          <header class="section__head section__head--center" data-reveal>
            <p class="eyebrow">Capability</p>
            <h2 class="h2" id="skills-title">Skills &amp; technologies</h2>
            <p class="lede">
              The technologies used across the portal, and the tools the team worked with.
            </p>
          </header>

          <ul class="tech-grid" role="list">
            <li class="tech" data-reveal>
              <span class="tech__icon" aria-hidden="true"><svg class="icon"><use href="#i-brackets"></use></svg></span>
              <h3>HTML5</h3><p>Semantic markup, ARIA labels and the track element for captions</p>
            </li>
            <li class="tech" data-reveal data-reveal-delay="40">
              <span class="tech__icon" aria-hidden="true"><svg class="icon"><use href="#i-brush"></use></svg></span>
              <h3>CSS3</h3><p>Responsive layouts with Flexbox and Grid, and a consistent design language</p>
            </li>
            <li class="tech" data-reveal data-reveal-delay="80">
              <span class="tech__icon" aria-hidden="true"><svg class="icon"><use href="#i-braces"></use></svg></span>
              <h3>JavaScript (ES6+)</h3><p>All portal interactivity, written without a front-end framework</p>
            </li>
            <li class="tech" data-reveal data-reveal-delay="120">
              <span class="tech__icon" aria-hidden="true"><svg class="icon"><use href="#i-layout"></use></svg></span>
              <h3>Frontend Development</h3><p>Wireframing, interface build and usability improvements</p>
            </li>
            <li class="tech" data-reveal>
              <span class="tech__icon" aria-hidden="true"><svg class="icon"><use href="#i-devices"></use></svg></span>
              <h3>Responsive Design</h3><p>A layout that holds from a small phone to a wide desktop</p>
            </li>
            <li class="tech" data-reveal data-reveal-delay="40">
              <span class="tech__icon" aria-hidden="true"><svg class="icon"><use href="#i-a11y"></use></svg></span>
              <h3>Accessibility</h3><p>WCAG 2.2, ARIA labels, keyboard navigation and contrast</p>
            </li>
            <li class="tech" data-reveal data-reveal-delay="80">
              <span class="tech__icon" aria-hidden="true"><svg class="icon"><use href="#i-volume"></use></svg></span>
              <h3>Web Speech API</h3><p>Speech synthesis for the screen reader and PDF-to-audio</p>
            </li>
            <li class="tech" data-reveal data-reveal-delay="120">
              <span class="tech__icon" aria-hidden="true"><svg class="icon"><use href="#i-chip"></use></svg></span>
              <h3>AI Chatbot</h3><p>Integration of the DIVA assistant into the portal</p>
            </li>
            <li class="tech" data-reveal>
              <span class="tech__icon" aria-hidden="true"><svg class="icon"><use href="#i-terminal"></use></svg></span>
              <h3>Python</h3><p>Selected for the back end, researched during the attachment</p>
            </li>
            <li class="tech" data-reveal data-reveal-delay="40">
              <span class="tech__icon" aria-hidden="true"><svg class="icon"><use href="#i-server"></use></svg></span>
              <h3>FastAPI</h3><p>The agreed back-end framework, with API design patterns explored</p>
            </li>
            <li class="tech" data-reveal data-reveal-delay="80">
              <span class="tech__icon" aria-hidden="true"><svg class="icon"><use href="#i-database"></use></svg></span>
              <h3>PostgreSQL</h3><p>The chosen relational database, with schema planning under way</p>
            </li>
            <li class="tech" data-reveal data-reveal-delay="120">
              <span class="tech__icon" aria-hidden="true"><svg class="icon"><use href="#i-repo"></use></svg></span>
              <h3>Git &amp; GitHub</h3><p>A shared repository for concurrent work across the intern team</p>
            </li>
            <li class="tech" data-reveal>
              <span class="tech__icon" aria-hidden="true"><svg class="icon"><use href="#i-board"></use></svg></span>
              <h3>Jira</h3><p>Kanban board with tasks organised into Epics and Sub-Epics</p>
            </li>
          </ul>
        </div>
      </section>

    </main>

  <div class="lightbox" id="lightbox" hidden>
      <div class="lightbox__backdrop" data-close-lightbox></div>
      <div class="lightbox__dialog" role="dialog" aria-modal="true" aria-labelledby="lightboxCaption">
        <figure class="lightbox__figure">
          <!-- src/alt are populated by the lightbox module -->
          <img id="lightboxImage" alt="">
          <figcaption id="lightboxCaption" class="lightbox__caption"></figcaption>
        </figure>

        <button class="lightbox__nav lightbox__nav--prev" type="button" data-lightbox-prev>
          <svg class="icon" aria-hidden="true"><use href="#i-arrow-left"></use></svg>
          <span class="sr-only">Previous image</span>
        </button>
        <button class="lightbox__nav lightbox__nav--next" type="button" data-lightbox-next>
          <svg class="icon" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
          <span class="sr-only">Next image</span>
        </button>
        <button class="lightbox__close" type="button" data-close-lightbox>
          <svg class="icon" aria-hidden="true"><use href="#i-close"></use></svg>
          <span class="sr-only">Close gallery</span>
        </button>

        <p class="lightbox__counter" aria-live="polite"></p>
      </div>
    </div>
</div>
