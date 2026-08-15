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
    nav_action: "AI in Action", nav_framework: "Framework", nav_about: "About Us", nav_contact: "Contact Us", nav_ailab: "AI Lab", nav_highlights: "Highlights", accessibility: "Accessibility",
    hero_badge: "Ministry of ICT, Communication & Innovation", hero_title_prefix: "Where", hero_title_suffix: "Meets Impact", hero_subtitle: "Welcome to the AI Unit of Mauritius - your guide to how artificial intelligence is making public services smarter, faster, and fairer for every citizen.",
    hero_cta_marketplace: "Explore Regional AI Marketplace", hero_cta_learn: "Learn More", badge_fair: "FAIR Guidelines", badge_ai4all: "AI4ALL Inclusion", badge_marketplace: "AI Marketplace", badge_smart: "Smart Services", badge_ppp: "PPP Adoption", badge_privacy: "Data Privacy",
    ailab_eyebrow: "AI Unit Facility", ailab_title1: "Explore the", ailab_title2: "AI Lab",
    ailab_desc: "A space at the AI Unit for learning, exploring and working with artificial intelligence - available primarily to students from colleges and universities, researchers, educators and members of the public.",
    ailab_tag1: "College & university students", ailab_tag2: "Researchers", ailab_tag3: "Educators", ailab_tag4: "Members of the public",
    ailab_cta: "Explore the AI Lab", ailab_note: "Sessions can be booked online.",
    // Keeps the <time> element: applyTranslations() assigns innerHTML, so the
    // markup survives a language switch. Update this date together with the
    // announcement in pages/ai-lab.php and pages/home.php.
    ailab_launch_flag: "Launching <time datetime=\"2026-08-20\">20 August 2026</time>",
    about_eyebrow: "Who We Are", about_title_prefix: "Driving Mauritius", about_title_suffix: "AI Future", about_mission_quote: "\"Mauritius is committed to becoming a smart, inclusive and future-ready nation - where every citizen benefits from responsible AI.\"",
    about_body1: "The AI Unit is the dedicated body established under the Ministry of Information Technology, Communication and Innovation (MITCI) to lead Mauritius' artificial intelligence journey. We coordinate AI governance, promote innovation, and ensure that technology serves all Mauritians - fairly and transparently.",
    about_body2: "We are the strategic vehicle for MITCI's <strong>Digital Transformation 2025-2029</strong> blueprint - a bold roadmap to modernise public services and position Mauritius as a leading AI nation in Africa.",
    about_body3: "Whether you are a citizen curious about AI, a business exploring new solutions, or a student building your future - the AI Unit is here for you.",
    diva_title: "Meet DIVA - Digital Interactive Virtual Assistant", diva_desc: "DIVA is a prototype assistant that answers questions based on three key documents: the Digital Transformation Blueprint, the AI Strategy and the FAIR Guidelines. Try asking: “What does FAIR stand for in the AI Framework?”", diva_chat: "Chat with DIVA",
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
    library_instructions: "Each document offers Download, View Online and Listen. Press a Listen button to have that document read aloud; press the same button again to pause. The progress bar below it shows how much has played.",
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
    team_tab1: "Mr. Ramakrishna", team_tab2: "Dr. Mohabeer", team_tab3: "Mr. Ramdhony",
    team_rama_tag: "Alignment & Safety", team_rama_role: "Head - AI Unit", team_rama_quote: "Ramakrishna Mudaliar serves as Head of the AI Unit, where he spearheads the country's people-centric approach to leverage Artificial Intelligence for responsible development and implementation at a national scale. He holds Master's Degrees from the University of Montpellier and the University of Manipal. With more than two decades of experience across both the private technology sector and public service, Ramakrishna brings a well-rounded perspective that bridges innovation with real-world implementation.",
    rama_stat1: "Years Experience", rama_stat2: "Master's Degrees", rama_stat3: "AI Unit Founded",
    // Mr. Ramdoyal's own details, as supplied by the AI Unit. These strings win
    // over the markup in pages/home.php - applyTranslations() overwrites it on
    // load - so the two copies must be edited together.
    team_tab4: "Mr. Ramdoyal",
    team_yudhaveer_tag: "AI & Digital Innovation", team_yudhaveer_role: "AI Expert", team_yudhaveer_quote: "Yudhaveer Vaibhav Ramdoyal serves as an AI Expert at the AI Unit, supporting the national vision to drive responsible digital transformation and modern public service delivery. He holds a Bachelor of Science (Hons) in Computer Science from the University of Mauritius and earned a Google AI Professional Certificate. Drawing on a strong background in data engineering and technical leadership across the corporate and financial technology sectors, Yudhaveer bridges core data capabilities with practical AI implementation. His expertise spans ICT strategy, enterprise transformation, and technology governance, positioning him to effectively advance national priorities in Artificial Intelligence and digital innovation.",
    yudhaveer_stat1: "Computer Science (Hons)", yudhaveer_stat2: "AI Professional Certificate", yudhaveer_stat3: "Corporate & Financial Sectors",
    team_heman_tag: "Architecture & Scale", team_heman_role: "AI Expert", team_heman_quote: "Dr. Heman Mohabeer is an AI researcher, strategist, and inventor serving as an AI Expert at the AI Unit of the Government of Mauritius. With a PhD in Artificial Intelligence and Machine Learning, he supports national AI policy development, advises on digital transformation, and promotes resilient, explainable, and locally owned AI systems.",
    heman_stat1: "AI & Machine Learning", heman_stat2: "Years Experience", heman_stat3: "Regional AI Leader",
    team_ruben_tag: "Digital Transformation", team_ruben_role: "AI Expert", team_ruben_quote: "Ruben Ramdhony is a Digital Transformation Executive and former Chief Information Officer with over 20 years of enterprise experience across Australia and Mauritius. He holds an MBA from Macquarie Business School, Australia. He is currently serving in an AI Expert capacity, translating policy into working systems, governance into practice, and strategy into measurable outcomes across Government.",
    ruben_stat1: "Years Enterprise", ruben_stat2: "Former Chief Info. Officer", ruben_stat3: "Cross-Border Experience",
    contact_eyebrow: "Get in Touch", contact_title1: "We're Here", contact_title2: "for You", contact_desc: "Have questions about AI in Mauritius? Want to partner with us or learn more about our programmes? Reach out - we welcome every question.",
    // applyTranslations() assigns this with innerHTML, so the \n line breaks
    // this string used to carry collapsed into ordinary spaces and rendered as
    // one long run of text in the narrow contact column. Kept to a single
    // short line, which wraps on its own when the column is too narrow.
    contact_address_title: "Address", contact_address_text: "Cyber Tower 2, Level 6, Ebene",
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
    // Screen-reader-only strings for the chat transcript's live region -
    // see #divaMessages / #divaStatus in includes/diva-widget.php.
    diva_speaker_bot: "DIVA said:", diva_speaker_user: "You said:", diva_typing: "DIVA is typing…",
    a11y_title: "Accessibility", a11y_subtitle: "Adjust this website to your needs", a11y_reset: "Reset",
    a11y_sr_title: "Screen Reader - Read this page aloud", a11y_read_btn_label: "Read page aloud", a11y_read_btn_hint: "Click to start · Space to pause / resume",
    a11y_kbd_hint: "Keyboard shortcuts (while reading)", a11y_kbd_playpause: "Play / Pause", a11y_kbd_stop: "Stop", a11y_kbd_slower: "Slower", a11y_kbd_faster: "Faster",
    a11y_voice_default: "Default voice", a11y_speed_label: "Speed:", a11y_profiles_label: "Quick Profiles",
    a11y_profile_vision: "Low Vision", a11y_profile_motor: "Motor", a11y_profile_dyslexia: "Dyslexia", a11y_profile_cognitive: "Cognitive", a11y_profile_elderly: "Senior",
    a11y_textsize: "Text Size", a11y_colour: "Colour & Display", a11y_colour_normal: "Normal", a11y_colour_highcontrast: "High Contrast", a11y_colour_dark: "Dark", a11y_colour_grayscale: "Greyscale", a11y_colour_negative: "Negative",
    a11y_toggle_links: "Highlight Links", a11y_toggle_images: "Hide Images", a11y_toggle_motion: "Stop Animations",
    a11y_reading: "Reading & Focus", a11y_toggle_dyslexia: "Dyslexia-Friendly Font", a11y_toggle_readguide: "Reading Guide Line", a11y_toggle_spacing: "Wider Letter Spacing", a11y_toggle_focus: "Bold Focus Outline",
    a11y_navigation: "Navigation", a11y_toggle_cursor: "Large Mouse Cursor", a11y_toggle_keyboard: "Show Keyboard Shortcuts",
    a11y_kbd_open: "Open panel:", a11y_kbd_close: "Close:", a11y_kbd_reset: "Reset:",

    // ─── SIMPLE LANGUAGE MODE: UI strings ───
    simple_toggle: "Simple language",
    simple_savings_note: "Saves about {n} min of reading",

    // ─── SIMPLE LANGUAGE MODE: Tier 1 (site copy) summaries ───
    // Final strings from Kate (simple-summaries-en.js), pasted verbatim -
    // not Claude drafts. Ready to review and ship as-is. Word counts are
    // Kate's own, shown as (full → simple).
    hero_subtitle_s: "Welcome to the AI Unit of Mauritius. We explain how AI is making public services better for everyone.", // (24 → 19)
    about_mission_quote_s: "\"Mauritius wants to be a smart and fair country, where AI helps every citizen.\"", // (19 → 15)
    about_body1_s: "The AI Unit leads Mauritius' work on artificial intelligence. We are part of the Ministry of ICT. We make sure AI is fair and open for everyone.", // (68 → 27)
    about_body2_s: "We carry out the Ministry's <strong>Digital Transformation plan for 2025 to 2029</strong>. The goal is better public services and a strong AI sector.", // (29 → 25)
    about_body3_s: "The AI Unit is here for everyone: citizens, businesses and students.", // (26 → 11)
    vision_text_s: "We want Mauritius to lead the region in safe, trusted AI that improves the economy, public services and daily life.", // (25 → 20)
    mission_text_s: "We lead how AI is built and managed in Mauritius. Every system must be safe, fair and open, and must help people and businesses.", // (29 → 24)
    diva_desc_s: "DIVA is a test assistant. It answers questions using three of our main documents.", // (23 → 13)
    action_desc_s: "See how AI is already helping people in Mauritius, from keeping children safe online to making technology easier to use.", // (22 → 20)
    action_card1_desc_s: "A booklet and four videos about online violence against children: how to spot it, how to stop it, and where to get help.", // (18 → 19)
    action_card2_desc_s: "A booklet that explains what AI is and how it can help you. Written for everyone.", // (15 → 16)
    marketplace_desc_s: "The Regional AI Marketplace connects people who build AI with people who need it, in Mauritius and nearby countries.", // (27 → 19)
    marketplace_card1_desc_s: "Add your AI product so government and businesses can find it.", // (12 → 10)
    marketplace_card2_desc_s: "Find checked AI tools for your business.", // (9 → 7)
    marketplace_card3_desc_s: "Find trusted AI tools to improve public services.", // (10 → 8)
    principles_desc_s: "We are a small team working on AI for Mauritius. We put people first and work in an honest, careful way.", // (25 → 22)
    team_desc_s: "Meet the people who lead AI work in Mauritius.", // (10 → 8)
    contact_desc_s: "Have a question, or want to work with us? Get in touch. We welcome every question.", // (23 → 16)

    // ─── SIMPLE LANGUAGE MODE: Tier 2 (policy-derived) summaries ───
    // *** PENDING MINISTRY REVIEW — DO NOT PUBLISH ***
    // Final drafts from Kate (simple-summaries-en.js), pasted verbatim - not
    // Claude drafts. Every factual claim, commitment, date and figure is
    // stated (by Kate) to have been preserved; still requires Ministry
    // sign-off before publication per CLAUDE.md section 5 - a plain-language
    // version of national policy that shifts meaning is a substantive
    // problem, not a style question. Do not merge to production until that
    // sign-off happens. The Task 14 fallback means the site works correctly
    // with only Tier 1 shipped if these are held back.
    framework_desc_s: "Six areas guide how Mauritius builds and manages AI, so everyone shares the benefits.", // (22 → 14)
    // FLAG FOR MINISTRY REVIEW: this summary says outreach runs "in English,
    // French and Kreol Morisien," but the current full-text dim3_text on
    // this site says only "available in English" (French/Kreol are not
    // currently live - see the site's own accessibility statement, which
    // says language switching "is still being completed"). Pasted verbatim
    // per instruction, not silently corrected - flagging so this specific
    // claim gets checked against reality before sign-off, not just against
    // the source document.
    dim1_text_s: "Mauritius needs fast internet, modern data centres, safe cloud services and strong cybersecurity. This lets AI reach every part of the country.", // (30 → 23)
    dim2_text_s: "We support startups, universities and new ideas, so people can build AI that solves real problems here.", // (27 → 18)
    dim3_text_s: "AI should reach every Mauritian, whatever their age, home district, language or schooling. We run programmes in every district, in English, French and Kreol Morisien.", // (52 → 30)
    dim4_text_s: "Clear rules keep AI safe and fair. The FAIR Guidelines set the standard. Our Data Protection Act follows international practice, including GDPR.", // (44 → 24)
    dim5_text_s: "Government, business and citizens work together on shared projects, so AI matches what people in Mauritius actually need.", // (40 → 22)
    dim6_text_s: "We work with international bodies, African countries and leading technology nations, and bring what we learn back to Mauritius.", // (36 → 21)
    library_desc_s: "Our four main documents: the Blueprint, the AI Strategy, the FAIR Guidelines and the AI Playbook.", // (19 → 17)
    doc1_desc_s: "The national plan for digital government services. Four main areas, and how they are managed.", // (9 → 14)
    doc2_desc_s: "Mauritius' first national AI plan: how AI is managed, taken up, and used in different sectors.", // (11 → 16)
    doc3_desc_s: "Guidelines for responsible AI, built on four ideas: fairness, accountability, inclusion and responsibility.", // (13 → 15)
    doc4_desc_s: "A step-by-step guide for AI projects in government, from the first small test to full use.", // (12 → 17)
    principle1_text_s: "AI must treat all citizens equally. We check systems for bias at every stage, so no community is put at a disadvantage.", // (33 → 24)
    principle2_text_s: "Every AI decision must be explainable and open to checking by official bodies. This is a requirement, not an option.", // (31 → 22)
    principle3_text_s: "Technology should serve everyone. We build AI that works across languages, abilities and locations, and we work openly and honestly.", // (40 → 22)
    principle4_text_s: "We work carefully and take responsibility. We learn as we go, and keep the long-term wellbeing of citizens at the centre.", // (34 → 22)

    // ─── SIMPLE LANGUAGE MODE: AI Lab page (pages/ai-lab.php) ───
    // Claude drafts, not yet reviewed by Kate/the Ministry - unlike the Tier
    // 1/2 strings above. Every fact from the full text is preserved (Government
    // approval, Ministry of ICT, the STEMpower Inc. partnership, who the lab is
    // for, and how booking works); only the wording is shortened. Flag for
    // review before treating as final.
    ailab_page_subtitle_s: "A place where students and innovators can learn and try out AI.", // (18 → 12)
    ailab_page_about1_s: "The Government approved the AI Lab as an Innovation Lab. The Ministry of ICT set it up. Here, you build, test and try AI yourself, instead of just reading about it.", // (42 → 29)
    ailab_page_about2_s: "The Ministry is partnering with <strong>STEMpower Inc.</strong>, a US non-profit. STEMpower supplies the lab's equipment and helps set it up.", // (27 → 20)
    ailab_page_who_s: "The AI Lab is open to secondary and university students, technopreneurs and innovators, to learn and experiment with AI.", // (23 → 19)
    ailab_page_book_intro_s: "Pick a date and time below, then fill in your details to book. Calendly handles booking and emails you a confirmation.", // (27 → 19)

    // ─── SIMPLE LANGUAGE MODE: Highlights page (pages/highlights.php) ───
    // Claude drafts, not yet reviewed - same caveat as the AI Lab strings
    // above. Covers only the substantial narrative paragraphs (hero lead,
    // section ledes, project descriptions); short list items, card blurbs
    // and figure captions were left as-is, matching how the homepage never
    // simplified its own tags/chips/short list text either.
    // Base (full-text) entries for the hl_ keys above. Without these,
    // applyTranslations() had nothing to fall back to when simple mode was
    // switched off, so it left the _s text on screen instead of restoring
    // the original paragraph - simple mode looked like it "didn't work"
    // because turning it off never actually turned it off.
    hl_hero_lead: "Over a ten-week industrial attachment at the AI Unit, hosted at the Mauritius Emerging Technologies Council in Ebene, a team of university interns redesigned and rebuilt aim.govmu.org, the official government portal for artificial intelligence in Mauritius. The work spanned front-end development, WCAG accessibility, speech synthesis and the integration of the DIVA chatbot. This page is a record of what was built.",
    hl_overview_lede: "The AI Unit had no permanent development staff, so its active projects were carried forward by the intern team. The placement began with a single intern analysing the existing portal and setting up the project; by the later weeks it had grown into a team of several interns from different Universities working in one shared codebase.",
    hl_revamp_lede: "aim.govmu.org is the official Mauritian government portal for artificial intelligence, covering news, policies, events and resources.",
    hl_revamp_analysis: "The work began with a full review of the existing portal, guided by the AI Unit's aspirations for its redevelopment. That analysis fed a detailed wireframe covering the home page, the navigation structure, the key content sections and the placement of interactive features such as the accessibility tool, and the chatbot. Once the wireframe was reviewed and approved by the mentor, front-end development began.",
    hl_revamp_kanban: "Project management for the portal team was run on a Kanban workflow, introduced in Week 2 after guidance from the mentor on Agile practice. It began as a physical board at the workplace and, at the mentor's recommendation, moved to a digital Jira board that the whole intern team adopted as it grew. Tasks were grouped into Epics and Sub-Epics matching the portal's features.",
    hl_revamp_github: "GitHub served as the team's version control platform, with a shared repository that let all the interns work concurrently on different features without conflicts in the code.",
    hl_diva_lede: "DIVA is an AI-powered assistant integrated into the portal to answer questions about artificial intelligence in Mauritius, the content of the portal and government AI resources. It is a prototype, grounded in three key documents: the Digital Transformation Blueprint, the AI Strategy and the FAIR Guidelines.",
    hl_diva_entry: "Users reach DIVA through two entry points. A dedicated section of the portal introduces the assistant and opens it with a Chat with DIVA button, and a floating chatbot widget stays available across the portal, so a conversation can be started or continued from any page without leaving the current content.",
    hl_diva_speech: "The Web Speech Synthesis API, a native browser API that turns text into speech without any third-party software, was integrated into the portal. Activated with a single control, it reads the content of DIVA's response aloud.",
    hl_a11y_lede: "In Week 3 the mentor raised the bar: the portal had to meet WCAG 2.2, the international guidelines for making web content usable by people with visual, hearing, physical, cognitive and other disabilities. Beyond meeting the guidelines, the interns built an accessibility toolbar into the portal itself, designed on the principle of user choice: each visitor decides how they want to use the site.",
    hl_a11y_standards: "Meeting WCAG 2.2 meant independent research into the A, AA and AAA conformance levels, testing the portal with browser-based accessibility evaluation tools, and reworking front-end code that had already been written. It was a practical lesson in building accessibility in from the start rather than retrofitting it.",

    hl_hero_lead_s: "University interns spent ten weeks at the AI Unit, hosted by the Mauritius Emerging Technologies Council in Ebene. They rebuilt aim.govmu.org, the government's AI portal. The work covered front-end development, accessibility, speech synthesis and the DIVA chatbot. This page shows what they built.", // (62 → 44)
    hl_overview_lede_s: "The AI Unit had no permanent developers, so interns carried the work forward. It began with one intern reviewing the portal and setting up the project. By the later weeks, several interns from different universities were working together in one shared codebase.", // (58 → 43)
    hl_revamp_lede_s: "aim.govmu.org is the government's AI portal for Mauritius, with news, policies, events and resources.", // (17 → 16)
    hl_revamp_analysis_s: "The team began by reviewing the existing portal, guided by the AI Unit's goals for it. That review shaped a detailed wireframe covering the home page, navigation, key content and features like the accessibility tool and chatbot. Once the mentor approved the wireframe, front-end development began.", // (65 → 48)
    hl_revamp_kanban_s: "The team managed the project with a Kanban workflow, introduced in Week 2 on the mentor's advice. It started as a physical board, then moved to a digital Jira board as the team grew. Tasks were grouped into Epics and Sub-Epics matching the portal's features.", // (65 → 46)
    hl_revamp_github_s: "GitHub was the team's version control platform. A shared repository let all the interns work on different features at the same time without conflicts.", // (29 → 25)
    hl_diva_lede_s: "DIVA is an AI assistant built into the portal. It answers questions about AI in Mauritius, the portal's content, and government AI resources. It is a prototype, based on three key documents: the Digital Transformation Blueprint, the AI Strategy and the FAIR Guidelines.", // (50 → 45)
    hl_diva_entry_s: "Users can reach DIVA in two ways. A section of the portal introduces DIVA with a Chat with DIVA button. A floating chatbot widget also stays on screen everywhere, so you can start or continue a chat from any page.", // (52 → 40)
    hl_diva_speech_s: "The team added the Web Speech Synthesis API, a browser feature that turns text into speech with no extra software. One button press reads DIVA's answer aloud.", // (35 → 27)
    hl_a11y_lede_s: "In Week 3, the mentor set a new goal: the portal had to meet WCAG 2.2, the international guidelines for making websites usable by people with visual, hearing, physical, cognitive and other disabilities. The interns also built an accessibility toolbar into the portal, so every visitor can choose how they want to use the site.", // (65 → 53)
    hl_a11y_standards_s: "Meeting WCAG 2.2 meant researching the A, AA and AAA conformance levels, testing the portal with browser accessibility tools, and reworking code that was already written. It taught the team that building accessibility in from the start is easier than adding it later.", // (47 → 42)

    // Remaining highlights.php paragraphs - previously hardcoded, not wired to
    // Simple Language at all. Short technical labels (tech-grid, steps) are
    // already plain, so they carry no _s variant and fall back to the full text.
    hl_hero_photo_caption: "The intern team at the AI Unit, METC, during the May to July 2026 attachment",

    hl_ov_tick1: "A flat, collaborative structure with the mentor as the single source of technical guidance, and work coordinated through a shared Kanban board.",
    hl_ov_tick1_s: "One mentor gave technical guidance. The team worked together and tracked tasks on a shared Kanban board.",
    hl_ov_tick2: "New interns were onboarded onto the existing code and workflow, features were divided across the team, and pieces like the PDF-to-audio converter were built and integrated by different interns working together.",
    hl_ov_tick2_s: "New interns learned the existing code and workflow fast. Work was split across the team, and features like the PDF-to-audio tool were built together.",
    hl_ov_tick3: "WCAG standards, the Web Speech API and Jira were all learned through self-directed research and then applied directly to the portal, with the mentor guiding rather than handing over solutions.",
    hl_ov_tick3_s: "Interns taught themselves WCAG, the Web Speech API and Jira, then used them on the portal. The mentor guided but did not hand over solutions.",
    hl_ov_tick4: "The portal serves a national audience, and the work was presented to the Ministry of Technology and to the Electoral Commission of Mauritius during the attachment.",
    hl_ov_tick4_s: "The portal serves the whole country. The team presented it to the Ministry of Technology and the Electoral Commission of Mauritius.",

    hl_revamp_screenshot_caption: "The rebuilt Framework Library. The Listen buttons are the PDF-to-audio feature: the AI Strategy, FAIR Guidelines, Digital Blueprint and AI Playbook can be read aloud.",
    hl_revamp_screenshot_caption_s: "The rebuilt Framework Library page. The Listen buttons read the AI Strategy, FAIR Guidelines, Digital Blueprint and AI Playbook aloud.",
    hl_revamp_step1: "Review of aim.govmu.org: styling, mobile-friendliness, accessibility gaps and missing features.",
    hl_revamp_step2: "A detailed wireframe of the home page, navigation and feature placement, approved before any code.",
    hl_revamp_step3: "A consistent design language built on typography, colour schemes and spacing.",
    hl_revamp_step4: "A fully responsive layout built with CSS Flexbox and Grid.",
    hl_revamp_spec_frontend: "Built with HTML5, CSS3 and JavaScript (ES6+) without any front-end framework, so the codebase stays easy to maintain for future developers.",
    hl_revamp_spec_frontend_s: "Built with HTML5, CSS3 and JavaScript, with no framework, so it stays easy to maintain.",
    hl_revamp_spec_backend: "Python with FastAPI and PostgreSQL was agreed as the back-end stack, with API design patterns and database schema research under way at the end of the attachment.",
    hl_revamp_spec_backend_s: "Python, FastAPI and PostgreSQL were chosen for the back end. API design and database planning began near the end.",
    hl_revamp_spec_responsive: "A fully responsive layout using CSS Flexbox and Grid, with a consistent scheme of typography, colour and spacing.",
    hl_revamp_spec_responsive_s: "A responsive layout using CSS Flexbox and Grid, with consistent typography, colour and spacing.",
    hl_revamp_kanban_caption: "The physical Kanban board that came first, before the workflow moved to Jira",

    hl_diva_entry_caption: "The invitation to start a conversation, placed alongside the documents themselves",
    hl_diva_feature_assistant: "Answers questions on AI in Mauritius, the portal's content and government AI resources, drawing on the four published documents.",
    hl_diva_feature_assistant_s: "Answers questions on AI in Mauritius and the portal's content, based on four published documents.",
    hl_diva_feature_integration: "The first integration attempt failed against the portal's Content Security Policy. The conflict was diagnosed through browser developer tools and resolved with a CSP configuration that permits the required cross-origin calls while keeping the portal secure.",
    hl_diva_feature_integration_s: "The chatbot first failed to load because of the portal's security settings. The team found the problem using browser tools and fixed it safely.",
    hl_diva_speech_caption: "The conversation view, with read-aloud, copy and voice input",
    hl_diva_feature_conversation: "Questions can be typed or spoken, answers can be read aloud or copied, and the conversation can be cleared and restarted at any point.",
    hl_diva_feature_conversation_s: "Questions can be typed or spoken. Answers can be read aloud or copied, and the chat can be cleared and restarted.",
    hl_diva_feature_accessibility: "Reading page content aloud supports users with visual impairments and lower literacy levels, in keeping with the WCAG principles behind the portal.",
    hl_diva_feature_accessibility_s: "Reading content aloud helps users with visual impairments or lower literacy, matching the portal's WCAG goals.",

    hl_a11y_panel1_caption: "A built-in screen reader reads the page in natural segments while highlighting the current element and scrolling it into view. Space pauses, S stops, arrow keys change the speed, and a slider and voice list give finer control. Five quick profiles, for low vision, motor, dyslexia, cognitive and senior needs, apply a set of adjustments in one click.",
    hl_a11y_panel1_caption_s: "A built-in screen reader reads the page aloud and highlights each part as it goes. Space pauses, S stops, and arrow keys change speed. Five one-click profiles cover low vision, motor, dyslexia, cognitive and senior needs.",
    hl_a11y_panel2_caption: "Base text size scales between 80% and 150% without breaking the page layout. Five display modes, Normal, High Contrast, Dark, Greyscale and Negative, cover different contrast needs, alongside toggles to highlight links, hide images and stop animations.",
    hl_a11y_panel2_caption_s: "Text size can scale from 80% to 150% without breaking the layout. Five colour modes, Normal, High Contrast, Dark, Greyscale and Negative, plus toggles to highlight links, hide images and stop animations.",
    hl_a11y_panel3_caption: "A dyslexia-friendly font toggle, a reading guide that follows the cursor, wider letter spacing and a bold focus outline for keyboard navigation, plus a large mouse pointer for users with motor difficulties and a keyboard shortcut guide. The panel itself opens with <kbd>Alt</kbd> + <kbd>A</kbd>.",
    hl_a11y_panel3_caption_s: "Extra tools: a dyslexia-friendly font, a reading guide, wider letter spacing, a bold focus outline, a large mouse pointer and a shortcut guide. Open the panel with <kbd>Alt</kbd> + <kbd>A</kbd>.",
    hl_a11y_principle_wcag: "The portal was tested against the guidelines with browser-based evaluation tools, and the required adjustments were identified and applied across the front-end code.",
    hl_a11y_principle_wcag_s: "The portal was tested against WCAG with browser tools, and the needed fixes were made in the code.",
    hl_a11y_principle_contrast: "Contrast ratios in the existing front end were reviewed and improved to meet the guidelines, with a High Contrast display mode available for users who need more.",
    hl_a11y_principle_contrast_s: "Colour contrast was reviewed and improved, with a High Contrast mode for users who need it.",
    hl_a11y_principle_keyboard: "Keyboard navigation support across the portal, a bold focus outline to keep the current position visible, and a shortcut guide for fully mouseless use.",
    hl_a11y_principle_keyboard_s: "The whole portal works with a keyboard: a bold focus outline and a shortcut guide support mouse-free use.",
    hl_a11y_principle_screenreaders: "The toolbar announces its state changes through an ARIA live region, keeping external screen readers such as NVDA and JAWS in step with what is on screen.",
    hl_a11y_principle_screenreaders_s: "The toolbar announces changes out loud, so screen readers like NVDA and JAWS stay in sync with the screen.",
    hl_a11y_principle_aria: "ARIA labels and text alternatives for non-text content were added throughout the front end so that assistive technology can name and describe every control.",
    hl_a11y_principle_aria_s: "ARIA labels and text alternatives were added throughout, so assistive technology can describe every control.",
    hl_a11y_principle_saved: "Every setting is stored in the browser's local storage, so a returning visitor's preferences are restored automatically with no account needed.",
    hl_a11y_principle_saved_s: "Every setting is saved in the browser, so a returning visitor's preferences come back automatically.",
    // User testing with SENA, 24 July 2026. These mirror the markup in
    // pages/highlights.php and win over it at runtime, so edit both together.
    // Mrs Burtony André is described by her role and by the perspective she
    // tested from - the source report does not state that she is blind, and
    // neither may these strings.
    hl_a11y_usertest_intro: "Mrs Aarthi Burtony André, SENA Resource Person for Learners with Visual Impairments, took part in accessibility testing of the website at the SENA Office on 24 July 2026, at the request of two AI Unit interns. The website was tested with the NVDA and Microsoft Narrator screen readers while it was still under development.",
    hl_a11y_usertest_intro_s: "Mrs Aarthi Burtony André works at SENA as Resource Person for Learners with Visual Impairments. She took part in accessibility testing of the website at the SENA Office on 24 July 2026. Two AI Unit interns asked her to test it. She used two screen readers: NVDA and Microsoft Narrator. The website was still being built at the time.",
    hl_a11y_usertest_appreciation: "Mrs Burtony André welcomed the Ministry's initiative in engaging with persons with disabilities during the development of its AI Policy website, and described the collaborative approach as greatly appreciated and as reflecting good practice in inclusive digital design. The AI Unit thanks her and SENA for taking part in this testing, which continues to inform accessibility work on the website.",
    hl_a11y_usertest_appreciation_s: "Mrs Burtony André was glad the Ministry chose to work with persons with disabilities while it was building its AI Policy website. She said this way of working together is much appreciated and is good practice in inclusive design. The AI Unit thanks her and SENA for helping test the website. Their feedback still guides the accessibility work.",

    hl_journey_lede: "The same six stages carried every piece of work from an open question to something ready to hand over.",
    hl_journey_lede_s: "The same six stages guided every piece of work, from question to finished result.",
    hl_journey_video_caption: "The video captioning feature: audio transcribed by hand into timed WebVTT files, surfaced through the HTML5 track element, with captions available in English and French. The interface presenting the videos and captions was also built by the interns.",
    hl_journey_video_caption_s: "Captions were typed by hand and added with HTML5's caption feature, in English and French. Interns also built the video player itself.",
    hl_journey_stage1: "Analysis of the existing aim.govmu.org site, plus self-directed research into WCAG 2.2, the Web Speech API and open data.",
    hl_journey_stage1_s: "Reviewed the old aim.govmu.org site and researched WCAG 2.2, the Web Speech API and open data.",
    hl_journey_stage2: "A wireframe of the home page, navigation and feature placement, approved by the mentor, alongside the Kanban board that would organise the work.",
    hl_journey_stage2_s: "Made a wireframe of the home page and navigation, approved by the mentor, and set up the Kanban board.",
    hl_journey_stage3: "Front-end build in HTML5, CSS3 and JavaScript, followed by the accessibility toolbar, speech synthesis, DIVA, video captions and PDF-to-audio.",
    hl_journey_stage3_s: "Built the front end in HTML5, CSS3 and JavaScript, then added the accessibility toolbar, speech, DIVA, captions and PDF-to-audio.",
    hl_journey_stage4: "The portal was checked against WCAG 2.2 with browser-based evaluation tools, and integration problems, like the chatbot's CSP conflict, were diagnosed through browser developer tools.",
    hl_journey_stage4_s: "Tested the portal against WCAG 2.2 and fixed problems, like the chatbot's security conflict, using browser tools.",
    hl_journey_stage5: "Early front-end code was reworked to meet the WCAG requirement, and the speech engine gained a voice selection algorithm to align voices with the chosen language.",
    hl_journey_stage5_s: "Reworked early code to meet WCAG, and added a voice-selection feature that matches voices to the chosen language.",
    hl_journey_stage6: "The portal was presented to the Ministry of Technology, back-end planning began, and the code structure was documented so incoming interns could continue the work.",
    hl_journey_stage6_s: "Presented the portal to the Ministry of Technology, started back-end planning, and documented the code for future interns.",
    hl_journey_closing_caption: "At the Ministry of Information Technology, Communication and Innovation, where the portal was presented.",

    hl_skills_lede: "The technologies used across the portal, and the tools the team worked with.",
    hl_skills_html: "Semantic markup, ARIA labels and the track element for captions",
    hl_skills_css: "Responsive layouts with Flexbox and Grid, and a consistent design language",
    hl_skills_js: "All portal interactivity, written without a front-end framework",
    hl_skills_frontend: "Wireframing, interface build and usability improvements",
    hl_skills_responsive: "A layout that holds from a small phone to a wide desktop",
    hl_skills_a11y: "WCAG 2.2, ARIA labels, keyboard navigation and contrast",
    hl_skills_speech: "Speech synthesis for the screen reader and PDF-to-audio",
    hl_skills_chatbot: "Integration of the DIVA assistant into the portal",
    hl_skills_python: "Selected for the back end, researched during the attachment",
    hl_skills_fastapi: "The agreed back-end framework, with API design patterns explored",
    hl_skills_postgresql: "The chosen relational database, with schema planning under way",
    hl_skills_git: "A shared repository for concurrent work across the intern team",
    hl_skills_jira: "Kanban board with tasks organised into Epics and Sub-Epics",
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

