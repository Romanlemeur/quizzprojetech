<footer class="site-footer">
  <div class="container footer-grid">
    <!-- Colonne 1 : Logo & accroche -->
    <div class="footer-col footer-logo">
      <h3><?= SITE_NAME ?></h3>
      <p>Test your knowledge with our interactive quizzes</p>
    </div>

    <!-- Colonne 2 : Liens -->
    <div class="footer-col">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="quiz.php">Quiz</a></li>
        <li><a href="leaderboard.php">Leaderboard</a></li>
        <?php if (!isLoggedIn()): ?>
          <li><a href="login.php">Login</a></li>
          <li><a href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>

    <!-- Colonne 3 : Support -->
    <div class="footer-col">
      <h4>Support</h4>
      <ul>
        <li><a href="#">Help Center</a></li>
        <li><a href="#">Terms of Service</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Contact Us</a></li>
      </ul>
    </div>
</footer>
