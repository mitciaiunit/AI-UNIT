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
}
