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
 * Content deliberately avoids claiming facilities, equipment, opening hours or
 * eligibility rules that the AI Unit has not stated. Anything still to be
 * confirmed is marked with .lab-tbc so it is easy to find and replace.
 */
$calendlyUrl = $calendlyUrl ?? null;
?>
<main class="main-content ai-lab" id="main-content" tabindex="-1">
  <div class="container">

    <div class="page-header">
      <div class="page-eyebrow">AI Unit Facility</div>
      <h1 class="page-title">AI Lab</h1>
      <p class="page-subtitle">
        A space at the AI Unit for learning, experimenting with and exploring
        artificial intelligence - open to students, researchers, educators and
        members of the public.
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

    <p class="section-summary" data-i18n="sum_ai_lab" hidden>
      In short: the AI Lab is a space at the AI Unit for learning about and trying out AI. Book a session using the calendar on this page.
    </p>

    <section class="lab-block" aria-labelledby="lab-about-title">
      <h2 id="lab-about-title">About the AI Lab</h2>
      <p>
        The AI Lab is a dedicated space at the AI Unit, created to support
        practical engagement with artificial intelligence. It is intended for
        learning, experimentation and exploration - somewhere to move beyond
        reading about AI and work with it directly.
      </p>
      <p>
        The lab is primarily available for public use, with a particular focus
        on students from colleges, universities and other educational
        institutions. Sessions are arranged in advance through the booking
        calendar further down this page.
      </p>
    </section>

    <section class="lab-block" aria-labelledby="lab-who-title">
      <h2 id="lab-who-title">Who can use the lab</h2>
      <p>The AI Lab is open to:</p>
      <ul class="lab-list" role="list">
        <li role="listitem">
          <h3>College students</h3>
          <p>Groups and individuals looking to build early familiarity with AI.</p>
        </li>
        <li role="listitem">
          <h3>University students</h3>
          <p>Undergraduate and postgraduate students working on AI-related study or coursework.</p>
        </li>
        <li role="listitem">
          <h3>Researchers</h3>
          <p>Those exploring AI methods, applications or their implications.</p>
        </li>
        <li role="listitem">
          <h3>Educators</h3>
          <p>Teachers and lecturers preparing or delivering AI-related teaching.</p>
        </li>
        <li role="listitem">
          <h3>Members of the public</h3>
          <p>Anyone with an interest in artificial intelligence and how it is used.</p>
        </li>
      </ul>
    </section>

    <section class="lab-block" aria-labelledby="lab-uses-title">
      <h2 id="lab-uses-title">What the lab is for</h2>
      <p>
        The lab is intended to support a range of AI-related activity, including:
      </p>
      <ul class="lab-tags" role="list">
        <li role="listitem">AI learning</li>
        <li role="listitem">Practical experimentation</li>
        <li role="listitem">Demonstrations</li>
        <li role="listitem">Research and exploration</li>
        <li role="listitem">Educational activities</li>
        <li role="listitem">AI-related projects</li>
      </ul>
      <p class="lab-tbc">
        <strong>To be confirmed:</strong> details of the facilities, equipment
        and software available in the lab will be published here once confirmed
        by the AI Unit.
      </p>
    </section>

    <section class="lab-block lab-booking" id="book" aria-labelledby="lab-book-title">
      <h2 id="lab-book-title">Book the AI Lab</h2>
      <p>
        Choose an available date and time in the booking calendar below, then
        enter the details requested to confirm your session. Bookings are
        handled by Calendly, an external scheduling service.
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
    </section>

    <section class="lab-block" aria-labelledby="lab-expect-title">
      <h2 id="lab-expect-title">What to expect when booking</h2>
      <ol class="lab-steps" role="list">
        <li role="listitem">
          <h3>Choose a slot</h3>
          <p>Pick a date and time from the calendar. Only available slots are shown.</p>
        </li>
        <li role="listitem">
          <h3>Enter your details</h3>
          <p>Calendly will ask for the information needed to confirm your session, such as your name and email address.</p>
        </li>
        <li role="listitem">
          <h3>Receive confirmation</h3>
          <p>Calendly sends your confirmation by email, along with any details the AI Unit has added to the booking.</p>
        </li>
      </ol>
      <p class="lab-tbc">
        <strong>To be confirmed:</strong> session lengths, group sizes, opening
        hours, and anything you should bring or prepare will be added here once
        the AI Unit has confirmed them.
      </p>
      <p class="lab-privacy">
        Booking details you enter are submitted to Calendly and handled under
        Calendly's own terms and privacy policy. See our
        <a href="<?= e(url('privacy-policy')) ?>">Privacy Policy</a> for how the
        AI Unit handles information it receives.
      </p>
    </section>

  </div>
</main>
