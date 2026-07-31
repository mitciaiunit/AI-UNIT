<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Logger;
use RuntimeException;

/**
 * Validates and stores gallery image uploads.
 *
 * Every rule here is server-side. Nothing the browser sends is trusted: not
 * the filename, not the Content-Type header, not any client-side check - all
 * three are attacker-controlled.
 */
final class ImageUploadService
{
    /**
     * Validates $_FILES entry and stores it.
     *
     * @param array<string, mixed> $file One entry from $_FILES.
     * @return string The stored basename, to be written to highlight_images.file_name.
     * @throws RuntimeException With a message safe to show an admin.
     */
    public function store(array $file): string
    {
        $this->assertUploadSucceeded($file);

        $tmpPath = (string) ($file['tmp_name'] ?? '');

        /*
         * is_uploaded_file() confirms the path really came from this request's
         * multipart body. Without it, a bug that let $tmp_name be influenced
         * elsewhere could be pointed at an arbitrary local file.
         */
        if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
            throw new RuntimeException('The upload could not be read. Please try again.');
        }

        $maxBytes = (int) config('highlights.max_upload_bytes');
        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0) {
            throw new RuntimeException('The file is empty.');
        }
        if ($size > $maxBytes) {
            throw new RuntimeException(sprintf(
                'The image is %s, which is over the %s limit.',
                $this->formatBytes($size),
                $this->formatBytes($maxBytes)
            ));
        }

        $extension = $this->resolveExtension($tmpPath);
        $fileName = $this->uniqueFileName($extension);
        $directory = $this->directory();
        $target = $directory . DIRECTORY_SEPARATOR . $fileName;

        if (!move_uploaded_file($tmpPath, $target)) {
            Logger::error('Failed to move uploaded highlight image', ['target' => $target]);

            throw new RuntimeException('The image could not be saved. Please try again.');
        }

        // Readable by the web server, not executable by anyone.
        @chmod($target, 0644);

        return $fileName;
    }

    /**
     * Deletes a stored file. Silently ignores anything that is not a plain
     * basename inside the upload directory, so a tampered row can never make
     * this reach elsewhere on disk.
     */
    public function delete(string $fileName): void
    {
        if ($fileName === '' || $fileName !== basename($fileName)) {
            return;
        }

        $path = $this->directory() . DIRECTORY_SEPARATOR . $fileName;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    public function exists(string $fileName): bool
    {
        if ($fileName === '' || $fileName !== basename($fileName)) {
            return false;
        }

        return is_file($this->directory() . DIRECTORY_SEPARATOR . $fileName);
    }

    /** Public URL for a stored file. */
    public function url(string $fileName): string
    {
        return rtrim((string) config('highlights.upload_url'), '/') . '/' . rawurlencode($fileName);
    }

    /**
     * Creates the upload directory on first use, along with the .htaccess that
     * stops anything in it being executed. An image is only inert as long as
     * the server refuses to run it: a valid GIF whose bytes also parse as PHP
     * is the classic way to turn an image upload into remote code execution.
     */
    public function directory(): string
    {
        $directory = (string) config('highlights.upload_dir');

        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('The upload directory could not be created.');
        }

        $htaccess = $directory . DIRECTORY_SEPARATOR . '.htaccess';
        if (!is_file($htaccess)) {
            file_put_contents($htaccess, $this->hardeningRules());
        }

        return $directory;
    }

    /**
     * @param array<string, mixed> $file
     */
    private function assertUploadSucceeded(array $file): void
    {
        $error = $file['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($error === UPLOAD_ERR_OK) {
            return;
        }

        throw new RuntimeException(match ($error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'The image is larger than the server allows.',
            UPLOAD_ERR_PARTIAL => 'The upload was interrupted. Please try again.',
            UPLOAD_ERR_NO_FILE => 'Please choose an image to upload.',
            UPLOAD_ERR_NO_TMP_DIR, UPLOAD_ERR_CANT_WRITE => 'The server could not write the file. Please contact IT.',
            UPLOAD_ERR_EXTENSION => 'The upload was blocked by the server configuration.',
            default => 'The image could not be uploaded.',
        });
    }

    /**
     * Determines the extension from the file's actual bytes.
     *
     * The type is read with finfo and cross-checked with getimagesize(): finfo
     * alone can be satisfied by a file that merely starts with image magic
     * bytes, whereas getimagesize() has to parse real dimensions out of it.
     */
    private function resolveExtension(string $tmpPath): string
    {
        /** @var array<string, string> $allowed */
        $allowed = (array) config('highlights.allowed_image_types');

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = (string) $finfo->file($tmpPath);

        if (!isset($allowed[$mime])) {
            throw new RuntimeException(sprintf(
                'That file is a %s. Please upload one of: %s.',
                $mime === '' ? 'file of an unrecognised type' : $mime,
                implode(', ', array_values($allowed))
            ));
        }

        $dimensions = @getimagesize($tmpPath);
        if ($dimensions === false || (int) $dimensions[0] <= 0 || (int) $dimensions[1] <= 0) {
            throw new RuntimeException('That file is not a readable image.');
        }

        return $allowed[$mime];
    }

    /** Random name: the client's filename is never used, in any part. */
    private function uniqueFileName(string $extension): string
    {
        do {
            $name = bin2hex(random_bytes(16)) . '.' . $extension;
        } while ($this->exists($name));

        return $name;
    }

    private function hardeningRules(): string
    {
        return <<<'APACHE'
        # Uploaded content: served, never executed.
        #
        # A file can be a valid image and valid PHP at the same time, so the only
        # safe assumption is that anything in this directory is hostile code.

        php_flag engine off

        <IfModule mod_rewrite.c>
            RewriteEngine Off
        </IfModule>

        # Belt and braces for servers where php_flag is unavailable (PHP-FPM):
        # strip every handler that could execute a file here.
        <FilesMatch "\.(php|php[0-9]|phtml|phar|pht|shtml|cgi|pl|py|asp|aspx|jsp|htaccess)$">
            Require all denied
        </FilesMatch>

        RemoveHandler .php .phtml .phar .cgi .pl .py
        RemoveType .php .phtml .phar

        # Anything served from here downloads or renders as-is; it is never sniffed
        # into something executable.
        <IfModule mod_headers.c>
            Header set X-Content-Type-Options "nosniff"
        </IfModule>
        APACHE;
    }

    private function formatBytes(int $bytes): string
    {
        return $bytes >= 1024 * 1024
            ? round($bytes / 1024 / 1024, 1) . ' MB'
            : round($bytes / 1024) . ' KB';
    }
}
