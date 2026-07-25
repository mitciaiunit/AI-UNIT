# AI Unit Website

The public website of the AI Unit, Ministry of Information Technology, Communication and Innovation (MITCI), Republic of Mauritius.

This is a **vanilla PHP 8.x** project (no framework) using **PDO** for database access, built to be run on **XAMPP** or **EasyPHP**. It was migrated from a static HTML/CSS/JS site (previously under `frontend/`) into a modular PHP architecture, preserving the original design, styling, JavaScript behaviour, responsiveness, and accessibility features exactly as they were.

## Folder Structure

```
public/             Web root — the ONLY folder your web server should point at
  index.php           Front controller: bootstraps the app and dispatches routes
  .htaccess            Rewrites clean URLs (e.g. /video/1) to index.php

pages/               PHP view templates — one (or one shared) template per route
  home.php             Homepage content (hero, about, framework, team, contact, …)
  privacy-policy.php, disclaimer.php, cookie-policy.php, accessibility.php
  document.php          PDF viewer chrome (used by every /document/{slug} route)
  video.php              Video player template (used by every /video/{id} route)
  booklet.php            pdf.js booklet reader (used by every /booklet/{slug} route)
  404.php

includes/            Shared, reusable HTML partials (no duplicated layout code)
  header.php, navbar.php, footer.php
  cookie-banner.php, a11y-panel.php, diva-widget.php, search-modal.php, video-modal.php
  layouts/app.php       Wraps a page's content with the partials above

config/
  config.php            Site settings: name, base URL, asset path, DIVA API URL, contact email
  database.php           Database connection settings (PDO)

database/
  schema.sql             Table definitions (site_settings, documents, videos, contact_messages)

app/
  Core/                  Router, Controller base class, View renderer, Database (PDO) singleton
  Controllers/           PageController, DocumentController, VideoController, BookletController
  Services/              (empty — reserved for future integrations, e.g. a DIVA API client)
  Repositories/          (empty — reserved for future DB-backed data access, using PDO + prepared statements)
  Models/                (empty — reserved for future entities: Document, Video, ContactMessage)
  Helpers/
    functions.php        Global helpers: config(), asset(), url(), page_title(), redirect(), e()

routes/
  web.php                The route table (path → controller method)

api/                 (empty — reserved for future endpoints, e.g. a contact form submit handler)

assets/              Static files served directly by the web server
  css/style.css, js/script.js, images/, video/, captions/, documents/, audio/

uploads/             (empty — reserved for future admin-uploaded content)
storage/             (empty — reserved for logs/cache; not web-accessible)
```

`bootstrap.php` (project root) wires everything together: it registers a small `App\` autoloader (no Composer required), loads `app/Helpers/functions.php`, applies `config/config.php`, and starts the session. `public/index.php` requires it, builds the `Router`, loads `routes/web.php`, and dispatches the current request.

## Running Locally with XAMPP / EasyPHP

1. Copy (or clone) this entire project folder into your web server's document root — e.g. `C:\xampp\htdocs\AI-UNIT` for XAMPP.
2. Run `composer install` once from the project root. This downloads PHPMailer and phpdotenv into `vendor/` (see [Contact Form](#contact-form) and [Environment Configuration](#environment-configuration-env) below). Nothing else in the site needs them, and every page still works without `vendor/` present; only outbound email and `.env` loading would be skipped.
3. Copy `.env.example` to `.env` and fill in your database credentials (see [Environment Configuration](#environment-configuration-env)).
4. Start Apache (and MySQL, once you use the database — see below) from the XAMPP/EasyPHP control panel.
5. Make sure Apache's `mod_rewrite` module is enabled (it is by default in XAMPP) and that `AllowOverride All` is set for the `htdocs` directory, so `public/.htaccess` takes effect.
6. Visit **`http://localhost/AI-UNIT/public/`** in your browser. That's the site's home page.
7. All other pages are reached through clean URLs handled by the router, e.g.:
   - `http://localhost/AI-UNIT/public/privacy-policy`
   - `http://localhost/AI-UNIT/public/document/blueprint`
   - `http://localhost/AI-UNIT/public/video/1`
   - `http://localhost/AI-UNIT/public/booklet/aie`

No build step and no Node tooling are required — this is plain PHP served directly by Apache, with Composer used solely to pull in two small dependencies (PHPMailer and phpdotenv — no framework).

### Quick check without XAMPP

You can also smoke-test the app with PHP's built-in server from the project root:

