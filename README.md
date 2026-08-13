# AI Unit Website

The public website of the AI Unit, Ministry of Information Technology, Communication and Innovation (MITCI), Republic of Mauritius.

This is a **vanilla PHP 8.x** project (no framework) using **PDO** for database access, built to be run on **XAMPP** or **EasyPHP**. It was migrated from a static HTML/CSS/JS site (previously under `frontend/`) into a modular PHP architecture, preserving the original design, styling, JavaScript behaviour, responsiveness, and accessibility features exactly as they were.

## Folder Structure

Everything the browser can reach is inside `public/`. Everything else - the
application code, configuration, `.env`, `vendor/` - sits above it and is not
served at any URL. **Apache's `DocumentRoot` must be `public/`, not the
repository root.**

```
public/             DOCUMENT ROOT - the ONLY folder your web server may point at
  index.php           Front controller: bootstraps the app and dispatches routes
  .htaccess            Clean-URL rewrite, security headers, dotfile deny
  assets/              Static files: css/, js/, images/, video/, captions/,
                       documents/, audio/
  uploads/             Admin-uploaded content; seeded Highlights images live in
                       public/uploads/highlights

pages/               PHP view templates - one (or one shared) template per route
  home.php             Homepage content (hero, about, framework, team, contact, …)
  privacy-policy.php, disclaimer.php, cookie-policy.php, accessibility.php
  highlights.php     Internship case study; ships its own CSS/JS (see below)
  admin/                Highlights admin templates
  document.php          PDF viewer chrome (used by every /document/{slug} route)
  video.php              Video player template (used by every /video/{id} route)
  booklet.php            pdf.js booklet reader (used by every /booklet/{slug} route)
  404.php

includes/            Shared, reusable HTML partials (no duplicated layout code)
  header.php, navbar.php, footer.php
  cookie-banner.php, a11y-panel.php, diva-widget.php, video-modal.php
  layouts/app.php       Wraps a page's content with the partials above
  layouts/admin.php     Signed-in Highlights admin layout
  layouts/admin-blank.php Sign-in layout

config/
  config.php            Site settings: name, base URL, asset path, DIVA API URL, contact email
  database.php           Database connection settings (PDO)

database/
  schema.sql             Table definitions and Highlights seed data

app/
  Core/                  Router, Controller base class, View renderer, Database (PDO) singleton
  Controllers/           PageController, DocumentController, VideoController, BookletController
  Services/              (empty - reserved for future integrations, e.g. a DIVA API client)
  Repositories/          (empty - reserved for future DB-backed data access, using PDO + prepared statements)
  Models/                (empty - reserved for future entities: Document, Video, ContactMessage)
  Helpers/
    functions.php        Global helpers: config(), asset(), url(), page_title(), redirect(), e()

routes/
  web.php                The route table (path → controller method)

api/                 (empty - reserved for future endpoints, e.g. a contact form submit handler)

storage/             Logs and cache. Above the document root, so unreachable.
tools/               Deployment scripts, admin-user CLI, Apache vhost template
.htaccess            Backstop deny rules, in case a server's DocumentRoot is
                     mistakenly set to the repository root instead of public/
```

`bootstrap.php` (project root) wires everything together: it registers a small `App\` autoloader (no Composer required), loads `app/Helpers/functions.php`, applies `config/config.php`, and starts the session. `public/index.php` requires it, builds the `Router`, loads `routes/web.php`, and dispatches the current request.

## Running Locally with XAMPP / EasyPHP

1. Clone the repository anywhere you like. It does **not** have to sit inside `htdocs`, and putting it there is no longer the recommended setup.
2. Run `composer install` once from the project root. This downloads PHPMailer and phpdotenv into `vendor/` (see [Contact Form](#contact-form) and [Environment Configuration](#environment-configuration-env) below). Nothing else in the site needs them, and every page still works without `vendor/` present; only outbound email and `.env` loading would be skipped.
3. Copy `.env.example` to `.env` and fill in your database credentials (see [Environment Configuration](#environment-configuration-env)).
4. **Point Apache at `public/`.** Copy the template in [`tools/apache-vhost.conf.example`](tools/apache-vhost.conf.example) into `xampp\apache\conf\extra\httpd-vhosts.conf`, replacing `/path/to/AI-UNIT` with the real path:

   ```apache
   <VirtualHost *:80>
       ServerName ai-unit.local
       DocumentRoot "C:/path/to/AI-UNIT/public"

       <Directory "C:/path/to/AI-UNIT/public">
           Options -Indexes +FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

   Add `127.0.0.1 ai-unit.local` to `C:\Windows\System32\drivers\etc\hosts`, or drop the `ServerName` line and use a spare port (`Listen 8081` + `<VirtualHost *:8081>`).
