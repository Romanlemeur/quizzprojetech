<?php
$pageTitle = "Quiz Results";
require_once 'includes/header.php';

// Get result data from URL parameters
$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;
$score = isset($_GET['score']) ? intval($_GET['score']) : 0;
$total = isset($_GET['total']) ? intval($_GET['total']) : 0;
$correct = isset($_GET['correct']) ? intval($_GET['correct']) : 0;
$percentage = isset($_GET['percentage']) ? intval($_GET['percentage']) : 0;

// Get quiz details
$quiz = getQuizById($quiz_id);

// Determine performance message based on percentage
$message = '';
if ($percentage >= 90) {
    $message = 'Excellent! You\'re a master of this topic!';
} elseif ($percentage >= 70) {
    $message = 'Great job! You have a solid understanding of this subject!';
} elseif ($percentage >= 50) {
    $message = 'Good effort! You know the basics, but there\'s room for improvement.';
} else {
    $message = 'Keep practicing! This topic needs a bit more study.';
}
?>

<div class="container">
    <div class="quiz-container">
        <div class="quiz-result card">
            <h1>Quiz Results</h1>
            
            <?php if ($quiz): ?>
                <h2><?= $quiz['title'] ?></h2>
            <?php endif; ?>
            
            <div class="result-score"><?= $percentage ?>%</div>
            
            <p>You got <strong><?= $correct ?></strong> out of <strong><?= $total ?></strong> questions correct.</p>
            <p>Your score: <strong><?= $score ?> points</strong></p>
            
            <div class="result-message">
                <p><?= $message ?></p>
            </div>
            
            <div class="result-actions">
                <?php if ($quiz): ?>
                    <a href="quiz.php?quiz_id=<?= $quiz_id ?>" class="btn btn-primary">Try Again</a>
                <?php endif; ?>
                <a href="quiz.php" class="btn btn-outline">Try Another Quiz</a>
                <a href="leaderboard.php" class="btn btn-secondary">View Leaderboard</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>