<?php

/**
 * Récupère le contenu d'une URL en passant par le proxy du lycée.
 *
 * 1) Essaie d'abord avec cURL via le proxy (plus fiable que file_get_contents).
 * 2) Si cURL n'est pas disponible ou échoue, retente avec file_get_contents.
 * 3) En dernier recours, tente une connexion directe (utile si le script est
 *    exécuté hors du réseau du lycée, par exemple pour une démonstration).
 *
 * Important : cette fonction ne fait plus d'echo en cas d'échec, car un echo
 * ici s'affichait au milieu de la page (avant même le <!doctype html>) et
 * cassait l'affichage. Elle retourne toujours false en cas d'échec, ce que
 * callTmdb() sait déjà gérer proprement.
 */
function getProxy($url)
{
    $proxyHost = '172.16.0.54:8080';

    // 1) Acces direct : le serveur local est souvent hors du reseau du lycee.
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 6,
            CURLOPT_FOLLOWLOCATION => true,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response !== false && $response !== '') {
            return $response;
        }
    }

    // 2) file_get_contents via le proxy
    $context = stream_context_create([
        'http' => [
            'proxy' => 'tcp://' . $proxyHost,
            'request_fulluri' => true,
            'timeout' => 6,
        ],
    ]);
    $response = @file_get_contents($url, false, $context);

    if ($response !== false && $response !== '') {
        return $response;
    }

    // 3) Dernier essai direct sans cURL
    $response = @file_get_contents($url, false, stream_context_create([
        'http' => ['timeout' => 6],
    ]));

    if ($response !== false) {
        return $response;
    }

    error_log('getProxy: impossible de récupérer ' . $url);

    return false;
}