<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Core\Logger;
use App\Models\ContactMessage;
use PDOException;

/**
 * Persistence for contact_messages. The only place in the app that knows
 * the table's column names or writes SQL for this feature.
 */
final class ContactMessageRepository
{
    /**
     * @return int|null The new row's id, or null if the insert failed.
     */
    public function save(ContactMessage $contactMessage): ?int
    {
        $sql = 'INSERT INTO contact_messages
                (full_name, email, subject, message, ip_address, user_agent)
                VALUES (:full_name, :email, :subject, :message, :ip_address, :user_agent)';

        try {
            $pdo = Database::connection();
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'full_name' => $contactMessage->fullName,
                'email' => $contactMessage->email,
                'subject' => $contactMessage->subject,
                'message' => $contactMessage->message,
                'ip_address' => $contactMessage->ipAddress,
                'user_agent' => $contactMessage->userAgent,
            ]);

            return (int) $pdo->lastInsertId();
        } catch (PDOException $e) {
            Logger::error('Failed to save contact message', ['error' => $e->getMessage()]);

            return null;
        }
    }
}
