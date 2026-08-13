<?php
/**
 * Public AI Lab page.
 *
 * Booking is handled entirely by Calendly - this page carries no booking
 * logic, no form and no database access. It presents the lab, then hands the
 * visitor to Calendly's own scheduling interface.
 *
 * $calendlyUrl is validated in PageController::calendlyUrl() and is either a
 * real https://calendly.com/... link or null. Null is the expected state until
 * the AI Unit supplies the link, and renders a clearly-marked notice rather
 * than a broken embed.
 *
 * Structure and classes follow the other standalone content pages
 * (see pages/accessibility.php): .main-content > .container > .page-header,
 * with #main-content and tabindex="-1" so the site's skip link and the
 * accessibility reader's focusSkipTarget() can move focus here.
 *
 * Kept deliberately short - four headings and one image. The reading order is
 * what is it -> why it exists -> who it is for -> how to book, so a visitor
 * reaches the booking calendar without scrolling through an article.
 *
 * Content is drawn only from what the AI Unit has supplied. Facilities,
 * equipment lists, opening hours and booking rules are NOT stated because they
 * have not been confirmed; the one outstanding item is marked with .lab-tbc.
 */
$calendlyUrl = $calendlyUrl ?? null;
?>
<main class="main-content ai-lab" id="main-content" tabindex="-1">
  <div class="container">

    <div class="page-header">
      <div class="page-eyebrow">AI Unit Facility</div>
      <h1 class="page-title">AI Lab</h1>
      <p class="page-subtitle" data-i18n="ailab_page_subtitle" tabindex="0">
        A practical space where students, technopreneurs and innovators can
        learn, experiment and explore artificial intelligence.
      </p>
      <p class="lab-hero-actions">
        <?php /* target="_self" because includes/header.php sets <base target="_blank">,
                 which would otherwise open this in-page jump in a new tab. */ ?>
        <a class="btn-primary" href="#book" target="_self">
          <span>Book an AI Lab session</span>
          <span class="cta-arrow" aria-hidden="true">→</span>
        </a>
      </p>
    </div>

    <?php
    /*
     * Launch announcement.
     *
     * Sits directly under the page introduction so it is the first thing read
     * after the title, and above the feature image, because on the day it
     * matters the date is more useful than the picture.
     *
     * <aside> rather than <section>: this is a time-bound notice about the
     * page's subject, not part of the lab's permanent description. It carries
     * its own <h2>, which keeps the heading order intact - the page's only
     * <h1> is the page title above, and every other block here is also an <h2>.
     *
     * The date is written once, into $labLaunchDate, and rendered both as the
     * machine-readable <time datetime> value and as the visible text, so the
     * two cannot drift apart.
     *
     * NOTE: this is a fixed announcement, not a countdown. On 20 August 2026 the
     * wording here (and the shorter flag on the homepage, pages/home.php) needs
     * changing to the present tense - nothing switches it over automatically.
     */
    $labLaunchDate = new DateTimeImmutable('2026-08-20');
    ?>
    <aside class="lab-launch" aria-labelledby="lab-launch-title">
      <p class="lab-launch-flag">
        <span class="lab-launch-dot" aria-hidden="true"></span>
        Launch announcement
      </p>
      <h2 class="lab-launch-title" id="lab-launch-title">
        AI Lab launching
        <time datetime="<?= e($labLaunchDate->format('Y-m-d')) ?>"><?= e($labLaunchDate->format('j F Y')) ?></time>
      </h2>
      <?php /* Deliberately no data-i18n: applyTranslations() replaces innerHTML
               from a static string, which would hardcode the date a second time
               in script.js and let the two copies drift. The date belongs in
               $labLaunchDate only. */ ?>
      <p class="lab-launch-body" tabindex="0">
        The AI Lab officially launches on
        <?= e($labLaunchDate->format('j F Y')) ?>. From that date the space is
        open to students, technopreneurs and innovators. Explore the platform and
        the resources below to see what the lab offers and how to take part.
      </p>
    </aside>

    <?php
    /*
     * Feature image, immediately under the introduction so the space is
     * understandable before any body text is read.
     *
     * width/height are the file's real pixel dimensions (1536x1024, 3:2). With
     * `height:auto` in CSS the browser uses them to reserve the right space
     * before the file arrives, so the text below does not jump - and because
     * the ratio is the image's own, nothing is stretched or cropped.
     *
     * eager/high priority: it sits at the top of the page, so lazy-loading it
     * would only delay the one thing that explains the page at a glance.
     */
    ?>
    <figure class="lab-figure">
      <img src="<?= e(asset('images/ai-lab.jpg')) ?>"
           width="1536" height="1024"
           alt="Artist's impression of the AI Lab: computer workstations showing code, a small robotics kit on a worktable, a round meeting table, and a whiteboard headed AI Project Workflow."
           loading="eager" decoding="async" fetchpriority="high">
      <?php /* Stated plainly: the lab is not yet photographed, and a government
               page should not imply this is a picture of the finished room. */ ?>
      <figcaption>Artist's impression of the AI Lab.</figcaption>
    </figure>

    <section class="lab-block" aria-labelledby="lab-about-title">
      <h2 id="lab-about-title">About the AI Lab</h2>
      <p data-i18n="ailab_page_about1" tabindex="0">
        The AI Lab is an Innovation Lab approved by the Government and
        established by the Ministry of Information Technology, Communication and
        Innovation. It is a space for working with artificial intelligence
        directly - building, testing and exploring, rather than reading about it.
      </p>
      <p data-i18n="ailab_page_about2" tabindex="0">
        The Ministry is entering into an agreement with
        <strong>STEMpower Inc.</strong>, a US-based non-profit organisation,
        which is supplying the laboratory's equipment and the expertise to
        install it.
      </p>
    </section>

    <section class="lab-block" aria-labelledby="lab-partnership-title">
      <h2 id="lab-partnership-title">What the partnership provides</h2>
      <ul class="lab-facts" role="list">
        <li role="listitem">Laboratory equipment, provided free of charge</li>
        <li role="listitem">Technical expertise for its installation</li>
        <li role="listitem">Training for the staff who will operate the centre</li>
        <li role="listitem">US$7,000 over two years towards consumables, furniture and additional equipment</li>
        <li role="listitem">Support for STEM education through STEMpower's international network</li>
      </ul>
    </section>

    <section class="lab-block" aria-labelledby="lab-who-title">
      <h2 id="lab-who-title">Who the lab is for</h2>
      <p data-i18n="ailab_page_who" tabindex="0">
        The AI Lab is open to secondary and higher education students, and to
        technopreneurs and innovators - for practical learning, experimentation,
        innovation and exploration.
      </p>
      <ul class="lab-tags" role="list">
        <li role="listitem">Secondary students</li>
        <li role="listitem">Higher education students</li>
        <li role="listitem">Technopreneurs</li>
        <li role="listitem">Innovators</li>
      </ul>
    </section>

    <section class="lab-block lab-booking" id="book" aria-labelledby="lab-book-title">
      <h2 id="lab-book-title">Book the AI Lab</h2>
      <p data-i18n="ailab_page_book_intro" tabindex="0">
        Choose an available date and time below, then enter the details
        requested to confirm your session. Booking is handled by Calendly, which
        sends your confirmation by email.
      </p>

      <?php if ($calendlyUrl !== null): ?>
        <?php
        /*
         * The embed is progressive: this container is inert markup, and
         * assets/js/ai-lab.js loads Calendly's widget into it. If that script
         * or Calendly itself cannot load - offline, blocked, or a content
         * blocker - the fallback link below is already in the DOM and remains
         * the working route to the same calendar. Nothing here depends on
         * JavaScript to be usable.
         *
         * data-calendly-url rather than an inline script: it keeps the page
         * free of injected JS and works unchanged under a strict CSP.
         */
        ?>
        <div class="lab-calendly"
             id="calendlyEmbed"
             data-calendly-url="<?= e($calendlyUrl) ?>"
             role="region"
             aria-label="AI Lab booking calendar">
          <noscript>
            <p class="lab-notice">
              The booking calendar needs JavaScript. Use the link below to book
              your session instead.
            </p>
          </noscript>
        </div>

        <p class="lab-fallback" id="calendlyFallback">
          <?php /* Opens on Calendly, so a new tab is correct here - and <base
                   target="_blank"> already provides it. rel guards the opener. */ ?>
          <a class="btn-primary" href="<?= e($calendlyUrl) ?>" rel="noopener noreferrer">
            <span>Open the booking calendar on Calendly</span>
            <span class="cta-arrow" aria-hidden="true">↗</span>
          </a>
        </p>
      <?php else: ?>
        <?php
        /*
         * Expected state until CALENDLY_AI_LAB_URL is set in .env. Says so
         * plainly rather than rendering an empty embed frame, and points at the
         * contact form so an enquiry is still possible.
         */
        ?>
        <p class="lab-notice" role="status">
          <strong>Online booking is not open yet.</strong>
          The AI Lab booking calendar will appear here once it goes live. In the
          meantime, please
          <a href="<?= e(url('/') . '#contact') ?>">contact the AI Unit</a>
          to enquire about a session.
        </p>
      <?php endif; ?>

      <p class="lab-tbc">
        <strong>To be confirmed:</strong> session lengths, group sizes, opening
        hours and anything to bring will be added here once the AI Unit has
        confirmed them.
      </p>
      <p class="lab-privacy">
        Booking details you enter are submitted to Calendly and handled under
        Calendly's own terms and privacy policy. See our
        <a href="<?= e(url('privacy-policy')) ?>">Privacy Policy</a> for how the
        AI Unit handles information it receives.
      </p>
    </section>

    <p class="lab-back-home">
      <a href="<?= e(url('/')) ?>">
        <span aria-hidden="true">←</span> Back to Homepage
      </a>
    </p>

  </div>
</main>
