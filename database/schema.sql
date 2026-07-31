-- AI Unit website - database schema
--
-- site_settings, documents, and videos only prepare the project for future
-- development - the current pages still use static PHP config arrays for
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

-- ---------------------------------------------------------------------------
-- Highlights CMS
--
-- Backs the /highlights page and its admin area. Unlike the three tables
-- above, these are live: the page reads them on every request.
-- ---------------------------------------------------------------------------

-- Staff who may sign in to the Highlights admin area.
--
-- Deliberately minimal: this is not a user-management system, and there are no
-- roles or permissions. It exists so the upload endpoints are not open to the
-- public. `is_active` allows revoking access without deleting the row (and so
-- losing the audit value of knowing the account existed).
CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    -- password_hash() output. 255 chars because the column must survive a
    -- future move to a longer algorithm without a schema change.
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(150) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_admin_users_username (username)
) ENGINE=InnoDB;

-- Groupings shown on the Highlights page: "AI Unit Internship", "Rodrigues &
-- Imperial Programme", and whatever staff add later. The page renders one
-- block per visible row in sort_order, so a new category appears with no code
-- change - which is the point of this table.
CREATE TABLE IF NOT EXISTS highlight_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(120) NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    is_visible TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_highlight_categories_slug (slug),
    KEY idx_highlight_categories_order (sort_order)
) ENGINE=InnoDB;

-- Gallery images. Only the file's basename is stored, never a path: the
-- directory is decided by App\Services\ImageUploadService, so moving the
-- upload folder is a config change rather than an UPDATE across every row,
-- and a stored value can never point outside it.
CREATE TABLE IF NOT EXISTS highlight_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    caption VARCHAR(500) NULL,
    -- Required, not nullable: this is a government site held to WCAG 2.2, and
    -- an image published without a text alternative is a conformance failure.
    alt_text VARCHAR(500) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    is_visible TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    -- RESTRICT, not CASCADE: deleting a category that still holds images would
    -- silently destroy them (and orphan their files on disk). The admin screen
    -- refuses the delete and says how many images are in the way instead.
    CONSTRAINT fk_highlight_images_category
        FOREIGN KEY (category_id) REFERENCES highlight_categories (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    UNIQUE KEY uq_highlight_images_file_name (file_name),
    KEY idx_highlight_images_category_order (category_id, sort_order)
) ENGINE=InnoDB;

-- Seed the current Highlights gallery. Framework Library documents are not
-- seeded here; those will have their own CMS later. These rows only cover the
-- reusable Highlights categories and image metadata used by the gallery.
INSERT INTO highlight_categories (slug, name, description, sort_order, is_visible)
VALUES
    ('ai-unit-internship', 'AI Unit Internship', 'Moments from the AI Unit internship, including website revamp, accessibility, DIVA and presentation work.', 1, 1),
    ('rodrigues-imperial-programme', 'Rodrigues & Imperial Programme', 'Programme highlights and activity images for Rodrigues and Imperial collaboration work.', 2, 1)
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    description = VALUES(description),
    sort_order = VALUES(sort_order),
    is_visible = VALUES(is_visible);

INSERT INTO highlight_images (category_id, title, caption, alt_text, file_name, sort_order, is_visible)
VALUES
    ((SELECT id FROM highlight_categories WHERE slug = 'ai-unit-internship'), 'Intern team at the AI Unit', 'The intern team at the AI Unit, METC, during the May to July 2026 attachment.', 'The internship cohort standing together in the AI Unit office.', 'team.jpg', 1, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'ai-unit-internship'), 'Planning the build', 'Interns reviewing the website architecture and delivery plan.', 'An intern presenting a diagram to colleagues seated around a meeting-room table.', 'team2.jpg', 2, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'ai-unit-internship'), 'Team collaboration', 'Collaborative project work during the internship.', 'A group of interns working together during the AI Unit attachment.', 'team3.jpg', 3, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'ai-unit-internship'), 'Kanban workflow', 'Tasks organised through a shared Kanban board.', 'A project Kanban board showing work items grouped by status.', 'kanban.jpg', 4, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'ai-unit-internship'), 'DIVA assistant integration', 'The DIVA chatbot integrated into the public portal.', 'A screenshot of the DIVA assistant interface on the AI Unit website.', 'DIVA1.png', 5, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'ai-unit-internship'), 'DIVA user experience', 'Refining the assistant experience for visitors.', 'A screenshot showing the DIVA assistant conversation panel.', 'DIVA2.png', 6, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'ai-unit-internship'), 'Accessibility toolbar', 'WCAG-focused accessibility controls added to the portal.', 'The accessibility toolbar interface showing controls for display and reading preferences.', 'Accessibility1.png', 7, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'ai-unit-internship'), 'Accessibility settings', 'Additional accessibility preferences for public users.', 'Accessibility setting controls displayed on the AI Unit website.', 'Accessibility2.png', 8, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'ai-unit-internship'), 'Accessibility review', 'Accessibility work tested and refined during the build.', 'A website accessibility panel with contrast and navigation controls.', 'Accessibility3.png', 9, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'ai-unit-internship'), 'Video captioning', 'Multilingual captions prepared for the AI in Action video player.', 'A video player screenshot with English and French caption controls.', 'VIDEO1.png', 10, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'ai-unit-internship'), 'Ministry presentation', 'The portal was presented at the Ministry of Information Technology, Communication and Innovation.', 'Interns standing at the Ministry reception beside a plaque about an inclusive digital society.', 'Mitci.jpg', 11, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'rodrigues-imperial-programme'), 'Programme activity', 'A moment from the Rodrigues and Imperial programme.', 'Participants gathered during a Rodrigues and Imperial programme activity.', 'RI_1.jpg', 1, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'rodrigues-imperial-programme'), 'Programme highlight', 'Highlights from programme delivery.', 'A programme highlight image showing participants during an activity.', 'Hm.jpg', 2, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'rodrigues-imperial-programme'), 'Rodrigues moment', 'A captured moment from the Rodrigues programme.', 'A Rodrigues programme activity image with people gathered indoors.', 'Rm.jpg', 3, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'rodrigues-imperial-programme'), 'Youth engagement', 'Youth engagement activity connected to the programme.', 'Young participants taking part in a programme activity.', 'Yr.jpg', 4, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'rodrigues-imperial-programme'), 'Workshop in progress', 'Programme work in progress.', 'Participants working together during a programme session.', 'wip.jpg', 5, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'rodrigues-imperial-programme'), 'Workshop collaboration', 'Collaborative programme activity.', 'Participants collaborating during a workshop session.', 'wip2.jpg', 6, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'rodrigues-imperial-programme'), 'Workshop discussion', 'Discussion during programme delivery.', 'Participants in discussion during a workshop.', 'wip3.jpg', 7, 1),
    ((SELECT id FROM highlight_categories WHERE slug = 'rodrigues-imperial-programme'), 'Workshop presentation', 'Presentation moment from the programme.', 'A presenter addressing participants during a workshop.', 'wip4.jpg', 8, 1)
ON DUPLICATE KEY UPDATE
    category_id = VALUES(category_id),
    title = VALUES(title),
    caption = VALUES(caption),
    alt_text = VALUES(alt_text),
    sort_order = VALUES(sort_order),
    is_visible = VALUES(is_visible);

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
