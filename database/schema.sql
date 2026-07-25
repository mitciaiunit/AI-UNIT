-- AI Unit website — database schema
--
-- site_settings, documents, and videos only prepare the project for future
-- development — the current pages still use static PHP config arrays for
-- those. contact_messages is live: the homepage contact form (see
-- App\Controllers\ContactController) reads and writes it.
--
-- Usage (XAMPP/EasyPHP): create a database named `ai_unit` in phpMyAdmin,
-- then import this file.

CREATE DATABASE IF NOT EXISTS ai_unit
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE ai_unit;

-- Key/value site configuration (site name, contact details, feature flags,
-- future DIVA API settings, etc.), editable without a code deploy.
CREATE TABLE IF NOT EXISTS site_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL,
    setting_value TEXT NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_site_settings_key (setting_key)
) ENGINE=InnoDB;

-- Framework Library documents (blueprint, AI strategy, FAIR guidelines,
-- playbook, and any future additions). Mirrors the data currently hardcoded
-- in App\Controllers\DocumentController.
CREATE TABLE IF NOT EXISTS documents (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    file_path VARCHAR(255) NOT NULL,
    category VARCHAR(100) NULL,
    pages SMALLINT UNSIGNED NULL,
    published_year VARCHAR(20) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_documents_slug (slug)
) ENGINE=InnoDB;

-- AI in Action video series (currently the 4-part Child Safety Series).
-- Mirrors the data currently hardcoded in App\Controllers\VideoController.
CREATE TABLE IF NOT EXISTS videos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    video_path VARCHAR(255) NOT NULL,
    series VARCHAR(100) NULL,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_videos_slug (slug)
) ENGINE=InnoDB;

-- Submissions from the homepage contact form.
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(150) NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    status ENUM('new', 'read', 'archived') NOT NULL DEFAULT 'new',
    submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
