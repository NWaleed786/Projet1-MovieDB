<?php
require_once("get-proxy.php");

// Clé TMDB fournie dans le projet
const TMDB_API_KEY = "9e43f45f94705cc8e1d5a0400d19a7b7";
const TMDB_BASE_URL = "https://api.themoviedb.org/3";
const TMDB_IMAGE_BASE = "https://image.tmdb.org/t/p/";

function callTmdb(string $endpoint, array $params = []): array
{
    $defaultParams = [
        'api_key' => TMDB_API_KEY,
        'language' => 'fr-FR'
    ];

    $params = array_merge($defaultParams, $params);
    $url = TMDB_BASE_URL . $endpoint . '?' . http_build_query($params);

    $response = getProxy($url);

    if ($response === false || empty($response)) {
        return [];
    }

    $result = json_decode($response, true);

    if (!is_array($result)) {
        return [];
    }

    return $result;
}

function popularMovies(): array
{
    $result = callTmdb('/movie/popular');
    return $result['results'] ?? [];
}

function topRatedMovies(): array
{
    $result = callTmdb('/movie/top_rated');
    return $result['results'] ?? [];
}

function nowPlayingMovies(): array
{
    // Films récemment sortis en salle = "derniers films mis à jour"
    $result = callTmdb('/movie/now_playing');
    return $result['results'] ?? [];
}

function upcomingMovies(): array
{
    $result = callTmdb('/discover/movie', [
        'primary_release_date.gte' => date('Y-m-d'),
        'region' => 'FR',
        'with_release_type' => '2|3',
        'sort_by' => 'primary_release_date.asc',
        'page' => 1,
    ]);
    return $result['results'] ?? [];
}

function moviesByGenre(int $genreId): array
{
    $result = callTmdb('/discover/movie', ['with_genres' => $genreId]);
    return $result['results'] ?? [];
}

function searchMovies(string $query): array
{
    $result = callTmdb('/search/movie', ['query' => $query]);
    return $result['results'] ?? [];
}

function searchMoviesByDate(string $startDate, string $endDate): array
{
    // Recherche par plage de dates de sortie, triée des plus récents aux plus anciens.
    $result = callTmdb('/discover/movie', [
        'primary_release_date.gte' => $startDate,
        'primary_release_date.lte' => $endDate,
        'sort_by' => 'primary_release_date.desc',
        'vote_count.gte' => 1,
    ]);

    return $result['results'] ?? [];
}

function searchActeurs(string $query): array
{
    $result = callTmdb('/search/person', ['query' => $query]);
    return $result['results'] ?? [];
}

function movieDetails(int $movieId): array
{
    $result = callTmdb('/movie/' . $movieId);

    // Si l'API répond avec une erreur (ex : id inexistant), on renvoie []
    // pour que la page affiche proprement "Film introuvable".
    if (isset($result['success']) && $result['success'] === false) {
        return [];
    }

    return $result;
}

function movieCredits(int $movieId): array
{
    $result = callTmdb('/movie/' . $movieId . '/credits');
    return $result['cast'] ?? [];
}

function acteurDetails(int $acteurId): array
{
    $result = callTmdb('/person/' . $acteurId);

    if (isset($result['success']) && $result['success'] === false) {
        return [];
    }

    return $result;
}

function acteurMovies(int $acteurId): array
{
    $result = callTmdb('/person/' . $acteurId . '/combined_credits');

    if (!isset($result['cast']) || !is_array($result['cast'])) {
        return [];
    }

    $movies = array_filter($result['cast'], function ($item) {
        return isset($item['media_type']) && $item['media_type'] === 'movie';
    });

    usort($movies, function ($a, $b) {
        return ($b['popularity'] ?? 0) <=> ($a['popularity'] ?? 0);
    });

    return array_slice($movies, 0, 12);
}

function regionsList(): array
{
    // Grandes industries du cinéma / code pays TMDB (with_origin_country)
    return [
        'US' => 'Hollywood (États-Unis)',
        'IN' => 'Bollywood (Inde)',
        'KR' => 'Corée du Sud (K-Dramas)',
        'JP' => 'Japon (Anime / J-Movies)',
        'FR' => 'France',
        'GB' => 'Royaume-Uni',
        'CN' => 'Chine',
        'HK' => 'Hong Kong',
        'IT' => 'Italie',
        'ES' => 'Espagne',
        'DE' => 'Allemagne',
        'NG' => 'Nigeria (Nollywood)',
        'EG' => 'Égypte',
        'TR' => 'Turquie',
    ];
}

function moviesByRegion(string $countryCode): array
{
    $result = callTmdb('/discover/movie', [
        'with_origin_country' => strtoupper($countryCode),
        'sort_by' => 'popularity.desc',
    ]);

    return $result['results'] ?? [];
}

function seriesByRegion(string $countryCode): array
{
    $result = callTmdb('/discover/tv', [
        'with_origin_country' => strtoupper($countryCode),
        'sort_by' => 'popularity.desc',
    ]);

    return $result['results'] ?? [];
}


