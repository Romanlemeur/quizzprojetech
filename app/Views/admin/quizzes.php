<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des Quiz</h1>
        <a href="<?= base_url('admin/createQuiz') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Quiz
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Questions</th>
                        <th>Tentatives max.</th>
                        <th>% de réussite</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quizzes as $quiz): ?>
                    <tr>
                        <td><?= esc($quiz['title']) ?></td>
                        <td><?= esc($quiz['category_id']) ?></td>
                        <td>
                            <span class="badge <?= $quiz['status'] === 'active' ? 'bg-success' : 'bg-warning' ?>">
                                <?= ucfirst($quiz['status']) ?>
                            </span>
                        </td>
                        <td><?= $quiz['question_count'] ?? '0' ?></td>
                        <td><?= $quiz['max_attempts'] ?? 'Illimité' ?></td>
                        <td><?= $quiz['passing_percentage'] ?>%</td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= base_url('admin/editQuiz/' . $quiz['id']) ?>" 
                                   class="btn btn-sm btn-primary" 
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('admin/manageQuestions/' . $quiz['id']) ?>" 
                                   class="btn btn-sm btn-info" 
                                   title="Gérer les questions">
                                    <i class="fas fa-list"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete(<?= $quiz['id'] ?>)" 
                                        title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(quizId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce quiz ?')) {
        window.location.href = '<?= base_url('admin/deleteQuiz/') ?>' + quizId;
    }
}
</script> 