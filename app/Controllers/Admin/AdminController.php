<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Logger;
use App\Models\AdminUser;
use App\Services\AuthService;

/**
 * Base class for every screen behind the admin sign-in.
 *
 * Extending this is what protects a controller: guard() is called at the top
 * of each action and there is no route that reaches an admin screen without
 * it. A future protected section only has to extend this class.
 */
abstract class AdminController extends Controller
{
    private const FLASH_KEY = 'admin_flash';

    protected AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    /**
     * Requires a signed-in admin. Returns the user, or null after having
     * already sent a redirect - callers must `return` immediately on null.
     */
    protected function guard(): ?AdminUser
    {
        /*
         * Checked before the session, because every admin screen both reads
         * and writes content. Letting one render without a database would show
         * an editor empty lists and forms whose Save button cannot work - an
         * interface that looks functional and silently is not. Better to say
         * so plainly and let them come back.
         */
        if (!$this->requireDatabase()) {
            return null;
        }

        $user = $this->auth->user();

        if ($user === null) {
            // Remember where they were headed so sign-in can bounce them back.
            $_SESSION['admin_intended'] = $_SERVER['REQUEST_URI'] ?? null;
            $this->flash('error', 'Please sign in to continue.');
            redirect(url('admin/login'));

            return null;
        }

        return $user;
    }

    /**
     * Requires a reachable database. Returns false after having already
     * rendered the outage page - callers must `return` immediately.
     *
     * Responds 503 with Retry-After so this reads as a temporary condition to
     * crawlers and monitoring rather than a broken or missing page.
     */
    protected function requireDatabase(): bool
    {
        if (Database::isAvailable()) {
            return true;
        }

        // The reason goes to the log; the screen gets a plain explanation.
        Logger::error('Admin area unavailable - database unreachable', [
            'error' => Database::failureReason(),
            'path' => $_SERVER['REQUEST_URI'] ?? '',
        ]);

        http_response_code(503);
        header('Retry-After: 300');

        $this->view('admin/database-unavailable', [
            'title' => 'Temporarily unavailable',
            'flash' => null,
        ], 'admin-blank');

        return false;
    }

    /**
     * Requires a valid CSRF token on a state-changing request. Returns false
     * after having already responded - callers must `return` immediately.
     */
    protected function guardCsrf(): bool
    {
        if (Csrf::isValid($_POST['csrf_token'] ?? null)) {
            return true;
        }

        $this->flash('error', 'That form expired. Please try again.');
        redirect(url('admin'));

        return false;
    }

    /**
     * Renders an admin page inside the admin layout.
     *
     * @param array<string, mixed> $data
     */
    protected function adminView(string $page, array $data = []): void
    {
        $data['flash'] = $this->takeFlash();
        $data['currentUser'] = $data['currentUser'] ?? $this->auth->user();

        $this->view('admin/' . $page, $data, 'admin');
    }

    /** Queues a one-shot message to show after a redirect. */
    protected function flash(string $type, string $message): void
    {
        $_SESSION[self::FLASH_KEY] = ['type' => $type, 'message' => $message];
    }

    /**
     * @return array{type: string, message: string}|null
     */
    protected function takeFlash(): ?array
    {
        $flash = $_SESSION[self::FLASH_KEY] ?? null;
        unset($_SESSION[self::FLASH_KEY]);

        return is_array($flash) ? $flash : null;
    }

    /**
     * Reads and trims a posted field.
     */
    protected function input(string $key, string $default = ''): string
    {
        $value = $_POST[$key] ?? $default;

        return is_string($value) ? trim($value) : $default;
    }

    protected function checkbox(string $key): bool
    {
        return isset($_POST[$key]);
    }
}