/**
 * Replaces emoji with Twemoji images.
 *
 * Windows has no colour glyph for regional-indicator pairs, so the Mauritius
 * and UK flags in the "AI for All" cards render as the bare letters MU and GB,
 * or as empty boxes, on most Windows browsers. Twemoji swaps them for real
 * flag images.
 *
 * The typeof guard is the required pattern: if the CDN is blocked, offline or
 * down, `twemoji` is simply never defined, this returns immediately, and the
 * page keeps the original emoji text. Nothing else in this file depends on it.
 *
 * svg rather than the library's default 72x72 PNG - it stays sharp at any size
 * and any zoom, which matters for a glyph sitting inside a line of text.
 */
function parseEmoji(root) {
  if (typeof twemoji === 'undefined') return;
  try {
    twemoji.parse(root || document.body, { folder: 'svg', ext: '.svg' });
  } catch (err) {
    // A parse failure must never take the rest of the page down with it.
    console.error('Twemoji parse failed:', err);
  }
}

function applyTranslations() {
  const T = translations[currentLang] || {};
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    const value = (simpleMode && T[key + '_s'] !== undefined) ? T[key + '_s'] : T[key];
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

  /*
   * Re-run after every translation pass, not once at startup. The loop above
   * rewrites [data-i18n] elements with innerHTML, and two of those strings
   * (ai_en_title, ai_km_title) carry the UK and Mauritius flags - so any
   * Twemoji images in them are destroyed and replaced with raw emoji text on
   * every language switch and every simple-language toggle. Parsing here is
   * the only point that catches all of them.
   */
  parseEmoji(document.body);

  // Notify screen reader and other components of language change
  window.dispatchEvent(new CustomEvent('aiunit-lang-changed', { detail: { lang: currentLang } }));
}

