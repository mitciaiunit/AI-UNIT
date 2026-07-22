<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

/**
 * The 4-part "Child Safety Series" video player. One template (pages/video.php)
 * driven by this per-video data; prev/next series links are derived
 * automatically from neighbouring entries instead of being hardcoded per page.
 */
final class VideoController extends Controller
{
    /** @var array<int, array{file: string, chip: array{en:string,fr:string}, videoNum: array{en:string,fr:string}, title: array{en:string,fr:string}, desc: array{en:string,fr:string}}> */
    private const VIDEOS = [
        1 => [
            'file' => 'video01.mp4',
            'chip' => ['en' => 'Child Safety', 'fr' => 'Sécurité enfants'],
            'videoNum' => ['en' => 'Video 1 of 4 · 2:37', 'fr' => 'Vidéo 1 sur 4 · 2:37'],
            'title' => ['en' => 'Forms of Digital Violence', 'fr' => 'Formes de violence numérique'],
            'desc' => [
                'en' => "This video introduces the different forms of digital violence targeting children and young people — including cyberbullying, online harassment, grooming, and identity theft. Designed to raise awareness among children, parents, and educators.",
                'fr' => "Cette vidéo présente les différentes formes de violence numérique ciblant les enfants et les jeunes — cyberharcèlement, harcèlement en ligne, grooming et usurpation d'identité. Conçue pour sensibiliser les enfants, les parents et les éducateurs.",
            ],
        ],
        2 => [
            'file' => 'video02.mp4',
            'chip' => ['en' => 'Child Safety', 'fr' => 'Sécurité enfants'],
            'videoNum' => ['en' => 'Video 2 of 4 · 4:21', 'fr' => 'Vidéo 2 sur 4 · 4:21'],
            'title' => ['en' => 'Consequences and Effects of Digital Violence', 'fr' => 'Conséquences et effets de la violence numérique'],
            'desc' => [
                'en' => "This video explores the serious consequences of digital violence on victims — from cyberbullying and sextortion to grooming and identity theft. It shows how these forms of harm affect young people's mental health, academic performance, and physical wellbeing.",
                'fr' => "Cette vidéo explore les graves conséquences de la violence numérique sur les victimes — du cyberharcèlement et de la sextorsion au grooming et à l'usurpation d'identité. Elle montre comment ces formes de violence affectent la santé mentale, les résultats scolaires et le bien-être physique des jeunes.",
            ],
        ],
        3 => [
            'file' => 'video03.mp4',
            'chip' => ['en' => 'Child Safety', 'fr' => 'Sécurité enfants'],
            'videoNum' => ['en' => 'Video 3 of 4 · 2:37', 'fr' => 'Vidéo 3 sur 4 · 2:37'],
            'title' => ["en" => "Children's Rights & Parental Responsibility", 'fr' => 'Les droits des enfants et la responsabilité parentale'],
            'desc' => [
                'en' => "This video explains the rights children have when facing digital violence — including the right to protection, privacy, and respect. It also outlines parents' and teachers' responsibilities, and shows victims how to report incidents to authorities such as CERT-MU, the Cybercrime Unit, Befrienders, and the Family Support line (113).",
                'fr' => "Cette vidéo explique les droits des enfants face à la violence numérique — notamment le droit à la protection, à la vie privée et au respect. Elle précise également les responsabilités des parents et des enseignants, et montre aux victimes comment signaler les incidents aux autorités : CERT-MU (MAUCORS+), la Cybercrime Unit, les Befrienders et le service Family Support (113).",
            ],
        ],
        4 => [
            'file' => 'video04.mp4',
            'chip' => ['en' => 'Child Safety', 'fr' => 'Sécurité enfants'],
            'videoNum' => ['en' => 'Video 4 of 4 · 4:21', 'fr' => 'Vidéo 4 sur 4 · 4:21'],
            'title' => ['en' => 'Regaining Control', 'fr' => 'Reprendre le contrôle'],
            'desc' => [
                'en' => "In this final episode, Sarah, Yusuf, and Priyashinee find the courage to speak up and reclaim their lives. Their stories show that silence protects abusers — but speaking out protects victims. No one is alone, and your voice is your strength.",
                'fr' => "Dans ce dernier épisode, Sarah, Yusuf et Priyashinee trouvent le courage de parler et de reprendre leur vie en main. Leurs histoires montrent que le silence protège les agresseurs — mais que la parole protège les victimes. Personne n'est seul, et votre voix est votre force.",
            ],
        ],
    ];

    public function show(string $id): void
    {
        $videoId = (int) $id;
        $video = self::VIDEOS[$videoId] ?? null;

        if ($video === null) {
            $this->notFound();

            return;
        }

        $prev = self::VIDEOS[$videoId - 1] ?? null;
        $next = self::VIDEOS[$videoId + 1] ?? null;

        $this->view('video', [
            'title' => sprintf('Video %d — %s · AI Unit Mauritius', $videoId, $video['title']['en']),
            'isHome' => false,
            'videoId' => $videoId,
            'video' => $video,
            'videoSrc' => asset('video/' . $video['file']),
            'captionFr' => asset('captions/video' . $videoId . '-fr.vtt'),
            'captionEn' => asset('captions/video' . $videoId . '-en.vtt'),
            'prevUrl' => $prev ? url('video/' . ($videoId - 1)) : null,
            'prevTitle' => $prev ? ['en' => $prev['title']['en'], 'fr' => $prev['title']['fr']] : null,
            'nextUrl' => $next ? url('video/' . ($videoId + 1)) : null,
            'nextTitle' => $next ? ['en' => $next['title']['en'], 'fr' => $next['title']['fr']] : null,
        ], null);
    }
}
