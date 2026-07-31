/**
 * Server-provided runtime config, emitted by includes/layouts/app.php as
 * window.AI_UNIT just before this file loads.
 *
 * assetBase must come from the server because the site can be installed in a
 * subdirectory (e.g. /AI-UNIT/), where a hardcoded "/assets/..." would resolve
 * against the domain root and 404. The fallbacks below only apply if the
 * config block is missing (e.g. a page that loads this script standalone).
 */
const AI_UNIT_CONFIG = window.AI_UNIT || {};
const ASSET_BASE = AI_UNIT_CONFIG.assetBase || '/assets';

// Translation Data - English only
const translations = {
  en: {
    nav_action: "AI in Action", nav_framework: "Framework", nav_about: "About Us", nav_contact: "Contact Us", nav_student: "Student Corner", accessibility: "Accessibility",
    hero_badge: "Ministry of ICT, Communication & Innovation", hero_title_prefix: "Where", hero_title_suffix: "Meets Impact", hero_subtitle: "Welcome to the AI Unit of Mauritius - your guide to how artificial intelligence is making public services smarter, faster, and fairer for every citizen.",
    hero_cta_marketplace: "Explore Regional AI Marketplace", hero_cta_learn: "Learn More", badge_fair: "FAIR Guidelines", badge_ai4all: "AI4ALL Inclusion", badge_marketplace: "AI Marketplace", badge_smart: "Smart Services", badge_ppp: "PPP Adoption", badge_privacy: "Data Privacy",
    about_eyebrow: "Who We Are", about_title_prefix: "Driving Mauritius", about_title_suffix: "AI Future", about_mission_quote: "\"Mauritius is committed to becoming a smart, inclusive and future-ready nation - where every citizen benefits from responsible AI.\"",
    about_body1: "The AI Unit is the dedicated body established under the Ministry of Information Technology, Communication and Innovation (MITCI) to lead Mauritius' artificial intelligence journey. We coordinate AI governance, promote innovation, and ensure that technology serves all Mauritians - fairly and transparently.",
    about_body2: "We are the strategic vehicle for MITCI's <strong>Digital Transformation 2025-2029</strong> blueprint - a bold roadmap to modernise public services and position Mauritius as a leading AI nation in Africa.",
    about_body3: "Whether you are a citizen curious about AI, a business exploring new solutions, or a student building your future - the AI Unit is here for you.",
    diva_title: "Meet DIVA - Digital Interactive Virtual Assistant", diva_desc: "DIVA is a prototype assistant that answers questions based on four key documents: the Digital Transformation Blueprint, AI Strategy, FAIR Guidelines, and AI Playbook.", diva_chat: "Chat with DIVA",
    vision_title: "Our Vision", vision_text: "Position Mauritius as a regional leader in trusted, responsible AI - powering economic transformation, elevating public services, and enhancing the quality of life for every citizen.",
    mission_title: "Our Mission", mission_text: "Drive responsible AI in Mauritius by leading its implementation and governance - ensuring every system is secure, ethical, and transparent, and that AI delivers meaningful impact for citizens and businesses.",
    objectives_title: "Our Six Objectives", obj1: "Govern Trusted AI", obj2: "Modernize Public Services", obj3: "Grow the AI Ecosystem", obj4: "Build Future-Ready Skills", obj5: "Strengthen Data Infrastructure", obj6: "Elevate Mauritius' Global Standing",
    framework_eyebrow: "AI Framework", framework_title_prefix: "Six Strategic", framework_title_suffix: "Dimensions", framework_desc: "Our six pillars guide how Mauritius builds, governs, and shares the benefits of artificial intelligence - from the ground up, for everyone.",
    dim1_title: "Digital Infrastructure Maturity", dim1_text: "Building the digital backbone Mauritius needs - high-speed internet, modern data centres, secure cloud platforms, and strong cybersecurity - so AI technologies can thrive and reach every part of the country.",
    dim1_tag1: "Connectivity", dim1_tag2: "Cloud", dim1_tag3: "Cybersecurity", dim1_tag4: "Data Centres",
    dim2_title: "Innovation Culture & Ecosystem", dim2_text: "Creating a culture where new ideas are welcomed - supporting startups, universities, and creative thinkers who want to build AI solutions that solve real problems for Mauritians.",
    dim2_tag1: "Startups", dim2_tag2: "R&D", dim2_tag3: "Collaboration", dim2_tag4: "Incubators",
    dim3_title: "AI for ALL and Inclusion (AI4AI)", dim3_text: "Ensuring no one is left behind. AI4AI means the benefits of artificial intelligence reach every Mauritian - regardless of age, location, language, or level of education. We run outreach programmes in every district, and our resources are available in English.",
    dim3_tag1: "Accessibility", dim3_tag2: "Digital Literacy", dim3_tag3: "Rural Outreach", dim3_tag4: "Multilingual",
    dim4_title: "Regulatory Framework", dim4_text: "Establishing clear, fair, and future-ready rules for AI. Our regulatory framework - including the FAIR Guidelines - ensures AI systems are safe, ethical, and accountable. Our Data Protection Act is aligned with international best practices including GDPR.",
    dim4_tag1: "Ethics", dim4_tag2: "Governance", dim4_tag3: "Policy", dim4_tag4: "Data Protection",
    dim5_title: "PPP Adoption (Public, Private, People)", dim5_text: "AI works best when everyone works together. We unite government agencies, private sector companies, and everyday citizens through shared goals and joint projects - building AI solutions that reflect the real needs of Mauritian society.",
    dim5_tag1: "Government", dim5_tag2: "Private Sector", dim5_tag3: "Citizens", dim5_tag4: "Partnerships",
    dim6_title: "International Collaboration", dim6_text: "Positioning Mauritius as an active global participant in AI governance and innovation. We partner with international bodies, African nations, and leading technology countries to bring the best of global AI knowledge home.",
    dim6_tag1: "African Union", dim6_tag2: "UN Partnerships", dim6_tag3: "India Cooperation", dim6_tag4: "Global Standards",
    action_eyebrow: "AI in Action", action_title_prefix: "AI Making a", action_title_suffix: "Difference", action_desc: "From protecting children online to making technology accessible for every Mauritian - see how AI is already transforming lives across our communities.",
    action_chip1: "Child Protection", action_card1_title: "Digital Violence Against Children", action_card1_desc: "An educational booklet and 4 videos to raise awareness, prevent, and take action against digital violence targeting children.",
    action_card1_note: "Together we can end digital violence - educational resources for children, parents, and educators.",
    action_booklet_label: "📘 Full educational booklet · 24 pages", action_booklet_btn: "Read the booklet",
    video1_title: "Video 1", video1_desc: "Forms of Digital Violence",
    video2_title: "Video 2", video2_desc: "Consequences and Effects",
    video3_title: "Video 3", video3_desc: "Children's Rights & Parental Responsibility",
    video4_title: "Video 4", video4_desc: "Regaining Control",
    action_chip2: "AI for All", action_card2_title: "Discover \"AI for All\"", action_card2_desc: "Our national booklet designed to make Artificial Intelligence accessible, understandable and beneficial to every citizen.",
    action_card2_note: "Making AI understandable for everyone - available in English version.",
    ai_en_title: "🇬🇧 AI For All - English Version", ai_en_sub: "The Future Belongs to Us", ai_en_btn: "Read the Booklet →",
    marketplace_eyebrow: "Regional AI Marketplace", marketplace_title1: "Connect. Build.", marketplace_title2: "Innovate Together.", marketplace_desc: "The Regional AI Marketplace connects solution providers, startups, businesses, and public institutions - accelerating the development, adoption, and deployment of AI-driven solutions across Mauritius and the region.",
    marketplace_browse: "Browse Solutions", marketplace_list: "List Your Solution",
    marketplace_card1_title: "For Startups & Innovators", marketplace_card1_desc: "List your AI product or service and reach government buyers and businesses",
    marketplace_card2_title: "For Businesses", marketplace_card2_desc: "Discover vetted AI solutions that can transform your operations",
    marketplace_card3_title: "For Public Institutions", marketplace_card3_desc: "Find trusted AI tools to modernise government services for citizens",
    library_eyebrow: "Framework", library_title1: "Framework Library", library_title2: "And AI Playbook", library_desc: "Our core governance documents: strategic blueprint, AI strategy, FAIR guidelines, and the AI Playbook for public sector implementation.",
    doc1_title: "BLUEPRINT", doc1_desc: "Digital Transformation Blueprint: 4 strategic pillars and governance framework.", doc1_pages: "54 Pages",
    doc2_title: "AI STRATEGY", doc2_desc: "Mauritius' first national AI strategy - governance, adoption framework, sectoral applications.", doc2_pages: "74 Pages",
    doc3_title: "FAIR GUIDELINES", doc3_desc: "Principles-based responsible AI guidelines for Fairness, Accountability, Inclusiveness & Responsibility.", doc3_pages: "38 Pages",
    doc4_title: "AI PLAYBOOK", doc4_desc: "Practical implementation guide for public sector AI projects - from pilot to production.", doc4_pages: "62 Pages",
    doc_download: "Download", doc_view: "View Online",
    principles_eyebrow: "About Us", principles_title1: "Our", principles_title2: "Mission And Values", principles_desc: "We are a dedicated team shaping the future of AI in Mauritius - guided by strong ethics, a people-first mindset, and a commitment to responsible innovation.",
    principle1_title: "Fairness", principle1_text: "AI systems must treat all citizens equitably. We enforce bias auditing and impact assessments throughout every stage of development - ensuring no community is disadvantaged by the technology built in their name.",
    principle2_title: "Accountability", principle2_text: "Explainability is a requirement, not an option. Every model decision must be interpretable, auditable, and traceable by authorised oversight bodies - building the public trust that responsible AI demands.",
    principle3_title: "Inclusiveness & Integrity", principle3_text: "Technology should serve everyone. We design AI tools that are accessible across language, ability, and geography - leaving no community behind, and upholding the highest standards of honesty and transparency in everything we do.",
    principle4_title: "Responsibility", principle4_text: "We act with purpose and accountability. Responsible AI means moving with care - shipping thoughtfully, learning continuously, and always keeping the long-term wellbeing of citizens at the centre of our work.",
    team_title1: "The People", team_title2: "Behind the Work", team_desc: "Meet the experts driving Mauritius' AI strategy and digital transformation.",
    team_tab1: "Mr. Ramakrishna", team_tab2: "Dr. Heman", team_tab3: "Mr. Ruben",
    team_rama_tag: "Alignment & Safety", team_rama_role: "Head - AI Unit", team_rama_quote: "Ramakrishna Mudaliar serves as Head of the AI Unit, where he spearheads the country's people-centric approach to leverage Artificial Intelligence for responsible development and implementation at a national scale. He holds Master's Degrees from the University of Montpellier and the University of Manipal. With more than two decades of experience across both the private technology sector and public service, Ramakrishna brings a well-rounded perspective that bridges innovation with real-world implementation.",
    rama_stat1: "Years Experience", rama_stat2: "Master's Degrees", rama_stat3: "AI Unit Founded",
    team_heman_tag: "Architecture & Scale", team_heman_role: "AI Expert", team_heman_quote: "Dr. Heman Mohabeer is an AI researcher, strategist, and inventor serving as an AI Expert at the AI Unit of the Government of Mauritius. With a PhD in Artificial Intelligence and Machine Learning, he supports national AI policy development, advises on digital transformation, and promotes resilient, explainable, and locally owned AI systems.",
    heman_stat1: "AI & Machine Learning", heman_stat2: "Years Experience", heman_stat3: "Regional AI Leader",
    team_ruben_tag: "Digital Transformation", team_ruben_role: "AI Expert", team_ruben_quote: "Ruben Ramdhony is a Digital Transformation Executive and former Chief Information Officer with over 20 years of enterprise experience across Australia and Mauritius. He holds an MBA from Macquarie Business School, Australia. He is currently serving in an AI Expert capacity, translating policy into working systems, governance into practice, and strategy into measurable outcomes across Government.",
    ruben_stat1: "Years Enterprise", ruben_stat2: "Former Chief Info. Officer", ruben_stat3: "Cross-Border Experience",
    contact_eyebrow: "Get in Touch", contact_title1: "We're Here", contact_title2: "for You", contact_desc: "Have questions about AI in Mauritius? Want to partner with us or learn more about our programmes? Reach out - we welcome every question.",
    contact_address_title: "Address", contact_address_text: "Ministry of Information Technology,\nCommunication and Innovation\nLevel 5, SIT Building, Ebène\nMauritius",
    contact_email_title: "Email", contact_phone_title: "Phone", contact_hours_title: "Office Hours", contact_hours_text: "Monday - Friday: 9:00 AM - 4:00 PM\nClosed on Public Holidays",
    form_name: "Your Name", form_email: "Email Address", form_topic: "Topic", form_topic_placeholder: "Select a topic",
    form_topic1: "AI Strategy Enquiry", form_topic2: "Partnership Proposal", form_topic3: "Public Services Feedback", form_topic4: "DIVA / Digital Services", form_topic5: "AI Marketplace", form_topic6: "Media & Press", form_topic7: "Other",
    form_message: "Your Message", form_send: "Send Message",
    footer_brand: "Ministry of Information Technology, Communication and Innovation - Republic of Mauritius. Building a smarter, fairer future with AI.",
    footer_nav: "Navigation", footer_about: "About Us", footer_team: "Meet the Team", footer_action: "AI in Action", footer_framework: "AI Framework",
    footer_resources: "Resources", footer_strategy: "National AI Strategy", footer_fair: "FAIR Guidelines", footer_blueprint: "Digital Blueprint", footer_playbook: "AI Playbook",
    footer_info: "Information", footer_privacy: "Privacy Policy", footer_disclaimer: "Disclaimer", footer_cookie: "Cookie Policy", footer_accessibility: "Accessibility Statement", footer_contact: "Connect with us",
    footer_disclaimer_text: "The Regional AI Marketplace is a facilitation tool. Listing a company or solution does not constitute an official government endorsement, certification, or guarantee of quality by the Ministry of Information Technology, Communication and Innovation or the Government of Mauritius. Users are encouraged to conduct their own due diligence before entering into technical or financial agreements.",
    footer_copyright: "© 2026 Artificial Intelligence Unit, Republic of Mauritius. Developed and Hosted by Government Online Centre.",
    diva_online: "Online & ready to help", diva_welcome: "Hello! I'm <strong>DIVA</strong> - the Government of Mauritius' AI assistant. I'm here to help you with questions about our Digital Transformation Blueprint, AI strategy, and government services.<br><br>You can also <strong>speak to me</strong> - press the microphone button below and ask your question out loud.",
    diva_sug1: "What is the Digital Transformation Blueprint?", diva_sug2: "What does FAIR stand for in the AI Framework?", diva_sug3: "How is AI used in Mauritius government services?",
    a11y_title: "Accessibility", a11y_subtitle: "Adjust this website to your needs", a11y_reset: "Reset",
    a11y_sr_title: "Screen Reader - Read this page aloud", a11y_read_btn_label: "Read page aloud", a11y_read_btn_hint: "Click to start · Space to pause / resume",
    a11y_kbd_hint: "Keyboard shortcuts (while reading)", a11y_kbd_playpause: "Play / Pause", a11y_kbd_stop: "Stop", a11y_kbd_slower: "Slower", a11y_kbd_faster: "Faster",
    a11y_voice_default: "Default voice", a11y_speed_label: "Speed:", a11y_profiles_label: "Quick Profiles",
    a11y_profile_vision: "Low Vision", a11y_profile_motor: "Motor", a11y_profile_dyslexia: "Dyslexia", a11y_profile_cognitive: "Cognitive", a11y_profile_elderly: "Senior",
    a11y_textsize: "Text Size", a11y_colour: "Colour & Display", a11y_colour_normal: "Normal", a11y_colour_highcontrast: "High Contrast", a11y_colour_dark: "Dark", a11y_colour_grayscale: "Greyscale", a11y_colour_negative: "Negative",
    a11y_toggle_links: "Highlight Links", a11y_toggle_images: "Hide Images", a11y_toggle_motion: "Stop Animations",
    a11y_reading: "Reading & Focus", a11y_toggle_dyslexia: "Dyslexia-Friendly Font", a11y_toggle_readguide: "Reading Guide Line", a11y_toggle_spacing: "Wider Letter Spacing", a11y_toggle_focus: "Bold Focus Outline",
    a11y_navigation: "Navigation", a11y_toggle_cursor: "Large Mouse Cursor", a11y_toggle_keyboard: "Show Keyboard Shortcuts",
    a11y_kbd_open: "Open panel:", a11y_kbd_close: "Close:",

    // ─── SIMPLE LANGUAGE MODE: UI strings ───
    simple_toggle: "Simple language", ft_show: "Read the full text", ft_hide: "Hide the full text", listen_page: "Listen to this page",
    simple_time_announcement: "Reading the full page takes about {full} minutes. Simple language mode reduces this to about {simple} minutes. Press Alt M to switch, or continue.",

    // ─── SIMPLE LANGUAGE MODE: per-section orientation summaries (shown only in simple mode) ───
    sum_about: "In short: the AI Unit leads Mauritius' work on artificial intelligence, making sure it is fair and open for everyone.",
    sum_framework: "In short: six areas Mauritius is working on to build AI that everyone can trust.",
    sum_action: "In short: real examples of AI helping people in Mauritius, from child safety online to accessible technology for all.",
    sum_marketplace: "In short: an online marketplace connecting AI businesses, startups and government buyers across Mauritius and the region.",
    sum_strategy: "In short: our four main policy documents on AI, available to read online or download.",
    sum_principles: "In short: the values that guide our work - fairness, accountability, inclusion and responsibility.",
    sum_team: "In short: meet the three people leading Mauritius' national AI strategy.",
    sum_contact: "In short: how to reach us by email, phone or post, and our office hours.",
  }
};
  