document.querySelectorAll('.lang-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    currentLang = btn.getAttribute('data-lang');
    applyTranslations();
    updateSimpleSavingsNote(); // word totals are per-language, so recompute after switching
    localStorage.setItem('ai_unit_lang', currentLang);
  });
});

const savedLang = localStorage.getItem('ai_unit_lang');
if (savedLang && (savedLang === 'fr' || savedLang === 'km')) {
  currentLang = savedLang;
  applyTranslations();
} else {
  /*
   * applyTranslations() runs on load only when a non-English language was
   * saved - an English visitor never reaches it, so the flags in the markup
   * would stay unparsed. This is the one pass that catches the default case;
   * every other pass happens inside applyTranslations() itself.
   */
  parseEmoji(document.body);
}

/* ─── SIMPLE LANGUAGE MODE ───
   Replaces long-form copy with short plain-language summaries (the `_s`
   variant of each translation key - see applyTranslations() above). Offered
   as a reading preference to every visitor and never switched on
   automatically - see CLAUDE_CODE_BRIEF_simple_mode.md section 3. */
const SIMPLE_MODE_KEY = 'aiunit_simple_mode_v1';
const simpleToggleBtn = document.getElementById('simple-toggle');
const simpleAnnouncer = document.getElementById('simple-announcer');
const simpleSavingsNote = document.getElementById('simple-savings-note');

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

