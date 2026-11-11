<?php
/*
 * Infosys Springboard is MITCI's flagship platform, so it leads twice: as the
 * "Start here" panel in the hero and as the first card in the list.
 * Its URL is written once here so the panel and the card cannot drift apart.
 */
$springboardUrl = 'https://infyspringboard.eu.onwingspan.com/web/en/';

/*
 * 'launch' => 'soon' renders a card as a blurred "Launching soon" teaser.
 * The teaser prints none of the provider's details (name, logo, text or link),
 * so nothing about the partner can be read from the page source before launch.
 * To unveil it, delete the 'launch' line.
 */
$providers = [
    [
        'name' => 'Infosys Springboard',
        'topic' => 'Build digital confidence',
        'description' => 'Free digital learning from Infosys, including AI and other technology topics.',
        'url' => $springboardUrl,
        'action' => 'Open Infosys Springboard',
        'accent' => 'infosys',
        'logo' => 'infosys.svg',
    ],
    [
        'name' => 'AI Foundations on AWS',
        'topic' => 'Build with AI',
        'description' => 'A free, 7-hour self-paced course on generative AI, prompting and Amazon PartyRock, offered by my.t.',
        'url' => 'https://learninghub.myt.mu/courses/ai-foundations-ai-foundational-10',
        'action' => 'View course on my.t',
        'accent' => 'aws',
        'logo' => 'aws.png',
    ],
    [
        'launch' => 'soon',
        'name' => 'Google AI Essentials',
        'topic' => 'Use AI at work',
        'description' => 'Practise writing prompts, using generative AI for everyday tasks and checking its output. Google offers this beginner course through Coursera. Check the course page for pricing.',
        'url' => 'https://www.coursera.org/google-certificates/ai-essentials-google',
        'action' => 'View Google AI Essentials',
        'accent' => 'google',
        'logo' => 'google.png',
    ],
];

