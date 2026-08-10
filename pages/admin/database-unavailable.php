<?php
/**
 * Shown instead of any admin screen when the database cannot be reached.
 *
 * Says what is wrong in terms an administrator can act on, and deliberately
 * carries no exception message, host, port, user or SQL - those go to
 * storage/logs, which is where whoever fixes it will look anyway.
 */
?>
<section class="adm-auth__card" aria-labelledby="adm-unavailable-title">
  <div class="adm-auth__head">
    <img src="<?= e(asset('images/logo.gif')) ?>" alt="">
    <h1 id="adm-unavailable-title">Content management is temporarily unavailable</h1>
  </div>

  <p style="font-size:.92rem;color:var(--adm-ink-3);margin:0 0 14px;">
    The Highlights admin cannot reach the database, so categories and gallery
    images cannot be listed or changed right now. Nothing has been lost - your
    existing content is untouched.
  </p>

  <p style="font-size:.92rem;color:var(--adm-ink-3);margin:0 0 18px;">
    If you are running the site locally, start <strong>MySQL</strong> from the
    XAMPP Control Panel and reload this page. If this is the live server,
    please contact the Government Online Centre. The technical details have
    been written to the application log.
  </p>

  <a class="adm-btn adm-btn--primary" href="<?= e(url('admin')) ?>">Try again</a>

  <a class="adm-auth__back" href="<?= e(url('highlights')) ?>">Back to the public Highlights page</a>
</section>