// Recalibrated from the brief's original 20%: that figure assumed a
// ~9,000-word, ~55-minute homepage. The real page is ~1,500 words
// (~9 minutes), where even a ~10% reduction is worth surfacing.
const TIME_SAVINGS_THRESHOLD = 0.10;

// Updates the small "Saves about N min of reading" note next to the Simple
// Language toggle - a static, visible replacement for what used to be a
// spoken-only announcement. Hidden while simple mode is already on (the
// saving no longer applies) or when the page's saving is too small to be
// worth mentioning.
function updateSimpleSavingsNote() {
  if (!simpleSavingsNote) return;
  if (simpleMode) { simpleSavingsNote.hidden = true; simpleSavingsNote.textContent = ''; return; }
  const totals = pageWordTotals();
  if (totals.full <= 0 || (totals.saved / totals.full) < TIME_SAVINGS_THRESHOLD) {
    simpleSavingsNote.hidden = true;
    simpleSavingsNote.textContent = '';
    return;
  }
  const savedMinutes = minutesFor(totals.full) - minutesFor(totals.full - totals.saved);
  if (savedMinutes < 1) {
    simpleSavingsNote.hidden = true;
    simpleSavingsNote.textContent = '';
    return;
  }
  const T = translations[currentLang] || {};
  const template = T['simple_savings_note'] || 'Saves about {n} min of reading';
  simpleSavingsNote.hidden = false;
  simpleSavingsNote.textContent = template.replace('{n}', savedMinutes);
}

