<?php
require_once('fonctions.php');

$today = date('Y-m-d');
$defaultStart = date('Y-m-d', strtotime('-30 days'));

$startDate = isset($_GET['start']) ? trim($_GET['start']) : '';
$endDate = isset($_GET['end']) ? trim($_GET['end']) : '';
$hasSearched = isset($_GET['start']) || isset($_GET['end']);

$movies = [];
$message = '';

function isValidDate(string $date): bool
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

if ($hasSearched) {
    if ($startDate === '' || $endDate === '') {
        $message = "Veuillez renseigner une date de début et une date de fin.";
    } elseif (!isValidDate($startDate) || !isValidDate($endDate)) {
        $message = "Format de date invalide. Utilisez le sélecteur de date (AAAA-MM-JJ).";
    } elseif ($startDate > $endDate) {
        $message = "La date de début doit être avant la date de fin.";
    } else {
        $movies = searchMoviesByDate($startDate, $endDate);
        if (empty($movies)) {
            $message = "Aucun film trouvé sorti entre le " . htmlspecialchars(formatDateFr($startDate)) . " et le " . htmlspecialchars(formatDateFr($endDate)) . ".";
        }
    }
}

require('header.php');
?>
<div class="container">
    <div class="hero">
        <h1>Recherche par date de sortie</h1>
        <p class="text-muted mb-0">Trouvez les films sortis entre deux dates.</p>
    </div>

    <form action="date-search.php" method="get" class="row g-3 align-items-end mb-4">
        <div class="col-12 col-sm-4">
            <label for="start" class="form-label">Du</label>
            <input type="date" id="start" name="start" class="form-control" value="<?= htmlspecialchars($startDate !== '' ? $startDate : $defaultStart); ?>" max="<?= htmlspecialchars($today); ?>">
        </div>
        <div class="col-12 col-sm-4">
            <label for="end" class="form-label">Au</label>
            <input type="date" id="end" name="end" class="form-control" value="<?= htmlspecialchars($endDate !== '' ? $endDate : $today); ?>" max="<?= htmlspecialchars($today); ?>">
        </div>
        <div class="col-12 col-sm-4">
            <button type="submit" class="btn btn-primary w-100">Rechercher</button>
        </div>
    </form>

    <?php if ($message !== ''): ?>
        <div class="alert alert-warning"><?= $message; ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <?php foreach ($movies as $movie): ?>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="card movie-card shadow-sm">
                    <img src="<?= htmlspecialchars(posterUrl($movie['poster_path'] ?? null, 'w500')); ?>" alt="Affiche du film">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($movie['title'] ?? 'Titre inconnu'); ?></h5>
                        <p class="small text-muted mb-1">Sortie : <?= htmlspecialchars(formatDateFr($movie['release_date'] ?? null)); ?></p>
                        <p class="card-text text-muted small"><?= limitText($movie['overview'] ?? '', 110); ?></p>
                        <a href="movie.php?id=<?= (int) ($movie['id'] ?? 0); ?>" class="btn btn-primary mt-auto">Voir</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require('footer.php'); ?>
