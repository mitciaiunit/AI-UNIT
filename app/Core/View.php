<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Minimal template renderer: includes a page template with $data extracted
 * into scope, optionally wrapping the captured output in a layout template.
 */
final class View
{
    private const PAGES_PATH = __DIR__ . '/../../pages/';
    private const LAYOUTS_PATH = __DIR__ . '/../../includes/layouts/';

    /**
     * @param array<string, mixed> $data
     */
    public static function render(string $page, array $data = [], ?string $layout = 'app'): void
    {
        $content = self::capture(self::PAGES_PATH . $page . '.php', $data);

        if ($layout === null) {
            echo $content;
            return;
        }

        $data['content'] = $content;
        echo self::capture(self::LAYOUTS_PATH . $layout . '.php', $data);
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function capture(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;

        return (string) ob_get_clean();
    }
}