function setSimpleMode(on, opts) {
  opts = opts || {};
  simpleMode = on;
  applyTranslations();
  updateSimpleSavingsNote();
  if (simpleToggleBtn) simpleToggleBtn.setAttribute('aria-pressed', String(on));
  try { localStorage.setItem(SIMPLE_MODE_KEY, on ? '1' : '0'); } catch (e) {}
  if (!opts.silent) {
    announceSimple(on
      ? 'Simple language on. Text is now simplified.'
      : 'Simple language off. Showing the full text.');
  }
}

if (simpleToggleBtn) {
  simpleToggleBtn.addEventListener('click', function () { setSimpleMode(!simpleMode); });
}

// Alt+M: quick keyboard toggle for simple language from anywhere on the
// page. Never touches the built-in screen reader - reading only ever starts
// from an explicit click on the reader's own controls.
function handleAltM() {
  setSimpleMode(!simpleMode);
}

document.addEventListener('keydown', function (e) {
  if (e.altKey && e.key.toLowerCase() === 'm') {
    e.preventDefault();
    handleAltM();
  }
});

// Restore saved preference and apply as early as this script runs (it loads
// before accessibility-widget.js, at the end of <body> - see Task 2; true
// before-first-paint would require moving script loading into <head>, which
// was out of scope here).
try {
  if (localStorage.getItem(SIMPLE_MODE_KEY) === '1') setSimpleMode(true, { silent: true });
} catch (e) {}
updateSimpleSavingsNote();

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
/**
 * Closes the mobile navigation, keeping the panel, the hamburger's icon and
 * its aria-expanded in step. Three things close this menu - picking a link,
 * pressing the hamburger again, and Escape - and they must not be able to
 * disagree about the state, which is why they all come through here.
 *
 * Returns focus to the hamburger when asked. That matters for Escape: the
 * closed panel is visibility:hidden, so whatever was focused inside it is gone
 * and focus would otherwise fall back to the top of the document.
 */
function closeMobileMenu(returnFocus){
  if(!navLinksDiv.classList.contains('mobile-open'))return;
  navLinksDiv.classList.remove('mobile-open');
  hamburger.classList.remove('open');
  hamburger.setAttribute('aria-expanded','false');
  if(returnFocus)hamburger.focus();
}

document.querySelectorAll('[data-scroll]').forEach(el=>{
  el.addEventListener('click',e=>{
    e.preventDefault();
    const target=document.getElementById(el.dataset.scroll);
    if(target)target.scrollIntoView({behavior:'smooth',block:'start'});
    // No focus return: the user is being sent to a section, so pulling focus
    // back up to the hamburger would undo the navigation they just asked for.
    closeMobileMenu(false);
  });
});

hamburger.addEventListener('click',()=>{
  const open=hamburger.classList.toggle('open');
  // Driven from the same value rather than toggled independently, so the panel
  // and the button cannot drift out of step.
  navLinksDiv.classList.toggle('mobile-open',open);
  hamburger.setAttribute('aria-expanded',open.toString());
});

const teamTabs = Array.from(document.querySelectorAll('.team-tab'));

// Shared by click and arrow-key switching, so both stay in sync: sets the
// clicked/arrowed-to tab active (and, per the roving-tabindex pattern below,
// the only one left in the Tab order), and swaps in its panel.
function activateTeamTab(tab) {
  const idx = tab.dataset.member;
  teamTabs.forEach(t => {
    const isTarget = t === tab;
    t.classList.toggle('active', isTarget);
    t.setAttribute('aria-selected', String(isTarget));
    t.setAttribute('tabindex', isTarget ? '0' : '-1');
  });
  document.querySelectorAll('.team-member-panel').forEach(p => p.classList.remove('active'));
  const panels = document.querySelectorAll('.team-member-panel');
  if (panels[idx]) panels[idx].classList.add('active');
}

