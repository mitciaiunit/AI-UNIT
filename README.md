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
2. Start Apache (and MySQL, once you use the database — see below) from the XAMPP/EasyPHP control panel.
3. Make sure Apache's `mod_rewrite` module is enabled (it is by default in XAMPP) and that `AllowOverride All` is set for the `htdocs` directory, so `public/.htaccess` takes effect.
4. Visit **`http://localhost/AI-UNIT/public/`** in your browser. That's the site's home page.
5. All other pages are reached through clean URLs handled by the router, e.g.:
   - `http://localhost/AI-UNIT/public/privacy-policy`
   - `http://localhost/AI-UNIT/public/document/blueprint`
   - `http://localhost/AI-UNIT/public/video/1`
   - `http://localhost/AI-UNIT/public/booklet/aie`

No build step, no Composer install, and no Node tooling are required — this is plain PHP served directly by Apache.

### Quick check without XAMPP

You can also smoke-test the app with PHP's built-in server from the project root:

```
php -S localhost:8000 router.php
```

using a one-line `router.php` that serves real files as-is and otherwise requires `public/index.php` (the built-in server, unlike Apache, needs an explicit router script to fall back to a front controller). This is only useful for a quick local check — XAMPP/Apache with `.htaccess` is the intended way to run the site.

## Database Configuration

The database is **prepared but not required yet** — no page currently reads from or writes to it. To set it up anyway (for future development):

1. In phpMyAdmin (or the MySQL CLI), import `database/schema.sql`. It creates the `ai_unit` database and four tables: `site_settings`, `documents`, `videos`, `contact_messages`.
2. By default, `config/database.php` connects as `root` with no password to `127.0.0.1:3306/ai_unit` — the standard XAMPP/EasyPHP MySQL defaults. Override any of these with environment variables if your setup differs:
   - `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`
3. Get a connection anywhere in the app via `App\Core\Database::connection()`, which returns a configured `PDO` instance (exceptions on error, prepared-statement-friendly).

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

- **No framework.** Routing, MVC-style separation, and templating are all hand-rolled in `app/Core/` — deliberately simple and dependency-free, per project requirements.
- **PSR-12 & namespaces.** All PHP under `app/` uses `declare(strict_types=1)`, the `App\` namespace, and one class per file.
- **Layout de-duplication.** The header, navbar, footer, cookie banner, and accessibility panel used to be copy-pasted (with drifting inconsistencies) across several pages. They're now single shared includes in `includes/`, used by every page.
- **Static-first data.** The video series, booklets, and Framework Library documents are still simple PHP arrays inside their controllers (matching the original static site's content exactly). The `documents`/`videos` database tables exist only as scaffolding for a future admin-managed version — nothing reads from them yet.
- **Original CSS/JS preserved.** `assets/css/style.css` and `assets/js/script.js` are the same files from the original site, moved as-is (only the handful of internal links that pointed at old `.html` filenames were updated to the new routes).
