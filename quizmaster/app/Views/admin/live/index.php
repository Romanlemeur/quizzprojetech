<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Gestion du Quiz en Direct</h5>
            </div>
            <div class="card-body">
                <h4><?= $quiz['title'] ?></h4>
                <p class="text-muted"><?= $quiz['description'] ?></p>
                
                <div class="progress mb-3">
                    <div id="question-progress-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
                
                <p id="question-progress" class="text-center mb-4">Prêt à commencer</p>
                
                <input type="hidden" id="quiz-id" value="<?= $quiz['id'] ?>">
                <input type="hidden" id="current-question" value="0">
                
                <div class="d-grid gap-2">
                    <button id="next-question-btn" class="btn btn-primary btn-lg">
                        <i class="fa fa-play"></i> Lancer / Question Suivante
                    </button>
                </div>
                
                <div class="alert alert-info mt-4">
                    <p><strong>Instructions:</strong></p>
                    <ol>
                        <li>Cliquez sur "Lancer" pour commencer le quiz et afficher la première question.</li>
                        <li>Les joueurs auront 10 secondes pour répondre à chaque question.</li>
                        <li>Cliquez sur "Question Suivante" pour passer à la question suivante.</li>
                        <li>Vous pouvez modifier les scores des joueurs à tout moment.</li>
                    </ol>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>Détails du Quiz</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Catégorie</th>
                        <td><?= $quiz['category_name'] ?></td>
                    </tr>
                    <tr>
                        <th>Nombre de questions</th>
                        <td><?= count($quiz['questions']) ?></td>
                    </tr>
                    <tr>
                        <th>Temps limite par question</th>
                        <td>10 secondes</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Participants <span class="badge bg-primary" id="participants-count"><?= $participantsCount ?></span></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Joueur</th>
                                <th>Score</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="participants-list">
                            <tr>
                                <td colspan="4" class="text-center">Chargement des participants...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour modifier le score -->
<div class="modal fade" id="update-score-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le score</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-score-form">
                    <input type="hidden" id="user-id-input" name="user_id">
                    <input type="hidden" id="quiz-id-input" name="quiz_id">
                    
                    <div class="mb-3">
                        <label for="score-input" class="form-label">Score</label>
                        <input type="number" class="form-control" id="score-input" name="score" min="0" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 