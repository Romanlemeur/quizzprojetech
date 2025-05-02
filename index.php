<?php
$pageTitle = "Home";
require_once 'includes/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Test Your Knowledge</h1>
            <p>Challenge yourself with our interactive quizzes on various topics. Compete with others and see your name on the leaderboard!</p>
            <div class="hero-actions">
                <?php if (isLoggedIn()): ?>
                    <a href="quiz.php" class="btn btn-primary">Start Quiz</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">Login to Start</a>
                    <a href="register.php" class="btn btn-outline">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2 class="section-title">Why Choose Our Platform?</h2>
        <div class="features-grid">
            <div class="feature-card card">
                <div class="feature-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <h3>Challenge Yourself</h3>
                <p>Test your knowledge on various topics with our diverse collection of quizzes.</p>
            </div>
            <div class="feature-card card">
                <div class="feature-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3>Compete with Others</h3>
                <p>See how you stack up against others on our leaderboard and earn bragging rights.</p>
            </div>
            <div class="feature-card card">
                <div class="feature-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Learn New Things</h3>
                <p>Expand your knowledge while having fun with our educational quizzes.</p>
            </div>
            <div class="feature-card card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Play Anywhere</h3>
                <p>Enjoy our quizzes on any device, whether you're at home or on the go.</p>
            </div>
        </div>
    </div>
</section>

<section class="categories">
    <div class="container">
        <h2 class="section-title">Quiz Categories</h2>
        <div class="categories-grid">
            <?php
            $categories = getQuizCategories();
            foreach ($categories as $category) {
                echo '<div class="category-card card">';
                echo '<h3>' . $category['name'] . '</h3>';
                echo '<p>' . $category['description'] . '</p>';
                
                // Count quizzes in this category
                $quizzes = getQuizzesByCategory($category['id']);
                $quiz_count = count($quizzes);
                
                echo '<p class="quiz-count">' . $quiz_count . ' ' . ($quiz_count == 1 ? 'Quiz' : 'Quizzes') . '</p>';
                echo '<a href="quiz.php?category=' . $category['id'] . '" class="btn btn-primary">Start Quiz</a>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</section>

<section class="leaderboard-preview">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Top Performers</h2>
            <a href="leaderboard.php" class="btn btn-outline">View Full Leaderboard</a>
        </div>
        <div class="card">
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Player</th>
                        <th>Quiz</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $leaderboard = getLeaderboard(null, 5);
                    foreach ($leaderboard as $index => $entry) {
                        $rank = $index + 1;
                        $rank_class = "rank-badge";
                        if ($rank <= 3) {
                            $rank_class .= " rank-" . $rank;
                        }
                        
                        echo '<tr>';
                        echo '<td><span class="' . $rank_class . '">' . $rank . '</span></td>';
                        echo '<td>' . $entry['username'] . '</td>';
                        echo '<td>' . $entry['quiz_title'] . '</td>';
                        echo '<td class="user-score">' . $entry['score'] . '</td>';
                        echo '</tr>';
                    }
                    
                    if (empty($leaderboard)) {
                        echo '<tr><td colspan="4" style="text-align: center;">No scores yet. Be the first to take a quiz!</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>