teamTabs.forEach(tab => {
  tab.addEventListener('click', () => activateTeamTab(tab));
});

// Roving-tabindex arrow key support, per the WAI-ARIA tabs pattern: Left/Right
// (and Up/Down) move between tabs and activate them, Home/End jump to the
// first/last. Without this, each tab was still individually reachable with
// Tab, but arrow-key switching - what screen reader users expect once
// they're inside a tablist - never worked.
document.querySelector('.team-tabs')?.addEventListener('keydown', function (e) {
  const current = teamTabs.indexOf(document.activeElement);
  if (current === -1) return;
  let next;
  if (e.key === 'ArrowRight' || e.key === 'ArrowDown') next = (current + 1) % teamTabs.length;
  else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') next = (current - 1 + teamTabs.length) % teamTabs.length;
  else if (e.key === 'Home') next = 0;
  else if (e.key === 'End') next = teamTabs.length - 1;
  else return;
  e.preventDefault();
  activateTeamTab(teamTabs[next]);
  teamTabs[next].focus();
});

/**
 * Whether the visitor has asked for reduced motion. Read on each call rather
 * than cached - the preference can change mid-session, and matchMedia reflects
 * that immediately. Mirrors the helper assets/js/highlights.js already uses.
 */
function prefersReducedMotion() {
  return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/*
 * The marketplace ticker is an SVG SMIL <animate> element (pages/home.php).
 * SMIL is not CSS, so no prefers-reduced-motion rule can touch it - this is
 * the one continuous animation on the site that genuinely needs JavaScript to
 * stop. pauseAnimations() freezes the SVG's own timeline, leaving the wave and
 * its "Regional AI Marketplace" text on screen and readable; only the scroll
 * stops. The listener keeps that true if the preference is changed later.
 */
(function () {
  if (!window.matchMedia) return;
  const query = window.matchMedia('(prefers-reduced-motion: reduce)');
  const applyTickerPreference = () => {
    document.querySelectorAll('.marketplace-wave-top svg').forEach(svg => {
      if (typeof svg.pauseAnimations !== 'function') return;
      if (query.matches) svg.pauseAnimations();
      else svg.unpauseAnimations();
    });
  };
  applyTickerPreference();
  if (typeof query.addEventListener === 'function') {
    query.addEventListener('change', applyTickerPreference);
  }
})();

const revealObs=new IntersectionObserver(entries=>{
  entries.forEach((entry,i)=>{if(entry.isIntersecting)setTimeout(()=>entry.target.classList.add('visible'),(i%4)*80);});
},{threshold:0.08,rootMargin:'0px 0px -32px 0px'});
document.querySelectorAll('.reveal').forEach(el=>revealObs.observe(el));

/* ─── CONTACT FORM ───
 *
 * Field order used for both clearing and focus. It matches the order of the
 * client checks below AND the order ContactValidator applies on the server, so
 * "the first invalid field" means the same thing whichever side produced the
 * errors. subject is included because the server can return a "Topic is too
 * long." error for it even though nothing here validates it.
 */
const CONTACT_FIELDS = ['name', 'email', 'subject', 'message'];

/**
 * Marks a field invalid: the class for styling, aria-invalid for assistive
 * technology, and the message into the span the field's aria-describedby
 * already points at. Both client-side checks and server-returned errors call
 * this, so the two can never drift apart in what they announce.
 */
function setContactFieldError(fieldId, msg) {
  const field = document.getElementById(fieldId);
  const errorEl = document.getElementById(fieldId + '-error');
  if (field) {
    field.classList.add('invalid');
    field.setAttribute('aria-invalid', 'true');
  }
  if (errorEl) {
    errorEl.textContent = msg;
    errorEl.classList.add('show');
  }
}

/** Undoes the above completely - no stale aria-invalid, no stale message. */
function clearContactFieldError(fieldId) {
  const field = document.getElementById(fieldId);
  const errorEl = document.getElementById(fieldId + '-error');
  if (field) {
    field.classList.remove('invalid');
    field.removeAttribute('aria-invalid');
  }
  if (errorEl) {
    errorEl.textContent = '';
    errorEl.classList.remove('show');
  }
}

/**
 * Moves focus to the first field still marked invalid, in CONTACT_FIELDS
 * order. aria-invalid is the single source of truth for "is this field bad",
 * so this works identically after a client check and after a server response.
 */
function focusFirstInvalidContactField() {
  const first = CONTACT_FIELDS
    .map(id => document.getElementById(id))
    .find(f => f && f.getAttribute('aria-invalid') === 'true');
  if (first) first.focus();
  return Boolean(first);
}

/*
 * Correcting a field clears its error as you type.
 *
 * Registered once, at load. This used to be attached at the END of the submit
 * handler with { once: true }, which meant it did not exist until after a
 * first failed submit, fired a single time, and was skipped entirely on the
 * success path - so a corrected field kept its aria-invalid and its stale
 * message. Binding once here also stops a listener being added per submit.
 */
CONTACT_FIELDS.forEach(id => {
  const field = document.getElementById(id);
  if (!field) return;
  field.addEventListener(field.tagName === 'SELECT' ? 'change' : 'input', () => {
    if (field.getAttribute('aria-invalid') === 'true') clearContactFieldError(id);
  });
});

document.getElementById('contactForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const form = e.target;
  const name = document.getElementById('name');
  const email = document.getElementById('email');
  const message = document.getElementById('message');
  const status = document.getElementById('formStatus');
  const btn = form.querySelector('[type="submit"]');

  const showFieldError = setContactFieldError;

  function clearErrors() {
    CONTACT_FIELDS.forEach(clearContactFieldError);
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
    // The polite summary says how many/that there are problems; focus then
    // takes the user to the first one, where its own message is read out as
    // the field's description. Two different pieces of information, announced
    // once each - which is why the per-field spans no longer carry
    // role="alert" (that would repeat the same text a third time).
    status.textContent = 'Please correct the errors above.';
    status.classList.add('show', 'error');
    focusFirstInvalidContactField();
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

    /*
     * Field-specific validation errors from the server. The server remains the
     * authority - the checks above are a convenience that saves a round trip,
     * and anything they miss (length limits, stricter email rules) still comes
     * back from ContactValidator and is rendered here through exactly the same
     * function, so a server error is indistinguishable from a client one in
     * aria-invalid, aria-describedby, message and focus behaviour.
     */
    if (data.errors) {
      Object.keys(data.errors).forEach(field => showFieldError(field, data.errors[field]));
    }
    status.textContent = data.message || 'Please correct the errors above.';
    status.classList.add('show', 'error');
    focusFirstInvalidContactField();
  } catch (err) {
    status.textContent = 'Something went wrong. Please check your connection and try again.';
    status.classList.add('show', 'error');
  } finally {
    btn.disabled = false;
    if (btn.innerHTML === 'Sending…') btn.innerHTML = originalBtnHtml;
  }
  // The "clear error on input" listeners that used to be registered here on
  // every submit now live at the top of this block, bound once at load.
});

// DIVA's backend URL comes from server config (config('diva.api_url'), settable
// via the DIVA_API_URL environment variable) rather than being hardcoded here,
// so it can be repointed per-environment without editing this file.
//
// Empty string, not a fallback address. This file is served verbatim to every
// visitor, so the loopback address this line used to fall back to was part of
// the delivered JavaScript whether or not the site was configured correctly -
// which is why the address is not written out here either. An empty value
// means "not configured", which sendDivaMessage() below refuses to act on; the
// server has already rendered the assistant as unavailable to match
// (includes/diva-widget.php).
const WORKER_URL = AI_UNIT_CONFIG.divaApiUrl || '';
const divaTrigger=document.getElementById('divaTrigger');
const divaPanel=document.getElementById('divaPanel');
const divaClose=document.getElementById('divaClose');
const divaClear=document.getElementById('divaClear');
const divaInput=document.getElementById('divaInput');
const divaSend=document.getElementById('divaSend');
const divaMic=document.getElementById('divaMic');
const divaMessages=document.getElementById('divaMessages');
const divaStatus=document.getElementById('divaStatus');
const openDiva=document.getElementById('openDiva');
const divaHistory=[];
let divaIsLoading=false;
let lastDivaResponse='';

/*
 * Left/right bubble position conveys speaker to sighted users only, so every
 * message also carries this text, hidden with .visually-hidden rather than
 * display:none/visibility:hidden so it still reaches the accessibility tree.
 * Falls back to the English strings if the active language has no
 * translations object yet (currently true for fr/km - see translations above).
 */
function divaSpeakerLabel(role) {
  const T = translations[currentLang] || translations.en;
  const key = role === 'user' ? 'diva_speaker_user' : 'diva_speaker_bot';
  return (T && T[key]) || translations.en[key];
}

