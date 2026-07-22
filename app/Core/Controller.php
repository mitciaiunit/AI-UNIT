<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    /**
     * @param array<string, mixed> $data
     */
    protected function view(string $page, array $data = [], ?string $layout = 'app'): void
    {
        View::render($page, $data, $layout);
    }

    protected function notFound(): void
    {
        http_response_code(404);
        View::render('404', [], 'app');
    }
}
