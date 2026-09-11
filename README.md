# Projet Movie DB

Application PHP qui utilise l'API TMDB pour afficher des films, des séries et des acteurs.

## Prérequis

- PHP 8 ou une version plus récente
- L'extension PHP cURL
- Une connexion Internet
- Une clé API TMDB dans `fonctions.php`

## Installation

1. Installer PHP. Sous Windows, XAMPP convient très bien. Sous Linux, installer PHP avec cURL depuis le gestionnaire de paquets de la distribution.
2. Placer le dossier du projet dans un emplacement de travail.
3. Ouvrir un terminal dans le dossier du projet.
4. Vérifier que PHP est disponible :

   ```text
   php -v
   ```

   Avec XAMPP sous Windows, utiliser si nécessaire :

   ```text
   C:\xampp\php\php.exe -v
   ```

5. Vérifier que la constante `TMDB_API_KEY` dans `fonctions.php` contient une clé API TMDB valide.

## Démarrer le serveur local

Depuis le dossier du projet, lancer :

```text
php -S localhost:8000 -t .
```

Avec XAMPP sous Windows, lancer :

```text
C:\xampp\php\php.exe -S localhost:8000 -t .
```

Puis ouvrir cette adresse dans un navigateur :

http://localhost:8000/

La page d'accueil redirige automatiquement vers les films populaires.

## Principales pages

- `popular.php` : films populaires
- `topRated.php` : films les mieux notés
- `latest.php` : films récemment sortis
- `upcoming.php` : films à venir
- `genreMovies.php` : films par genre
- `region.php` : films par région
- `kdramas.php` : séries coréennes
- `search-movies.php` : recherche de films
- `search-series.php` : recherche de séries
- `search-acteurs.php` : recherche d'acteurs

## Arrêter le serveur

Dans le terminal où le serveur fonctionne, appuyer sur `Ctrl+C`.