let currentLang = 'en';
// mfe = Morisyen (ISO 639-3) - the correct <html lang> value for Kreol
// Morisien content, so screen readers switch phonetics/voice instead of
// reading it with English pronunciation rules.
const HTML_LANG = { en: 'en', fr: 'fr', km: 'mfe' };

// Simple language mode: when on, applyTranslations() prefers a `_s` (short)
// variant of each translation key over the full-length one. Any key without
// a `_s` variant falls back to the full text - see CLAUDE_CODE_BRIEF_simple.md
// Task 1. Set by the navbar toggle (Task 2), not here.
let simpleMode = false;

function applyTranslations() {
  const T = translations[currentLang] || {};
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    let value;

    if (simpleMode && T[key + '_s'] !== undefined) {
      value = T[key + '_s'];
      el.setAttribute('data-simplified', 'true');
    } else {
      value = T[key];
      el.removeAttribute('data-simplified');
    }
    if (value === undefined) return;

    if ((el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') && el.placeholder) {
      el.placeholder = value;
    } else {
      el.innerHTML = value;
    }
  });
  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.classList.toggle('active', btn.getAttribute('data-lang') === currentLang);
  });
  // Update document lang attribute for screen reader and accessibility.
  // Lives here (not just the click handler below) so every caller of
  // applyTranslations() - including restoring a saved language on page
  // load - keeps <html lang> in sync with the actual content language.
  document.documentElement.lang = HTML_LANG[currentLang] || 'en';
  // Notify screen reader and other components of language change
  window.dispatchEvent(new CustomEvent('aiunit-lang-changed', { detail: { lang: currentLang } }));
}

