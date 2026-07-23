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

    /**
     * @param array<string, mixed> $data
     */
    protected function jsonResponse(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
