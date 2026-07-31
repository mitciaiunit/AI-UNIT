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
        $this->view('highlights', [
            'title' => 'Highlights',
            'isHome' => false,
            'navCurrent' => 'highlights',
            'categories' => $this->highlights->publishedCategories(),
            'highlightService' => $this->highlights,
            // This page carries its own design system; see the scoping note in
            // assets/css/highlights.css.
            'pageStyles' => ['highlights.css'],
            'pageScripts' => ['highlights.js'],
        ]);
    }
}