document.querySelectorAll('.lang-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    currentLang = btn.getAttribute('data-lang');
    applyTranslations();
    localStorage.setItem('ai_unit_lang', currentLang);
  });
});

const savedLang = localStorage.getItem('ai_unit_lang');
if (savedLang && (savedLang === 'fr' || savedLang === 'km')) {
  currentLang = savedLang;
  applyTranslations();
}

/* ─── SIMPLE LANGUAGE MODE ───
   Replaces long-form copy with short plain-language summaries (the `_s`
   variant of each translation key - see applyTranslations() above). Offered
   as a reading preference to every visitor and never switched on
   automatically - see CLAUDE_CODE_BRIEF_simple_mode.md section 3. Two
   independent localStorage keys (simple language, listen-to-page) per
   section 3.4 of that brief. */
const SIMPLE_MODE_KEY = 'aiunit_simple_mode_v1';
const LISTEN_PAGE_KEY = 'aiunit_listen_page_v1';
const simpleToggleBtn = document.getElementById('simple-toggle');
const listenBtn = document.getElementById('listen-page');
const simpleAnnouncer = document.getElementById('simple-announcer');
let announcedTimeSavingsThisSession = false; // module flag, not localStorage - once per session, reappears next visit

function announceSimple(msg) {
  if (!simpleAnnouncer) return;
  simpleAnnouncer.textContent = '';
  requestAnimationFrame(function () { simpleAnnouncer.textContent = msg; });
}

function countWords(str) {
  return (String(str).replace(/<[^>]*>/g, ' ').match(/\S+/g) || []).length;
}

// Starting estimate only - calibrate against a real timed NVDA read of the
// full page at reading rate 1.0x and set the measured figure. See brief
// Task 5. Record both measured read times for the IA report.
const WORDS_PER_MINUTE = 160;

function minutesFor(words) {
  return Math.max(1, Math.round(words / WORDS_PER_MINUTE));
}

// Full-page word count and how many words simple mode would save, derived
// from the translations object rather than the DOM so it is correct
// regardless of which mode is currently showing.
function pageWordTotals() {
  const T = translations[currentLang] || {};
  let full = 0, saved = 0;
  document.querySelectorAll('[data-i18n]').forEach(function (el) {
    const key = el.getAttribute('data-i18n');
    if (!key || T[key] === undefined) return;
    const fullWords = countWords(T[key]);
    full += fullWords;
    const shortKey = key + '_s';
    if (T[shortKey] !== undefined) saved += Math.max(0, fullWords - countWords(T[shortKey]));
  });
  return { full: full, saved: saved };
}

