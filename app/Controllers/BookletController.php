<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

/**
 * The pdf.js-based booklet reader. One template (pages/booklet.php) shared by
 * all three booklets, driven entirely by this per-booklet config — including
 * the fully translated UI strings each booklet originally hardcoded.
 */
final class BookletController extends Controller
{
    /** @var array<string, array<string, mixed>> */
    private const BOOKLETS = [
        'aie' => [
            'lang' => 'en',
            'pageTitle' => "AI For All — English Version · AI Unit, Mauritius",
            'file' => 'documents/Booklet_English.pdf',
            'downloadName' => 'AI-For-All-English.pdf',
            'strings' => [
                'skipLink' => 'Skip to main content',
                'backBtn' => 'Back To Homepage',
                'breadcrumbCategory' => 'AI in Action',
                'breadcrumbTitle' => 'AI For All — English Version',
                'downloadAria' => 'Download the booklet as PDF',
                'downloadLabel' => 'Download PDF',
                'thumbSidebarAria' => 'Page thumbnails',
                'allPages' => 'All Pages',
                'viewerAria' => 'AI For All booklet reader',
                'loadingText' => 'Loading booklet…',
                'loadingHint' => 'If the document does not appear, please ensure',
                'prevPage' => 'Previous page',
                'nextPage' => 'Next page',
                'firstPage' => 'First page',
                'lastPage' => 'Last page',
                'toggleThumbs' => 'Toggle thumbnails',
                'zoomOut' => 'Zoom out',
                'zoomIn' => 'Zoom in',
                'fitScreen' => 'Fit to screen',
                'share' => 'Share',
                'fullscreen' => 'Fullscreen',
                'sharePopupAria' => 'Share options',
                'sharePopupTitle' => 'Share this booklet',
                'copyLink' => 'Copy page link',
                'shareDownload' => 'Download PDF',
                'print' => 'Print',
                'kbdNavigate' => 'Navigate',
                'kbdZoom' => 'Zoom',
                'kbdFullscreen' => 'Fullscreen',
                'kbdFirstPage' => 'First page',
                'kbdLastPage' => 'Last page',
                'errorTitle' => 'Unable to load booklet',
                'errorHint' => 'Please ensure',
                'errorHint2' => 'is present and accessible on the server.',
                'errorDownloadHint' => 'You can also use the Download PDF button above.',
                'pageAlt' => 'Page %d of AI For All — English Version',
                'linkCopied' => 'Link copied to clipboard!',
                'linkCopyFailed' => 'Could not copy link.',
            ],
        ],
        'aim' => [
            'lang' => 'mfe',
            'pageTitle' => "AI Pou Nou Tou — Version Kreol · Linite L'IA, Moris",
            'file' => 'documents/Booklet_Kreol.pdf',
            'downloadName' => 'AI-Pou-Nou-Tou-Kreol.pdf',
            'strings' => [
                'skipLink' => 'Ale direkteman lor kontenu',
                'backBtn' => 'Retourne',
                'breadcrumbCategory' => "L'IA an Aksyon",
                'breadcrumbTitle' => 'AI Pou Nou Tou — Version Kreol',
                'downloadAria' => 'Telecharge livret la koumm PDF',
                'downloadLabel' => 'Telecharge PDF',
                'thumbSidebarAria' => 'Miniature bann paz',
                'allPages' => 'Tou bann paz',
                'viewerAria' => 'Lecter livret AI Pou Nou Tou',
                'loadingText' => 'Livret pe charge…',
                'loadingHint' => 'Si dokiman pa afiche, asire ou ki',
                'prevPage' => 'Paz avan',
                'nextPage' => 'Paz apre',
                'firstPage' => 'Premye paz',
                'lastPage' => 'Dernie paz',
                'toggleThumbs' => 'Montre / kasiet miniature',
                'zoomOut' => 'Zoom aryer',
                'zoomIn' => 'Zoom avan',
                'fitScreen' => 'Adapte a lekran',
                'share' => 'Partaz',
                'fullscreen' => 'Plen lekran',
                'sharePopupAria' => 'Opsyon partaz',
                'sharePopupTitle' => 'Partaz livret la',
                'copyLink' => 'Kopye lyen paz la',
                'shareDownload' => 'Telecharge PDF',
                'print' => 'Imprime',
                'kbdNavigate' => 'Navige',
                'kbdZoom' => 'Zoom',
                'kbdFullscreen' => 'Plen lekran',
                'kbdFirstPage' => 'Premye paz',
                'kbdLastPage' => 'Dernie paz',
                'errorTitle' => 'Nou pa kapav sarz livret la',
                'errorHint' => 'Asire ou ki fichier',
                'errorHint2' => 'disponib lor server.',
                'errorDownloadHint' => 'Ou kapav itiliz bouton Telecharge PDF anlerla.',
                'pageAlt' => 'Paz %d AI Pou Nou Tou — Version Kreol',
                'linkCopied' => 'Lyen kopye!',
                'linkCopyFailed' => 'Nou pa finn arrive kopye lyen la.',
            ],
        ],
        'livret' => [
            'lang' => 'fr',
            'pageTitle' => 'Violence Numérique Contre Les Enfants — Livret Pédagogique',
            'file' => 'documents/violence.pdf',
            'downloadName' => 'Livret-Violence-Numerique.pdf',
            'strings' => [
                'skipLink' => 'Aller au contenu principal',
                'backBtn' => 'Back To Homepage',
                'breadcrumbCategory' => 'AI in Action',
                'breadcrumbTitle' => 'Violence Numérique Contre Les Enfants',
                'downloadAria' => 'Télécharger le livret en PDF',
                'downloadLabel' => 'Télécharger PDF',
                'thumbSidebarAria' => 'Miniatures des pages',
                'allPages' => 'Toutes les pages',
                'viewerAria' => 'Lecteur du livret pédagogique',
                'loadingText' => 'Chargement du livret en cours…',
                'loadingHint' => "Si le document ne s'affiche pas, vérifiez que",
                'prevPage' => 'Page précédente',
                'nextPage' => 'Page suivante',
                'firstPage' => 'Première page',
                'lastPage' => 'Dernière page',
                'toggleThumbs' => 'Afficher / masquer les miniatures',
                'zoomOut' => 'Zoom arrière',
                'zoomIn' => 'Zoom avant',
                'fitScreen' => "Ajuster à l'écran",
                'share' => 'Partager',
                'fullscreen' => 'Plein écran',
                'sharePopupAria' => 'Options de partage',
                'sharePopupTitle' => 'Partager ce livret',
                'copyLink' => 'Copier le lien de la page',
                'shareDownload' => 'Télécharger PDF',
                'print' => 'Imprimer',
                'kbdNavigate' => 'Naviguer',
                'kbdZoom' => 'Zoom',
                'kbdFullscreen' => 'Plein écran',
                'kbdFirstPage' => '1ère page',
                'kbdLastPage' => 'Dernière page',
                'errorTitle' => 'Impossible de charger le livret',
                'errorHint' => 'Assurez-vous que le fichier',
                'errorHint2' => 'est bien présent sur le serveur.',
                'errorDownloadHint' => 'Vous pouvez aussi utiliser le bouton Télécharger PDF ci-dessus.',
                'pageAlt' => 'Page %d du livret Violence Numérique Contre Les Enfants',
                'linkCopied' => 'Lien copié dans le presse-papiers !',
                'linkCopyFailed' => 'Impossible de copier le lien.',
            ],
        ],
    ];

    public function show(string $slug): void
    {
        $booklet = self::BOOKLETS[$slug] ?? null;

        if ($booklet === null) {
            $this->notFound();

            return;
        }

        $this->view('booklet', [
            'title' => $booklet['pageTitle'],
            'lang' => $booklet['lang'],
            'pdfUrl' => asset($booklet['file']),
            'downloadName' => $booklet['downloadName'],
            's' => $booklet['strings'],
        ], null);
    }
}
