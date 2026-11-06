<?php
$providers = [
    [
        'name' => 'AWS Skill Builder',
        'topic' => 'AI and cloud skills',
        'description' => 'Explore Amazon Web Services training on generative AI, machine learning and building with AI in the cloud. The catalogue includes free self-paced courses and paid options.',
        'url' => 'https://aws.amazon.com/ai/learn/',
        'action' => 'Explore AWS training',
        'accent' => 'aws',
    ],
    [
        'name' => 'Google AI Essentials',
        'topic' => 'AI for everyday tasks',
        'description' => 'Practise writing prompts, using generative AI for everyday tasks and checking its output. Google offers this beginner course through Coursera. Check the course page for pricing.',
        'url' => 'https://www.coursera.org/google-certificates/ai-essentials-google',
        'action' => 'View Google AI Essentials',
        'accent' => 'google',
    ],
    [
        'name' => 'Infosys Springboard',
        'topic' => 'AI and digital literacy',
        'description' => 'Explore free digital learning from Infosys, including AI and other technology topics. Course availability may vary by region.',
        'url' => 'https://infyspringboard.onwingspan.com/web/en/page/home',
        'action' => 'Open Infosys Springboard',
        'accent' => 'infosys',
    ],
];

$segments = [
    ['Pre-primary', 'Build curiosity and safe digital habits through stories, play and guided activities.'],
    ['Primary', 'Recognise AI in everyday tools and start talking about fairness and safe use.'],
    ['Secondary', 'Explore AI through creative projects, coding and discussion about its role in society.'],
    ['Technical and vocational education (TVET)', 'Apply AI to practical tasks in technical and vocational settings.'],
    ['Tertiary education', 'Use AI responsibly in higher education, research and innovation.'],
    ['Special educational needs (SEN)', 'Explore assistive AI tools for accessibility, independence and personalised learning.'],
    ['Working adults', 'Apply AI responsibly to improve productivity and solve problems at work.'],
    ['Non-working adults', 'Build confidence using AI in daily life and staying safe online.'],
    ['Policy-makers and leaders', 'Develop the skills to guide responsible AI adoption, governance and policy.'],
];
?>
<main class="ai4ai" id="main-content" tabindex="-1">
  <header class="ai4ai-intro">
    <div class="container">
      <p class="ai4ai-kicker">AI for All and Inclusion</p>
      <h1>AI4AI</h1>
      <p class="ai4ai-lead">Find AI training in one place. Start with the basics, learn how to use AI responsibly or build skills for work and study.</p>
      <p class="ai4ai-context">AI4AI is Mauritius’ national programme to make AI knowledge and skills accessible to people of all ages and backgrounds.</p>
      <a class="ai4ai-jump" href="#ai4ai-training" target="_self">Browse training platforms <span aria-hidden="true">↓</span></a>
    </div>
  </header>

  <section class="container ai4ai-section" id="ai4ai-training" aria-labelledby="ai4ai-training-title">
    <h2 id="ai4ai-training-title">Choose a training platform</h2>
    <p class="ai4ai-section-intro">Courses take place on the provider’s website. Links open in a new tab. Check each provider for account requirements, costs and eligibility.</p>

    <ul class="ai4ai-platforms">
      <?php foreach ($providers as $provider): ?>
        <li class="ai4ai-platform ai4ai-platform--<?= e($provider['accent']) ?>">
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
        <?php foreach ($segments as $index => [$name, $description]): ?>
          <li>
            <span class="ai4ai-segment-number" aria-hidden="true"><?= $index + 1 ?></span>
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
