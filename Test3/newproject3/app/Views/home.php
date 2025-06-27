<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Bienvenue sur InvaderBar&nbsp;!</h1>
            <p>Défiez-vous avec nos quiz interactifs sur divers sujets. Affrontez d'autres joueurs et voyez votre nom dans le classement !</p>
            <div class="hero-actions">
                <?php if ($isLoggedIn): ?>
                    <a href="<?= base_url('quiz') ?>" class="btn btn-primary">Start Quiz</a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="btn btn-primary">Login to Start</a>
                    <a href="<?= base_url('register') ?>" class="btn btn-outline">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Alerte pour les quiz en direct -->
<?php if (isset($liveQuiz) && $liveQuiz): ?>
<div class="container">
    <div class="live-quiz-alert">
        <div>
            <h4>Quiz en direct!</h4>
            <p>Le quiz "<?= esc($liveQuiz['title']) ?>" est actuellement en direct. Rejoignez-le maintenant!</p>
        </div>
        <a href="<?= site_url('quiz/live') ?>" class="btn-join">Rejoindre</a>
    </div>
</div>
<?php endif; ?>

<section class="categories-populaires">
    <div class="container">
        <h2 class="section-title">Catégories Populaires</h2>
        <div class="categories-grid">
            <?php foreach ($categories as $category): ?>
                <div class="category-card card">
                    <h3><?= $category['name'] ?></h3>
                    <p><?= $category['description'] ?></p>
                    <a href="<?= base_url('quiz/category/' . $category['id']) ?>" class="btn btn-primary">Voir Quiz</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="quizz-du-moment">
    <div class="container">
        <h2 class="section-title">Quizz du Moment</h2>
        <div class="quiz-grid">
            <?php foreach ($currentQuizzes as $quiz): ?>
                <div class="quiz-card card">
                    <h3><?= $quiz['title'] ?></h3>
                    <p><?= $quiz['description'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Comment ça marche -->
<section class="how-it-works">
    <div class="container">
        <h2 class="section-title">Comment ça marche?</h2>
        <div class="steps-grid">
            <div class="step-card card">
                <div class="card-body">
                    <i class="fas fa-user-plus fa-3x mb-3 text-primary"></i>
                    <h3>1. Créez un compte</h3>
                    <p>Inscrivez-vous pour accéder à tous les quiz et suivre vos progrès.</p>
                </div>
            </div>
            <div class="step-card card">
                <div class="card-body">
                    <i class="fas fa-search fa-3x mb-3 text-primary"></i>
                    <h3>2. Rejoignez un quiz</h3>
                    <p>Amusez vous avec les autres utilisateurs et défiez l'admin!</p>
                </div>
            </div>
            <div class="step-card card">
                <div class="card-body">
                    <i class="fas fa-trophy fa-3x mb-3 text-primary"></i>
                    <h3>3. Gagnez des points</h3>
                    <p>Répondez correctement aux questions et montez dans le classement!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="leaderboard-preview">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Meilleurs Joueurs</h2>
            <a href="<?= base_url('leaderboard') ?>" class="btn btn-outline">Voir le classement complet</a>
        </div>
        <div class="card">
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Rang</th>
                        <th>Joueur</th>
                        <th>Quiz</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($topPlayers)): ?>
                        <tr>
                            <td colspan="4" class="text-center">Aucun score disponible.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($topPlayers as $index => $player): ?>
                            <tr>
                                <td><span class="rank-badge rank-<?= $index < 3 ? ($index + 1) : '' ?>"><?= $index + 1 ?></span></td>
                                <td><?= $player['username'] ?></td>
                                <td><?= $player['quiz_title'] ?></td>
                                <td class="user-score"><?= $player['score'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>