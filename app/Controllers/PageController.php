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
     * The only page that carries styling and behaviour of its own: it is a
     * self-contained case study with a different design language from the rest
     * of the site, so it loads student-corner.css/.js on top of the shared
     * assets rather than folding its rules into style.css.
     */
    public function studentCorner(): void
    {
        $this->view('student-corner', [
            'title' => 'Student Corner',
            'isHome' => false,
            'navCurrent' => 'student-corner',
            'pageStyles' => ['student-corner.css'],
            'pageScripts' => ['student-corner.js'],
        ]);
    }
}
