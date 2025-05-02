<div class="container">
    <div class="quiz-container">
        <div class="quiz-header">
            <h1><?= $category['name'] ?> Quizzes</h1>
            <p>Select a quiz to begin</p>
        </div>
        
        <div class="quizzes-grid">
            <?php if (empty($quizzes)): ?>
                <p>No quizzes available in this category yet.</p>
                <a href="<?= base_url('quiz') ?>" class="btn btn-primary">Back to Categories</a>
            <?php else: ?>
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="quiz-card card">
                        <div class="card-body">
                            <h3><?= $quiz['title'] ?></h3>
                            <p><?= $quiz['description'] ?></p>
                            <?php if ($quiz['time_limit'] > 0): ?>
                                <p><i class="fas fa-clock"></i> <?= floor($quiz['time_limit'] / 60) ?> minutes</p>
                            <?php endif; ?>
                            <a href="<?= base_url('quiz/start/' . $quiz['id']) ?>" class="btn btn-primary">Start Quiz</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>