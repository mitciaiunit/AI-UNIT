<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

/**
 * Sign-in and sign-out. The only admin controller whose actions are reachable
 * without an existing session.
 */
final class AuthController extends AdminController
{
    public function showLogin(): void
    {
        if ($this->auth->check()) {
            redirect(url('admin'));

            return;
        }

        $this->view('admin/login', [
            'title' => 'Sign in',
            'flash' => $this->takeFlash(),
            'lockedOut' => $this->auth->isLockedOut(),
            'lockoutSeconds' => $this->auth->lockoutSecondsRemaining(),
        ], 'admin-blank');
    }

    public function login(): void
    {
        if (!$this->guardCsrf()) {
            return;
        }

        if ($this->auth->isLockedOut()) {
            $this->flash('error', sprintf(
                'Too many failed attempts. Try again in %d minute(s).',
                (int) ceil($this->auth->lockoutSecondsRemaining() / 60)
            ));
            redirect(url('admin/login'));

            return;
        }

        $username = $this->input('username');
        $password = $_POST['password'] ?? '';

        if ($username === '' || !is_string($password) || $password === '') {
            $this->flash('error', 'Please enter your username and password.');
            redirect(url('admin/login'));

            return;
        }

        if (!$this->auth->attempt($username, $password)) {
            /*
             * One message for every failure mode - unknown username, wrong
             * password, deactivated account. Saying which would tell an
             * attacker whether a username exists.
             */
            $this->flash('error', 'Those details were not recognised.');
            redirect(url('admin/login'));

            return;
        }

        $intended = $_SESSION['admin_intended'] ?? null;
        unset($_SESSION['admin_intended']);

        redirect($this->safeIntendedUrl($intended) ?? url('admin'));
    }

    public function logout(): void
    {
        if (!$this->guardCsrf()) {
            return;
        }

        $this->auth->logout();
        $this->flash('success', 'You have been signed out.');
        redirect(url('admin/login'));
    }

    /**
     * Only ever redirect back to a path on this site. Without this check the
     * stored value is an open redirect: an attacker could seed the session
     * with an external URL and bounce a freshly-signed-in admin to it.
     */
    private function safeIntendedUrl(mixed $intended): ?string
    {
        if (!is_string($intended) || $intended === '') {
            return null;
        }

        $path = parse_url($intended, PHP_URL_PATH);
        if (!is_string($path)) {
            return null;
        }

        // A scheme, a host, or a protocol-relative "//host" means off-site.
        if (parse_url($intended, PHP_URL_HOST) !== null || str_starts_with($intended, '//')) {
            return null;
        }

        $base = rtrim((string) config('site.base_url'), '/');
        if ($base !== '' && !str_starts_with($path, $base)) {
            return null;
        }

        return $path;
    }
}
