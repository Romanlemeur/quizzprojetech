<section class="quizz-du-moment">
    <div class="container auth-container">
        <div class="auth-card">
            <h2 class="section-title" style="margin-bottom: 32px;">Connexion</h2>
            <!-- Affichage des erreurs -->
            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger">
                    <?= session('error') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->has('message')): ?>
                <div class="alert alert-success">
                    <?= session('message') ?>
                </div>
            <?php endif; ?>
            <!-- Formulaire de connexion -->
            <form action="<?= site_url('login') ?>" method="post">
                <?php if (!empty($redirect)): ?>
                    <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                    <?php if (isset($validation) && $validation->hasError('email')): ?>
                        <small class="form-error"><?= $validation->getError('email') ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <?php if (isset($validation) && $validation->hasError('password')): ?>
                        <small class="form-error"><?= $validation->getError('password') ?></small>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>
            <div class="mt-3 text-center">
                <p>Pas encore inscrit? <a href="<?= site_url('register') ?>">Créer un compte</a></p>
            </div>
        </div>
    </div>
</section>