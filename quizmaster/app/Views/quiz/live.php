<div class="quiz-live-container">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Quiz en Direct: <?= $quiz['title'] ?></h5>
                </div>
                <div class="card-body">
                    <!-- Attente de question -->
                    <div id="waiting-screen" class="text-center">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <h4>En attente de la prochaine question...</h4>
                        <p>L'administrateur va lancer le quiz. Soyez prêt!</p>
                    </div>
                    
                    <!-- Zone de question -->
                    <div id="question-screen" class="d-none">
                        <div class="timer-container mb-3">
                            <div class="timer-bar" id="timer-bar"></div>
                            <div class="timer-text" id="timer-text">10</div>
                        </div>
                        
                        <h4 id="question-text" class="mb-4"></h4>
                        
                        <div id="options-container" class="options-container">
                            <!-- Les options seront générées ici par JavaScript -->
                        </div>
                    </div>
                    
                    <!-- Résultat de la réponse -->
                    <div id="result-screen" class="text-center d-none">
                        <div id="result-icon" class="result-icon mb-3">
                            <i class="fa fa-check-circle text-success"></i>
                        </div>
                        <h4 id="result-text">Bonne réponse!</h4>
                        <p id="points-text">Vous avez gagné <span id="points-earned">0</span> points</p>
                        <p class="text-muted">En attente de la prochaine question...</p>
                    </div>
                    
                    <!-- Fin du quiz -->
                    <div id="finish-screen" class="text-center d-none">
                        <div class="mb-3">
                            <i class="fa fa-trophy fa-3x text-warning"></i>
                        </div>
                        <h4>Quiz terminé!</h4>
                        <p>Merci de votre participation. Votre score final est: <span id="final-score">0</span></p>
                        <div class="d-grid gap-2 mt-4">
                            <a href="<?= site_url('quiz') ?>" class="btn btn-primary">Retour aux Quiz</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Classement en Direct</h5>
                </div>
                <div class="card-body p-0">
                    <div class="leaderboard-container">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Joueur</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody id="leaderboard-body">
                                <tr>
                                    <td colspan="3" class="text-center">Chargement du classement...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Informations cachées -->
