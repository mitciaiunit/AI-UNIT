<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\HighlightService;

/**
 * The public /highlights page.
 *
 * Split out of PageController because this page is no longer static: its
 * gallery is database-driven, so it has a dependency the other content pages
 * do not.
 */
final class HighlightsController extends Controller
{
    public function __construct(
        private readonly HighlightService $highlights = new HighlightService(),
    ) {
    }

    public function index(): void
    {
        $highlights = $this->highlights->published();

        $this->view('highlights', [
            'title' => 'Highlights',
            'description' => 'A record of the ten-week industrial attachment at the AI Unit: rebuilding the aim.govmu.org portal, WCAG accessibility work, speech synthesis and the DIVA assistant, with galleries from the internship and the Rodrigues and Imperial programme.',
            'isHome' => false,
            'navCurrent' => 'highlights',
            // $categories keeps the view's existing loop unchanged; $highlights
            // carries why the list looks the way it does, so the page can tell
            // "nothing published" apart from "database unreachable".
            'categories' => $highlights->categories,
            'highlights' => $highlights,
            'highlightService' => $this->highlights,
            // This page carries its own design system; see the scoping note in
            // assets/css/highlights.css.
            'pageStyles' => ['highlights.css'],
            'pageScripts' => ['highlights.js'],
        ]);
    }
}