5. `AllowOverride All` is required - `public/.htaccess` supplies the clean-URL rewrite, the security headers and the dotfile deny, and none of them take effect without it. `mod_rewrite` and `mod_headers` must be enabled; both are on by default in XAMPP.
6. Restart Apache from the XAMPP control panel (a reload is not enough when a new `Listen` line is added), and start MySQL if you are using the database.
7. Visit **`http://ai-unit.local/`** (or `http://localhost:8081/`). All other pages are clean URLs handled by the router:
   - `http://ai-unit.local/privacy-policy`
   - `http://ai-unit.local/document/blueprint`
   - `http://ai-unit.local/video/1`
   - `http://ai-unit.local/booklet/aie`
   - `http://ai-unit.local/admin/login`

No build step and no Node tooling are required - this is plain PHP served directly by Apache, with Composer used solely to pull in two small dependencies (PHPMailer and phpdotenv - no framework).

### Why `DocumentRoot` must be `public/`

`public/` contains the front controller, `assets/` and `uploads/` - and nothing else. `.env`, `app/`, `config/`, `database/`, `storage/` and `vendor/` all live one level above it, so no URL reaches them.

Setting `DocumentRoot` to the repository root instead publishes all of it. That is not hypothetical: it is how this site was deployed until work order WO-01b, and `http://localhost/AI-UNIT/.env` returned HTTP 200 with the real SMTP and database credentials in the body. The repository-root `.htaccess` denies those paths as a backstop against the same mistake, but the containment is the directory layout - the `.htaccess` is only the seatbelt.

### Subdirectory deployment

The application derives its URL prefix from the request, so it also runs unchanged under a path - every generated link picks up the prefix automatically, with no configuration. Alias the path to `public/`:

```apache
Alias /ai-unit "C:/path/to/AI-UNIT/public"
```

or, on XAMPP, make a junction inside `htdocs` that targets `public/` (note the final segment - **not** the repository root):

```
mklink /J D:\xampp2\htdocs\ai-unit C:\path\to\AI-UNIT\public
```

The site is then at `http://localhost/ai-unit/`.

If you need to override the auto-detected prefix - typically behind a reverse proxy that rewrites paths - set `APP_BASE_URL` in `.env`.

### Media files (videos, audio, PDFs, captions) - required, not in this repo

The site's videos, audio narrations, PDFs and caption files total roughly **1.5 GB** and are **not stored in git**. `.gitignore` excludes `*.mp4`, `*.mp3`, `*.pdf` and `*.vtt`, and GitHub rejects files over 100 MB (one video alone is 385 MB). A fresh clone therefore has empty `public/assets/video`, `public/assets/audio`, `public/assets/documents` and `public/assets/captions` folders.

Without these files the site still loads, but: **videos stay at 0:00, the Listen buttons stay at 0:00, the PDF viewer reports "Unable to load the booklet", and document View/Download links 404.** If you are seeing those symptoms, this is why.

They live in the original pre-migration project folder (`AI-Unit-Website`), whose layout differs from this one. To copy them in with the right mapping:

```
powershell -ExecutionPolicy Bypass -File tools\deploy-media.ps1
```

