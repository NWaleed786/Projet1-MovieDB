# Projet Movie DB

Application PHP permettant de consulter les films, séries et acteurs depuis l'API [TMDB](https://www.themoviedb.org/).

## Fonctionnalités

- Films populaires, mieux notés, récemment sortis et à venir
- Films à venir filtrés sur les sorties françaises et actualisés avec l'API TMDB
- Recherche de films, séries et acteurs
- Fiches détaillées des films, séries et acteurs
- Filtrage par genre, région et période de sortie
- Catalogue de K-Dramas

## Prérequis

- PHP 8.0 ou supérieur
- Extension PHP cURL recommandée
- Une clé API TMDB configurée dans `fonctions.php`
- Une connexion Internet pour interroger TMDB et charger les affiches

## Installation sous Windows

### Avec XAMPP

1. Installer [XAMPP](https://www.apachefriends.org/).
2. Copier le projet dans un dossier de travail, par exemple :

   ```text
   C:\xampp\htdocs\Projet-MovieDB
   ```

3. Ouvrir PowerShell dans le dossier du projet.
4. Vérifier PHP :

   ```powershell
   C:\xampp\php\php.exe -v
   ```

5. Démarrer le serveur PHP local :

   ```powershell
   C:\xampp\php\php.exe -S localhost:8000 -t .
   ```

6. Ouvrir [http://localhost:8000/](http://localhost:8000/) dans le navigateur.

### Avec PHP dans le PATH

Si PHP est ajouté au `PATH` Windows, lancer simplement :

```powershell
php -S localhost:8000 -t .
```

## Installation sous Linux

### Debian, Ubuntu et distributions compatibles

1. Installer PHP et cURL :

   ```bash
   sudo apt update
   sudo apt install php php-curl
   ```

2. Cloner le dépôt et entrer dans le dossier :

   ```bash
   git clone https://github.com/NWaleed786/Projet-MovieDB.git
   cd Projet-MovieDB
   ```

3. Démarrer le serveur PHP local :

   ```bash
   php -S localhost:8000 -t .
   ```

4. Ouvrir [http://localhost:8000/](http://localhost:8000/) dans le navigateur.

## Configuration de la clé TMDB

La clé API est utilisée dans `fonctions.php` par la constante `TMDB_API_KEY`.
Pour un déploiement public, il est recommandé de stocker cette clé dans une variable d'environnement plutôt que de la publier dans le dépôt.

## Pages principales

| Page | Utilisation |
| --- | --- |
| `popular.php` | Films populaires |
| `topRated.php` | Films les mieux notés |
| `latest.php` | Films récemment sortis |
| `upcoming.php` | Prochaines sorties |
| `date-search.php` | Recherche par période |
| `genreMovies.php?id=28` | Films par genre |
| `region.php?code=US` | Films par région |
| `kdramas.php` | Séries coréennes |
| `search-movies.php?query=gladiator` | Recherche de films |
| `search-series.php?query=squid+game` | Recherche de séries |
| `search-acteurs.php?query=tom` | Recherche d'acteurs |

## Arrêter le serveur

Dans le terminal qui exécute PHP, utiliser `Ctrl+C`.