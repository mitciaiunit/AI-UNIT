<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Sends the Content-Security-Policy for every page the application renders.
 *
 * Called once from bootstrap.php, before any output.
 *
 * WHY THIS IS IN PHP AND THE OTHER HEADERS ARE NOT
 * ------------------------------------------------
 * X-Content-Type-Options, Referrer-Policy, X-Frame-Options and
 * Permissions-Policy are static strings, so the root .htaccess sets them -
 * that way they also cover static files under assets/ and uploads/, and
 * Apache's own error pages, which never reach PHP.
 *
 * The CSP cannot be static: connect-src has to name whichever DIVA endpoint
 * is configured, and that is only known once config/config.php has been read.
 * Sending it from here also means the single most important header does not
 * depend on mod_headers being loaded on the target server.
 *
 * ABOUT 'unsafe-inline'
 * ---------------------
 * The site currently carries inline <script> blocks in five templates, three
 * <style> blocks, 23 inline style attributes, and seven inline event handlers
 * (onclick/onsubmit). Nonces do not cover inline event handlers at all, and
 * removing them is a refactor of markup this work item is explicitly not
 * allowed to touch. Declaring 'unsafe-inline' is therefore an accurate
 * description of what the site does today, not an oversight.
 *
 * The policy is still worth having without it: the host allow-lists below stop
 * an injected <script src> pointing anywhere the site does not already use,
 * object-src 'none' blocks plugin content, base-uri 'self' blocks base-tag
 * hijacking, and frame-ancestors 'self' blocks clickjacking. Dropping
 * 'unsafe-inline' is a follow-up once the inline handlers are gone.
 */
final class SecurityHeaders
{
    /**
     * pdf.js, loaded by the booklet viewer (pages/booklet.php).
     */
    private const CDNJS = 'https://cdnjs.cloudflare.com';

    /**
     * Tabler icon webfont and its stylesheet (booklet viewer), and the Twemoji
     * 14.0.2 library loaded by includes/layouts/app.php.
     */
    private const JSDELIVR = 'https://cdn.jsdelivr.net';

    /**
     * Where Twemoji fetches its flag images from. The library's own default
     * asset base, verified live at 14.0.2 - the script comes from jsDelivr but
     * the SVGs it points at do not, so allowing the script origin alone would
     * load the library and then block every image it inserted.
     */
    private const TWEMOJI_ASSETS = 'https://twemoji.maxcdn.com';

    /**
     * Calendly's booking widget (assets/js/ai-lab.js). Both forms are needed:
     * a wildcard does not match the bare apex domain.
     */
    private const CALENDLY = ['https://calendly.com', 'https://*.calendly.com'];

    /**
     * Google Fonts. The stylesheet host and the font-file host are different
     * and are allowed under different directives. Requested statically in
     * includes/header.php and injected at runtime by
     * assets/js/accessibility-widget.js.
     */
    private const GOOGLE_FONTS_CSS = 'https://fonts.googleapis.com';
    private const GOOGLE_FONTS_FILES = 'https://fonts.gstatic.com';

    /**
     * Hero background video (pages/home.php). Hardcoded there, so it is
     * hardcoded here too - if that URL changes, this constant changes with it
     * or the video stops playing.
     */
    private const HERO_VIDEO_HOST = 'https://d8j0ntlcm91z4.cloudfront.net';

    public static function send(): void
    {
        // Nothing to send for tools/create-admin.php and friends, and a
        // header() call after output has begun is a warning, not a fix.
        if (PHP_SAPI === 'cli' || headers_sent()) {
            return;
        }

        // Apache/PHP advertise the exact PHP build by default, which tells an
        // attacker which version-specific bugs are worth trying.
        header_remove('X-Powered-By');

        header('Content-Security-Policy: ' . self::policy());
    }

    private static function policy(): string
    {
        $calendly = implode(' ', self::CALENDLY);

        $directives = [
            // Anything not named below falls back to same-origin only.
            'default-src' => "'self'",

            'script-src' => "'self' 'unsafe-inline' " . self::CDNJS . ' ' . self::JSDELIVR . ' ' . $calendly,
            'style-src' => "'self' 'unsafe-inline' " . self::GOOGLE_FONTS_CSS
                . ' ' . self::JSDELIVR . ' ' . $calendly,

            // data: covers the SVG cursor defined in accessibility-widget.js.
            'font-src' => "'self' data: " . self::GOOGLE_FONTS_FILES . ' ' . self::JSDELIVR,

            // blob: is required by pdf.js, which paints pages to a canvas and
            // can hand back blob URLs for them.
            'img-src' => "'self' data: blob: " . self::TWEMOJI_ASSETS . ' ' . $calendly,

            'media-src' => "'self' " . self::HERO_VIDEO_HOST,

            // 'self' covers the same-origin PDF iframe in pages/document.php.
            'frame-src' => "'self' " . $calendly,

            // pdf.js sets workerSrc to a cdnjs URL and falls back to a blob
            // worker when the browser refuses a cross-origin one, so both are
            // allowed rather than guessing which path a given browser takes.
            'worker-src' => "'self' blob: " . self::CDNJS,

            /*
             * Built by joining non-empty parts rather than by concatenation:
             * divaOrigin() returns '' whenever DIVA is unconfigured, which is
             * now a supported state rather than a theoretical one, and simple
             * concatenation left a doubled space in the emitted header. CSP
             * parsers ignore the extra whitespace, but a security header that
             * looks malformed invites someone to "fix" it.
             */
            /*
             * The font and CDN origins appear here as well as in style-src /
             * font-src because Chrome checks the speculative connection a
             * <link rel="preconnect"> or stylesheet fetch opens against
             * connect-src, not only the eventual resource load. Without them
             * every page logged a CSP violation per stylesheet: the fonts
             * still rendered, but a console full of policy noise hides the
             * violations that would matter.
             *
             * Found by capturing the real browser console during final QA - no
             * static inspection of the header could have surfaced it. These
             * origins are already trusted for the very same files by style-src
             * and font-src, so nothing new is permitted.
             */
            'connect-src' => implode(' ', array_filter([
                "'self'",
                self::divaOrigin(),
                self::GOOGLE_FONTS_CSS,
                self::GOOGLE_FONTS_FILES,
                self::JSDELIVR,
                $calendly,
            ])),

            // No <object>/<embed>/<applet> anywhere on the site.
            'object-src' => "'none'",

            // Stops an injected <base> silently re-pointing every relative URL.
            'base-uri' => "'self'",

            // Clickjacking: the modern equivalent of X-Frame-Options.
            'frame-ancestors' => "'self'",

            // The contact form is the only form, and it posts to /contact.
            'form-action' => "'self'",
        ];

        $parts = [];
        foreach ($directives as $name => $sources) {
            $parts[] = $name . ' ' . $sources;
        }

        return implode('; ', $parts);
    }

    /**
     * The scheme://host[:port] of the configured DIVA endpoint, for
     * connect-src. Returns an empty string when DIVA is not configured or the
     * value is not a usable http(s) URL - in that case the browser simply
     * refuses the request, which is the correct outcome for a misconfigured
     * endpoint.
     */
    private static function divaOrigin(): string
    {
        $url = (string) config('diva.api_url', '');
        if ($url === '') {
            return '';
        }

        $parts = parse_url($url);
        $scheme = $parts['scheme'] ?? '';
        $host = $parts['host'] ?? '';

        if ($host === '' || !in_array($scheme, ['http', 'https'], true)) {
            return '';
        }

        $origin = $scheme . '://' . $host;

        return isset($parts['port']) ? $origin . ':' . $parts['port'] : $origin;
    }
}
