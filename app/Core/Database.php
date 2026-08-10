<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * Lazily-created PDO singleton. Not used by any page yet - reserved for the
 * future Repositories that will back the site_settings/documents/videos/
 * contact_messages tables.
 */
final class Database
{
    private static ?PDO $connection = null;

    /**
     * Remembers a failed connection for the rest of the request.
     *
     * Without this every call retried the connection, and a refused TCP
     * connection is not free: with MySQL stopped each attempt cost ~2s, so the
     * Highlights page (two queries) spent ~4s before rendering nothing. A page
     * that queried more, or a database on another host with a longer TCP
     * timeout, would run into max_execution_time. One request, one attempt.
     */
    private static ?PDOException $failure = null;

    public static function connection(): PDO
    {
        if (self::$failure !== null) {
            throw self::$failure;
        }

        if (self::$connection === null) {
            $config = require dirname(__DIR__, 2) . '/config/database.php';

            $dsn = sprintf(
                '%s:host=%s;port=%s;dbname=%s;charset=%s',
                $config['driver'],
                $config['host'],
                $config['port'],
                $config['database'],
                $config['charset']
            );

            try {
                self::$connection = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                /*
                 * The original message can carry the host, port, user and
                 * database name. It is recorded once for the log and then
                 * rethrown - callers must never put it in front of a visitor.
                 */
                self::$failure = new PDOException(
                    'Database connection failed: ' . $e->getMessage(),
                    (int) $e->getCode()
                );

                throw self::$failure;
            }
        }

        return self::$connection;
    }

    /**
     * Whether the database can be reached, without making the caller handle an
     * exception. Used by the admin area to refuse politely up front rather than
     * letting a query fail halfway through rendering a screen.
     */
    public static function isAvailable(): bool
    {
        try {
            self::connection();

            return true;
        } catch (PDOException) {
            return false;
        }
    }

    /** The reason the connection failed, for logging only. Never shown. */
    public static function failureReason(): ?string
    {
        return self::$failure?->getMessage();
    }
}
