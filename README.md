# Projet Movie DB

Application PHP qui utilise l'API TMDB pour afficher des films, des séries et des acteurs.

## Prérequis

- PHP 8 ou une version plus récente
- L'extension PHP cURL
- Une connexion Internet
- Une clé API TMDB dans `fonctions.php`

## Installation sous Windows

### Prérequis

1. Installer [XAMPP](https://www.apachefriends.org/).
2. Vérifier que PHP est disponible dans le dossier XAMPP :

   ```powershell
   C:\xampp\php\php.exe -v
   ```

3. Vérifier que l'extension cURL est activée dans `C:\xampp\php\php.ini` :

   ```ini
   extension=curl
   ```

   Si la ligne commence par `;`, supprimer le `;`, puis redémarrer le terminal.

### Installation et démarrage

1. Placer le dossier du projet dans un emplacement de travail, par exemple `C:\projets\Projet-MovieDB`.
2. Ouvrir PowerShell dans ce dossier.
3. Vérifier que `TMDB_API_KEY` dans `fonctions.php` contient une clé API TMDB valide.
4. Démarrer le serveur :

   ```powershell
   C:\xampp\php\php.exe -S localhost:8000 -t .
   ```

5. Ouvrir [http://localhost:8000/](http://localhost:8000/) dans le navigateur.

## Installation sous Linux

### Prérequis

Installer PHP et cURL avec le gestionnaire de paquets de la distribution.

Pour Debian ou Ubuntu :

```bash
sudo apt update
sudo apt install php php-curl
```

Vérifier l'installation :

```bash
php -v
```

### Installation et démarrage

1. Placer le dossier du projet dans un emplacement de travail.
2. Ouvrir un terminal dans ce dossier.
3. Vérifier que `TMDB_API_KEY` dans `fonctions.php` contient une clé API TMDB valide.
4. Démarrer le serveur :

   ```bash
   php -S localhost:8000 -t .
   ```

5. Ouvrir [http://localhost:8000/](http://localhost:8000/) dans le navigateur.

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