<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="/admin/quiz/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Créer un quiz
        </a>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if (empty($quizzes)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun quiz créé pour le moment</p>
                    <a href="/admin/quiz/create" class="btn btn-primary">Créer votre premier quiz</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Titre</th>
                                <th>Catégorie</th>
                                <th>Questions</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($quizzes as $quiz): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($quiz['image'])): ?>
                                            <img src="/uploads/quiz/<?= $quiz['image'] ?>" 
                                                 alt="<?= esc($quiz['title']) ?>" 
                                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-secondary d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px; border-radius: 4px;">
                                                <i class="fas fa-image text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= esc($quiz['title']) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= esc($quiz['description'] ?? '') ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?= esc($quiz['category_name'] ?? 'Non catégorisé') ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?= $quiz['question_count'] ?? 0 ?> questions</span>
                                    </td>
                                    <td>
                                        <?php if ($quiz['is_live'] == 1): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-broadcast-tower"></i> EN DIRECT
                                            </span>
                                        <?php elseif ($quiz['is_active'] == 1): ?>
                                            <span class="badge bg-primary">Actif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php if ($quiz['is_live'] == 0): ?>
                                                <a href="/admin/quiz/start-live/<?= $quiz['id'] ?>" 
                                                   class="btn btn-sm btn-success" title="Lancer en direct">
                                                    <i class="fas fa-play"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="/admin/live" class="btn btn-sm btn-warning" title="Gérer le direct">
                                                    <i class="fas fa-cog"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <a href="/admin/quiz/edit/<?= $quiz['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <a href="/admin/quiz/delete/<?= $quiz['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               title="Supprimer"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz ?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> 