// Builds a "Read the full text" disclosure after every element simple mode
// just replaced, from the translations object - no full text is duplicated
// in the HTML. Uses the `hidden` attribute (not a CSS class) so collapsed
// text genuinely leaves the accessibility tree. Idempotent: safe to call
// repeatedly, e.g. every time the mode is toggled.
function rebuildDisclosures() {
  document.querySelectorAll('.full-text-wrap').forEach(function (el) { el.remove(); });
  if (!simpleMode) return;

  const T = translations[currentLang] || {};
  document.querySelectorAll('[data-simplified="true"]').forEach(function (el) {
    const key = el.getAttribute('data-i18n');
    if (!key || T[key] === undefined) return;

    const wrap = document.createElement('div');
    wrap.className = 'full-text-wrap';

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'full-text-toggle';
    btn.setAttribute('aria-expanded', 'false');
    const panelId = 'ft-' + key;
    btn.setAttribute('aria-controls', panelId);
    const btnLabel = document.createElement('span');
    btnLabel.textContent = T['ft_show'] || 'Read the full text';
    btn.appendChild(btnLabel);

    const panel = document.createElement('div');
    panel.className = 'full-text-body';
    panel.id = panelId;
    panel.hidden = true;
    panel.innerHTML = T[key];

    btn.addEventListener('click', function () {
      const expanded = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', String(!expanded));
      panel.hidden = expanded;
      btnLabel.textContent = expanded ? (T['ft_show'] || 'Read the full text') : (T['ft_hide'] || 'Hide the full text');
    });

    wrap.appendChild(btn);
    wrap.appendChild(panel);
    el.insertAdjacentElement('afterend', wrap);
  });
}

function setSimpleMode(on, opts) {
  opts = opts || {};
  simpleMode = on;
  applyTranslations();
  document.querySelectorAll('.section-summary').forEach(function (el) { el.hidden = !on; });
  rebuildDisclosures();
  if (simpleToggleBtn) simpleToggleBtn.setAttribute('aria-pressed', String(on));
  try { localStorage.setItem(SIMPLE_MODE_KEY, on ? '1' : '0'); } catch (e) {}
  if (!opts.silent) {
    announceSimple(on
      ? 'Simple language on. Long sections replaced with short summaries.'
      : 'Simple language off. Showing the full text.');
  }
}

if (simpleToggleBtn) {
  simpleToggleBtn.addEventListener('click', function () { setSimpleMode(!simpleMode); });
}

// #sr-read-btn belongs to accessibility-widget.js, which self-injects it on
// DOMContentLoaded and is kept unmodified (see the widget-swap task earlier
// in this thread) - so it is looked up by id rather than held as a direct
// reference, and forwarded a real click rather than driven programmatically.
function startBuiltInReading() {
  const btn = document.getElementById('sr-read-btn');
  if (btn) btn.click();
}

function isReaderActive() {
  const btn = document.getElementById('sr-read-btn');
  return !!btn && (btn.classList.contains('active') || btn.classList.contains('paused'));
}

function speakAnnouncement(text, onEnd) {
  if (!window.speechSynthesis) { onEnd(); return; }
  const u = new SpeechSynthesisUtterance(text);
  u.lang = HTML_LANG[currentLang] || 'en';
  u.onend = onEnd;
  u.onerror = onEnd;
  window.speechSynthesis.speak(u);
}

// Alt+M: works whether or not the reader is running. If speech is in
// progress when pressed (either the built-in reader or our own time-saving
// announcement), cancel it, switch mode, and restart the reader from the
// top so it picks up the new (simple or full) text.
function handleAltM() {
  const wasSpeaking = !!(window.speechSynthesis && window.speechSynthesis.speaking);
  if (window.speechSynthesis) window.speechSynthesis.cancel();
  setSimpleMode(!simpleMode);
  if (wasSpeaking) setTimeout(startBuiltInReading, 150);
}

document.addEventListener('keydown', function (e) {
  if (e.altKey && e.key.toLowerCase() === 'm') {
    e.preventDefault();
    handleAltM();
  }
});

// Mirror #sr-read-btn's active/paused class onto our own "Listen to this
// page" button, since the real reading state lives inside
// accessibility-widget.js. Polls briefly for the button to exist, since
// this script tag loads before accessibility-widget.js does.
(function watchReaderButton() {
  const btn = document.getElementById('sr-read-btn');
  if (!btn) { setTimeout(watchReaderButton, 300); return; }
  function sync() { if (listenBtn) listenBtn.setAttribute('aria-pressed', String(isReaderActive())); }
  new MutationObserver(sync).observe(btn, { attributes: true, attributeFilter: ['class'] });
  sync();
  if (localStorage.getItem(LISTEN_PAGE_KEY) === '1' && !isReaderActive()) btn.click();
})();

if (listenBtn) {
  listenBtn.addEventListener('click', function () {
    try { localStorage.setItem(LISTEN_PAGE_KEY, '1'); } catch (e) {}

    if (isReaderActive()) {
      startBuiltInReading(); // forwards the click; the widget's own button owns play/pause/stop
      return;
    }

    const totals = pageWordTotals();
    const shouldAnnounce = !simpleMode
      && !announcedTimeSavingsThisSession
      && totals.full > 0
      && (totals.saved / totals.full) >= 0.20;

    if (!shouldAnnounce) { startBuiltInReading(); return; }

    announcedTimeSavingsThisSession = true;
    const fullMinutes = minutesFor(totals.full);
    const simpleMinutes = minutesFor(totals.full - totals.saved);
    const T = translations[currentLang] || {};
    const template = T['simple_time_announcement'] ||
      'Reading the full page takes about {full} minutes. Simple language mode reduces this to about {simple} minutes. Press Alt M to switch, or continue.';
    speakAnnouncement(template.replace('{full}', fullMinutes).replace('{simple}', simpleMinutes), startBuiltInReading);
  });
}

// Restore saved preference and apply as early as this script runs (it loads
// before accessibility-widget.js, at the end of <body> - see Task 2; true
// before-first-paint would require moving script loading into <head>, which
// was out of scope here).
try {
  if (localStorage.getItem(SIMPLE_MODE_KEY) === '1') setSimpleMode(true, { silent: true });
} catch (e) {}

/* ─── HERO BACKGROUND VIDEO ───
   The hero sits on a CSS gradient with the video layered over it. If the video
   file is missing or fails to decode, an empty <video> box can paint black over
   that gradient, so hide the element and let the gradient show through. */
(function () {
  const heroVideo = document.getElementById('heroVideo');
  if (!heroVideo) return;

  const markUnavailable = () => heroVideo.classList.add('is-unavailable');

  // A failing <source> fires "error" on the source element, not the <video>.
  heroVideo.querySelectorAll('source').forEach(s => s.addEventListener('error', markUnavailable));
  heroVideo.addEventListener('error', markUnavailable);
  heroVideo.addEventListener('loadeddata', () => heroVideo.classList.remove('is-unavailable'));

  // Covers the case where every source failed before these listeners attached.
  if (heroVideo.networkState === HTMLMediaElement.NETWORK_NO_SOURCE) markUnavailable();
})();

