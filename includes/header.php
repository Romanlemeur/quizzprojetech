<?php 
require_once 'includes/config.php';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= SITE_NAME ?> — <?= $pageTitle ?? '' ?></title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header class="simple-header">
<div class="header-left">
    <a href="index.php" class="site-logo">QuizMaster</a>
  </div>
  <button class="mobile-menu-toggle" aria-label="Toggle menu">
    <span></span><span></span><span></span>
  </button>

  <nav class="header-nav">
   <a href="https://invader.bar/">Home</a>
    <a href="quiz.php"       class="<?= $currentPage==='quiz.php'       ? 'active' : '' ?>">Quiz</a>
    <a href="leaderboard.php" class="<?= $currentPage==='leaderboard.php' ? 'active' : '' ?>">Leaderboard</a>
  </nav>

  <div class="header-right">
    <?php if(isLoggedIn()): ?>
      <a href="profile.php" class="btn-login">Mon compte</a>
    <?php else: ?>
      <a href="login.php"    class="btn-login">Connexion</a>
    <?php endif; ?>
  </div>
</header>

  </header>
  <main>
  <script>
  document.querySelector('.mobile-menu-toggle')
    .addEventListener('click', function() {
      document.querySelector('.simple-header').classList.toggle('menu-open');
      this.classList.toggle('open');
    });
</script>
