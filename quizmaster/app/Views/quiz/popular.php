<div class="retro-bg-layer"></div>

<section class="quizz-week hero-week">
  
  <div class="hero-overlay"></div>
  <div class="container hero-content">
    <h2 class="section-title">Quiz de la Semaine</h2>
    <div class="week-calendar">
      
      <div class="day-header">Lundi</div>
      <div class="day-header">Mardi</div>
      <div class="day-header">Mercredi</div>
      <div class="day-header">Jeudi</div>
      <div class="day-header">Vendredi</div>
      <div class="day-header">Samedi</div>
      <div class="day-header">Dimanche</div>

      <?php foreach ($weekSchedule as $day => $quizzes): ?>
        <div class="day-cell">
          <?php if (!empty($quizzes)): ?>
            <?php foreach ($quizzes as $quiz): ?>
              <h4><?= $quiz['title'] ?></h4>
              <p><?= $quiz['time'] ?></p>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="quizz-popular transparent-bg">
  <div class="container">
    <h2 class="section-title">Quizz Populaires</h2>
    <div class="quiz-grids">
      <?php foreach ($popularQuizzes as $quiz): ?>
        <div class="quiz-cards card">
          <div class="card-body">
            <h3><?= $quiz['title'] ?></h3>
            <p><?= $quiz['description'] ?></p>
            <a href="<?= base_url('quiz/start/' . $quiz['id']) ?>" class="btn btn-primary">Jouer</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="quizz-original transparent-bg">
  <div class="container">
    <h2 class="section-title">Quizz Originaux</h2>
    <div class="quiz-grids">
      <?php foreach ($originalQuizzes as $quiz): ?>
        <div class="quiz-cards card">
          <div class="card-body">
            <h3><?= $quiz['title'] ?></h3>
            <p><?= $quiz['description'] ?></p>
            <a href="<?= base_url('quiz/start/' . $quiz['id']) ?>" class="btn btn-primary">Jouer</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section> 