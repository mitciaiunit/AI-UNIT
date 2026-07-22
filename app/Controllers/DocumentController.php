<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

/**
 * Minimal PDF viewer chrome (its own topbar/footer, no site navbar).
 *
 * Documents are addressed by a fixed slug rather than a raw file path in the
 * query string (the original site used `document.html?doc=../assets/...`),
 * so the URL can't be used to reference arbitrary files on disk.
 */
final class DocumentController extends Controller
{
    /** @var array<string, array{title: string, file: string}> */
    private const DOCUMENTS = [
        'blueprint' => [
            'title' => 'Digital Transformation Blueprint 2025–2029',
            'file' => 'documents/blueprint.pdf',
        ],
        'aistrategy' => [
            'title' => 'National AI Strategy 2025–2029',
            'file' => 'documents/aistrategy.pdf',
        ],
        'fairguidelines' => [
            'title' => 'FAIR Guidelines',
            'file' => 'documents/fairguidelines.pdf',
        ],
        'playbook' => [
            'title' => 'AI Playbook 2026 Edition',
            'file' => 'documents/playbook.pdf',
        ],
    ];

    public function show(string $slug): void
    {
        $document = self::DOCUMENTS[$slug] ?? null;

        if ($document === null) {
            $this->notFound();

            return;
        }

        $this->view('document', [
            'title' => $document['title'],
            'docUrl' => asset($document['file']),
            'downloadName' => basename($document['file']),
        ], null);
    }
}