const navbar=document.getElementById('navbar');
const navLinksDiv=document.getElementById('navLinks');
const hamburger=document.getElementById('hamburger');
const sections=document.querySelectorAll('section[id]');
const allNavLinks=document.querySelectorAll('.nav-link');
window.addEventListener('scroll',()=>{
  navbar.classList.toggle('scrolled',window.scrollY>30);
  let current='';
  sections.forEach(sec=>{if(window.scrollY>=sec.offsetTop-90)current=sec.id;});
  allNavLinks.forEach(link=>link.classList.toggle('active',link.dataset.scroll===current));
});
document.querySelectorAll('[data-scroll]').forEach(el=>{
  el.addEventListener('click',e=>{
    e.preventDefault();
    const target=document.getElementById(el.dataset.scroll);
    if(target)target.scrollIntoView({behavior:'smooth',block:'start'});
    navLinksDiv.classList.remove('mobile-open');
    hamburger.classList.remove('open');
    hamburger.setAttribute('aria-expanded','false');
  });
});
hamburger.addEventListener('click',()=>{
  const open=hamburger.classList.toggle('open');
  navLinksDiv.classList.toggle('mobile-open');
  hamburger.setAttribute('aria-expanded',open.toString());
});

document.querySelectorAll('.dimension-header').forEach(btn=>{
  btn.addEventListener('click',()=>{
    const item=btn.closest('.dimension-item');
    const body=item.querySelector('.dimension-body');
    const isOpen=item.classList.contains('open');
    document.querySelectorAll('.dimension-item').forEach(di=>{di.classList.remove('open');di.querySelector('.dimension-header').setAttribute('aria-expanded','false');di.querySelector('.dimension-body').style.maxHeight='0';});
    if(!isOpen){item.classList.add('open');btn.setAttribute('aria-expanded','true');body.style.maxHeight=body.scrollHeight+200+'px';}
  });
});

document.querySelectorAll('.team-tab').forEach(tab=>{
  tab.addEventListener('click',()=>{
    const idx=tab.dataset.member;
    document.querySelectorAll('.team-tab').forEach(t=>{t.classList.remove('active');t.setAttribute('aria-selected','false');});
    tab.classList.add('active');tab.setAttribute('aria-selected','true');
    document.querySelectorAll('.team-member-panel').forEach(p=>p.classList.remove('active'));
    const panels=document.querySelectorAll('.team-member-panel');
    if(panels[idx])panels[idx].classList.add('active');
  });
});

const revealObs=new IntersectionObserver(entries=>{
  entries.forEach((entry,i)=>{if(entry.isIntersecting)setTimeout(()=>entry.target.classList.add('visible'),(i%4)*80);});
},{threshold:0.08,rootMargin:'0px 0px -32px 0px'});
document.querySelectorAll('.reveal').forEach(el=>revealObs.observe(el));

document.getElementById('contactForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const form = e.target;
  const name = document.getElementById('name');
  const email = document.getElementById('email');
  const message = document.getElementById('message');
  const status = document.getElementById('formStatus');
  const btn = form.querySelector('[type="submit"]');

  function showFieldError(fieldId, msg) {
    const field = document.getElementById(fieldId);
    const errorEl = document.getElementById(fieldId + '-error');
    if (field) field.classList.add('invalid');
    if (errorEl) {
      errorEl.textContent = msg;
      errorEl.classList.add('show');
    }
  }

  function clearErrors() {
    [name, email, message].forEach(field => field.classList.remove('invalid'));
    document.querySelectorAll('.field-error').forEach(el => { el.textContent = ''; el.classList.remove('show'); });
    status.classList.remove('show', 'success', 'error');
  }

  let valid = true;
  clearErrors();

  // Validate name
  if (!name.value.trim()) {
    showFieldError('name', 'Please enter your name.');
    valid = false;
  }

  // Validate email
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!email.value.trim()) {
    showFieldError('email', 'Please enter your email address.');
    valid = false;
  } else if (!emailPattern.test(email.value.trim())) {
    showFieldError('email', 'Please enter a valid email address.');
    valid = false;
  }

  // Validate message
  if (!message.value.trim()) {
    showFieldError('message', 'Please enter a message.');
    valid = false;
  }

  if (!valid) {
    status.textContent = 'Please correct the errors above.';
    status.classList.add('show', 'error');
    return;
  }

  const originalBtnHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = 'Sending…';

  try {
    const response = await fetch(form.dataset.endpoint, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: new FormData(form),
    });
    const data = await response.json();

    if (data.success) {
      status.textContent = data.message;
      status.classList.add('show', 'success');
      form.reset();

      btn.innerHTML = '✓ Sent <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>';
      btn.style.background = '#047857';
      setTimeout(() => {
        btn.innerHTML = originalBtnHtml;
        btn.style.background = '';
        btn.disabled = false;
      }, 3000);
      return;
    }

    // Field-specific validation errors from the server
    if (data.errors) {
      Object.keys(data.errors).forEach(field => showFieldError(field, data.errors[field]));
    }
    status.textContent = data.message || 'Please correct the errors above.';
    status.classList.add('show', 'error');
  } catch (err) {
    status.textContent = 'Something went wrong. Please check your connection and try again.';
    status.classList.add('show', 'error');
  } finally {
    btn.disabled = false;
    if (btn.innerHTML === 'Sending…') btn.innerHTML = originalBtnHtml;
  }

  // Clear error on input
  [name, email, message].forEach(field => {
    field.addEventListener('input', function() {
      field.classList.remove('invalid');
      const errorEl = document.getElementById(field.id + '-error');
      if (errorEl) errorEl.classList.remove('show');
    }, { once: true });
  });
});

// DIVA's backend URL comes from server config (config('diva.api_url'), settable
// via the DIVA_API_URL environment variable) rather than being hardcoded here,
// so it can be repointed per-environment without editing this file.
const WORKER_URL = AI_UNIT_CONFIG.divaApiUrl || 'http://127.0.0.1:8000/api/chat';
const divaTrigger=document.getElementById('divaTrigger');
const divaPanel=document.getElementById('divaPanel');
const divaClose=document.getElementById('divaClose');
const divaClear=document.getElementById('divaClear');
const divaInput=document.getElementById('divaInput');
const divaSend=document.getElementById('divaSend');
const divaMic=document.getElementById('divaMic');
const divaMessages=document.getElementById('divaMessages');
const openDiva=document.getElementById('openDiva');
const divaHistory=[];
let divaIsLoading=false;
let lastDivaResponse='';