function divaSpeakerSpan(role) {
  const speaker = document.createElement('span');
  speaker.className = 'visually-hidden';
  speaker.setAttribute('data-i18n', role === 'user' ? 'diva_speaker_user' : 'diva_speaker_bot');
  speaker.textContent = divaSpeakerLabel(role);
  return speaker;
}

/* ─── ADD DIVA MESSAGE (with Read-Aloud + Copy per message) ─── */
function addDivaMessage(text, role) {
  const div = document.createElement('div');
  div.className = 'diva-msg ' + role;
  div.appendChild(divaSpeakerSpan(role));
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
  // aria-hidden goes on the whole bubble, not just the text node, so the
  // "DIVA said:" speaker prefix is exposed to assistive tech at the same
  // moment as the finished reply below - otherwise it would be announced
  // on its own the instant this element is inserted, ahead of any content.
  div.setAttribute('aria-hidden', 'true');
  div.appendChild(divaSpeakerSpan('bot'));
  const content = document.createElement('div');
  div.appendChild(content);
  divaMessages.appendChild(div);

  if (prefersReducedMotion()) {
    // The reply arrives complete instead of a word at a time. This is the
    // final state of the loop below, not a reduced one - the same text, the
    // same element, the same action buttons appended afterwards; only the
    // 25ms-per-word reveal is skipped.
    content.textContent = text;
    divaMessages.scrollTop = divaMessages.scrollHeight;
  } else {
    const words = text.split(' ');
    for (const word of words) {
      content.textContent += word + ' ';
      divaMessages.scrollTop = divaMessages.scrollHeight;
      await new Promise(resolve => setTimeout(resolve, 25));
    }
  }

  div.removeAttribute('aria-hidden');

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

  // #divaMessages uses aria-relevant="additions" so that the word-by-word
  // typing effect above (a text mutation on a node already in the DOM)
  // doesn't get read aloud piecemeal. But that also means simply removing
  // aria-hidden a few lines up is invisible to it - an attribute change
  // isn't a node "addition", so screen readers never announced the
  // finished reply. Re-inserting the now-complete, now-visible node makes
  // it a genuine addition, so NVDA/JAWS/VoiceOver announce it once, in full.
  div.remove();
  divaMessages.appendChild(div);

  divaMessages.scrollTop = divaMessages.scrollHeight;
  lastDivaResponse = text;
}

