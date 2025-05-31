<div class="container mt-4">
    <h1>Tableau de bord administrateur</h1>
    
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quiz</h5>
                    <p class="card-text display-4"><?= $total_quizzes ?></p>
                    <a href="<?= base_url('admin/manageQuizzes') ?>" class="btn btn-primary">Gérer les quiz</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Catégories</h5>
                    <p class="card-text display-4"><?= $total_categories ?></p>
                    <a href="<?= base_url('admin/manageCategories') ?>" class="btn btn-primary">Gérer les catégories</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs</h5>
                    <p class="card-text display-4"><?= $total_users ?></p>
                    <a href="<?= base_url('admin/manageUsers') ?>" class="btn btn-primary">Gérer les utilisateurs</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quiz récents</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Statut</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_quizzes as $quiz): ?>
                            <tr>
                                <td><?= esc($quiz['title']) ?></td>
                                <td>
                                    <span class="badge <?= $quiz['status'] === 'active' ? 'bg-success' : 'bg-warning' ?>">
                                        <?= ucfirst($quiz['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($quiz['created_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/editQuiz/' . $quiz['id']) ?>" class="btn btn-sm btn-primary">Modifier</a>
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