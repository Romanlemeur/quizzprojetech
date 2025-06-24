<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">
            <i class="fas fa-tachometer-alt me-2"></i>
            Dashboard Administrateur
        </h1>
    </div>
</div>

<!-- Statistiques rapides -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?= count($quiz) ?></h4>
                        <p class="mb-0">Quiz créés</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?= $sessionActive ? '1' : '0' ?></h4>
                        <p class="mb-0">Session active</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-play-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?= count($participants) ?></h4>
                        <p class="mb-0">Participants</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?= session()->get('pseudo') ?></h4>
                        <p class="mb-0">Connecté</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-shield fa-2x"></i>
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
                    <div class="col-md-6">
                        <h6>Quiz en cours : <?= $sessionActive['nomQuiz'] ?? 'Quiz' ?></h6>
                        <p class="text-muted">Démarré le : <?= date('d/m/Y H:i', strtotime($sessionActive['dateHeureDebut'])) ?></p>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="/admin/live-session/<?= $sessionActive['idSession'] ?>" class="btn btn-primary">
                            <i class="fas fa-eye me-1"></i>Voir en direct
                        </a>
                        <a href="/admin/stop-session/<?= $sessionActive['idSession'] ?>" class="btn btn-danger" 
                           onclick="return confirm('Êtes-vous sûr de vouloir arrêter cette session ?')">
                            <i class="fas fa-stop me-1"></i>Arrêter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Participants -->
<?php if (!empty($participants)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Participants (<?= count($participants) ?>)
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Pseudo</th>
                                <th>Email</th>
                                <th>Score</th>
                                <th>Connexion</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($participants as $participant): ?>
                            <tr>
                                <td><?= $participant['pseudo'] ?></td>
                                <td><?= $participant['email'] ?></td>
                                <td>
                                    <span class="badge bg-primary"><?= $participant['scoreActuel'] ?> pts</span>
                                </td>
                                <td><?= date('H:i', strtotime($participant['dateHeureConnexion'])) ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" data-bs-target="#editScoreModal" 
                                            data-participation="<?= $participant['idParticipation'] ?>"
                                            data-pseudo="<?= $participant['pseudo'] ?>"
                                            data-score="<?= $participant['scoreActuel'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

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
                <a href="/admin/quiz" class="btn btn-primary">
                    <i class="fas fa-list me-1"></i>Gérer les quiz
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Actions rapides -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Actions rapides
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/admin/quiz" class="btn btn-outline-primary">
                        <i class="fas fa-list me-1"></i>Gérer les quiz
                    </a>
                    <a href="/admin/add-quiz" class="btn btn-outline-success">
                        <i class="fas fa-plus me-1"></i>Créer un quiz
                    </a>
                    <a href="/admin/admins" class="btn btn-outline-info">
                        <i class="fas fa-users-cog me-1"></i>Gérer les administrateurs
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Quiz récents
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($quiz)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach (array_slice($quiz, 0, 5) as $q): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0"><?= $q['nomQuiz'] ?></h6>
                                <small class="text-muted"><?= $q['pointsParBonneReponse'] ?> pts par bonne réponse</small>
                            </div>
                            <div>
                                <a href="/admin/questions/<?= $q['idQuiz'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if (!$sessionActive): ?>
                                <a href="/admin/start-session/<?= $q['idQuiz'] ?>" class="btn btn-sm btn-success">
                                    <i class="fas fa-play"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">Aucun quiz créé</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour modifier le score -->
<div class="modal fade" id="editScoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le score</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/admin/update-score">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Joueur</label>
                        <input type="text" class="form-control" id="playerName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="newScore" class="form-label">Nouveau score</label>
                        <input type="number" class="form-control" id="newScore" name="score" min="0" required>
                        <input type="hidden" id="participationId" name="idParticipation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Script pour le modal de modification de score
document.addEventListener('DOMContentLoaded', function() {
    const editScoreModal = document.getElementById('editScoreModal');
    if (editScoreModal) {
        editScoreModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const participation = button.getAttribute('data-participation');
            const pseudo = button.getAttribute('data-pseudo');
            const score = button.getAttribute('data-score');
            
            document.getElementById('playerName').value = pseudo;
            document.getElementById('newScore').value = score;
            document.getElementById('participationId').value = participation;
        });
    }
});
</script>
<?= $this->endSection() ?> 