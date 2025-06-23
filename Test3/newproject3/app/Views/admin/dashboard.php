<div class="dashboard-stats">
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fa fa-question-circle"></i>
                    </div>
                    <div class="stat-card-info">
                        <h5 class="stat-card-title">Total des Quiz</h5>
                        <p class="stat-card-value"><?= $quizCount ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="stat-card-info">
                        <h5 class="stat-card-title">Utilisateurs</h5>
                        <p class="stat-card-value"><?= $userCount ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fa fa-folder"></i>
                    </div>
                    <div class="stat-card-info">
                        <h5 class="stat-card-title">Catégories</h5>
                        <p class="stat-card-value"><?= $categoryCount ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fa fa-broadcast-tower"></i>
                    </div>
                    <div class="stat-card-info">
                        <h5 class="stat-card-title">Quiz en Direct</h5>
                        <p class="stat-card-value"><?= $liveQuiz ? 1 : 0 ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Quiz en Direct</h5>
            </div>
            <div class="card-body">
                <?php if ($liveQuiz): ?>
                <div class="alert alert-info">
                    Le quiz "<strong><?= $liveQuiz['title'] ?></strong>" est actuellement en direct!
                </div>
                <div class="d-grid gap-2">
                    <a href="<?= site_url('admin/live') ?>" class="btn btn-primary">
                        <i class="fa fa-broadcast-tower"></i> Gérer le Quiz en Direct
                    </a>
                    <a href="<?= site_url('admin/quiz/stop-live/' . $liveQuiz['id']) ?>" class="btn btn-danger">
                        <i class="fa fa-stop"></i> Arrêter le Quiz en Direct
                    </a>
                </div>
                <?php else: ?>
                <p class="text-muted">Aucun quiz n'est actuellement en direct.</p>
                <p>Lancez un quiz en direct depuis la <a href="<?= site_url('admin/quizzes') ?>">liste des quiz</a>.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Actions Rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= site_url('admin/quiz/create') ?>" class="btn btn-success">
                        <i class="fa fa-plus"></i> Créer un Nouveau Quiz
                    </a>
                    <a href="<?= site_url('admin/users') ?>" class="btn btn-info">
                        <i class="fa fa-users"></i> Gérer les Utilisateurs
                    </a>
                    <a href="<?= site_url('admin/statistics') ?>" class="btn btn-secondary">
                        <i class="fa fa-chart-bar"></i> Voir les Statistiques
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> 