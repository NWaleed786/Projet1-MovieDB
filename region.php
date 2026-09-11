<?php
require_once('fonctions.php');

$regions = regionsList();
$code = isset($_GET['code']) ? strtoupper(trim($_GET['code'])) : 'US';

if (!array_key_exists($code, $regions)) {
    $code = 'US';
}

$movies = moviesByRegion($code);
$regionLabel = $regions[$code];

require('header.php');
?>
<div class="container">
    <h2 class="section-title">Films : <?= htmlspecialchars($regionLabel); ?></h2>

    <div class="row g-4">
        <?php foreach ($movies as $movie): ?>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="card movie-card shadow-sm">
                    <img src="<?= htmlspecialchars(posterUrl($movie['poster_path'] ?? null, 'w500')); ?>" alt="Affiche du film">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($movie['title'] ?? 'Titre inconnu'); ?></h5>
                        <p class="small text-muted mb-1">Sortie : <?= htmlspecialchars(formatDateFr($movie['release_date'] ?? null)); ?></p>
                        <p class="card-text text-muted small"><?= limitText($movie['overview'] ?? '', 110); ?></p>
                        <a href="movie.php?id=<?= (int) ($movie['id'] ?? 0); ?>" class="btn btn-outline-primary mt-auto">Voir</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($movies)): ?>
            <div class="col-12">
                <div class="alert alert-warning">Aucun film trouvé pour cette région.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require('footer.php'); ?>
