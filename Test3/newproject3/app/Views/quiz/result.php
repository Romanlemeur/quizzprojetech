<div class="container">
    <div class="quiz-container">
        <div class="quiz-result card" style="position:relative;overflow:hidden;">
            <h1>Quiz Results</h1>
            
            <h2><?= $quiz['title'] ?></h2>
            
            <div class="result-score" style="animation:winnerPulse 1.5s infinite alternate; color: gold;">
                <i class="fas fa-trophy"></i> <?= $percentage ?>%
            </div>
            
            <p>You got <strong><?= $correct ?></strong> out of <strong><?= $total ?></strong> questions correct.</p>
            <p>Your score: <strong><?= $score ?> points</strong></p>
            
            <div class="result-message">
                <p><?= $message ?></p>
                <p style="color:var(--color-primary);font-size:1.2rem;">Bravo, vous avez terminé le quiz !</p>
            </div>
            
            <div class="result-actions">
                <a href="<?= base_url('quiz/start/' . $quiz['id']) ?>" class="btn btn-primary">Try Again</a>
                <a href="<?= base_url('quiz') ?>" class="btn btn-outline">Try Another Quiz</a>
                <a href="<?= base_url('leaderboard') ?>" class="btn btn-secondary">View Leaderboard</a>
            </div>
            <div id="confetti-canvas" style="position:fixed;top:0;left:0;width:100vw;height:100vh;pointer-events:none;z-index:9999;"></div>
        </div>
    </div>
</div>

<style>
@keyframes winnerPulse {
  0% { transform: scale(1) rotate(-5deg); filter: drop-shadow(0 0 8px gold); }
  100% { transform: scale(1.15) rotate(5deg); filter: drop-shadow(0 0 24px gold); }
}
</style>

<script>
(function(){
  // Confetti animation
  function launchConfetti() {
    if (window.confettiLaunched) return;
    window.confettiLaunched = true;
    const canvas = document.getElementById('confetti-canvas');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
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
  launchConfetti();
  setTimeout(function(){ window.location.href = '/'; }, 5000);
})();
</script>