/* ─── ADD DIVA MESSAGE (with Read-Aloud + Copy per message) ─── */
function addDivaMessage(text, role) {
  const div = document.createElement('div');
  div.className = 'diva-msg ' + role;
  const content = document.createElement('div');
  content.textContent = text;
  div.appendChild(content);

  if (role === 'bot') {
    const actions = document.createElement('div');
    actions.className = 'diva-actions';

    // READ ALOUD BUTTON
    const speakBtn = document.createElement('button');
    speakBtn.className = 'diva-read-aloud';
    speakBtn.title = 'Read aloud';
    speakBtn.setAttribute('aria-label', 'Read this response aloud');
    speakBtn.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
        <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
        <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
      </svg>`;
    speakBtn.addEventListener('click', () => {
      speakText(text, speakBtn);
    });

    // COPY BUTTON
    const copyBtn = document.createElement('button');
    copyBtn.className = 'diva-copy-btn';
    copyBtn.title = 'Copy Response';
    copyBtn.setAttribute('aria-label', 'Copy this response');
    copyBtn.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
      </svg>`;
    copyBtn.addEventListener('click', async () => {
      await navigator.clipboard.writeText(text);
      copyBtn.innerHTML = '✓';
      setTimeout(() => {
        copyBtn.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
          </svg>`;
      }, 1500);
    });

    actions.appendChild(speakBtn);
    actions.appendChild(copyBtn);
    div.appendChild(actions);
  }

  divaMessages.appendChild(div);
  divaMessages.scrollTop = divaMessages.scrollHeight;
}

/* ─── TYPE DIVA MESSAGE (typing effect with actions) ─── */
async function typeDivaMessage(text, source = null) {
  const div = document.createElement('div');
  div.className = 'diva-msg bot';
  const content = document.createElement('div');
  // Hidden from the accessibility tree while typing, so the word-by-word
  // visual effect doesn't fire a separate live-region announcement per
  // word inside #divaMessages (role="log"). The finished message is
  // exposed to assistive tech in one step once typing completes below.
  content.setAttribute('aria-hidden', 'true');
  div.appendChild(content);
  divaMessages.appendChild(div);
  const words = text.split(' ');

  for (const word of words) {
    content.textContent += word + ' ';
    divaMessages.scrollTop = divaMessages.scrollHeight;
    await new Promise(resolve => setTimeout(resolve, 25));
  }

  content.removeAttribute('aria-hidden');

  // ACTION BUTTONS CONTAINER
  const actions = document.createElement('div');
  actions.className = 'diva-actions';

  // READ ALOUD BUTTON
  const speakBtn = document.createElement('button');
  speakBtn.className = 'diva-read-aloud';
  speakBtn.title = 'Read Aloud';
  speakBtn.setAttribute('aria-label', 'Read this response aloud');
  speakBtn.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
      <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
      <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
    </svg>`;
  speakBtn.addEventListener('click', () => {
    speakText(text, speakBtn);
  });

  // COPY BUTTON
  const copyBtn = document.createElement('button');
  copyBtn.className = 'diva-copy-btn';
  copyBtn.title = 'Copy Response';
  copyBtn.setAttribute('aria-label', 'Copy this response');
  copyBtn.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
      <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
    </svg>`;
  copyBtn.addEventListener('click', async () => {
    await navigator.clipboard.writeText(text);
    copyBtn.innerHTML = '✓';
    setTimeout(() => {
      copyBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
          <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
        </svg>`;
    }, 1500);
  });

  actions.appendChild(speakBtn);
  actions.appendChild(copyBtn);
  div.appendChild(actions);

  divaMessages.scrollTop = divaMessages.scrollHeight;
  lastDivaResponse = text;
}

/* ─── CLEAR CHAT ─── */
function clearDivaChat() {
  divaHistory.length = 0;
  divaMessages.innerHTML = `
    <div class="diva-msg bot" data-i18n="diva_welcome">Hello! I'm <strong>DIVA</strong> - the Government of Mauritius' AI assistant. I'm here to help you with questions about our Digital Transformation Blueprint, AI strategy, and government services.<br><br>You can also <strong>speak to me</strong> - press the microphone button below and ask your question out loud.</div>
    <div class="diva-suggestions">
      <button class="diva-suggestion-btn" onclick="pickSuggestion(this)" data-i18n="diva_sug1">What is the Digital Transformation Blueprint?</button>
      <button class="diva-suggestion-btn" onclick="pickSuggestion(this)" data-i18n="diva_sug2">What does FAIR stand for in the AI Framework?</button>
      <button class="diva-suggestion-btn" onclick="pickSuggestion(this)" data-i18n="diva_sug3">How is AI used in Mauritius government services?</button>
    </div>
  `;
  // Re-apply translations to the new welcome message
  if (typeof applyTranslations === 'function') applyTranslations();
}

divaClear?.addEventListener('click', () => {
  if (confirm('Start a new conversation?')) {
    clearDivaChat();
  }
});

/* ─── SPEECH RECOGNITION ─── */
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
let recognition;
let isListening = false;

if (SpeechRecognition) {
  recognition = new SpeechRecognition();
  recognition.lang = navigator.language || 'en-GB';
  recognition.continuous = false;
  recognition.interimResults = true;
}

divaMic?.addEventListener('click', () => {
  if (!recognition) {
    addDivaMessage('Voice input is not supported in your browser. Please type your question.', 'bot');
    return;
  }
  if (isListening) {
    recognition.stop();
    return;
  }
  recognition.lang = navigator.language || 'en-GB';
  try {
    recognition.start();
  } catch (e) {
    console.warn('Speech recognition error:', e);
  }
});

if (recognition) {
  recognition.onstart = () => {
    isListening = true;
    divaMic.classList.add('listening');
    divaMic.setAttribute('aria-label', 'Listening… speak now. Press again to stop.');
    divaInput.placeholder = 'Listening…';
  };
  recognition.onend = () => {
    isListening = false;
    divaMic.classList.remove('listening');
    divaMic.setAttribute('aria-label', 'Speak to DIVA - press to start voice input');
    divaInput.placeholder = 'Type or speak your question…';
  };
  recognition.onresult = (event) => {
    let transcript = '';
    for (let i = event.resultIndex; i < event.results.length; i++) {
      transcript += event.results[i][0].transcript;
    }
    divaInput.value = transcript;
    if (event.results[event.results.length - 1].isFinal) {
      setTimeout(() => sendDivaMessage(), 400);
    }
  };
  recognition.onerror = (e) => {
    console.warn('Voice input error:', e.error);
    isListening = false;
    divaMic.classList.remove('listening');
    divaMic.setAttribute('aria-label', 'Speak to DIVA - press to start voice input');
    divaInput.placeholder = 'Type or speak your question…';
  };
}

/* ─── SPEAK TEXT (TTS) - shared by DIVA and used by screen reader for Kreol fallback ───
 *
 * Language mapping:
 *   en  → en-GB  (British English - used for both DIVA and screen reader)
 *   fr  → fr-FR
 *   km  → fr-FR  (Kreol Morisien has no native TTS; French is the closest match)
 *
 * Preferred voice priority (same list used by DIVA and screen reader):
 *   en-GB: Google UK English Female → Google UK English Male → Microsoft Hazel → Microsoft George
 *   fr-FR: Google Français → Microsoft Julie → Microsoft Hortense → Thomas
 */
const DIVA_LANG_MAP = { en: 'en-GB', fr: 'fr-FR', km: 'fr-FR' };
const DIVA_PREFERRED_VOICES = {
  en: ['Google UK English Female', 'Google UK English Male', 'Microsoft Hazel - English (United Kingdom)', 'Microsoft George - English (United Kingdom)', 'Daniel'],
  fr: ['Google Français', 'Microsoft Julie - French (France)', 'Microsoft Hortense - French (France)', 'Thomas'],
  km: ['Google Français', 'Microsoft Julie - French (France)', 'Microsoft Hortense - French (France)', 'Thomas']
};

function getBestDivaVoice(effectiveLang) {
  const voices = window.speechSynthesis ? window.speechSynthesis.getVoices() : [];
  const targetLocale = DIVA_LANG_MAP[effectiveLang] || 'en-GB';
  const prefs = DIVA_PREFERRED_VOICES[effectiveLang] || DIVA_PREFERRED_VOICES['en'];
  // Try preferred voices first
  for (const name of prefs) {
    const match = voices.find(v => v.name === name);
    if (match) return match;
  }
  // Fallback: first voice matching the locale prefix
  return voices.find(v => v.lang && v.lang.replace('_', '-').startsWith(targetLocale)) || null;
}

let currentDivaSpeakBtn = null;

function speakText(text, button) {
  if (!text) return;
  if (!window.speechSynthesis) {
    addDivaMessage('Text-to-speech is not supported in your browser.', 'bot');
    return;
  }

  // Same button clicked while speaking → stop
  if (currentDivaSpeakBtn === button && window.speechSynthesis.speaking) {
    window.speechSynthesis.cancel();
    currentDivaSpeakBtn = null;
    if (button) button.classList.remove('speaking');
    return;
  }

  // Stop any other ongoing speech first
  window.speechSynthesis.cancel();
  if (currentDivaSpeakBtn) {
    currentDivaSpeakBtn.classList.remove('speaking');
  }

  const utterance = new SpeechSynthesisUtterance(text);
  // For Kreol, fall back to French; for English always use en-GB
  const effectiveLang = currentLang === 'km' ? 'fr' : currentLang;
  const targetLocale = DIVA_LANG_MAP[effectiveLang] || 'en-GB';
  utterance.lang = targetLocale;
  utterance.rate = 1;
  utterance.pitch = 1;
  utterance.volume = 1;

  const bestVoice = getBestDivaVoice(effectiveLang);
  if (bestVoice) {
    utterance.voice = bestVoice;
    utterance.lang = bestVoice.lang;
  }

  currentDivaSpeakBtn = button;
  if (button) button.classList.add('speaking');

  utterance.onend = function() {
    if (currentDivaSpeakBtn) currentDivaSpeakBtn.classList.remove('speaking');
    currentDivaSpeakBtn = null;
  };
  utterance.onerror = function(e) {
    if (e.error !== 'canceled' && e.error !== 'interrupted') {
      if (currentDivaSpeakBtn) currentDivaSpeakBtn.classList.remove('speaking');
      currentDivaSpeakBtn = null;
    }
  };

  speechSynthesis.speak(utterance);
}

/* ─── TYPING INDICATOR ─── */
function showDivaTyping() {
  const wrap = document.createElement('div');
  wrap.className = 'diva-typing-dots';
  wrap.id = 'diva-typing';
  for (let i = 0; i < 3; i++) {
    const s = document.createElement('span');
    wrap.appendChild(s);
  }
  divaMessages.appendChild(wrap);
  divaMessages.scrollTop = divaMessages.scrollHeight;
}

function hideDivaTyping() {
  const el = document.getElementById('diva-typing');
  if (el) el.remove();
}

function setDivaLoading(on) {
  divaIsLoading = on;
  divaInput.disabled = on;
  divaSend.disabled = on;
  divaSend.style.opacity = on ? '0.5' : '1';
  divaSend.style.cursor = on ? 'not-allowed' : 'pointer';
}

/* ─── SEND MESSAGE ─── */
function pickSuggestion(btn) {
  event.stopPropagation();
  divaInput.value = btn.textContent.trim();
  const suggestions = document.querySelector('.diva-suggestions');
  if (suggestions) suggestions.remove();
  sendDivaMessage();
}

async function sendDivaMessage() {
  const msg = divaInput.value.trim();
  if (!msg || divaIsLoading) return;
  addDivaMessage(msg, 'user');
  divaInput.value = '';
  divaHistory.push({ role: 'user', content: msg });
  setDivaLoading(true);
  showDivaTyping();

  try {
    const response = await fetch(WORKER_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ model: 'claude-sonnet-4-20250514', max_tokens: 400, messages: divaHistory })
    });
    if (!response.ok) throw new Error('API error ' + response.status);
    const data = await response.json();
    if (data.error) throw new Error(data.error.message || 'API error');
    const reply = (data.content || []).filter(b => b.type === 'text').map(b => b.text).join('\n').trim();
    if (!reply) throw new Error('Empty response');
    divaHistory.push({ role: 'assistant', content: reply });
    hideDivaTyping();
    await typeDivaMessage(reply);
  } catch (err) {
    hideDivaTyping();
    divaHistory.pop();
    addDivaMessage("Sorry, I'm having trouble connecting right now. Please try again in a moment.", 'bot');
    console.error('DIVA API error:', err);
  } finally {
    setDivaLoading(false);
    if (!divaInput.disabled) divaInput.focus();
  }
}