```
php -S localhost:8000 router.php
```

using a one-line `router.php` that serves real files as-is and otherwise requires `public/index.php` (the built-in server, unlike Apache, needs an explicit router script to fall back to a front controller). This is only useful for a quick local check — XAMPP/Apache with `.htaccess` is the intended way to run the site.

## Environment Configuration (`.env`)

Every environment-specific value — database credentials, mail settings, app mode — lives in `.env`, not in code. `.env` is gitignored and never committed; **`.env.example` is the tracked template** and documents every variable the app reads.

1. `cp .env.example .env` (once, per machine/deployment).
2. Fill in real values — at minimum the `DATABASE_*` credentials for your MySQL setup.
3. That's it. `bootstrap.php` loads `.env` automatically on every request via [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) (`Dotenv::createImmutable(__DIR__)->safeLoad()`), populating `$_ENV`/`$_SERVER`, before `config/config.php` and `config/database.php` are read.

A few things worth knowing about how this fits together:

- **Nothing breaks without a `.env` file.** `safeLoad()` (not `load()`) means a missing `.env` is not a fatal error — every value in `config/config.php`/`config/database.php` still has a working fallback (the same XAMPP-friendly defaults this project always shipped with), so a checkout with no `.env` at all still runs against `127.0.0.1:3306/ai_unit` as `root` with an empty password.
- **A real server environment variable always wins over `.env`.** phpdotenv never overwrites a variable that's already set, so if you'd rather configure a deployment via Apache's `SetEnv` (or any real process environment variable) instead of shipping a `.env` file, that keeps working exactly as before — `.env` is an additional, more convenient way to set these, not the only way.
- **Everything is read through one helper.** `env('KEY', $default)` (`app/Helpers/functions.php`) checks `$_ENV`, then `$_SERVER`, then `getenv()`, in that order, and treats an empty value the same as "not set". `config/*.php` call `env(...)` to build the arrays that the existing `config('site.name')`-style helper then serves from — no new configuration system, just a new way for the existing one to get its values.

## Database Configuration

The database backs the live contact form (`contact_messages`); `site_settings`, `documents`, and `videos` remain scaffolding for future work. To set it up:

