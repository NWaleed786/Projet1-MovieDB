<?php
require_once('fonctions.php');
$series = koreanDramas();
require('header.php');
?>
<div class="container">
    <div class="hero">
        <h1>K-Dramas populaires</h1>
        <p class="text-muted mb-0">Séries coréennes (catégorie séries TV de l'API TMDB, pas des films).</p>
    </div>

    <div class="row g-4">
        <?php foreach ($series as $serie): ?>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="card movie-card shadow-sm">
                    <img src="<?= htmlspecialchars(posterUrl($serie['poster_path'] ?? null, 'w500')); ?>" alt="Affiche de la série">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($serie['name'] ?? 'Titre inconnu'); ?></h5>
                        <p class="small text-muted mb-1">1ère diffusion : <?= htmlspecialchars(formatDateFr($serie['first_air_date'] ?? null)); ?></p>
                        <p class="card-text text-muted small"><?= limitText($serie['overview'] ?? '', 110); ?></p>
                        <a href="serie.php?id=<?= (int) ($serie['id'] ?? 0); ?>" class="btn btn-outline-primary mt-auto">Voir</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($series)): ?>
            <div class="col-12">
                <div class="alert alert-warning">Aucune série trouvée.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require('footer.php'); ?>
