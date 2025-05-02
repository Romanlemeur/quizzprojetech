<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizMaster - <?= $title ?? 'Online Quiz Platform' ?></title>
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/animations.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="<?= base_url() ?>">QuizMaster</a>
                </div>
                <nav class="main-nav">
                    <ul>
                        <li class="<?= current_url() == base_url() ? 'active' : '' ?>">
                            <a href="<?= base_url() ?>">Home</a>
                        </li>
                        <li class="<?= strpos(current_url(), 'quiz') !== false ? 'active' : '' ?>">
                            <a href="<?= base_url('quiz') ?>">Quiz</a>
                        </li>
                        <li class="<?= strpos(current_url(), 'leaderboard') !== false ? 'active' : '' ?>">
                            <a href="<?= base_url('leaderboard') ?>">Leaderboard</a>
                        </li>
                    </ul>
                </nav>
                <div class="user-actions">
                    <?php if (is_logged_in()): ?>
                        <div class="user-profile">
                            <span class="username"><?= session()->get('username') ?></span>
                            <a href="<?= base_url('logout') ?>" class="btn btn-outline">Logout</a>
                        </div>
                    <?php else: ?>
                        <a href="<?= base_url('login') ?>" class="btn btn-primary">Login</a>
                        <a href="<?= base_url('register') ?>" class="btn btn-outline">Register</a>
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
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li><a href="<?= base_url('quiz') ?>">Quiz</a></li>
            <li><a href="<?= base_url('leaderboard') ?>">Leaderboard</a></li>
            <?php if (is_logged_in()): ?>
                <li><a href="<?= base_url('profile') ?>">Profile</a></li>
                <li><a href="<?= base_url('logout') ?>">Logout</a></li>
            <?php else: ?>
                <li><a href="<?= base_url('login') ?>">Login</a></li>
                <li><a href="<?= base_url('register') ?>">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <main>