/* ─── CLEAR CHAT ─── */
function clearDivaChat() {
  divaHistory.length = 0;
  divaMessages.innerHTML = `
    <div class="diva-msg bot" id="divaWelcomeMsg" tabindex="-1"><span class="visually-hidden" data-i18n="diva_speaker_bot">DIVA said:</span><span data-i18n="diva_welcome">Hello! I'm <strong>DIVA</strong> - the Government of Mauritius' AI assistant. I'm here to help you with questions about our Digital Transformation Blueprint, AI strategy, and government services.<br><br>You can also <strong>speak to me</strong> - press the microphone button below and ask your question out loud.</span></div>
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
    // clearDivaChat() replaces the whole message list, so anything focused in
    // there - a suggestion chip, a speak button - is destroyed and focus falls
    // to the document. Only step in when that has actually happened; if focus
    // is still on Clear itself it is already where the user left it.
    if (divaPanel && !divaPanel.contains(document.activeElement)) {
      divaDeferFocus(divaInitialFocusTarget());
    }
  }
});

/* ─── SPEECH RECOGNITION ─── */
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
let recognition;
let isListening = false;
let divaMicArmed = false;
let divaMicStartTimer = null;
// Fallback-only: if the user never gives a confirming second press (e.g.
// they're not using a screen reader), open the mic on its own after this
// long. Kept as a safety net, not the primary mechanism - see below.
const DIVA_MIC_FALLBACK_DELAY = 2500;

if (SpeechRecognition) {
  recognition = new SpeechRecognition();
  recognition.lang = navigator.language || 'en-GB';
  recognition.continuous = false;
  recognition.interimResults = true;
}

function divaMicReset() {
  divaMicArmed = false;
  if (divaMicStartTimer) {
    clearTimeout(divaMicStartTimer);
    divaMicStartTimer = null;
  }
  divaMic.classList.remove('preparing', 'listening');
  divaMic.setAttribute('aria-label', 'Speak to DIVA - press to start voice input');
  divaInput.placeholder = 'Type or speak your question…';
}

function startDivaRecognition() {
  divaMicArmed = false;
  if (divaMicStartTimer) {
    clearTimeout(divaMicStartTimer);
    divaMicStartTimer = null;
  }
  // This site's own built-in screen reader (accessibility-widget.js) speaks
  // through the same speakers via speechSynthesis - if it's still talking
  // (or has a focus-announcement queued) the instant the mic opens, that
  // gets captured exactly like an external screen reader's speech would.
  // Unlike NVDA, this one is ours to silence.
  if (window.__aiUnitStopScreenReader) window.__aiUnitStopScreenReader();
  try {
    recognition.start();
  } catch (e) {
    console.warn('Speech recognition error:', e);
    divaMicReset();
  }
}

/*
 * Why "arm, then confirm" instead of just opening the mic on click:
 * the moment we change the mic button's aria-label/placeholder to announce
 * "Listening…", a screen reader (e.g. NVDA) speaks that change out loud
 * because the button has focus. If audio capture were already running at
 * that instant, the recognizer would pick up NVDA's own voice instead of
 * (or mixed with) the user's. A fixed delay can't fix this reliably -
 * NVDA's speech rate is user-configurable and unknowable from here.
 *
 * Instead, the first press only "arms" listening and announces once; the
 * *second* press is what actually opens the mic. Because that second press
 * is user-initiated, a screen reader user naturally performs it only after
 * they've heard the announcement finish - so the mic never opens mid-speech.
 * A fallback timer still opens it automatically for mouse/non-AT users so
 * they don't need to click twice.
 */
divaMic?.addEventListener('click', () => {
  if (!recognition) {
    addDivaMessage('Voice input is not supported in your browser. Please type your question.', 'bot');
    return;
  }
  if (isListening) {
    recognition.stop();
    return;
  }
  if (divaMicArmed) {
    startDivaRecognition();
    return;
  }
  // Silence the built-in screen reader before even announcing "getting
  // ready" - otherwise that announcement is competing with (or gets cut
  // into) whatever it was already saying.
  if (window.__aiUnitStopScreenReader) window.__aiUnitStopScreenReader();
  divaMicArmed = true;
  recognition.lang = navigator.language || 'en-GB';
  divaMic.classList.add('preparing');
  divaMic.setAttribute('aria-label', 'Getting ready to listen. Press the microphone again when you’re ready to speak.');
  divaInput.placeholder = 'Press mic again when ready…';

  divaMicStartTimer = setTimeout(startDivaRecognition, DIVA_MIC_FALLBACK_DELAY);
});

if (recognition) {
  recognition.onstart = () => {
    isListening = true;
    // Deliberately not touching aria-label/placeholder here: the "getting
    // ready" text announced at arm-time already told the user listening is
    // about to begin. Speaking anything new the instant capture opens is
    // exactly the collision this whole flow exists to avoid - so the visual
    // pulse (class only) is the only feedback at this exact moment.
    divaMic.classList.remove('preparing');
    divaMic.classList.add('listening');
  };
  recognition.onend = () => {
    isListening = false;
    divaMicReset();
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
    divaMicReset();
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
  // Purely decorative - the actual "DIVA is typing…" announcement comes from
  // #divaStatus below, so this doesn't get announced a second time as a
  // separate addition inside #divaMessages.
  wrap.setAttribute('aria-hidden', 'true');
  for (let i = 0; i < 3; i++) {
    const s = document.createElement('span');
    wrap.appendChild(s);
  }
  divaMessages.appendChild(wrap);
  divaMessages.scrollTop = divaMessages.scrollHeight;
  if (divaStatus) {
    const T = translations[currentLang] || translations.en;
    divaStatus.textContent = (T && T.diva_typing) || translations.en.diva_typing;
  }
}

function hideDivaTyping() {
  const el = document.getElementById('diva-typing');
  if (el) el.remove();
  // Cleared, not left showing "DIVA is typing…", once the reply (or an
  // error) has actually landed in #divaMessages.
  if (divaStatus) divaStatus.textContent = '';
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
  // Single choke point for every way a message can be sent - the send button,
  // Enter in the field, a suggestion chip and the voice input all arrive here -
  // so one guard covers them all. With no endpoint configured there is nothing
  // to send to, and guessing an address is what this work item removed.
  if (!WORKER_URL) return;

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
      // No model name. The proxy picks the model itself and ignores anything
      // sent from here - verified against the live endpoint - so the field this
      // used to carry had no effect, and a model name chosen in the browser is
      // not something a server should honour anyway.
      body: JSON.stringify({ max_tokens: 400, messages: divaHistory })
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

/* ─── DIVA PANEL: MODAL DIALOG BEHAVIOUR ───
 *
 * Modelled on the accessibility panel in accessibility-widget.js, which is this
 * project's established modal: role="dialog", aria-modal toggled in lockstep
 * with the open class, a Tab trap, and a keydown listener attached only while
 * the dialog is open. Following the same shape keeps the two dialogs behaving
 * alike and means the Escape handler cannot fire when DIVA is closed - there is
 * nothing listening then, so no guard, no stopPropagation, and no interference
 * with the video modal, the mobile menu or the accessibility panel.
 */

const divaWidget = document.getElementById('divaWidget');

/** The control that opened the dialog, so focus can be handed back to it. */
let divaLastFocused = null;

/** Body children we switched to inert, so only our own state is undone. */
let divaInertedNodes = [];

/**
 * Focusable controls inside the panel, in DOM order.
 *
 * Queried live rather than kept in a list: the suggestion chips delete
 * themselves when used, Clear rebuilds them, and replies can add speak buttons.
 * offsetParent is null for anything display:none, which is how the closed panel
 * and any hidden control drop out; disabled controls are excluded by the
 * selector. Same filter the accessibility panel uses.
 */
function divaFocusable() {
  if (!divaPanel) return [];
  const sel = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';
  return Array.from(divaPanel.querySelectorAll(sel))
    .filter(el => el.offsetParent !== null && !el.hasAttribute('hidden') && el.getAttribute('aria-hidden') !== 'true');
}

/**
 * Everything except the widget is made inert while the dialog is open, so the
 * page behind it cannot be reached by Tab, click or assistive technology.
 *
 * The widget itself is skipped - it holds both the panel and its trigger, and
 * making an ancestor inert would take the dialog down with the page. Each node
 * is tagged so that closing only clears inertness this code applied, never
 * something another component set.
 *
 * The focus trap below is the real guarantee; this is belt and braces for
 * browsers that support it, which is why an older browser simply skips it.
 */
function divaSetBackgroundInert(on) {
  if (!('inert' in HTMLElement.prototype) || !divaWidget) return;

  if (on) {
    divaInertedNodes = Array.from(document.body.children)
      .filter(el => el !== divaWidget && !el.contains(divaWidget) && !el.inert);
    divaInertedNodes.forEach(el => { el.inert = true; });
    return;
  }

  divaInertedNodes.forEach(el => { el.inert = false; });
  divaInertedNodes = [];
}

/**
 * Focus is moved on the next frame rather than immediately: the panel's display
 * only changes when the "open" class is applied, and focus() does nothing to an
 * element that is still display:none. The same applies on the way out, where
 * below 480px the trigger is itself hidden until the class is removed (WO-06).
 */
function divaDeferFocus(el) {
  if (!el || typeof el.focus !== 'function') return;
  if (typeof requestAnimationFrame === 'function') requestAnimationFrame(() => el.focus());
  else el.focus();
}

/**
 * The welcome message if present, so a screen reader announces it - the
 * "Hello, I'm DIVA…" greeting - the moment the panel opens, rather than
 * landing straight on the input field and skipping the introduction.
 * Falls back to the input, then the first focusable control.
 */
function divaInitialFocusTarget() {
  const welcome = document.getElementById('divaWelcomeMsg');
  if (welcome && welcome.offsetParent !== null) return welcome;
  if (divaInput && !divaInput.disabled && divaInput.offsetParent !== null) return divaInput;
  return divaFocusable()[0] || null;
}

function onDivaKeydown(e) {
  if (e.key === 'Escape') {
    e.preventDefault();
    closeDivaPanel();
    return;
  }
  if (e.key !== 'Tab') return;

  const focusable = divaFocusable();
  // A dialog with nothing to focus is not a trap worth enforcing; letting Tab
  // through is better than throwing on focusable[0].
  if (!focusable.length) return;

  const first = focusable[0];
  const last = focusable[focusable.length - 1];

  // Focus somehow outside the dialog while it is open - pull it back rather
  // than letting Tab walk away into the (inert) page.
  if (!divaPanel.contains(document.activeElement)) {
    e.preventDefault();
    (e.shiftKey ? last : first).focus();
    return;
  }
  if (e.shiftKey && document.activeElement === first) {
    e.preventDefault();
    last.focus();
  } else if (!e.shiftKey && document.activeElement === last) {
    e.preventDefault();
    first.focus();
  }
}

function openDivaPanel(opener) {
  if (!divaPanel || divaPanel.classList.contains('open')) return;

  // The trigger is the usual opener, but the homepage "Chat with DIVA" button
  // (#openDiva) opens it too, and focus has to go back to whichever was used.
  divaLastFocused = (opener && typeof opener.focus === 'function') ? opener : divaTrigger;

  divaPanel.classList.add('open');
  divaPanel.setAttribute('aria-modal', 'true');
  divaTrigger.setAttribute('aria-expanded', 'true');
  divaSetBackgroundInert(true);
  document.addEventListener('keydown', onDivaKeydown);
  divaDeferFocus(divaInitialFocusTarget());
}

function closeDivaPanel() {
  if (!divaPanel || !divaPanel.classList.contains('open')) return;

  /*
   * Focus is handed back only when it is currently inside the dialog, or
   * nowhere at all - those are the cases where closing would otherwise strand
   * it on a hidden element or drop it to the top of the document.
   *
   * When it is elsewhere the user has already moved it deliberately: they
   * clicked the trigger, or clicked something else on the page and the
   * outside-click handler closed the dialog behind them. Pulling focus back
   * there would take it away from whatever they just chose.
   */
  const active = document.activeElement;
  const shouldRestore = !active || active === document.body || divaPanel.contains(active);

  divaPanel.classList.remove('open');
  divaPanel.removeAttribute('aria-modal');
  divaTrigger.setAttribute('aria-expanded', 'false');
  divaSetBackgroundInert(false);
  document.removeEventListener('keydown', onDivaKeydown);

  if (shouldRestore) {
    // The opener may have been removed or hidden while the dialog was open, so
    // fall back to the trigger, and to nothing at all if that is unusable
    // (DIVA unavailable, WO-09) rather than focusing a dead element.
    const opener = divaLastFocused;
    const usable = el => el && document.contains(el) && !el.disabled && typeof el.focus === 'function';
    divaDeferFocus(usable(opener) ? opener : (usable(divaTrigger) ? divaTrigger : null));
  }

  divaLastFocused = null;
}

divaTrigger.addEventListener('click', () => {
  if (divaPanel.classList.contains('open')) closeDivaPanel();
  else openDivaPanel(divaTrigger);
});

divaClose.addEventListener('click', closeDivaPanel);

divaSend.addEventListener('click', sendDivaMessage);
divaInput.addEventListener('keydown', e => { if (e.key === 'Enter') sendDivaMessage(); });

// The homepage's "Chat with DIVA" button is the second way in. It passes
// itself as the opener so focus returns here, not to the floating trigger.
// stopPropagation is kept: without it this same click reaches the
// outside-click handler below and closes the dialog it just opened.
openDiva?.addEventListener('click', (e) => {
  e.stopPropagation();
  openDivaPanel(openDiva);
  divaMessages.scrollTop = divaMessages.scrollHeight;
});

// Pre-existing outside-click close, now routed through closeDivaPanel so it
// clears aria-modal, background inertness and the keydown listener like every
// other close path. It does not steal focus - see the note in closeDivaPanel.
document.addEventListener('click', function (e) {
  if (divaPanel && divaPanel.classList.contains('open') && divaWidget && !divaWidget.contains(e.target)) {
    closeDivaPanel();
  }
});

// The two "AI for All" booklet controls are plain links in pages/home.php now.
// They used to be handled here with window.open('/booklet/aie'), which hardcoded
// a root-relative path and so 404ed on any deployment that is not at the domain
// root. A link built with url() carries the right prefix on its own; JavaScript
// has no business in it.
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
    // The visible label alone is ambiguous with four identical "Listen"
    // buttons on the page - aria-label names which document this one is,
    // so a screen reader announces "Listen to <title>" / "Pause <title>"
    // instead of four indistinguishable "Listen" buttons.
    const title = button.dataset.docTitle;
    if (title) button.setAttribute('aria-label', 'Listen to ' + title);
}

function setButtonPause(button) {
    const label = button.querySelector('span');
    if (label) label.textContent = 'Pause';
    button.classList.add('is-playing');
    button.setAttribute('aria-pressed', 'true');
    const title = button.dataset.docTitle;
    if (title) button.setAttribute('aria-label', 'Pause ' + title);
}

function formatTime(seconds) {

    if (isNaN(seconds)) {
        return '0:00';
    }

    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);

    return `${mins}:${String(secs).padStart(2, '0')}`;
}