| Source | Destination |
|---|---|
| `video\*.mp4` | `public\assets\video\` |
| `Audio\*.mp3` | `public\assets\audio\` |
| `document\*.pdf` | `public\assets\documents\` |
| `vtt\*.vtt` | `public\assets\captions\` |

The `public\` prefix matters: media copied above the document root is not reachable by a browser.

The source-to-destination mapping above is not written into the script - it is read from [`tools/media-manifest.json`](tools/media-manifest.json), the same file the verifier below uses, so the two cannot disagree about where media belongs.

### Checking that the media is actually there

`tools/verify-assets.ps1` reads the manifest and reports anything missing, empty, truncated or altered. It exits non-zero on failure, so it can gate a deploy:

```
powershell -ExecutionPolicy Bypass -File tools\verify-assets.ps1
powershell -ExecutionPolicy Bypass -File tools\verify-assets.ps1 -Checksum
powershell -ExecutionPolicy Bypass -File tools\verify-assets.ps1 -BaseUrl http://localhost:8081
powershell -ExecutionPolicy Bypass -File tools\verify-assets.ps1 -ProjectRoot D:\xampp2\htdocs\AI-UNIT
```

`-ProjectRoot` defaults to the repository this script sits in, so a plain run needs no arguments. `-BaseUrl` additionally fetches every asset over HTTP and checks the status and `Content-Type` - the only way to catch a server-side problem a filesystem check cannot see, such as captions being served without `text/vtt` (browsers silently ignore a `<track>` that arrives with the wrong type).

Assets marked `"required": false` in the manifest report as warnings instead of failures; that is how an accepted, known gap is recorded without turning every run red.

After legitimately replacing a media file, refresh its recorded size and hash with `-UpdateManifest`.

### Deploying changes to the running site

If Apache serves a **separate copy** of the site rather than your git working tree (e.g. `D:\xampp2\htdocs\AI-UNIT`), editing a file in the repo has no effect until it is copied across - a mismatch that repeatedly looked like "my change didn't work" when the change was correct and simply had not been deployed.

Run this after any change you want to see in the browser:

```
powershell -ExecutionPolicy Bypass -File tools\deploy.ps1          # copy code
powershell -ExecutionPolicy Bypass -File tools\deploy.ps1 -DryRun  # preview only
```

`-Target` is the directory that **contains** `public/`, not `public/` itself: the deployment keeps the repository's shape, with private code above the document root and `public/` below it. Apache's `DocumentRoot` then points at `<target>\public`.

It copies tracked code only and deliberately preserves the target's `.env`, `vendor/`, `storage/logs/`, `public/uploads/` and the large media folders, so deploying code never wipes credentials, uploads or media. Then hard-refresh the browser (Ctrl+F5).

### Quick check without XAMPP

You can also smoke-test the app with PHP's built-in server. Run it from the project root, with `public/` as the document root:

```
php -S 127.0.0.1:5600 -t public router.php
```

`-t public` is not optional. The built-in server does not read `.htaccess`, so `public/` being the document root is the only thing keeping `.env` and the application source out of reach; `router.php` enforces the same boundary a second time and refuses any path that resolves outside `public/`.

`router.php` serves real files under `public/` as-is and hands everything else to `public/index.php` - the built-in server, unlike Apache, needs an explicit router script to fall back to a front controller. This is only useful for a quick local check; Apache with `.htaccess` is the intended way to run the site.

## Environment Configuration (`.env`)

Every environment-specific value - database credentials, mail settings, app mode - lives in `.env`, not in code. `.env` is gitignored and never committed; **`.env.example` is the tracked template** and documents every variable the app reads.

1. `cp .env.example .env` (once, per machine/deployment).
2. Fill in real values - at minimum the `DATABASE_*` credentials for your MySQL setup.
3. That's it. `bootstrap.php` loads `.env` automatically on every request via [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) (`Dotenv::createImmutable(__DIR__)->safeLoad()`), populating `$_ENV`/`$_SERVER`, before `config/config.php` and `config/database.php` are read.

A few things worth knowing about how this fits together:

- **Nothing breaks without a `.env` file.** `safeLoad()` (not `load()`) means a missing `.env` is not a fatal error - every value in `config/config.php`/`config/database.php` still has a working fallback (the same XAMPP-friendly defaults this project always shipped with), so a checkout with no `.env` at all still runs against `127.0.0.1:3306/ai_unit` as `root` with an empty password.
- **A real server environment variable always wins over `.env`.** phpdotenv never overwrites a variable that's already set, so if you'd rather configure a deployment via Apache's `SetEnv` (or any real process environment variable) instead of shipping a `.env` file, that keeps working exactly as before - `.env` is an additional, more convenient way to set these, not the only way.
- **Everything is read through one helper.** `env('KEY', $default)` (`app/Helpers/functions.php`) checks `$_ENV`, then `$_SERVER`, then `getenv()`, in that order, and treats an empty value the same as "not set". `config/*.php` call `env(...)` to build the arrays that the existing `config('site.name')`-style helper then serves from - no new configuration system, just a new way for the existing one to get its values.

## Database Configuration

The database backs the live contact form (`contact_messages`) and the Highlights gallery/admin area. `site_settings`, `documents`, and `videos` remain scaffolding for future work. To set it up:

1. In phpMyAdmin (or the MySQL CLI), import `database/schema.sql`. It creates the `ai_unit` database, the contact table, scaffolding tables for future document/video CMS work, and the live Highlights admin tables. It also seeds the current Highlights categories and gallery images.
2. Set `DATABASE_HOST`, `DATABASE_PORT`, `DATABASE_NAME`, `DATABASE_USERNAME`, `DATABASE_PASSWORD` in `.env` to match (see [Environment Configuration](#environment-configuration-env) above). Left unset, `config/database.php` falls back to `root` with no password on `127.0.0.1:3306/ai_unit` - the standard XAMPP/EasyPHP MySQL defaults. (The older `DB_HOST`/`DB_PORT`/`DB_NAME`/`DB_USER`/`DB_PASS` names are still read as a fallback if you already had those set somewhere.)
3. Get a connection anywhere in the app via `App\Core\Database::connection()`, which returns a configured `PDO` instance (exceptions on error, prepared-statement-friendly).

## Highlights Admin

The renamed `/highlights` page is database-driven for its gallery section. The Framework Library documents are intentionally not managed here; their tables remain future scaffolding only.

Admin routes:

- `/admin/login` - sign in
- `/admin` - dashboard
- `/admin/categories` - add, edit, hide/show, delete and reorder categories
- `/admin/images` - upload, replace, edit, hide/show, delete, move between categories and reorder gallery images

Create the first administrator after importing `database/schema.sql`:

```
php tools/create-admin.php <username> <password> [display-name]
```

Passwords are stored with `password_hash()` and checked with `password_verify()`. Admin requests use session authentication, CSRF tokens, idle timeout enforcement and prepared statements. Uploaded images are saved under `public/uploads/highlights`, validated server-side by file size, MIME type and image dimensions, and protected by an `.htaccess` file that prevents executable content from running there.

## Contact Form

The homepage contact form (`Controller -> Service -> Repository -> Database`) is fully wired up:

- `App\Controllers\ContactController` - thin HTTP adapter; checks the CSRF token and returns JSON.
- `App\Services\ContactService` - orchestrates spam checks, validation, saving, and the email notification.
- `App\Services\ContactValidator` - required/optional fields, length limits, email format.
- `App\Services\SpamGuard` - honeypot field, minimum fill time, session-based rate limiting (no CAPTCHA).
- `App\Repositories\ContactMessageRepository` - the only class that writes SQL for this feature (prepared statements throughout).
- `App\Core\Csrf` - session-bound token, embedded as a hidden field and checked on every submit.

Every successful (and honeypot/timing-blocked) submission is logged or stored; database errors are written to `storage/logs/app.log` via `App\Core\Logger` and never shown to the user.

Email notifications are **off by default** - submissions are always saved to the database regardless of whether email is enabled or working. `App\Services\EmailService` sends over real SMTP via [PHPMailer](https://github.com/PHPMailer/PHPMailer) (installed with `composer install`, see above) - not PHP's built-in `mail()`, which has no way to authenticate with a real mail provider and is frequently blocked or unconfigured on shared hosting and local dev stacks alike.

To turn emails on, set these in `.env` (see [Environment Configuration](#environment-configuration-env) above - `.env.example` documents every one of these with the same explanations as the table below):

| Variable | Purpose |
|---|---|
| `EMAIL_ENABLED` | Set to `true` to actually send. Anything else (including unset) leaves email off - submissions still save normally. |
| `CONTACT_EMAIL` | Where notifications are sent (defaults to the site contact email). |
| `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` | The From: header on outgoing notifications. |
| `SMTP_HOST` | Your mail provider's SMTP server, e.g. `smtp.gmail.com` or your host's mail server. **Required** once `EMAIL_ENABLED=true` - an empty host is the #1 reason SMTP sending fails. |
| `SMTP_PORT` | Usually `587` (STARTTLS) or `465` (SMTPS). Defaults to `587`. |
| `SMTP_USERNAME`, `SMTP_PASSWORD` | Your mailbox credentials. Leave both blank only if your SMTP server genuinely allows unauthenticated relay (uncommon). |
| `SMTP_ENCRYPTION` | `tls` (STARTTLS, default), `ssl` (implicit TLS/SMTPS), or empty for none. |

Every send attempt is logged to `storage/logs/app.log` via `App\Core\Logger`: a success logs the recipient and SMTP host; a failure logs PHPMailer's own diagnostic string (`$mailer->ErrorInfo`), which distinguishes connection failures from authentication failures in plain language; and while `EMAIL_ENABLED` is false, every attempt still logs an "email skipped" line so it's never silently unclear whether sending is off versus broken.

Spam-guard thresholds (`CONTACT_MIN_SUBMIT_SECONDS`, `CONTACT_RATE_LIMIT_MAX`, `CONTACT_RATE_LIMIT_WINDOW`) are also in `.env` - see `config('contact.*')`.

## Configuration (`config/config.php`)

Site-wide values - site name, base URL, asset path, the (placeholder) DIVA API URL, and the contact email/phone - live in `config/config.php` and are read anywhere via the `config('site.name')`-style helper. Never hardcode these values in a template; add a new key to `config/config.php` instead.

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
3. **Add the view template** at `pages/my-new-page.php` - just the page's unique HTML content. It's automatically wrapped with the shared header, navbar, footer, cookie banner, accessibility panel, and DIVA widget by `includes/layouts/app.php`.
4. Use `asset('images/foo.png')` for any asset reference and `url('my-new-page')` for any internal link - never hardcode `/assets/...` or page paths directly, so the site keeps working regardless of install location.

For a page that needs its own completely different layout (like the PDF/booklet/video viewers, which intentionally don't use the main navbar), pass `null` as the layout in `$this->view('page', $data, null)` and make the template a full, self-contained HTML document.

### Pages with their own CSS or JavaScript

A page that needs styling or behaviour of its own - rather than a full separate
layout - adds `pageStyles` / `pageScripts` to its view data. They are filenames
under `public/assets/css/` and `public/assets/js/`, loaded after the shared assets (scripts
are deferred), and only on that page:

```php
$this->view('highlights', [
    'title' => 'Highlights',
    'pageStyles' => ['highlights.css'],
    'pageScripts' => ['highlights.js'],
]);
```

**Scope such a stylesheet under a single root class.** `highlights.css`
brings its own design system (Inter, its own token ramp, its own element
resets), so every rule in it is nested under `.sc`, the class on the page's
outermost wrapper. Without that, its `body`, `img`, `h1–h4` and `:root` rules
would restyle the shared navbar, footer and DIVA widget that the same layout
renders around it. Where a class name is shared with `style.css` (`.hero`), the
scoped rule resets the inherited properties explicitly - higher specificity only
wins for properties both rules declare.

## Architecture Notes

- **No framework.** Routing, MVC-style separation, and templating are all hand-rolled in `app/Core/` - deliberately simple, per project requirements. Composer exists solely to install PHPMailer (real SMTP sending for the contact form); the app's own classes are still autoloaded by the hand-rolled `App\` autoloader in `bootstrap.php`, not Composer's PSR-4.
- **PSR-12 & namespaces.** All PHP under `app/` uses `declare(strict_types=1)`, the `App\` namespace, and one class per file.
- **Layout de-duplication.** The header, navbar, footer, cookie banner, and accessibility panel used to be copy-pasted (with drifting inconsistencies) across several pages. They're now single shared includes in `includes/`, used by every page.
- **Static-first data.** The video series, booklets, and Framework Library documents are still simple PHP arrays inside their controllers (matching the original static site's content exactly). The `documents`/`videos` database tables exist only as scaffolding for a future admin-managed version - nothing reads from them yet.
- **Original CSS/JS preserved.** `public/assets/css/style.css` and `public/assets/js/script.js` are the same files from the original site, moved as-is (only the handful of internal links that pointed at old `.html` filenames were updated to the new routes).