function searchSeries(string $query): array
{
    $result = callTmdb('/search/tv', ['query' => $query]);
    return $result['results'] ?? [];
}

function serieDetails(int $serieId): array
{
    $result = callTmdb('/tv/' . $serieId);

    if (isset($result['success']) && $result['success'] === false) {
        return [];
    }

    return $result;
}

function serieCredits(int $serieId): array
{
    $result = callTmdb('/tv/' . $serieId . '/credits');
    return $result['cast'] ?? [];
}

function genresList(): array
{
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    // Liste complète et à jour récupérée depuis l'API TMDB.
    $result = callTmdb('/genre/movie/list');
    $genres = [];

    if (!empty($result['genres']) && is_array($result['genres'])) {
        foreach ($result['genres'] as $genre) {
            if (isset($genre['id'], $genre['name'])) {
                $genres[(int) $genre['id']] = $genre['name'];
            }
        }
    }

    // Repli statique si l'API/le proxy est indisponible, pour que le site
    // reste utilisable (menus, pages de genre) même sans connexion.
    if (empty($genres)) {
        $genres = [
            28 => 'Action',
            12 => 'Aventure',
            16 => 'Animation',
            35 => 'Comédie',
            80 => 'Crime',
            99 => 'Documentaire',
            18 => 'Drame',
            10751 => 'Familial',
            14 => 'Fantastique',
            36 => 'Histoire',
            27 => 'Horreur',
            10402 => 'Musique',
            9648 => 'Mystère',
            10749 => 'Romance',
            878 => 'Science-Fiction',
            10770 => 'Téléfilm',
            53 => 'Thriller',
            10752 => 'Guerre',
            37 => 'Western',
        ];
    }

    $cache = $genres;

    return $genres;
}

function getGenreName(int $genreId): string
{
    $genres = genresList();
    return $genres[$genreId] ?? 'Genre inconnu';
}

function placeholderImage(int $width, int $height, string $label = "Pas d'image"): string
{
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">'
        . '<rect width="100%" height="100%" fill="#e9ecef"/>'
        . '<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="Arial, sans-serif" font-size="20" fill="#6c757d">'
        . htmlspecialchars($label)
        . '</text></svg>';

    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

function posterUrl(?string $path, string $size = 'w500'): string
{
    if (empty($path)) {
        // Image générée localement (pas de dépendance à un service externe
        // qui peut être hors service).
        return placeholderImage(500, 750);
    }

    return TMDB_IMAGE_BASE . $size . $path;
}

function profileUrl(?string $path, string $size = 'w185'): string
{
    if (empty($path)) {
        return placeholderImage(300, 450);
    }

    return TMDB_IMAGE_BASE . $size . $path;
}

function formatDateFr(?string $date): string
{
    if (empty($date)) {
        return 'Date inconnue';
    }

    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return 'Date inconnue';
    }

    return date('d/m/Y', $timestamp);
}

function limitText(?string $text, int $limit = 180): string
{
    $text = trim((string) $text);

    if ($text === '') {
        return 'Aucune description disponible.';
    }

    if (mb_strlen($text) <= $limit) {
        return $text;
    }

    return mb_substr($text, 0, $limit) . '...';
}

/**
 * Cherche la meilleure bande-annonce YouTube d'un film ou d'une série.
 * $mediaType doit être 'movie' ou 'tv'.
 * Retourne ['key' => ..., 'name' => ..., 'url' => ...] ou null si aucune trouvée.
 */
function getYoutubeTrailer(string $mediaType, int $id): ?array
{
    $mediaType = $mediaType === 'tv' ? 'tv' : 'movie';
    $result = callTmdb('/' . $mediaType . '/' . $id . '/videos');
    $videos = $result['results'] ?? [];

    if (empty($videos)) {
        return null;
    }

    $youtubeVideos = array_filter($videos, function ($video) {
        return ($video['site'] ?? '') === 'YouTube';
    });

    if (empty($youtubeVideos)) {
        return null;
    }

    // On préfère une vraie "Trailer" officielle, sinon un Teaser, sinon la première vidéo YouTube dispo.
    usort($youtubeVideos, function ($a, $b) {
        $score = function ($video) {
            $points = 0;
            if (($video['type'] ?? '') === 'Trailer') {
                $points += 10;
            } elseif (($video['type'] ?? '') === 'Teaser') {
                $points += 5;
            }
            if (!empty($video['official'])) {
                $points += 3;
            }
            return $points;
        };

        return $score($b) <=> $score($a);
    });

    $best = reset($youtubeVideos);

    if (empty($best['key'])) {
        return null;
    }

    return [
        'key' => $best['key'],
        'name' => $best['name'] ?? 'Bande-annonce',
        'url' => 'https://www.youtube.com/watch?v=' . $best['key'],
    ];
}