/* ─── DIVA PANEL TOGGLE ─── */
divaTrigger.addEventListener('click', () => {
  const open = divaPanel.classList.toggle('open');
  divaTrigger.setAttribute('aria-expanded', open.toString());
  if (open && divaInput) setTimeout(() => divaInput.focus(), 100);
});

divaClose.addEventListener('click', () => {
  divaPanel.classList.remove('open');
  divaTrigger.setAttribute('aria-expanded', 'false');
});

divaSend.addEventListener('click', sendDivaMessage);
divaInput.addEventListener('keydown', e => { if (e.key === 'Enter') sendDivaMessage(); });

openDiva?.addEventListener('click', (e) => {
  e.stopPropagation();
  divaPanel.classList.add('open');
  divaTrigger.setAttribute('aria-expanded', 'true');
  if (divaInput) setTimeout(() => divaInput.focus(), 150);
  divaMessages.scrollTop = divaMessages.scrollHeight;
});

document.addEventListener('click', function (e) {
  const divaWidget = document.getElementById('divaWidget');
  if (divaPanel && divaPanel.classList.contains('open') && divaWidget && !divaWidget.contains(e.target)) {
    divaPanel.classList.remove('open');
    divaTrigger.setAttribute('aria-expanded', 'false');
  }
});

document.getElementById('readEnBooklet')?.addEventListener('click', () => window.open('/booklet/aie', '_blank'));
document.getElementById('readKmBooklet')?.addEventListener('click', () => window.open('/booklet/aim', '_blank'));
document.getElementById('browseSolutionsBtn')?.addEventListener('click', () => window.open('https://aimarketplace.govmu.org/', '_blank', 'noopener'));
document.getElementById('listSolutionBtn')?.addEventListener('click', () => window.open('https://aimarketplace.govmu.org/search', '_blank', 'noopener'));
(function(){
  const modal=document.getElementById('videoModal');
  const modalVideo=document.getElementById('modalVideo');
  const modalTitle=document.getElementById('modalVideoTitle');
  const closeModalBtn=document.getElementById('closeModalBtn');
  const trackEn=document.getElementById('track-en');
  const trackFr=document.getElementById('track-fr');
  const trackKm=document.getElementById('track-km');
  if(!modal)return;

  function setSubtitleTracks() {
    const tracks = modalVideo.textTracks;
    for (let i = 0; i < tracks.length; i++) {
      const track = tracks[i];
      if (track.kind === 'subtitles') {
        if (track.language === 'en' && currentLang === 'en') {
          track.mode = 'showing';
        } else if (track.language === 'fr' && currentLang === 'fr') {
          track.mode = 'showing';
        } else if (track.language === 'mfe' && currentLang === 'km') {
          track.mode = 'showing';
        } else {
          track.mode = 'disabled';
        }
      }
    }
  }

  function openVideoModal(src,title){
    modalVideo.pause();
    // Caption files use the source media set's naming: "videoN.vtt" is French
    // and "videoNe.vtt" is English. The video files are "video0N.mp4", so the
    // leading zero has to be dropped to find the matching captions. There is
    // no Kreol caption file in the source set, so that track is left empty.
    const baseName = src.split('/').pop().replace('.mp4', '');
    const captionBase = baseName.replace(/^video0*/, 'video');
    if (trackEn) trackEn.src = ASSET_BASE + '/captions/' + captionBase + 'e.vtt';
    if (trackFr) trackFr.src = ASSET_BASE + '/captions/' + captionBase + '.vtt';
    if (trackKm) trackKm.src = '';
    modalVideo.src = src;
    modalVideo.load();
    modalVideo.addEventListener('loadedmetadata', function onLoaded() {
      setSubtitleTracks();
      modalVideo.removeEventListener('loadedmetadata', onLoaded);
    });
    modalTitle.innerText = title || 'Video Player';
    modal.classList.add('active');
    modalVideo.play().catch(()=>{});
  }

  function closeVideoModal(){
    modal.classList.remove('active');
    modalVideo.pause();
    modalVideo.src = '';
    if (trackEn) trackEn.src = '';
    if (trackFr) trackFr.src = '';
    if (trackKm) trackKm.src = '';
  }

  document.querySelectorAll('.video-item').forEach(item => {
    const src = item.getAttribute('data-video-src');
    const title = item.getAttribute('data-video-title');
    if (src) item.addEventListener('click', e => { e.preventDefault(); e.stopPropagation(); openVideoModal(src, title); });
  });

  if (closeModalBtn) closeModalBtn.addEventListener('click', closeVideoModal);
  modal.addEventListener('click', e => { if (e.target === modal) closeVideoModal(); });

  document.addEventListener('keydown', e => {
    if (!modal.classList.contains('active')) return;
    switch (e.key) {
      case 'Escape': closeVideoModal(); break;
      case ' ': e.preventDefault(); if (modalVideo.paused) modalVideo.play(); else modalVideo.pause(); break;
      case 'ArrowRight': modalVideo.currentTime += 10; break;
      case 'ArrowLeft': modalVideo.currentTime -= 10; break;
    }
  });

  window.addEventListener('aiunit-lang-changed', function() {
    if (modal.classList.contains('active')) {
      setSubtitleTracks();
    }
  });
})();

