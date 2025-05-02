</main>
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h3>QuizMaster</h3>
                    <p>Test your knowledge with our interactive quizzes</p>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?= base_url() ?>">Home</a></li>
                        <li><a href="<?= base_url('quiz') ?>">Quiz</a></li>
                        <li><a href="<?= base_url('leaderboard') ?>">Leaderboard</a></li>
                        <?php if (!is_logged_in()): ?>
                            <li><a href="<?= base_url('login') ?>">Login</a></li>
                            <li><a href="<?= base_url('register') ?>">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>Contact Us</h4>
                    <p><i class="fas fa-envelope"></i> info@quizmaster.com</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> QuizMaster. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="<?= base_url('js/scripts.js') ?>"></script>
</body>
</html>