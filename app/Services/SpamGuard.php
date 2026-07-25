<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Lightweight, CAPTCHA-free spam signals for the contact form:
 * a honeypot field, a minimum human-plausible fill time, and a
 * session-based submission counter.
 */
final class SpamGuard
{
    private const SESSION_KEY = 'contact_rate_limit';

    /** Name of the hidden honeypot input — real users never see or fill it. */
    public const HONEYPOT_FIELD = 'website';

    /** Hidden field carrying the server timestamp of when the form was rendered. */
    public const TIMING_FIELD = 'form_rendered_at';

    public function __construct(
        private readonly int $minSubmitSeconds,
        private readonly int $rateLimitMax,
        private readonly int $rateLimitWindowSeconds,
    ) {
    }

    /**
     * @param array<string, mixed> $input
     */
    public function isHoneypotTriggered(array $input): bool
    {
        return trim((string) ($input[self::HONEYPOT_FIELD] ?? '')) !== '';
    }

    /**
     * True if the form was submitted faster than a human plausibly could
     * (or the timing field is missing/tampered with).
     *
     * @param array<string, mixed> $input
     */
    public function isTooFast(array $input): bool
    {
        $renderedAt = (int) ($input[self::TIMING_FIELD] ?? 0);

        if ($renderedAt <= 0) {
            return true;
        }

        return (time() - $renderedAt) < $this->minSubmitSeconds;
    }

    /**
     * Increments this session's submission counter and reports whether it
     * has now exceeded the allowed rate. Resets the window once it expires.
     */
    public function tooManyAttempts(): bool
    {
        $now = time();
        $bucket = $_SESSION[self::SESSION_KEY] ?? ['count' => 0, 'window_start' => $now];

        if ($now - $bucket['window_start'] > $this->rateLimitWindowSeconds) {
            $bucket = ['count' => 0, 'window_start' => $now];
        }

        $bucket['count']++;
        $_SESSION[self::SESSION_KEY] = $bucket;

        return $bucket['count'] > $this->rateLimitMax;
    }
}
