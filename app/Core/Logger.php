<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Minimal file logger for internal errors (e.g. database failures) that must
 * never be shown to the user but still need to be recorded somewhere.
 */
final class Logger
{
    private const LOG_FILE = __DIR__ . '/../../storage/logs/app.log';

    /**
     * @param array<string, mixed> $context
     */
    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function warning(string $message, array $context = []): void
    {
        self::write('WARNING', $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    private static function write(string $level, string $message, array $context): void
    {
        $line = sprintf(
            '[%s] %s: %s%s%s',
            date('Y-m-d H:i:s'),
            $level,
            $message,
            $context === [] ? '' : ' ',
            $context === [] ? '' : json_encode($context, JSON_UNESCAPED_SLASHES)
        );

        error_log($line . PHP_EOL, 3, self::LOG_FILE);
    }
}
