<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Logger;
use App\Models\HighlightCategory;
use App\Models\HighlightImage;
use Throwable;

/**
 * A read-through mirror of the published Highlights galleries.
 *
 * This is NOT a second source of content. It is only ever written from a
 * successful database read, and only ever read when the database cannot be
 * reached - so the database stays the single place anything is authored, and
 * the cache can never diverge from it except by being older.
 *
 * The image files themselves live on disk in uploads/highlights and are not
 * touched by a database outage, so a cached render still shows real pictures.
 */
final class HighlightsCache
{
    private const FILE = 'highlights.json';

    /** Bumped if the stored shape ever changes, so old files are ignored. */
    private const VERSION = 1;

    /**
     * Records the current published state. Failures here are logged and
     * swallowed: a cache that cannot be written must never break the page it
     * was meant to protect.
     *
     * @param list<HighlightCategory> $categories
     */
    public function store(array $categories): void
    {
        $payload = [
            'version' => self::VERSION,
            'stored_at' => time(),
            'categories' => array_map(
                static fn (HighlightCategory $c): array => [
                    'id' => $c->id,
                    'slug' => $c->slug,
                    'name' => $c->name,
                    'description' => $c->description,
                    'sort_order' => $c->sortOrder,
                    'images' => array_map(
                        static fn (HighlightImage $i): array => [
                            'id' => $i->id,
                            'category_id' => $i->categoryId,
                            'title' => $i->title,
                            'caption' => $i->caption,
                            'alt_text' => $i->altText,
                            'file_name' => $i->fileName,
                            'sort_order' => $i->sortOrder,
                        ],
                        $c->images
                    ),
                ],
                $categories
            ),
        ];

        try {
            $json = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $path = $this->path();

            /*
             * Write to a temporary file and rename it into place. rename() is
             * atomic on the same filesystem, so a request reading the cache
             * while another writes it can never see a half-written file.
             */
            $temp = $path . '.' . bin2hex(random_bytes(4)) . '.tmp';
            if (file_put_contents($temp, $json, LOCK_EX) === false || !rename($temp, $path)) {
                @unlink($temp);
                Logger::error('Could not write the highlights cache', ['path' => $path]);
            }
        } catch (Throwable $e) {
            Logger::error('Could not write the highlights cache', ['error' => $e->getMessage()]);
        }
    }

    /**
     * The last recorded state, or null if there is none or it is unreadable.
     *
     * Only visible content is ever written here, so nothing hidden in the CMS
     * can resurface through the cache.
     *
     * @return list<HighlightCategory>|null
     */
    public function load(): ?array
    {
        $path = $this->path();
        if (!is_file($path)) {
            return null;
        }

        try {
            $raw = file_get_contents($path);
            if ($raw === false || $raw === '') {
                return null;
            }

            $payload = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

            if (!is_array($payload)
                || ($payload['version'] ?? null) !== self::VERSION
                || !is_array($payload['categories'] ?? null)
            ) {
                return null;
            }

            $categories = [];
            foreach ($payload['categories'] as $c) {
                $images = [];
                foreach ($c['images'] ?? [] as $i) {
                    $images[] = new HighlightImage(
                        $i['id'] !== null ? (int) $i['id'] : null,
                        (int) $i['category_id'],
                        (string) $i['title'],
                        $i['caption'] !== null ? (string) $i['caption'] : null,
                        (string) $i['alt_text'],
                        (string) $i['file_name'],
                        (int) $i['sort_order'],
                        true,
                    );
                }

                $category = new HighlightCategory(
                    $c['id'] !== null ? (int) $c['id'] : null,
                    (string) $c['slug'],
                    (string) $c['name'],
                    $c['description'] !== null ? (string) $c['description'] : null,
                    (int) $c['sort_order'],
                    true,
                    $images,
                );

                $categories[] = $category;
            }

            return $categories === [] ? null : $categories;
        } catch (Throwable $e) {
            Logger::error('Could not read the highlights cache', ['error' => $e->getMessage()]);

            return null;
        }
    }

    private function path(): string
    {
        $directory = dirname(__DIR__, 2) . '/storage/cache';

        if (!is_dir($directory)) {
            @mkdir($directory, 0755, true);
        }

        return $directory . DIRECTORY_SEPARATOR . self::FILE;
    }
}
