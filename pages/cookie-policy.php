<main class="main-content" id="main-content" tabindex="-1">
  <div class="container">
    <div class="page-header">
      <h1 class="page-title">Cookie &amp; Analytics Policy</h1>
      <p class="page-meta">Effective date: 10 September 2026</p>
    </div>

    <div class="plain-policy">
      <h2>1.1 What Are Cookies</h2>
      <p>Cookies are small text files placed on your device when you visit a website. They enable the website to recognise your browser on subsequent visits. Cookies do not execute code and cannot carry viruses.</p>
      <p>This Policy explains what this website stores on your device, what it loads from other organisations, and how you can control it.</p>

      <h2>1.2 Cookies This Site Sets</h2>
      <p>The AI Unit website sets one cookie of its own:</p>
      <ul>
        <li><strong>PHPSESSID</strong>: a session cookie that keeps your visit consistent from page to page and protects forms against cross-site request forgery. It expires when you close your browser, cannot be read by JavaScript, and is not used to track you.</li>
      </ul>
      <p>This cookie is strictly necessary for the site to operate, so no consent is required for it.</p>

      <h2>1.3 Information Stored On Your Device</h2>
      <p>The site stores two preferences in your browser's local storage. These are not cookies, are never transmitted to the AI Unit, and remain on your device until you clear your browser data:</p>
      <ul>
        <li>your language choice</li>
        <li>your accessibility settings, if you change any</li>
      </ul>

      <h2>1.4 Third-Party Services</h2>
      <p>Some pages load content from other organisations. Those organisations may set their own cookies, which the AI Unit does not control and cannot read.</p>
      <ul>
        <li><strong>Calendly</strong> provides the booking calendar on the AI Lab page. It loads when that page opens and may set cookies of its own. Booking details you enter are submitted to Calendly and handled under Calendly's own terms and privacy policy.</li>
        <li><strong>Google Maps</strong> provides the map in the contact section of the homepage. It does not load unless you select "Show map". Nothing is requested from Google until you do.</li>
      </ul>
      <p>Other pages load fonts, icons, video and the DIVA assistant from external providers. These receive the technical information that any web request carries, including your IP address, in order to deliver the content. The AI Unit does not use them to track visitors.</p>

      <h2>1.5 Analytics</h2>
      <p>The AI Unit does not use Google Analytics, or any other analytics or tracking service, on this website. No analytics cookies are set and no usage statistics are collected.</p>
      <p>Should analytics be introduced in future, this Policy will be updated before they are deployed, and consent will be sought where it is required.</p>

      <h2>1.6 Your Choices</h2>
      <p>Because this site sets only a strictly necessary session cookie of its own, no cookie consent banner is presented.</p>
      <p>You can control or delete cookies, including those set by the third parties described above, through your browser settings. Blocking third-party cookies may prevent the booking calendar or the map from working. The rest of the site will function normally.</p>

      <h2>1.7 Data Subject Rights in Relation to Cookie Data</h2>
      <p>Where cookie data constitutes personal data under the Data Protection Act, you have the same rights as described in Section C.2 of the Privacy Policy.</p>
      <p>Requests relating to cookie-generated personal data should be directed to the AI Unit compliance representative at: <?= email_link() ?>.</p>

      <h2>1.8 Changes to This Policy</h2>
      <p>We may update this Policy to reflect changes in technology, regulation, or the services we use. The effective date at the top of this document will be updated accordingly.</p>
    </div>

    <?php /* target="_self" because includes/header.php used to set <base
             target="_blank">; kept for consistency with the other back
             buttons (highlights.php, ai-lab.php) even though that base tag
             has since been removed. */ ?>
    <p class="page-back">
      <a class="btn-back" href="<?= e(url('/')) ?>" target="_self">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
        <span>Back to Homepage</span>
      </a>
    </p>
  </div>
</main>
