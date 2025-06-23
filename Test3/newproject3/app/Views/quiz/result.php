<div class="container">
    <div class="quiz-container">
        <div class="quiz-result card">
            <h1>Quiz Results</h1>
            
            <h2><?= $quiz['title'] ?></h2>
            
            <div class="result-score"><?= $percentage ?>%</div>
            
            <p>You got <strong><?= $correct ?></strong> out of <strong><?= $total ?></strong> questions correct.</p>
            <p>Your score: <strong><?= $score ?> points</strong></p>
            
            <div class="result-message">
                <p><?= $message ?></p>
            </div>
            
            <div class="result-actions">
                <a href="<?= base_url('quiz/start/' . $quiz['id']) ?>" class="btn btn-primary">Try Again</a>
                <a href="<?= base_url('quiz') ?>" class="btn btn-outline">Try Another Quiz</a>
                <a href="<?= base_url('leaderboard') ?>" class="btn btn-secondary">View Leaderboard</a>
            </div>
        </div>
    </div>
</div>