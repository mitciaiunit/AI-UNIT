<?php
/**
 * Server-rendered outcome of a contact form submission.
 *
 * Only reached when the form was posted WITHOUT JavaScript. The fetch in
 * assets/js/script.js sends X-Requested-With and receives JSON, so this page
 * never appears for a scripted submission - see ContactController::submit().
 *
 * Without it, a visitor with JavaScript disabled received a raw JSON document
 * as their confirmation screen.
 *
 * @var bool                $success
 * @var string              $message
 * @var array<string,string> $errors  Field name => message, may be empty.
 */
$title = $success ? 'Message Sent' : 'Message Not Sent';
$isHome = false;
?>
<main class="main-content" id="main-content" tabindex="-1">
  <div class="container">
    <div class="page-header">
      <div class="page-eyebrow"><?= $success ? 'Contact' : 'Contact - Not Sent' ?></div>
      <h1 class="page-title"><?= $success ? 'Thank you' : 'Your message was not sent' ?></h1>
      <p class="page-subtitle"><?= e($message) ?></p>

      <?php if (!$success && $errors !== []): ?>
        <?php /* Named list so a screen reader announces which fields to correct,
                 rather than only the summary sentence above. */ ?>
        <h2 class="page-subtitle">Please correct the following</h2>
        <ul>
          <?php foreach ($errors as $field => $error): ?>
            <li><?= e(is_string($field) ? ucfirst(str_replace('_', ' ', $field)) . ': ' : '') ?><?= e((string) $error) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <p class="page-subtitle">
        <a href="<?= e(url('/#contact')) ?>">Return to the contact form</a>
        or email
        <?= email_link() ?>.
      </p>
    </div>
  </div>
</main>