<input type="hidden" id="quiz-id" value="<?= $quiz['id'] ?>">
<input type="hidden" id="user-id" value="<?= session()->get('user_id') ?>">
<input type="hidden" id="current-question" value="-1">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const quizId = document.getElementById('quiz-id').value;
    const userId = document.getElementById('user-id').value;
    const currentQuestion = document.getElementById('current-question');
    
    const waitingScreen = document.getElementById('waiting-screen');
    const questionScreen = document.getElementById('question-screen');
    const resultScreen = document.getElementById('result-screen');
    const finishScreen = document.getElementById('finish-screen');
    
    const questionText = document.getElementById('question-text');
    const optionsContainer = document.getElementById('options-container');
    const timerBar = document.getElementById('timer-bar');
    const timerText = document.getElementById('timer-text');
    
    const resultIcon = document.getElementById('result-icon');
    const resultText = document.getElementById('result-text');
    const pointsEarned = document.getElementById('points-earned');
    const finalScore = document.getElementById('final-score');
    
    // Variables de jeu
    let timer;
    let timeLeft = 10;
    let questionStartTime;
    let currentScore = 0;
    let hasAnswered = false;
    
    // Vérifier la question actuelle toutes les 2 secondes
    checkCurrentQuestion();
    setInterval(checkCurrentQuestion, 2000);
    
    // Mettre à jour le classement toutes les 3 secondes
    updateLeaderboard();
    setInterval(updateLeaderboard, 3000);
    
    // Vérifier la question actuelle
    function checkCurrentQuestion() {
        fetch(`<?= site_url('quiz/get-current-question') ?>?quiz_id=${quizId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Quiz terminé
                    if (data.finished) {
                        showFinishScreen();
                        return;
                    }
                    
                    // Nouvelle question
                    if (data.current_question > currentQuestion.value) {
                        currentQuestion.value = data.current_question;
                        showQuestion(data.question, data.time_limit);
                    }
                }
            })
            .catch(error => console.error('Erreur:', error));
    }
    
    // Afficher une question
    function showQuestion(question, timeLimit) {
        // Cacher les autres écrans
        waitingScreen.classList.add('d-none');
        resultScreen.classList.add('d-none');
        finishScreen.classList.add('d-none');
        questionScreen.classList.remove('d-none');
        
        // Afficher la question
        questionText.textContent = question.question_text;
        
        // Générer les options
        optionsContainer.innerHTML = '';
        question.options.forEach(option => {
            const optionButton = document.createElement('button');
            optionButton.className = 'option-button';
            optionButton.textContent = option.option_text;
            optionButton.dataset.id = option.id;
            optionButton.dataset.questionId = question.id;
            optionButton.addEventListener('click', selectOption);
            optionsContainer.appendChild(optionButton);
        });
        
        // Démarrer le timer
        timeLeft = timeLimit;
        timerText.textContent = timeLeft;
        timerBar.style.width = '100%';
        hasAnswered = false;
        questionStartTime = Date.now();
        
        clearInterval(timer);
        timer = setInterval(() => {
            timeLeft--;
            timerText.textContent = timeLeft;
            timerBar.style.width = (timeLeft / timeLimit * 100) + '%';
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                if (!hasAnswered) {
                    showResult(false, 0);
                }
            }
        }, 1000);
    }
    
    // Sélectionner une option
    function selectOption(event) {
        if (hasAnswered) return;
        
        hasAnswered = true;
        clearInterval(timer);
        
        const optionId = event.target.dataset.id;
        const questionId = event.target.dataset.questionId;
        const timeSpent = (Date.now() - questionStartTime) / 1000;
        
        // Désactiver tous les boutons
        document.querySelectorAll('.option-button').forEach(button => {
            button.disabled = true;
        });
        
        // Envoyer la réponse
        fetch('<?= site_url('quiz/submit-live-answer') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `quiz_id=${quizId}&question_id=${questionId}&option_id=${optionId}&time_spent=${timeSpent}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showResult(data.is_correct, data.points_earned);
                currentScore = data.new_score;
                finalScore.textContent = currentScore;
            }
        })
        .catch(error => console.error('Erreur:', error));
    }
    
    // Afficher le résultat
    function showResult(isCorrect, points) {
        questionScreen.classList.add('d-none');
        resultScreen.classList.remove('d-none');
        
        if (isCorrect) {
            resultIcon.innerHTML = '<i class="fa fa-check-circle fa-3x text-success"></i>';
            resultText.textContent = 'Bonne réponse!';
            resultText.className = 'text-success';
        } else {
            resultIcon.innerHTML = '<i class="fa fa-times-circle fa-3x text-danger"></i>';
            resultText.textContent = 'Mauvaise réponse ou temps écoulé';
            resultText.className = 'text-danger';
        }
        
        pointsEarned.textContent = points;
    }
    
    // Afficher l'écran de fin
    function showFinishScreen() {
        waitingScreen.classList.add('d-none');
        questionScreen.classList.add('d-none');
        resultScreen.classList.add('d-none');
        finishScreen.classList.remove('d-none');
        
        finalScore.textContent = currentScore;
    }
    
    // Mettre à jour le classement
    function updateLeaderboard() {
        fetch(`<?= site_url('quiz/get-live-leaderboard') ?>?quiz_id=${quizId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const leaderboardBody = document.getElementById('leaderboard-body');
                    
                    if (data.leaderboard.length === 0) {
                        leaderboardBody.innerHTML = `
                            <tr>
                                <td colspan="3" class="text-center">Pas encore de participants</td>
                            </tr>
                        `;
                        return;
                    }
                    
                    leaderboardBody.innerHTML = '';
                    data.leaderboard.forEach((participant, index) => {
                        const isCurrentUser = participant.user_id == userId;
                        const rowClass = isCurrentUser ? 'table-primary' : '';
                        
                        leaderboardBody.innerHTML += `
                            <tr class="${rowClass}">
                                <td>${index + 1}</td>
                                <td>${participant.username}</td>
                                <td>${participant.score}</td>
                            </tr>
                        `;
                    });
                }
            })
            .catch(error => console.error('Erreur:', error));
    }
});
</script>

<style>
.timer-container {
    position: relative;
    height: 40px;
    background-color: #f0f0f0;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 20px;
}

.timer-bar {
    height: 100%;
    width: 100%;
    background-color: #4CAF50;
    transition: width 1s linear;
}

.timer-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 18px;
    font-weight: bold;
    color: #fff;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.options-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.option-button {
    padding: 15px;
    border: 2px solid #ddd;
    border-radius: 10px;
    background-color: #f9f9f9;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s;
}

.option-button:hover {
    background-color: #e9e9e9;
    border-color: #bbb;
}

.option-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.result-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.leaderboard-container {
    max-height: 400px;
    overflow-y: auto;
}
</style> 