$segments = [
    ['Pre-primary', 'Build curiosity and safe digital habits through stories, play and guided activities.', 'M3 4h13v10H8l-5 4V4m13 4h5v10l-3-3h-2M6 8h1m2 0h1m2 0h1'],
    ['Primary', 'Recognise AI in everyday tools and start talking about fairness and safe use.', 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Zm0 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-4 11v-2a4 4 0 0 1 8 0v2'],
    ['Secondary', 'Explore AI through creative projects, coding and discussion about its role in society.', 'M4 6h16v14H4zM8 3v6m8-6v6M4 10h16m-12 4h1m3 0h1m3 0h1m-8 3h1m3 0h1m3 0h1'],
    ['Technical and vocational education (TVET)', 'Apply AI to practical tasks in technical and vocational settings.', 'M3 7h18v13H3zM8 7V4h8v3M3 12h18m-11 0v2h4v-2'],
    ['Tertiary education', 'Use AI responsibly in higher education, research and innovation.', 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Zm0 0c3 3 4 6 4 9s-1 6-4 9c-3-3-4-6-4-9s1-6 4-9ZM3 12h18'],
    ['Special educational needs (SEN)', 'Explore assistive AI tools for accessibility, independence and personalised learning.', 'M3 20v-3m5 3v-6m5 6v-9m5 9V8M3 12l5-5 4 2 8-6m-5 0h5v5'],
    ['Working adults', 'Apply AI responsibly to improve productivity and solve problems at work.', 'M5 9h13v5a5 5 0 0 1-5 5h-3a5 5 0 0 1-5-5V9Zm13 1h2a2 2 0 0 1 0 4h-2M3 21h18M10 6c-2-2 2-2 0-4m5 4c-2-2 2-2 0-4'],
    ['Non-working adults', 'Build confidence using AI in daily life and staying safe online.', 'M3 10h5l11-5v14l-11-5H3zm5 4 2 6h3l-2-5m10-5 2-1m-2 5 2 1'],
    ['Policy-makers and leaders', 'Develop the skills to guide responsible AI adoption, governance and policy.', 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Zm0 0c3 3 4 6 4 9s-1 6-4 9c-3-3-4-6-4-9s1-6 4-9ZM3 12h18'],
];
?>
<main class="ai4ai" id="main-content" tabindex="-1">
  <header class="ai4ai-intro">
    <div class="container ai4ai-hero">
      <div class="ai4ai-stage">
        <h1 class="ai4ai-wordmark">AI4AI</h1>
        <?php /* Finished frame of the cube animation: shown until the 3D scene draws, and kept if it cannot. */ ?>
        <img class="ai4ai-poster" src="<?= e(asset('images/ai4ai/ai4ai-cube.webp')) ?>"
             width="1200" height="720" alt="" role="presentation" fetchpriority="high">
        <div class="ai4ai-canvas" aria-hidden="true"></div>
        <div class="ai4ai-stage-controls">
          <button class="ai4ai-theme-toggle" type="button" aria-pressed="false" hidden>Light background</button>
          <button class="ai4ai-motion-toggle" type="button" aria-label="Pause AI4AI animation" hidden>Pause</button>
        </div>
      </div>
      <div class="ai4ai-hero-copy">
        <p class="ai4ai-kicker">AI for All and Inclusion</p>
        <p class="ai4ai-lead">Learn AI. Find your starting point.</p>
        <p class="ai4ai-context">Explore training for everyday life, work and study through Mauritius’ national AI4AI programme.</p>
        <a class="ai4ai-jump" href="#ai4ai-training" target="_self">Browse training platforms <span aria-hidden="true">↓</span></a>
      </div>
    </div>
    <section class="container" aria-labelledby="ai4ai-starter-title">
      <div class="ai4ai-starter">
        <div>
          <p class="ai4ai-starter-label">New to AI? Start here</p>
          <h2 id="ai4ai-starter-title">Infosys Springboard</h2>
          <p>MITCI’s flagship learning platform for AI4AI. Explore free digital learning from Infosys, including AI and other technology topics.</p>
        </div>
        <div class="ai4ai-starter-action">
          <div class="ai4ai-starter-logo">
            <img src="<?= e(asset('images/ai4ai/infosys.svg')) ?>" alt="" role="presentation">
          </div>
          <a class="ai4ai-starter-link" href="<?= e($springboardUrl) ?>" target="_blank" rel="noopener noreferrer">
            Open Infosys Springboard <span aria-hidden="true">↗</span><span class="visually-hidden"> (opens in a new tab)</span>
          </a>
          <p>Register or log in on Infosys Springboard to start learning.</p>
        </div>
      </div>
    </section>
  </header>

  <section class="container ai4ai-section" id="ai4ai-training" aria-labelledby="ai4ai-training-title">
    <h2 id="ai4ai-training-title">Choose a training platform</h2>
    <p class="ai4ai-section-intro">Courses take place on the provider’s website. Links open in a new tab. Check each provider for account requirements, costs and eligibility.</p>

    <ul class="ai4ai-platforms">
      <?php foreach ($providers as $provider): ?>
        <?php if (($provider['launch'] ?? null) === 'soon'): ?>
        <li class="ai4ai-platform ai4ai-platform--soon">
          <?php /* Stand-in shapes, blurred: the card's outline without any of its content. */ ?>
          <div class="ai4ai-soon-preview" aria-hidden="true">
            <span class="ai4ai-soon-shape ai4ai-soon-shape--logo"></span>
            <span class="ai4ai-soon-shape ai4ai-soon-shape--topic"></span>
            <span class="ai4ai-soon-shape ai4ai-soon-shape--title"></span>
            <span class="ai4ai-soon-shape"></span>
            <span class="ai4ai-soon-shape"></span>
            <span class="ai4ai-soon-shape ai4ai-soon-shape--short"></span>
            <span class="ai4ai-soon-shape ai4ai-soon-shape--action"></span>
          </div>
          <div class="ai4ai-soon-message">
            <p class="ai4ai-soon-badge">Launching soon</p>
            <h3>A new training platform</h3>
            <p>Another partner is joining AI4AI. Details will be shared at launch.</p>
          </div>
        </li>
        <?php continue; endif; ?>
        <li class="ai4ai-platform ai4ai-platform--<?= e($provider['accent']) ?>">
          <div class="ai4ai-logo">
            <img src="<?= e(asset('images/ai4ai/' . $provider['logo'])) ?>" alt="" role="presentation">
          </div>
          <p class="ai4ai-topic"><?= e($provider['topic']) ?></p>
          <h3><?= e($provider['name']) ?></h3>
          <p><?= e($provider['description']) ?></p>
          <a class="ai4ai-provider-link" href="<?= e($provider['url']) ?>" target="_blank" rel="noopener noreferrer">
            <span><?= e($provider['action']) ?></span>
            <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 5h6v6m0-6-9 9m7-1v5a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h5"/></svg>
            <span class="visually-hidden">(opens in a new tab)</span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>

  <section class="ai4ai-audiences" aria-labelledby="ai4ai-audiences-title">
    <div class="container ai4ai-section">
      <h2 id="ai4ai-audiences-title">Who AI4AI is designed for</h2>
      <p class="ai4ai-section-intro">The National AI Strategy identifies nine learner groups. Each group has different needs and a different starting point.</p>
      <ol class="ai4ai-segments">
        <?php foreach ($segments as $index => [$name, $description, $icon]): ?>
          <li>
            <span class="ai4ai-segment-number" aria-hidden="true">
              <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="<?= e($icon) ?>"/></svg>
              <span><?= $index + 1 ?></span>
            </span>
            <div>
              <h3><?= e($name) ?></h3>
              <p><?= e($description) ?></p>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>
      <a class="ai4ai-strategy" href="<?= e(url('document/aistrategy')) ?>" target="_self">Read the National AI Strategy</a>
    </div>
  </section>
</main>
<script type="module" src="<?= e(asset('js/ai4ai-hero.js')) ?>"></script>
