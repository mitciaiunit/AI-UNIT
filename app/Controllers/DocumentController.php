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
    /** @var array<string, array{title: string, description: string, file: string}> */
    private const DOCUMENTS = [
        'blueprint' => [
            'title' => 'Digital Transformation Blueprint 2025–2029',
            'description' => 'Read the Digital Transformation Blueprint 2025–2029, the Government of Mauritius roadmap for modernising public services and building national digital capability.',
            'file' => 'documents/blueprint.pdf',
        ],
        'aistrategy' => [
            'title' => 'National AI Strategy 2025–2029',
            'description' => 'Read the National AI Strategy 2025–2029, setting out how Mauritius will govern, adopt and grow artificial intelligence across the economy and the public sector.',
            'file' => 'documents/aistrategy.pdf',
        ],
        'fairguidelines' => [
            'title' => 'FAIR Guidelines',
            'description' => 'Read the FAIR Guidelines, the AI Unit framework for keeping AI systems in Mauritius fair, accountable, inclusive and responsible.',
            'file' => 'documents/fairguidelines.pdf',
        ],
        'playbook' => [
            'title' => 'AI Playbook 2026 Edition',
            'description' => 'Read the AI Playbook 2026 Edition, the practical guide for public sector teams implementing artificial intelligence in Mauritius.',
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

        $imageDescriptions = (require dirname(__DIR__) . '/Data/document-image-descriptions.php')[$slug] ?? [];

        $this->view('document', [
            'title' => $document['title'],
            'description' => $document['description'],
            'ogType' => 'article',
            'docUrl' => asset($document['file']),
            'downloadName' => basename($document['file']),
            'imageDescriptions' => $imageDescriptions,
        ], null);
    }
}
