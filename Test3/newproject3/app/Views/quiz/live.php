<style>
:root {
  --color-primary: #39ff14;
  --color-border: #333;
  --color-bg: #000;
  --space-xs: 8px;
  --space-sm: 16px;
  --space-md: 24px;
  --space-lg: 32px;
  --space-xl: 48px;
  --font-size-sm: 14px;
  --font-size-md: 16px;
  --font-size-lg: 20px;
  --font-size-xl: 24px;
  --font-size-xxl: 32px;
  --radius-sm: 8px;
  --radius-md: 12px;
}

.quiz-live-container {
  background: var(--color-bg);
  min-height: 100vh;
  color: #fff;
  font-family: 'Arial', sans-serif;
}

.quiz-header {
  background: rgba(0,0,0,0.8);
  padding: var(--space-md);
  border-bottom: 2px solid var(--color-primary);
  text-align: center;
}

.quiz-title {
  font-family: 'Orbitron', sans-serif;
  font-size: var(--font-size-xl);
  color: var(--color-primary);
  margin: 0;
}

.quiz-subtitle {
  color: #ccc;
  margin: var(--space-xs) 0 0 0;
}

.quiz-stats {
  list-style: none;
  display: flex;
  gap: var(--space-md);
  padding: 0;
  margin: var(--space-md) 0 0 0;
  justify-content: center;
}

.quiz-stats li {
  background: rgba(0,0,0,0.6);
  padding: var(--space-xs) var(--space-md);
  border-radius: var(--radius-sm);
  border: 1px solid var(--color-border);
}

.main-content {
  display: flex;
  gap: var(--space-lg);
  padding: var(--space-lg);
  max-width: 1400px;
  margin: 0 auto;
}

.quiz-section {
  flex: 2;
  background: rgba(0,0,0,0.7);
  border-radius: var(--radius-md);
  border: 1px solid var(--color-border);
  overflow: hidden;
}

.leaderboard-section {
  flex: 1;
  background: rgba(0,0,0,0.7);
  border-radius: var(--radius-md);
  border: 1px solid var(--color-border);
  max-height: 600px;
}

.section-header {
  background: rgba(57,255,20,0.1);
  padding: var(--space-md);
  border-bottom: 1px solid var(--color-border);
  font-family: 'Orbitron', sans-serif;
  font-size: var(--font-size-lg);
  color: var(--color-primary);
}

.section-content {
  padding: var(--space-lg);
}

/* Écrans de quiz */
.quiz-screen {
  text-align: center;
  padding: var(--space-xl);
}

.waiting-screen {
  background: linear-gradient(135deg, rgba(0,0,0,0.8), rgba(57,255,20,0.1));
}

.waiting-spinner {
  width: 60px;
  height: 60px;
  border: 4px solid var(--color-border);
  border-top: 4px solid var(--color-primary);
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto var(--space-lg);
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.waiting-message {
  font-size: var(--font-size-lg);
  color: var(--color-primary);
  margin-bottom: var(--space-sm);
}

.waiting-subtitle {
  color: #ccc;
  font-size: var(--font-size-md);
}

/* Question screen */
.question-screen {
  background: linear-gradient(135deg, rgba(0,0,0,0.9), rgba(0,0,0,0.7));
}

.question-text {
  font-family: 'Orbitron', sans-serif;
  font-size: var(--font-size-lg);
  margin-bottom: var(--space-lg);
  line-height: 1.4;
  color: #fff;
}

.question-counter {
  color: var(--color-primary);
  font-size: var(--font-size-md);
  margin-bottom: var(--space-sm);
}

.options-container {
  display: grid;
  gap: var(--space-sm);
  margin-bottom: var(--space-lg);
}

.option-button {
  background: rgba(0,0,0,0.8);
  border: 2px solid var(--color-border);
  border-radius: var(--radius-sm);
  padding: var(--space-md);
  color: #fff;
  font-size: var(--font-size-md);
  cursor: pointer;
  transition: all 0.2s ease;
  text-align: left;
}

.option-button:hover {
  background: rgba(57,255,20,0.1);
  border-color: var(--color-primary);
  transform: translateY(-2px);
}

.option-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
}

.option-button.correct {
  background: rgba(0,255,255,0.2);
  border-color: #00ffff;
}

.option-button.wrong {
  background: rgba(255,17,119,0.2);
  border-color: #ff1177;
}

/* Result screen */
.result-screen {
  background: linear-gradient(135deg, rgba(0,0,0,0.8), rgba(0,0,0,0.6));
}

.result-icon {
  font-size: 64px;
  margin-bottom: var(--space-lg);
}

