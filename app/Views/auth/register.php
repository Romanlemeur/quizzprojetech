<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    Inscription
                </h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="/auth/register">
                    <div class="mb-3">
                        <label for="pseudo" class="form-label">
                            <i class="fas fa-user me-1"></i>Pseudo
                        </label>
                        <input type="text" class="form-control" id="pseudo" name="pseudo" 
                               value="<?= old('pseudo') ?>" required minlength="3" maxlength="50">
                        <div class="form-text">Le pseudo doit contenir entre 3 et 50 caractères.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= old('email') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i>Mot de passe
                        </label>
                        <input type="password" class="form-control" id="password" name="password" 
                               required minlength="6">
                        <div class="form-text">Le mot de passe doit contenir au moins 6 caractères.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">
                            <i class="fas fa-lock me-1"></i>Confirmer le mot de passe
                        </label>
                        <input type="password" class="form-control" id="confirm_password" 
                               name="confirm_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                J'accepte les <a href="#" class="text-decoration-none">conditions d'utilisation</a>
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-user-plus me-2"></i>Créer mon compte
                        </button>
                    </div>
                </form>
                
                <hr class="my-4">
                
                <div class="text-center">
                    <p class="mb-0">Déjà un compte ?</p>
                    <a href="/auth/login" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-sign-in-alt me-1"></i>Se connecter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validation côté client pour la confirmation du mot de passe
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Les mots de passe ne correspondent pas');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('password').addEventListener('input', function() {
    const confirmPassword = document.getElementById('confirm_password');
    if (confirmPassword.value) {
        confirmPassword.dispatchEvent(new Event('input'));
    }
});
</script>
<?= $this->endSection() ?> 