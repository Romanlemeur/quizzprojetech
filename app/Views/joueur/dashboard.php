<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">
            <i class="fas fa-gamepad me-2"></i>
            Dashboard Joueur
        </h1>
    </div>
</div>

<!-- Bienvenue -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">Bienvenue, <?= session()->get('pseudo') ?> !</h4>
                        <p class="mb-0">Prêt à participer à un quiz ? Rejoignez une session active ou attendez qu'un administrateur en lance une.</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="fas fa-user-circle fa-4x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Session active -->
<?php if ($sessionActive): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-play-circle me-2"></i>
                    Session Active
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h6>Quiz en cours : <?= $sessionActive['nomQuiz'] ?? 'Quiz' ?></h6>
                        <p class="text-muted">Démarré le : <?= date('d/m/Y H:i', strtotime($sessionActive['dateHeureDebut'])) ?></p>
                        <?php if ($participation): ?>
                            <p class="mb-0">
                                <strong>Votre score : </strong>
                                <span class="badge bg-primary"><?= $participation['scoreActuel'] ?> pts</span>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 text-end">
                        <?php if ($participation): ?>
                            <a href="/joueur/play/<?= $sessionActive['idSession'] ?>" class="btn btn-primary">
                                <i class="fas fa-play me-1"></i>Continuer
                            </a>
                        <?php else: ?>
                            <a href="/joueur/join-session/<?= $sessionActive['idSession'] ?>" class="btn btn-success">
                                <i class="fas fa-sign-in-alt me-1"></i>Rejoindre
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<!-- Aucune session active -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">
                    <i class="fas fa-pause-circle me-2"></i>
                    Aucune session active
                </h5>
            </div>
            <div class="card-body text-center">
                <p class="mb-3">Aucun quiz n'est actuellement en cours.</p>
                <p class="text-muted">Revenez plus tard ou contactez un administrateur.</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Statistiques personnelles -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Mes statistiques
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h3 class="text-primary"><?= $participation ? $participation['scoreActuel'] : '0' ?></h3>
                            <p class="text-muted mb-0">Points actuels</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <h3 class="text-success"><?= $sessionActive ? '1' : '0' ?></h3>
                        <p class="text-muted mb-0">Sessions actives</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cog me-2"></i>
                    Actions rapides
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/joueur/profile" class="btn btn-outline-primary">
                        <i class="fas fa-user me-1"></i>Mon profil
                    </a>
                    <?php if ($sessionActive && $participation): ?>
                        <a href="/joueur/results/<?= $sessionActive['idSession'] ?>" class="btn btn-outline-info">
                            <i class="fas fa-trophy me-1"></i>Voir les résultats
                        </a>
                    <?php endif; ?>
                    <button class="btn btn-outline-secondary" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-1"></i>Actualiser
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Guide d'utilisation -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Comment jouer ?
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-sign-in-alt fa-2x text-primary"></i>
                        </div>
                        <h6>1. Rejoindre une session</h6>
                        <p class="text-muted small">Quand un quiz est actif, cliquez sur "Rejoindre"</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-question-circle fa-2x text-success"></i>
                        </div>
                        <h6>2. Répondre aux questions</h6>
                        <p class="text-muted small">Choisissez une réponse parmi les 4 proposées dans le temps imparti</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-trophy fa-2x text-warning"></i>
                        </div>
                        <h6>3. Voir vos résultats</h6>
                        <p class="text-muted small">Consultez votre score et votre classement à la fin du quiz</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auto-refresh -->
<script>
// Actualisation automatique toutes les 30 secondes
setInterval(function() {
    // Vérifier si une session est active
    fetch('/joueur/dashboard')
        .then(response => response.text())
        .then(html => {
            // Si une session est maintenant active, recharger la page
            if (html.includes('Session Active') && !document.querySelector('.border-success'))) {
                location.reload();
            }
        })
        .catch(error => console.log('Erreur lors de la vérification:', error));
}, 30000);
</script>
<?= $this->endSection() ?> 