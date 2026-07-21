<main class="main-content a11y-statement" id="main-content">
  <div class="container">
    <div class="page-header">
      <div class="page-eyebrow">Legal &amp; Compliance</div>
      <h1 class="page-title">Accessibility Statement</h1>
      <p class="page-subtitle">This page explains how we make this website accessible, what tools are available to help you, and how to reach us if something does not work for you.</p>
    </div>
    <p class="page-meta">AI Unit · Ministry of ICT, Communication &amp; Innovation · Republic of Mauritius · Last reviewed: May 2026</p>

    <div class="block">
      <h2>Our approach</h2>
      <p>We want this website to work for everyone. That includes people who use a screen reader, people who cannot use a mouse, people with low vision, and people who find it easier to read in a different colour or font.</p>
      <p>We are working to meet <strong>WCAG 2.1 Level AA</strong> - the standard set by the World Wide Web Consortium for accessible websites. We are not fully there yet, but we are actively improving and we review the site regularly.</p>
    </div>

    <div class="block">
      <h2>What you can do on this site</h2>
      <p>We have built a number of tools directly into the site. You can find them by clicking <strong>Accessibility</strong> in the top navigation bar.</p>
      <div class="tool-list" role="list">
        <div class="tool-row" role="listitem">
          <div class="tool-name">Read the page aloud</div>
          <div class="tool-desc">Press "Read page aloud" in the Accessibility panel and the site will read the full page to you using your browser's built-in voice. You can pause it with Space, stop it with S, and change the speed using the slider.</div>
        </div>
        <div class="tool-row" role="listitem">
          <div class="tool-name">Make text bigger or smaller</div>
          <div class="tool-desc">Use the + and - buttons in the Accessibility panel. Your preference is saved so it works next time too.</div>
        </div>
        <div class="tool-row" role="listitem">
          <div class="tool-name">Change colours</div>
          <div class="tool-desc">Switch to high contrast, dark, greyscale, or negative colour mode — whichever is easiest for you to read.</div>
        </div>
        <div class="tool-row" role="listitem">
          <div class="tool-name">Dark mode</div>
          <div class="tool-desc">Click the moon icon in the navigation bar to switch to a dark background. It is remembered on your next visit.</div>
        </div>
        <div class="tool-row" role="listitem">
          <div class="tool-name">Dyslexia-friendly font and reading line</div>
          <div class="tool-desc">Turn on a dyslexia-friendly font and a horizontal guide line that follows your cursor as you read.</div>
        </div>
        <div class="tool-row" role="listitem">
          <div class="tool-name">Stop all movement</div>
          <div class="tool-desc">If the animated elements on the page bother you, you can stop all animations with one click.</div>
        </div>
        <div class="tool-row" role="listitem">
          <div class="tool-name">Keyboard navigation</div>
          <div class="tool-desc">You can use the entire site with just a keyboard. Tab moves between links and buttons, Enter activates them, and Escape closes panels. Every focused element has a visible blue outline so you always know where you are.</div>
        </div>
        <div class="tool-row" role="listitem">
          <div class="tool-name">Quick profiles</div>
          <div class="tool-desc">Choose a pre-set profile — Low Vision, Motor, Dyslexia, Cognitive, or Senior — and all the relevant settings turn on at once.</div>
        </div>
        <div class="tool-row" role="listitem">
          <div class="tool-name">Speak to DIVA</div>
          <div class="tool-desc">When chatting with DIVA (our AI assistant), press the microphone button to ask your question by voice instead of typing.</div>
        </div>
        <div class="tool-row" role="listitem">
          <div class="tool-name">Skip to main content</div>
          <div class="tool-desc">If you are using a keyboard or screen reader, press Tab as soon as the page loads and a "Skip to main content" link will appear. This lets you jump straight to the content without going through the navigation.</div>
        </div>
      </div>
    </div>

    <div class="block">
      <h2>Languages</h2>
      <p>This site is available in <strong>English</strong>, <strong>French</strong> and <strong>Kreol Morisien</strong>. Use the language buttons at the top of the page to switch.</p>
      <p>Please note that the language switching feature is still being completed — some sections may not yet be available in all three languages. We are working on this.</p>
    </div>

    <hr />

    <div class="block">
      <h2>What is not fully accessible yet</h2>
      <p>We have fixed all the critical issues found in our May 2026 audit. These are the things we are still working on:</p>
      <div class="status-list">
        <div class="status-row"><div class="dot dot-amber"></div><span>Language switching between English, French and Kreol is not yet fully working across all sections.</span></div>
        <div class="status-row"><div class="dot dot-amber"></div><span>Some of the PDF documents in the Framework Library may not read correctly in a screen reader. We are updating them.</span></div>
      </div>
      <p style="margin-top: 16px;">These were fixed in May 2026:</p>
      <div class="status-list">
        <div class="status-row"><div class="dot dot-green"></div><span>Text was too light in several parts of the site. We darkened it so it now meets the minimum contrast ratio for readability.</span></div>
        <div class="status-row"><div class="dot dot-green"></div><span>Some heading levels were skipped (for example going from a level 2 heading straight to a level 4). This confused screen readers. It is now fixed.</span></div>
        <div class="status-row"><div class="dot dot-green"></div><span>Some images were missing proper text descriptions. All images now have alt text.</span></div>
        <div class="status-row"><div class="dot dot-green"></div><span>Navigation links inside a hidden section were still reachable by keyboard but not announced by screen readers. This has been corrected.</span></div>
      </div>
    </div>

    <hr />

    <div class="block">
      <h2>Tell us if something does not work</h2>
      <div class="notice">
        If part of this site is not working for you, please get in touch. We will try to respond within <strong>5 working days</strong> and fix the problem as quickly as we can.
      </div>
      <div class="contact-card">
        <div class="contact-line">
          <span class="contact-key">Email</span>
          <span class="contact-val"><a href="mailto:<?= e(config('site.contact_email')) ?>"><?= e(config('site.contact_email')) ?></a></span>
        </div>
        <div class="contact-line">
          <span class="contact-key">Phone</span>
          <span class="contact-val"><?= e(config('site.contact_phone')) ?></span>
        </div>
        <div class="contact-line">
          <span class="contact-key">Hours</span>
          <span class="contact-val">Monday to Friday, 9:00 AM - 4:00 PM. Closed on public holidays.</span>
        </div>
        <div class="contact-line">
          <span class="contact-key">Address</span>
          <span class="contact-val">Level 5, SIT Building, Ebène, Mauritius</span>
        </div>
      </div>
    </div>

    <div class="block">
      <h2>When we last checked</h2>
      <ul class="plain">
        <li>We ran an automated accessibility audit using WAVE in May 2026 and fixed all critical issues found.</li>
        <li>We built and launched the Accessibility toolbar in May 2026.</li>
        <li>Our next review is planned for November 2026.</li>
      </ul>
    </div>

    <hr />

    <p class="footer-note">
      This statement covers this website and was prepared by the AI Unit, Ministry of Information Technology, Communication and Innovation, Republic of Mauritius. It was last updated in May 2026.<br><br>
      <a href="<?= e(url('/')) ?>">← Return to Homepage</a>
    </p>
  </div>
</main>
