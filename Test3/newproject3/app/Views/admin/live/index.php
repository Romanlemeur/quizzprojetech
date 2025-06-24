<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Gestion du Quiz en Direct</h5>
                <div>
                    <button id="stop-quiz-btn" class="btn btn-sm btn-danger" onclick="stopQuiz()">
                        <i class="fas fa-stop"></i> Arrêter le Quiz
                    </button>
                </div>
            </div>
            <div class="card-body">
                <h4><?= $quiz['title'] ?></h4>
                <p class="text-muted"><?= $quiz['description'] ?></p>
                
                <div class="progress mb-3">
                    <div id="question-progress-bar" class="progress-bar bg-primary" role="progressbar" style="width: 0%"></div>
                </div>
                
                <div class="text-center mb-4">
                    <p id="question-progress" class="mb-2">Prêt à commencer</p>
                    <div id="current-question-display" class="alert alert-info" style="display: none;">
                        <h6 id="question-text"></h6>
                        <div id="question-options"></div>
                    </div>
                </div>
                
                <input type="hidden" id="quiz-id" value="<?= $quiz['id'] ?>">
                <input type="hidden" id="current-question" value="0">
                <input type="hidden" id="total-questions" value="<?= count($quiz['questions']) ?>">
                
                <div class="d-grid gap-2">
                    <button id="next-question-btn" class="btn btn-primary btn-lg">
                        <i class="fas fa-play"></i> Lancer le Quiz
                    </button>
                </div>
                
                <div class="alert alert-info mt-4">
                    <p><strong>Instructions:</strong></p>
                    <ol>
                        <li>Cliquez sur "Lancer" pour commencer le quiz et afficher la première question</li>
                        <li>Les joueurs auront <strong>10 secondes</strong> pour répondre à chaque question</li>
                        <li>Cliquez sur "Question Suivante" pour passer à la question suivante</li>
                        <li>Vous pouvez modifier les scores des joueurs à tout moment</li>
                        <li>Le classement se met à jour automatiquement</li>
                    </ol>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>Détails du Quiz</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-sm">
                            <tr>
                                <th>Catégorie</th>
                                <td><?= $quiz['category_name'] ?></td>
                            </tr>
                            <tr>
                                <th>Questions</th>
                                <td><?= count($quiz['questions']) ?></td>
                            </tr>
                            <tr>
                                <th>Temps/question</th>
                                <td>10 secondes</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-center">
                            <div class="badge bg-success fs-6 p-3">
                                <i class="fas fa-broadcast-tower"></i> EN DIRECT
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Participants Connectés</h5>
                <span class="badge bg-primary fs-6" id="participants-count">0</span>
            </div>
            <div class="card-body">
                <div id="participants-loading" class="text-center py-3">
                    <i class="fas fa-spinner fa-spin"></i> Chargement...
                </div>
                <div class="table-responsive" id="participants-table" style="display: none;">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Joueur</th>
                                <th>Score</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="participants-list">
                        </tbody>
                    </table>
                </div>
                <div id="no-participants" class="text-center py-3 text-muted" style="display: none;">
                    <i class="fas fa-users"></i><br>
                    Aucun participant connecté
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
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="update-score-form">
                    <input type="hidden" id="user-id-input" name="user_id">
                    <input type="hidden" id="quiz-id-input" name="quiz_id">
                    
                    <div class="mb-3">
                        <label for="player-name" class="form-label">Joueur</label>
                        <input type="text" class="form-control" id="player-name" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="score-input" class="form-label">Nouveau Score</label>
                        <input type="number" class="form-control" id="score-input" name="score" min="0" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let currentQuestionId = null;
let currentQuestionIndex = 0;
let totalQuestions = parseInt(document.getElementById('total-questions').value);
let quizId = document.getElementById('quiz-id').value;
let isQuizStarted = false;

// Refresh automatique des participants
let participantsInterval;

document.addEventListener('DOMContentLoaded', function() {
    loadParticipants();
    startParticipantsRefresh();
    updateProgressBar(); // Initialiser l'affichage
});

// Gestion du bouton Lancer/Question Suivante
document.getElementById('next-question-btn').addEventListener('click', function() {
    if (!isQuizStarted) {
        startQuiz();
    } else {
        nextQuestion();
    }
});

function startQuiz() {
    console.log('Démarrage du quiz...');
    console.log('Quiz ID:', quizId);    
    // On démarre à partir de null (aucune question encore)
    fetch('/admin/live/next-question', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            quiz_id: quizId,
            current_question_id: null
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            isQuizStarted = true;
            currentQuestionId = data.current_question_id;
            currentQuestionIndex = data.current_question_index;
            document.getElementById('current-question').value = currentQuestionId;
            updateProgressBar();
            updateInterface();
            // Changer le bouton
            const btn = document.getElementById('next-question-btn');
            btn.innerHTML = '<i class="fas fa-arrow-right"></i> Question Suivante';
        } else {
            alert('Erreur: ' + data.message);
        }
    });
}