let currentAudio = null;
let currentButton = null;
let currentPlayer = null;
let currentAudioFile = null;
let isDragging = false;

document.querySelectorAll('.btn-listen-audio').forEach(button => {
    button.addEventListener('click', function () {

        const audioFile = this.dataset.audio || this.dataset.audioSrc;

        if (!audioFile) return;

        const player = this.closest('.doc-card').querySelector('.audio-player-inline');

        if (!player) return;

        if (currentAudio && currentAudioFile === audioFile) {
            player.classList.add('active');

            if (currentAudio.paused) {
                currentAudio.play();
                setButtonPause(this);
            } else {
                currentAudio.pause();
                setButtonPlay(this);
            }

            return;
        }

        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;

            if (currentButton) {
                setButtonPlay(currentButton);
            }

            resetPlayer(currentPlayer);
        }

        currentAudio = new Audio(audioFile);
        currentAudioFile = audioFile;
        currentButton = this;
        currentPlayer = player;

        player.classList.add('active');

        currentAudio.addEventListener('loadedmetadata', updateAudioDisplay);
        currentAudio.addEventListener('timeupdate', updateAudioDisplay);

        currentAudio.addEventListener('ended', function () {
            currentAudio.currentTime = 0;
            setButtonPlay(currentButton);
            resetPlayer(currentPlayer);
        });

        currentAudio.play();
        setButtonPause(this);
    });
});

document.querySelectorAll('.audio-progress-bar').forEach(progressBar => {

    progressBar.addEventListener('click', function (event) {
        seekAudio(this, event);
    });

    progressBar.addEventListener('mousedown', function (event) {
        isDragging = true;
        seekAudio(this, event);
    });
});

document.addEventListener('mousemove', function (event) {

    if (!isDragging || !currentAudio || !currentPlayer) return;

    const progressBar =
        currentPlayer.querySelector('.audio-progress-bar');

    seekAudio(progressBar, event);
});

document.addEventListener('mouseup', function () {
    isDragging = false;
});

function seekAudio(progressBar, event) {

    if (!currentAudio || isNaN(currentAudio.duration)) return;

    const player = progressBar.closest('.audio-player-inline');

    if (player !== currentPlayer) return;

    const rect = progressBar.getBoundingClientRect();

    let percentage =
        (event.clientX - rect.left) / rect.width;

    percentage = Math.max(0, Math.min(1, percentage));

    currentAudio.currentTime =
        percentage * currentAudio.duration;

    updateAudioDisplay();
}

document.addEventListener('keydown', function (event) {

    if (!currentAudio || !currentPlayer || !currentButton) return;

    if (
        event.target.tagName === 'INPUT' ||
        event.target.tagName === 'TEXTAREA'
    ) {
        return;
    }

    if (event.key === 'ArrowRight') {
        event.preventDefault();

        currentAudio.currentTime = Math.min(
            currentAudio.currentTime + 10,
            currentAudio.duration
        );

        updateAudioDisplay();
    }

    if (event.key === 'ArrowLeft') {
        event.preventDefault();

        currentAudio.currentTime = Math.max(
            currentAudio.currentTime - 10,
            0
        );

        updateAudioDisplay();
    }

    if (event.code === 'Space') {
        event.preventDefault();

        if (currentAudio.paused) {
            currentAudio.play();
            setButtonPause(currentButton);
        } else {
            currentAudio.pause();
            setButtonPlay(currentButton);
        }
    }
});

function updateAudioDisplay() {

    if (!currentAudio || !currentPlayer || isNaN(currentAudio.duration)) return;

    const playedEl = currentPlayer.querySelector('.audio-played');
    const leftEl = currentPlayer.querySelector('.audio-left');
    const progressFill = currentPlayer.querySelector('.audio-progress-fill');

    const playedTime = currentAudio.currentTime;
    const leftTime = currentAudio.duration - currentAudio.currentTime;

    if (playedEl) {
        playedEl.textContent = formatTime(playedTime) + ' played';
    }

    if (leftEl) {
        leftEl.textContent = formatTime(leftTime) + ' left';
    }

    if (progressFill) {
        const percent =
            (currentAudio.currentTime / currentAudio.duration) * 100;

        progressFill.style.width = percent + '%';
    }
}

function resetPlayer(player) {

    if (!player) return;

    const playedEl = player.querySelector('.audio-played');
    const leftEl = player.querySelector('.audio-left');
    const progressFill = player.querySelector('.audio-progress-fill');

    if (playedEl) {
        playedEl.textContent = '0:00 played';
    }

    if (leftEl) {
        leftEl.textContent = '0:00 left';
    }

    if (progressFill) {
        progressFill.style.width = '0%';
    }
}

function setButtonPlay(button) {
    const label = button.querySelector('span');
    if (label) label.textContent = 'Play';
    button.classList.remove('is-playing');
    button.setAttribute('aria-pressed', 'false');
}

function setButtonPause(button) {
    const label = button.querySelector('span');
    if (label) label.textContent = 'Pause';
    button.classList.add('is-playing');
    button.setAttribute('aria-pressed', 'true');
}

function formatTime(seconds) {

    if (isNaN(seconds)) {
        return '0:00';
    }

    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);

    return `${mins}:${String(secs).padStart(2, '0')}`;
}
