<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

/**
 * Static, mostly-content pages that share the full site layout (navbar,
 * footer, cookie banner, accessibility panel, DIVA widget).
 */
final class PageController extends Controller
{
    public function home(): void
    {
        $this->view('home', ['title' => '', 'isHome' => true]);
    }

    public function privacyPolicy(): void
    {
        $this->view('privacy-policy', ['title' => 'Privacy & Algorithmic Transparency Policy', 'isHome' => false]);
    }

    public function disclaimer(): void
    {
        $this->view('disclaimer', ['title' => 'Disclaimer', 'isHome' => false]);
    }

    public function cookiePolicy(): void
    {
        $this->view('cookie-policy', ['title' => 'Cookie & Analytics Policy', 'isHome' => false]);
    }

    public function accessibility(): void
    {
        $this->view('accessibility', ['title' => 'Accessibility Statement', 'isHome' => false]);
    }

    /**
     * Public AI Lab page. Booking is delegated to Calendly; nothing about a
     * booking is stored or processed here, so this action touches no database.
     */
    public function aiLab(): void
    {
        $this->view('ai-lab', [
            'title' => 'AI Lab',
            'isHome' => false,
            'navCurrent' => 'ai-lab',
            'calendlyUrl' => $this->calendlyUrl(),
            'pageStyles' => ['ai-lab.css'],
            'pageScripts' => ['ai-lab.js'],
        ]);
    }

    /**
     * The configured Calendly link, or null when it is unset or not a Calendly
     * URL.
     *
     * Validated rather than trusted because the value is rendered into the page
     * and handed to Calendly's embed script. Restricting it to https on
     * calendly.com means a typo, a leftover placeholder, or a tampered
     * environment variable degrades to the "not yet configured" notice instead
     * of pointing the public at an arbitrary third-party site.
     */
    private function calendlyUrl(): ?string
    {
        $url = trim((string) config('ai_lab.calendly_url'));

        if ($url === '' || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return null;
        }

        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');

        if (($parts['scheme'] ?? '') !== 'https') {
            return null;
        }

        if ($host !== 'calendly.com' && !str_ends_with($host, '.calendly.com')) {
            return null;
        }

        return $url;
    }
}