.result-icon.success {
  color: var(--color-primary);
}

.result-icon.error {
  color: #ff1177;
}

.result-text {
  font-size: var(--font-size-xl);
  margin-bottom: var(--space-sm);
  color: #fff;
}

.result-message {
  color: #ccc;
  font-size: var(--font-size-md);
}

/* Finish screen */
.finish-screen {
  background: linear-gradient(135deg, rgba(0,0,0,0.9), rgba(57,255,20,0.1));
}

.finish-icon {
  font-size: 80px;
  color: #ffd700;
  margin-bottom: var(--space-lg);
}

.finish-title {
  font-size: var(--font-size-xl);
  color: var(--color-primary);
  margin-bottom: var(--space-md);
}

.final-score {
  font-size: var(--font-size-lg);
  color: #fff;
  margin-bottom: var(--space-lg);
}

.btn-primary {
  background: var(--color-primary);
  color: var(--color-bg);
  border: none;
  padding: var(--space-sm) var(--space-lg);
  border-radius: var(--radius-sm);
  font-size: var(--font-size-md);
  font-weight: bold;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-primary:hover {
  background: #2bff00;
  transform: translateY(-2px);
}

/* Leaderboard */
.leaderboard-table {
  width: 100%;
  border-collapse: collapse;
}

.leaderboard-table th,
.leaderboard-table td {
  padding: var(--space-sm);
  text-align: left;
  border-bottom: 1px solid var(--color-border);
}

.leaderboard-table th {
  background: rgba(57,255,20,0.1);
  color: var(--color-primary);
  font-weight: bold;
}

.leaderboard-table tr:hover {
  background: rgba(57,255,20,0.05);
}

.leaderboard-table tr.current-user {
  background: rgba(57,255,20,0.2);
  border-left: 3px solid var(--color-primary);
}

.rank-badge {
  background: var(--color-primary);
  color: var(--color-bg);
  padding: 2px 8px;
  border-radius: 12px;
  font-size: var(--font-size-sm);
  font-weight: bold;
}

.score-value {
  font-weight: bold;
  color: var(--color-primary);
}

/* Responsive */
@media (max-width: 768px) {
  .main-content {
    flex-direction: column;
    padding: var(--space-md);
  }
  
  .quiz-stats {
    flex-direction: column;
    align-items: center;
  }
  
  .section-content {
    padding: var(--space-md);
  }
}

@keyframes winnerPulse {
  0% { transform: scale(1) rotate(-5deg); filter: drop-shadow(0 0 8px gold); }
  100% { transform: scale(1.15) rotate(5deg); filter: drop-shadow(0 0 24px gold); }
}
</style>

<div class="quiz-live-container">
  <!-- Header -->
  <div class="quiz-header">
    <h1 class="quiz-title">Quiz en Direct: <?= $quiz['title'] ?></h1>
    <p class="quiz-subtitle">Participez en temps réel avec les autres joueurs</p>
    <ul class="quiz-stats">
      <li>Questions: <span id="total-questions">-</span></li>
      <li>Participants: <span id="participants-count">-</span></li>
      <li>Votre score: <span id="user-score">0</span></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Quiz Section -->
    <div class="quiz-section">
      <div class="section-header">
        <i class="fas fa-gamepad"></i> Quiz en Cours
      </div>
      <div class="section-content">
        <!-- Waiting Screen -->
        <div id="waiting-screen" class="quiz-screen waiting-screen">
          <div class="waiting-spinner"></div>
          <h2 class="waiting-message" id="waiting-message">En attente du lancement...</h2>
          <p class="waiting-subtitle">L'administrateur va bientôt lancer le quiz. Soyez prêt !</p>
        </div>

        <!-- Question Screen -->
        <div id="question-screen" class="quiz-screen question-screen" style="display:none;">
          <div class="question-counter" id="question-counter">Question 1 sur 3</div>
          <div class="question-text" id="question-text"></div>
          
          <div class="options-container" id="options-container">
            <!-- Options will be generated here -->
          </div>
        </div>

        <!-- Result Screen -->
        <div id="result-screen" class="quiz-screen result-screen" style="display:none;">
          <div class="result-icon" id="result-icon">
            <i class="fas fa-check-circle success"></i>
          </div>
          <h2 class="result-text" id="result-text">Bonne réponse !</h2>
          <p class="result-message" id="result-message">En attente de la prochaine question...</p>
        </div>

        <!-- Finish Screen -->
        <div id="finish-screen" class="quiz-screen finish-screen" style="display:none;">
          <div class="finish-icon" id="winner-animation">
            <i class="fas fa-trophy" style="color: gold; font-size: 4rem; animation: winnerPulse 1.5s infinite alternate;"></i>
          </div>
          <h2 class="finish-title">Quiz Terminé !</h2>
          <p class="final-score">Votre score final: <span id="final-score">0</span></p>
          <p class="final-message" style="color:var(--color-primary);font-size:1.2rem;">Bravo, vous avez terminé le quiz !</p>
          <button class="btn btn-primary" onclick="location.href='<?= site_url('quiz') ?>'">
            <i class="fas fa-home"></i> Retour aux Quiz
          </button>
          <div id="confetti-canvas" style="position:fixed;top:0;left:0;width:100vw;height:100vh;pointer-events:none;z-index:9999;"></div>
        </div>
      </div>
    </div>

    <!-- Leaderboard Section -->
    <div class="leaderboard-section">
      <div class="section-header">
        <i class="fas fa-trophy"></i> Classement en Direct
      </div>
      <div class="section-content">
        <table class="leaderboard-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Joueur</th>
              <th>Score</th>
            </tr>
          </thead>
          <tbody id="leaderboard-body">
            <tr>
              <td colspan="3" style="text-align: center; color: #ccc;">
                <i class="fas fa-spinner fa-spin"></i> Chargement...
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Hidden data -->
<input type="hidden" id="quiz-id" value="<?= $quiz['id'] ?>">
<input type="hidden" id="user-id" value="<?= session()->get('user_id') ?>">

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Configuration
  const quizId = document.getElementById('quiz-id').value;
  const userId = parseInt(document.getElementById('user-id').value);
  
  // Elements
  const waitingScreen = document.getElementById('waiting-screen');
  const questionScreen = document.getElementById('question-screen');
  const resultScreen = document.getElementById('result-screen');
  const finishScreen = document.getElementById('finish-screen');
  
  const questionText = document.getElementById('question-text');
  const questionCounter = document.getElementById('question-counter');
  const optionsContainer = document.getElementById('options-container');
  
  const resultIcon = document.getElementById('result-icon');
  const resultText = document.getElementById('result-text');
  const resultMessage = document.getElementById('result-message');
  
  const finalScore = document.getElementById('final-score');
  const userScore = document.getElementById('user-score');
  const totalQuestions = document.getElementById('total-questions');
  const participantsCount = document.getElementById('participants-count');
  
  // Game state
  let currentQuestionId = null;
  let hasAnswered = false;
  
  // Initialize
  pollServer();
  setInterval(pollServer, 3000);
  
  function pollServer() {
    // Get current question
    fetch(`/quiz/get-current-question?quiz_id=${quizId}`)
      .then(response => response.json())
      .then(data => {
        console.log('Poll response:', data);
        
        if (data.status === 'in_progress' && data.question) {
          currentQuestionId = data.question.id;
          displayQuestion(data);
        } else if (data.status === 'finished') {
          displayFinishScreen(data.leaderboard);
        } else {
          displayWaitingScreen(data.message || 'En attente du lancement...');
        }
      })
      .catch(error => {
        console.error('Poll error:', error);
        displayWaitingScreen('Erreur de connexion');
      });
    
    // Update leaderboard
    updateLeaderboard();
  }
  
  function displayWaitingScreen(message) {
    hideAllScreens();
    waitingScreen.style.display = 'block';
    document.getElementById('waiting-message').textContent = message;
  }
  
  function displayQuestion(data) {
    hideAllScreens();
    questionScreen.style.display = 'block';
    
    // Réinitialiser hasAnswered pour permettre de répondre à cette nouvelle question
    hasAnswered = false;
    
    // Update question info
    questionCounter.textContent = `Question ${data.currentQuestionIndex} sur ${data.totalQuestions}`;
    questionText.textContent = data.question.question_text;
    totalQuestions.textContent = data.totalQuestions;
    
    // Generate options
    optionsContainer.innerHTML = '';
    data.question.options.forEach(option => {
      const button = document.createElement('button');
      button.className = 'option-button';
      button.textContent = option.option_text;
      button.onclick = () => submitAnswer(data.question.id, option.id);
      optionsContainer.appendChild(button);
    });
  }
  
  function displayResultScreen(isCorrect, message) {
    hideAllScreens();
    resultScreen.style.display = 'block';
    
    if (isCorrect) {
      resultIcon.innerHTML = '<i class="fas fa-check-circle success"></i>';
      resultText.textContent = 'Bonne réponse !';
    } else {
      resultIcon.innerHTML = '<i class="fas fa-times-circle error"></i>';
      resultText.textContent = 'Mauvaise réponse ou temps écoulé';
    }
    
    resultMessage.textContent = message || 'En attente de la prochaine question...';
    
    // Return to waiting after 3 seconds
    setTimeout(() => {
      displayWaitingScreen('En attente de la prochaine question...');
    }, 3000);
  }
  
  function launchConfetti() {
    if (window.confettiLaunched) return;
    window.confettiLaunched = true;
    const canvas = document.createElement('canvas');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    canvas.style.position = 'fixed';
    canvas.style.top = 0;
    canvas.style.left = 0;
    canvas.style.pointerEvents = 'none';
    canvas.style.zIndex = 9999;
    document.body.appendChild(canvas);
    const ctx = canvas.getContext('2d');
    const confettiColors = ['#39FF14', '#FF1177', '#FFD700', '#4CA2FF', '#fff'];
    const confetti = Array.from({length: 120}, () => ({
      x: Math.random() * canvas.width,
      y: Math.random() * -canvas.height,
      r: 6 + Math.random() * 8,
      d: 2 + Math.random() * 4,
      color: confettiColors[Math.floor(Math.random() * confettiColors.length)],
      tilt: Math.random() * 10 - 10,
      tiltAngle: 0,
      tiltAngleIncremental: (Math.random() * 0.07) + 0.05
    }));
    function drawConfetti() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      confetti.forEach(c => {
        ctx.beginPath();
        ctx.lineWidth = c.r;
        ctx.strokeStyle = c.color;
        ctx.moveTo(c.x + c.tilt + c.r / 3, c.y);
        ctx.lineTo(c.x + c.tilt, c.y + c.tilt + c.r);
        ctx.stroke();
      });
      updateConfetti();
      requestAnimationFrame(drawConfetti);
    }
    function updateConfetti() {
      confetti.forEach(c => {
        c.y += c.d;
        c.tiltAngle += c.tiltAngleIncremental;
        c.tilt = Math.sin(c.tiltAngle) * 15;
        if (c.y > canvas.height) {
          c.x = Math.random() * canvas.width;
          c.y = -10;
        }
      });
    }
    drawConfetti();
    setTimeout(() => { canvas.remove(); }, 5000);
  }
  
  function displayFinishScreen(leaderboard) {
    hideAllScreens();
    finishScreen.style.display = 'block';
    const userScoreData = leaderboard.find(p => p.user_id == userId);
    finalScore.textContent = userScoreData ? userScoreData.score : '0';
    launchConfetti();
    setTimeout(() => { window.location.href = '/'; }, 5000);
  }
  
  function submitAnswer(questionId, optionId) {
    if (hasAnswered) return;
    hasAnswered = true;
    
    // Disable all buttons
    optionsContainer.querySelectorAll('button').forEach(b => {
      b.disabled = true;
    });
    
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
      console.log('Submit response:', data);
      
      if (data.success) {
        // Afficher le résultat avec les informations détaillées
        displayResultScreen(data.isCorrect, data.message);
        
        // Mettre à jour le leaderboard immédiatement
        updateLeaderboard();
      } else {
        displayResultScreen(false, data.message || 'Erreur lors de l\'envoi');
      }
    })
    .catch(error => {
      console.error('Submit error:', error);
      displayResultScreen(false, 'Erreur lors de l\'envoi');
    });
  }
  
  function updateLeaderboard() {
    fetch(`/quiz/get-live-leaderboard?quiz_id=${quizId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success && data.leaderboard) {
          const tbody = document.getElementById('leaderboard-body');
          tbody.innerHTML = '';
          
          if (data.leaderboard.length === 0) {
            tbody.innerHTML = `
              <tr>
                <td colspan="3" style="text-align: center; color: #ccc;">
                  Aucun participant
                </td>
              </tr>
            `;
          } else {
            data.leaderboard.forEach((player, index) => {
              const row = document.createElement('tr');
              if (player.user_id == userId) {
                row.classList.add('current-user');
              }
              
              row.innerHTML = `
                <td><span class="rank-badge">${index + 1}</span></td>
                <td>${player.username}</td>
                <td class="score-value">${player.score}</td>
              `;
              
              tbody.appendChild(row);
            });
          }
          
          participantsCount.textContent = data.leaderboard.length;
          
          // Update user score
          const userData = data.leaderboard.find(p => p.user_id == userId);
          if (userData) {
            userScore.textContent = userData.score;
          }
        }
      })
      .catch(error => {
        console.error('Leaderboard error:', error);
      });
  }
  
  function hideAllScreens() {
    waitingScreen.style.display = 'none';
    questionScreen.style.display = 'none';
    resultScreen.style.display = 'none';
    finishScreen.style.display = 'none';
  }
});
</script>