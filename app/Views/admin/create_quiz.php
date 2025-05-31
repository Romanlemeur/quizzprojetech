<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2><?= isset($quiz) ? 'Modifier le Quiz' : 'Créer un nouveau Quiz' ?></h2>
                </div>
                <div class="card-body">
                    <form action="<?= isset($quiz) ? base_url('admin/editQuiz/' . $quiz['id']) : base_url('admin/createQuiz') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="title" 
                                   name="title" 
                                   value="<?= isset($quiz) ? esc($quiz['title']) : old('title') ?>" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3"><?= isset($quiz) ? esc($quiz['description']) : old('description') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Catégorie</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Sélectionner une catégorie</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" 
                                            <?= (isset($quiz) && $quiz['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                        <?= esc($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php if (isset($quiz)): ?>
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="draft" <?= $quiz['status'] === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                                <option value="active" <?= $quiz['status'] === 'active' ? 'selected' : '' ?>>Actif</option>
                            </select>
                        </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="time_limit" class="form-label">Limite de temps (en minutes, optionnel)</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="time_limit" 
                                   name="time_limit" 
                                   value="<?= isset($quiz) ? esc($quiz['time_limit']) : old('time_limit') ?>"
                                   min="0">
                        </div>

                        <div class="mb-3">
                            <label for="passing_percentage" class="form-label">Pourcentage de réussite requis</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="passing_percentage" 
                                   name="passing_percentage" 
                                   value="<?= isset($quiz) ? esc($quiz['passing_percentage']) : old('passing_percentage', 60) ?>"
                                   min="0" 
                                   max="100" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="max_attempts" class="form-label">Nombre maximum de tentatives (optionnel)</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="max_attempts" 
                                   name="max_attempts" 
                                   value="<?= isset($quiz) ? esc($quiz['max_attempts']) : old('max_attempts') ?>"
                                   min="0">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <?= isset($quiz) ? 'Mettre à jour' : 'Créer' ?>
                            </button>
                            <a href="<?= base_url('admin/manageQuizzes') ?>" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 