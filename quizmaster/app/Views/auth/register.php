<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Inscription</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif; ?>
                    
                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success"><?= session('success') ?></div>
                        <p class="text-center">
                            <a href="<?= site_url('login') ?>" class="btn btn-primary">Se connecter</a>
                        </p>
                    <?php else: ?>
                        <form action="<?= site_url('register') ?>" method="post">
                            <div class="form-group mb-3">
                                <label for="username">Nom d'utilisateur</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>" required>
                                <?php if (isset($validation) && $validation->hasError('username')): ?>
                                    <small class="text-danger"><?= $validation->getError('username') ?></small>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                                <?php if (isset($validation) && $validation->hasError('email')): ?>
                                    <small class="text-danger"><?= $validation->getError('email') ?></small>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="password">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="form-text text-muted">Au moins 6 caractères</small>
                                <?php if (isset($validation) && $validation->hasError('password')): ?>
                                    <small class="text-danger"><?= $validation->getError('password') ?></small>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="confirm_password">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                <?php if (isset($validation) && $validation->hasError('confirm_password')): ?>
                                    <small class="text-danger"><?= $validation->getError('confirm_password') ?></small>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">S'inscrire</button>
                            </div>
                        </form>
                    <?php endif; ?>
                    
                    <div class="mt-3 text-center">
                        <p>Déjà inscrit? <a href="<?= site_url('login') ?>">Se connecter</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>