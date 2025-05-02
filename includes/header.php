<?php 
require_once 'includes/config.php';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> - <?= isset($pageTitle) ? $pageTitle : 'Online Quiz Platform' ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php"><?= SITE_NAME ?></a>
                </div>
                <nav class="main-nav">
                    <ul>
                        <li class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">
                            <a href="index.php">Home</a>
                        </li>
                        <li class="<?= $currentPage == 'quiz.php' ? 'active' : '' ?>">
                            <a href="quiz.php">Quiz</a>
                        </li>
                        <li class="<?= $currentPage == 'leaderboard.php' ? 'active' : '' ?>">
                            <a href="leaderboard.php">Leaderboard</a>
                        </li>
                    </ul>
                </nav>
                <div class="user-actions">
                    <?php if (isLoggedIn()): ?>
                        <div class="user-profile">
                            <span class="username"><?= $_SESSION['username'] ?></span>
                            <a href="logout.php" class="btn btn-outline">Logout</a>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Login</a>
                        <a href="register.php" class="btn btn-outline">Register</a>
                    <?php endif; ?>
                </div>
                <button class="mobile-menu-toggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>
    <div class="mobile-menu">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="quiz.php">Quiz</a></li>
            <li><a href="leaderboard.php">Leaderboard</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <main>