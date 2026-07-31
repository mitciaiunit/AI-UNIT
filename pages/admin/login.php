<?php
$disabled = !empty($lockedOut);
?>
<section class="adm-auth__card" aria-labelledby="admin-login-title">
  <div class="adm-auth__head">
    <img src="<?= e(asset('images/logo.gif')) ?>" alt="" width="46" height="46">
    <h1 id="admin-login-title">Highlights admin</h1>
    <p>Sign in to manage categories and gallery images.</p>
  </div>

  <form method="post" action="<?= e(url('admin/login')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <div class="adm-field">
      <label for="username">Username <span class="adm-required">*</span></label>
      <input type="text" id="username" name="username" autocomplete="username" required<?= $disabled ? ' disabled' : '' ?>>
    </div>

    <div class="adm-field">
      <label for="password">Password <span class="adm-required">*</span></label>
      <input type="password" id="password" name="password" autocomplete="current-password" required<?= $disabled ? ' disabled' : '' ?>>
    </div>

    <?php if ($disabled): ?>
      <p class="adm-field__hint">Too many failed attempts. Try again in <?= e((string) ceil(($lockoutSeconds ?? 0) / 60)) ?> minute(s).</p>
    <?php endif; ?>

    <button type="submit" class="adm-btn adm-btn--primary"<?= $disabled ? ' disabled' : '' ?>>Sign in</button>
  </form>

  <a class="adm-auth__back" href="<?= e(url('highlights')) ?>">Back to Highlights</a>
</section>
