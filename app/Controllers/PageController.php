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
        $this->view('home', [
            'title' => '',
            'isHome' => true,
            // /home and / render the same page; both canonicalise to /.
            'canonicalPath' => '/',
            'description' => 'The AI Unit leads Mauritius\' artificial intelligence strategy for the Ministry of Information Technology, Communication and Innovation - AI governance, the Digital Transformation Blueprint 2025-2029, public service modernisation and the national AI framework.',
        ]);
    }

    public function privacyPolicy(): void
    {
        $this->view('privacy-policy', [
            'title' => 'Privacy & Algorithmic Transparency Policy',
            'isHome' => false,
            'description' => 'How the AI Unit collects, uses and protects personal data on this site, and how decisions made with the help of algorithms are kept transparent and accountable.',
        ]);
    }

    public function disclaimer(): void
    {
        $this->view('disclaimer', [
            'title' => 'Disclaimer',
            'isHome' => false,
            'description' => 'Terms of use for the AI Unit website: the status of the information published here, and the limits of listings in the Regional AI Marketplace.',
        ]);
    }

    public function cookiePolicy(): void
    {
        $this->view('cookie-policy', [
            'title' => 'Cookie & Analytics Policy',
            'isHome' => false,
            'description' => 'Which cookies and browser storage this site uses, what each one is for, how long it lasts, and how to control them.',
        ]);
    }

    public function accessibility(): void
    {
        $this->view('accessibility', [
            'title' => 'Accessibility Statement',
            'isHome' => false,
            'description' => 'The AI Unit\'s accessibility commitment: the WCAG 2.1 AA standard applied to this site, the reading and navigation tools built into it, and how to report a barrier.',
        ]);
    }

    /**
     * Public AI Lab page. Booking is delegated to Calendly; nothing about a
     * booking is stored or processed here, so this action touches no database.
     */
    public function aiLab(): void
    {
        $this->view('ai-lab', [
            'title' => 'AI Lab',
            'description' => 'The AI Lab at the AI Unit - a government-approved innovation lab equipped in partnership with STEMpower, open to secondary and higher education students, technopreneurs and innovators. Book a session online.',
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
