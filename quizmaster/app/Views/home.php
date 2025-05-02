<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Test Your Knowledge</h1>
            <p>Challenge yourself with our interactive quizzes on various topics. Compete with others and see your name on the leaderboard!</p>
            <div class="hero-actions">
                <?php if (is_logged_in()): ?>
                    <a href="<?= base_url('quiz') ?>" class="btn btn-primary">Start Quiz</a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="btn btn-primary">Login to Start</a>
                    <a href="<?= base_url('register') ?>" class="btn btn-outline">Register</a>
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
            <?php if (empty($categories)): ?>
                <p>No categories available yet.</p>
            <?php else: ?>
                <?php foreach ($categories as $category): ?>
                    <div class="category-card card">
                        <h3><?= $category['name'] ?></h3>
                        <p><?= $category['description'] ?></p>
                        <?php 
                        $categoryModel = new \App\Models\CategoryModel();
                        $quizCount = $categoryModel->getQuizCount($category['id']); 
                        ?>
                        <p class="quiz-count"><?= $quizCount ?> <?= ($quizCount == 1) ? 'Quiz' : 'Quizzes' ?></p>
                        <a href="<?= base_url('quiz/category/' . $category['id']) ?>" class="btn btn-primary">Start Quiz</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="leaderboard-preview">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Top Performers</h2>
            <a href="<?= base_url('leaderboard') ?>" class="btn btn-outline">View Full Leaderboard</a>
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
                    <?php if (empty($leaderboard)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No scores yet. Be the first to take a quiz!</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($leaderboard as $index => $entry): ?>
                            <?php 
                            $rank = $index + 1;
                            $rankClass = "rank-badge";
                            if ($rank <= 3) {
                                $rankClass .= " rank-" . $rank;
                            }
                            ?>
                            <tr>
                                <td><span class="<?= $rankClass ?>"><?= $rank ?></span></td>
                                <td><?= $entry['username'] ?></td>
                                <td><?= $entry['quiz_title'] ?></td>
                                <td class="user-score"><?= $entry['score'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>