1. In phpMyAdmin (or the MySQL CLI), import `database/schema.sql`. It creates the `ai_unit` database and four tables: `site_settings`, `documents`, `videos`, `contact_messages`.
2. Set `DATABASE_HOST`, `DATABASE_PORT`, `DATABASE_NAME`, `DATABASE_USERNAME`, `DATABASE_PASSWORD` in `.env` to match (see [Environment Configuration](#environment-configuration-env) above). Left unset, `config/database.php` falls back to `root` with no password on `127.0.0.1:3306/ai_unit` — the standard XAMPP/EasyPHP MySQL defaults. (The older `DB_HOST`/`DB_PORT`/`DB_NAME`/`DB_USER`/`DB_PASS` names are still read as a fallback if you already had those set somewhere.)
3. Get a connection anywhere in the app via `App\Core\Database::connection()`, which returns a configured `PDO` instance (exceptions on error, prepared-statement-friendly).

## Contact Form

The homepage contact form (`Controller -> Service -> Repository -> Database`) is fully wired up:

- `App\Controllers\ContactController` — thin HTTP adapter; checks the CSRF token and returns JSON.
- `App\Services\ContactService` — orchestrates spam checks, validation, saving, and the email notification.
- `App\Services\ContactValidator` — required/optional fields, length limits, email format.
- `App\Services\SpamGuard` — honeypot field, minimum fill time, session-based rate limiting (no CAPTCHA).
- `App\Repositories\ContactMessageRepository` — the only class that writes SQL for this feature (prepared statements throughout).
- `App\Core\Csrf` — session-bound token, embedded as a hidden field and checked on every submit.

Every successful (and honeypot/timing-blocked) submission is logged or stored; database errors are written to `storage/logs/app.log` via `App\Core\Logger` and never shown to the user.

Email notifications are **off by default** — submissions are always saved to the database regardless of whether email is enabled or working. `App\Services\EmailService` sends over real SMTP via [PHPMailer](https://github.com/PHPMailer/PHPMailer) (installed with `composer install`, see above) — not PHP's built-in `mail()`, which has no way to authenticate with a real mail provider and is frequently blocked or unconfigured on shared hosting and local dev stacks alike.

To turn emails on, set these in `.env` (see [Environment Configuration](#environment-configuration-env) above — `.env.example` documents every one of these with the same explanations as the table below):

| Variable | Purpose |
|---|---|
| `EMAIL_ENABLED` | Set to `true` to actually send. Anything else (including unset) leaves email off — submissions still save normally. |
| `CONTACT_EMAIL` | Where notifications are sent (defaults to the site contact email). |
| `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` | The From: header on outgoing notifications. |
| `SMTP_HOST` | Your mail provider's SMTP server, e.g. `smtp.gmail.com` or your host's mail server. **Required** once `EMAIL_ENABLED=true` — an empty host is the #1 reason SMTP sending fails. |
| `SMTP_PORT` | Usually `587` (STARTTLS) or `465` (SMTPS). Defaults to `587`. |
| `SMTP_USERNAME`, `SMTP_PASSWORD` | Your mailbox credentials. Leave both blank only if your SMTP server genuinely allows unauthenticated relay (uncommon). |
| `SMTP_ENCRYPTION` | `tls` (STARTTLS, default), `ssl` (implicit TLS/SMTPS), or empty for none. |

Every send attempt is logged to `storage/logs/app.log` via `App\Core\Logger`: a success logs the recipient and SMTP host; a failure logs PHPMailer's own diagnostic string (`$mailer->ErrorInfo`), which distinguishes connection failures from authentication failures in plain language; and while `EMAIL_ENABLED` is false, every attempt still logs an "email skipped" line so it's never silently unclear whether sending is off versus broken.

Spam-guard thresholds (`CONTACT_MIN_SUBMIT_SECONDS`, `CONTACT_RATE_LIMIT_MAX`, `CONTACT_RATE_LIMIT_WINDOW`) are also in `.env` — see `config('contact.*')`.

## Configuration (`config/config.php`)

Site-wide values — site name, base URL, asset path, the (placeholder) DIVA API URL, and the contact email/phone — live in `config/config.php` and are read anywhere via the `config('site.name')`-style helper. Never hardcode these values in a template; add a new key to `config/config.php` instead.

The base URL is auto-detected from the request, so the site works unmodified whether it's installed at the domain root or in a subdirectory like `/AI-UNIT/`. Override it with the `APP_BASE_URL` environment variable if you need to force a specific value (e.g. behind a reverse proxy).

## How to Add a New Page

1. **Add a route** in `routes/web.php`:
   ```php
   $router->get('/my-new-page', [PageController::class, 'myNewPage']);
   ```
2. **Add a controller method** (in `app/Controllers/PageController.php`, or a new controller under `app/Controllers/` for a bigger feature):
   ```php
   public function myNewPage(): void
   {
       $this->view('my-new-page', ['title' => 'My New Page', 'isHome' => false]);
   }
   ```
3. **Add the view template** at `pages/my-new-page.php` — just the page's unique HTML content. It's automatically wrapped with the shared header, navbar, footer, cookie banner, accessibility panel, and DIVA widget by `includes/layouts/app.php`.
4. Use `asset('images/foo.png')` for any asset reference and `url('my-new-page')` for any internal link — never hardcode `/assets/...` or page paths directly, so the site keeps working regardless of install location.

For a page that needs its own completely different layout (like the PDF/booklet/video viewers, which intentionally don't use the main navbar), pass `null` as the layout in `$this->view('page', $data, null)` and make the template a full, self-contained HTML document.

## Architecture Notes

- **No framework.** Routing, MVC-style separation, and templating are all hand-rolled in `app/Core/` — deliberately simple, per project requirements. Composer exists solely to install PHPMailer (real SMTP sending for the contact form); the app's own classes are still autoloaded by the hand-rolled `App\` autoloader in `bootstrap.php`, not Composer's PSR-4.
- **PSR-12 & namespaces.** All PHP under `app/` uses `declare(strict_types=1)`, the `App\` namespace, and one class per file.
- **Layout de-duplication.** The header, navbar, footer, cookie banner, and accessibility panel used to be copy-pasted (with drifting inconsistencies) across several pages. They're now single shared includes in `includes/`, used by every page.
- **Static-first data.** The video series, booklets, and Framework Library documents are still simple PHP arrays inside their controllers (matching the original static site's content exactly). The `documents`/`videos` database tables exist only as scaffolding for a future admin-managed version — nothing reads from them yet.
- **Original CSS/JS preserved.** `assets/css/style.css` and `assets/js/script.js` are the same files from the original site, moved as-is (only the handful of internal links that pointed at old `.html` filenames were updated to the new routes).