function nextQuestion() {
    let currentQuestionId = document.getElementById('current-question').value;
    console.log('nextQuestion - current_question_id:', currentQuestionId);
    console.log('nextQuestion - quiz_id:', quizId);
    
    fetch('/admin/live/next-question', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            quiz_id: quizId,
            current_question_id: currentQuestionId
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('nextQuestion response:', data);
        if (data.success) {
            if (data.finished) {
                alert('Quiz terminé !');
                window.location.href = '/admin/quizzes';
            } else {
                currentQuestionId = data.current_question_id;
                currentQuestionIndex = data.current_question_index;
                document.getElementById('current-question').value = currentQuestionId;
                console.log('Updated current_question_id to:', currentQuestionId);
                updateProgressBar();
            }
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('nextQuestion error:', error);
    });
}

function updateProgressBar() {
    if (!currentQuestionIndex || currentQuestionIndex < 1) {
        document.getElementById('question-progress').textContent = 'Prêt à commencer';
        document.getElementById('question-progress-bar').style.width = '0%';
    } else {
        document.getElementById('question-progress').textContent = `Question ${currentQuestionIndex} sur ${totalQuestions}`;
        const progressPercentage = (currentQuestionIndex / totalQuestions) * 100;
        document.getElementById('question-progress-bar').style.width = progressPercentage + '%';
    }
}

function updateInterface() {
    // Mise à jour de la barre de progression
    const progress = (currentQuestionIndex / totalQuestions) * 100;
    document.getElementById('question-progress-bar').style.width = progress + '%';
    
    // Mise à jour du texte de progression
    const progressText = `Question ${currentQuestionIndex} sur ${totalQuestions}`;
    document.getElementById('question-progress').textContent = progressText;
}

function showCurrentQuestion(question) {
    if (!question) return;
    
    const display = document.getElementById('current-question-display');
    const questionText = document.getElementById('question-text');
    const optionsDiv = document.getElementById('question-options');
    
    questionText.textContent = question.question_text;
    
    let optionsHtml = '<div class="row">';
    question.options.forEach((option, index) => {
        const letter = String.fromCharCode(65 + index); // A, B, C, D
        const isCorrect = option.is_correct == 1;
        optionsHtml += `
            <div class="col-6 mb-2">
                <div class="badge ${isCorrect ? 'bg-success' : 'bg-secondary'} w-100 text-start">
                    ${letter}. ${option.option_text}
                    ${isCorrect ? ' <i class="fas fa-check"></i>' : ''}
                </div>
            </div>
        `;
    });
    optionsHtml += '</div>';
    
    optionsDiv.innerHTML = optionsHtml;
    display.style.display = 'block';
}

function loadParticipants() {
    fetch('/admin/live/participants?quiz_id=' + quizId, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('participants-loading').style.display = 'none';
        
        if (data.success && data.participants.length > 0) {
            displayParticipants(data.participants);
            document.getElementById('participants-table').style.display = 'block';
            document.getElementById('no-participants').style.display = 'none';
        } else {
            document.getElementById('participants-table').style.display = 'none';
            document.getElementById('no-participants').style.display = 'block';
        }
        
        updateParticipantsCount(data.participants ? data.participants.length : 0);
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('participants-loading').style.display = 'none';
        document.getElementById('no-participants').style.display = 'block';
    });
}

function displayParticipants(participants) {
    const tbody = document.getElementById('participants-list');
    tbody.innerHTML = '';
    
    participants.forEach((participant, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><span class="badge bg-primary">${index + 1}</span></td>
            <td>${participant.username}</td>
            <td><strong>${participant.score}</strong></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" 
                        onclick="openScoreModal(${participant.user_id}, '${participant.username}', ${participant.score})"
                        title="Modifier le score">
                    <i class="fas fa-edit"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function updateParticipantsCount(count) {
    document.getElementById('participants-count').textContent = count;
}

function startParticipantsRefresh() {
    participantsInterval = setInterval(loadParticipants, 3000); // Refresh toutes les 3 secondes
}

function openScoreModal(userId, username, currentScore) {
    document.getElementById('user-id-input').value = userId;
    document.getElementById('quiz-id-input').value = quizId;
    document.getElementById('player-name').value = username;
    document.getElementById('score-input').value = currentScore;
    
    const modal = new bootstrap.Modal(document.getElementById('update-score-modal'));
    modal.show();
}

// Gestion du formulaire de modification de score
document.getElementById('update-score-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/live/update-score', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('update-score-modal'));
            modal.hide();
            
            // Rafraîchir la liste des participants
            loadParticipants();
            
            // Afficher un message de succès
            showAlert('Score mis à jour avec succès !', 'success');
        } else {
            showAlert('Erreur: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('Erreur de connexion', 'danger');
    });
});

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insérer l'alerte en haut de la page
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Nettoyer les intervals quand on quitte la page
window.addEventListener('beforeunload', function() {
    if (participantsInterval) {
        clearInterval(participantsInterval);
    }
});
</script> 