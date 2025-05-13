<?php

$pageTitle = "Quiz Space";
require_once 'includes/header.php';


$questions = [
    ['text' => 'Quelle est la planète la plus proche du Soleil?', 'options' => ['Mercure', 'Vénus', 'Terre', 'Mars'], 'answer' => 'Mercure'],
    ['text' => 'Combien de lunes la Terre a-t-elle?', 'options' => ['1', '2', '3', '4'], 'answer' => '1'],
    ['text' => 'Quel est le nom du centre de notre galaxie?', 'options' => ['Andromède', 'Sagittaire A*', 'Orion', 'Pôle'], 'answer' => 'Sagittaire A*'],
    ['text' => 'Quelle est la vitesse de la lumière?', 'options' => ['300000 km/s', '150000 km/s', '1000 km/s', '30000 km/s'], 'answer' => '300000 km/s'],
    ['text' => 'Quel est le nom de notre galaxie?', 'options' => ['Voie Lactée', 'Andromède', 'Triangulum', 'Grande Ourse'], 'answer' => 'Voie Lactée'],
    ['text' => 'Quelle planète est connue comme la planète rouge?', 'options' => ['Jupiter', 'Mars', 'Saturne', 'Vénus'], 'answer' => 'Mars'],
    ['text' => 'Combien de planètes dans notre système solaire?', 'options' => ['7', '8', '9', '10'], 'answer' => '8'],
];

?>

<style>
  
  .quiz-detail, .quiz-play, #quiz-recap { color: #fff; padding: 50px 50px;}
  .quiz-stats { list-style: none; display: flex; gap: var(--space-md); padding: 0; margin: 70px 70px; }
  .quiz-stats li { background: rgba(0,0,0,0.6); padding: var(--space-sm) var(--space-md); border-radius: var(--radius-sm); }
  .question-box { font-family: 'Orbitron', sans-serif; font-size: var(--font-size-lg); margin-bottom: var(--space-md); }
  .options-list { list-style: none; padding: 0; display: grid; gap: var(--space-sm); }
  .option-item { background: #111; padding: var(--space-sm) var(--space-md); border: 2px solid var(--color-border); border-radius: var(--radius-sm); cursor: pointer; transition: background 0.2s, border-color 0.2s; }
  .option-item:hover { background: rgba(57,255,20,0.1); border-color: var(--color-primary); }
  .option-item.correct { background: rgba(0,255,255,0.2); border-color: #00ffff; }
  .option-item.wrong { background: rgba(255,17,119,0.2); border-color: #ff1177; }
  .timer { font-family: 'VT323', monospace; font-size: var(--font-size-xxl); margin-top: var(--space-md); }
  #quiz-recap { padding: 50px 50px ; }
  #quiz-recap h2 { margin-bottom: var(--space-lg); }
  #missed-list { list-style: none; padding: 0; margin-bottom: var(--space-lg); }
  #missed-list li { background: rgba(0,0,0,0.6); padding: var(--space-sm) var(--space-md); border-radius: var(--radius-sm); margin-bottom: var(--space-xs); }
</style>


<section id="quiz-detail" class="quiz-detail">
  <div class="container">
    <h1>Quiz Space</h1>
    <p>Testez vos connaissances sur l'univers, les étoiles et la cosmologie.</p>
    <ul class="quiz-stats">
      <li>Questions : <?= count($questions) ?></li>
      <li>Difficulté : Moyenne</li>
    </ul>
    <button class="btn btn-primary" onclick="startQuiz()">Start</button>
  </div>
</section>


<section id="quiz-play" class="quiz-play" style="display:none;">
  <div class="container">
    <div id="question-box" class="question-box"></div>
    <ul id="options-list" class="options-list"></ul>
    <div id="timer" class="timer">5</div>
    <div class="score-counter">Score: <span id="score">0</span></div>
  </div>
</section>


<section id="quiz-recap" style="display:none;">
  <div class="container">
    <h2>Récapitulatif</h2>
    <p>Votre score : <span id="final-score"></span> / <?= count($questions) ?></p>
    <h3>Questions manquées :</h3>
    <ul id="missed-list"></ul>
    <button class="btn btn-primary" onclick="location.reload()">Recommencer</button>
  </div>
</section>

<script>
  const questions = <?= json_encode($questions) ?>;
  let currentIndex = 0, score = 0;
  let missed = [];
  let timerInterval;

  function startQuiz() {
    document.getElementById('quiz-detail').style.display = 'none';
    document.getElementById('quiz-play').style.display = 'block';
    loadQuestion();
  }

  function loadQuestion() {
    if (currentIndex >= questions.length) return showRecap();
    const q = questions[currentIndex];
    document.getElementById('question-box').textContent = q.text;
    const list = document.getElementById('options-list'); list.innerHTML = '';
    q.options.forEach(opt => {
      const li = document.createElement('li');
      li.textContent = opt; li.className = 'option-item';
      li.onclick = () => selectOption(li, opt, q.answer);
      list.appendChild(li);
    });
    document.getElementById('timer').textContent = 5;
    clearInterval(timerInterval);
    let timeLeft = 5;
    timerInterval = setInterval(() => {
      if (--timeLeft <= 0) return selectOption(null, null, q.answer);
      document.getElementById('timer').textContent = timeLeft;
    }, 1000);
  }

  function selectOption(el, selected, correct) {
    clearInterval(timerInterval);
    if (selected === correct) {
      score++;
      if (el) el.classList.add('correct');
    } else {
      if (el) el.classList.add('wrong');
      missed.push({text: questions[currentIndex].text, correct});
    }
    document.getElementById('score').textContent = score;
    document.querySelectorAll('.option-item').forEach(li => li.onclick = null);
    setTimeout(() => { currentIndex++; loadQuestion(); }, 1000);
  }

  function showRecap() {
    document.getElementById('quiz-play').style.display = 'none';
    document.getElementById('quiz-recap').style.display = 'block';
    document.getElementById('final-score').textContent = score;
    const ul = document.getElementById('missed-list'); ul.innerHTML = '';
    missed.forEach(m => {
      const li = document.createElement('li');
      li.textContent = `${m.text} (Bonne réponse: ${m.correct})`;
      ul.appendChild(li);
    });
  }
</script>

<?php require_once 'includes/footer.php'; ?>
