<?php
require_once('fonctions.php');

$regions = regionsList();
$code = isset($_GET['code']) ? strtoupper(trim($_GET['code'])) : 'US';

if (!array_key_exists($code, $regions)) {
    $code = 'US';
}

$isKoreanRegion = $code === 'KR';
$titles = $isKoreanRegion ? seriesByRegion($code) : moviesByRegion($code);
$regionLabel = $regions[$code];

require('header.php');
?>
<div class="container">
    <h2 class="section-title"><?= $isKoreanRegion ? 'Séries : ' : 'Films : '; ?><?= htmlspecialchars($regionLabel); ?></h2>

    <div class="row g-4">
        <?php foreach ($titles as $title): ?>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="card movie-card shadow-sm">
                    <img src="<?= htmlspecialchars(posterUrl($title['poster_path'] ?? null, 'w500')); ?>" alt="Affiche">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($title[$isKoreanRegion ? 'name' : 'title'] ?? 'Titre inconnu'); ?></h5>
                        <p class="small text-muted mb-1"><?= $isKoreanRegion ? '1ère diffusion : ' : 'Sortie : '; ?><?= htmlspecialchars(formatDateFr($title[$isKoreanRegion ? 'first_air_date' : 'release_date'] ?? null)); ?></p>
                        <p class="card-text text-muted small"><?= limitText($title['overview'] ?? '', 110); ?></p>
                        <a href="<?= $isKoreanRegion ? 'serie.php' : 'movie.php'; ?>?id=<?= (int) ($title['id'] ?? 0); ?>" class="btn btn-outline-primary mt-auto">Voir</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($titles)): ?>
            <div class="col-12">
                <div class="alert alert-warning">Aucun résultat trouvé pour cette région.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require('footer.php'); ?>
