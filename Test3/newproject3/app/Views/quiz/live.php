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
                        <h4 id="waiting-message">En attente de la prochaine question...</h4>
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
    let currentQuestionId = null; // Pour éviter de réafficher la même question
    
    // Boucle principale pour interroger le serveur
    function pollServer() {
        // 1. Obtenir la question actuelle
        fetch(`/quiz/get-current-question?quiz_id=${quizId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Poll response:', data); // Debug
                if (data.status === 'in_progress') {
                    if (data.question && data.question.id !== currentQuestionId) {
                        currentQuestionId = data.question.id;
                        displayQuestion(data);
                    }
                } else if (data.status === 'finished') {
                    displayFinishScreen(data.leaderboard);
                    // Arrêter la boucle
                } else {
                    // 'waiting' ou erreur
                    displayWaitingScreen(data.message);
                }
            })
            .catch(error => {
                console.error("Erreur de connexion:", error);
                displayWaitingScreen("Erreur de connexion avec le serveur.");
            });

        // 2. Mettre à jour le classement (fait en parallèle)
        updateLeaderboard();
    }

    function displayWaitingScreen(message) {
        waitingScreen.classList.remove('d-none');
        questionScreen.classList.add('d-none');
        resultScreen.classList.add('d-none');
        finishScreen.classList.add('d-none');
        document.getElementById('waiting-message').textContent = message || 'Le quiz va bientôt commencer...';
    }

    function displayQuestion(data) {
        waitingScreen.classList.add('d-none');
        resultScreen.classList.add('d-none');
        questionScreen.classList.remove('d-none');

        // Mettre à jour le texte et les options
        questionText.textContent = `(${data.currentQuestionIndex}/${data.totalQuestions}) ${data.question.question_text}`;
        optionsContainer.innerHTML = '';
        data.question.options.forEach(option => {
            const button = document.createElement('button');
            button.className = 'option-button';
            button.textContent = option.option_text;
            button.onclick = () => submitAnswer(data.question.id, option.id);
            optionsContainer.appendChild(button);
        });

        // Gérer le minuteur
        startTimer(new Date(data.ends_at));
    }

    function displayResultScreen(isCorrect, message) {
        questionScreen.classList.add('d-none');
        resultScreen.classList.remove('d-none');
        
        resultText.textContent = isCorrect ? 'Bonne réponse !' : 'Mauvaise réponse ou temps écoulé.';
        resultIcon.innerHTML = isCorrect ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>';
        
        // Après quelques secondes, retourner à l'écran d'attente
        setTimeout(() => {
            displayWaitingScreen('En attente de la prochaine question...');
        }, 3000);
    }

    function displayFinishScreen(leaderboard) {
        // Cacher tous les autres écrans
        waitingScreen.classList.add('d-none');
        questionScreen.classList.add('d-none');
        resultScreen.classList.add('d-none');
        finishScreen.classList.remove('d-none');
        
        // Afficher le score final (si on peut le récupérer)
        const userScore = leaderboard.find(p => p.user_id == userId);
        finalScore.textContent = userScore ? userScore.score : 'N/A';
    }

    function startTimer(endTime) {
        clearInterval(timer);
        
        const update = () => {
            const now = new Date();
            const timeLeft = Math.round((endTime - now) / 1000);
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                timerText.textContent = '0';
                timerBar.style.width = '0%';
                displayResultScreen(false, "Temps écoulé !");
            } else {
                timerText.textContent = timeLeft;
                const totalDuration = 15; // Durée définie côté admin
                timerBar.style.width = `${(timeLeft / totalDuration) * 100}%`;
            }
        };
        
        update();
        timer = setInterval(update, 500);
    }

    function submitAnswer(questionId, optionId) {
        clearInterval(timer); // Arrêter le timer dès qu'on répond
        
        // Désactiver les boutons pour éviter double clic
        optionsContainer.querySelectorAll('button').forEach(b => b.disabled = true);
        
        fetch('/quiz/submit-live-answer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                quiz_id: quizId,
                question_id: questionId,
                option_id: optionId,
                user_id: userId
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Submit response:', data); // Debug
            displayResultScreen(data.success, data.message);
        })
        .catch(error => {
            console.error('Submit error:', error);
            displayResultScreen(false, 'Erreur lors de l\'envoi de la réponse');
        });
    }

    function updateLeaderboard() {
        fetch(`/quiz/get-live-leaderboard?quiz_id=${quizId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.leaderboard) {
                    const leaderboardBody = document.getElementById('leaderboard-body');
                    leaderboardBody.innerHTML = ''; // Vider
                    
                    if (data.leaderboard.length === 0) {
                         leaderboardBody.innerHTML = '<tr><td colspan="3" class="text-center">Personne n\'a encore rejoint...</td></tr>';
                    } else {
                        data.leaderboard.forEach((player, index) => {
                            const row = document.createElement('tr');
                            // Mettre en surbrillance le joueur actuel
                            if (player.user_id == userId) {
                                row.classList.add('table-primary');
                            }
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${player.username}</td>
                                <td>${player.score}</td>
                            `;
                            leaderboardBody.appendChild(row);
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Leaderboard error:', error);
            });
    }

    // Lancer la machine
    pollServer(); // Premier appel
    setInterval(pollServer, 3000); // Puis toutes les 3 secondes
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