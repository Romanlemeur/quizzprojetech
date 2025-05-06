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

<!-- Catégories Populaires (Exemples statiques) -->
<section class="categories-populaires">
    <div class="container">
        <h2 class="section-title">Catégories Populaires</h2>
        <div class="categories-grid">
            <div class="category-card card">
                <h3>Science</h3>
                <p>Quiz sur la physique, la chimie et la biologie.</p>
                <a href="#" class="btn btn-primary">Voir Quiz</a>
            </div>
            <div class="category-card card">
                <h3>Histoire</h3>
                <p>Explorez les grandes dates et personnages historiques.</p>
                <a href="#" class="btn btn-primary">Voir Quiz</a>
            </div>
            <div class="category-card card">
                <h3>Cinéma</h3>
                <p>Questions sur les films cultes et les réalisateurs.</p>
                <a href="#" class="btn btn-primary">Voir Quiz</a>
            </div>
        </div>
    </div>
</section>

<!-- Quizz du Moment (Exemples statiques) -->
<section class="quizz-du-moment">
    <div class="container">
        <h2 class="section-title">Quizz du Moment</h2>
        <div class="quiz-grid">
            <div class="quiz-card card">
                <h3>Quiz Space</h3>
                <p>Testez vos connaissances sur l'univers et les étoiles.</p>
                <a href="#" class="btn btn-primary">Jouer</a>
            </div>
            <div class="quiz-card card">
                <h3>Quiz Food</h3>
                <p>Tout savoir sur la gastronomie mondiale.</p>
                <a href="#" class="btn btn-primary">Jouer</a>
            </div>
            <div class="quiz-card card">
                <h3>Quiz Tech</h3>
                <p>Les dernières innovations et gadgets.</p>
                <a href="#" class="btn btn-primary">Jouer</a>
            </div>
        </div>
    </div>
</section>

<!-- Meilleurs Joueurs (Top 5 statique) -->
<section class="leaderboard-preview">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Meilleurs Joueurs</h2>
            <a href="leaderboard.php" class="btn btn-outline">Voir le classement complet</a>
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
                    <tr>
                        <td><span class="rank-badge rank-1">1</span></td>
                        <td>Alice</td>
                        <td>Quiz Space</td>
                        <td class="user-score">95</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge rank-2">2</span></td>
                        <td>Bob</td>
                        <td>Quiz Tech</td>
                        <td class="user-score">90</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge rank-3">3</span></td>
                        <td>Charlie</td>
                        <td>Quiz Food</td>
                        <td class="user-score">88</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge">4</span></td>
                        <td>Diana</td>
                        <td>Quiz Histoire</td>
                        <td class="user-score">85</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge">5</span></td>
                        <td>Ethan</td>
                        <td>Quiz Science</td>
                        <td class="user-score">82</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>