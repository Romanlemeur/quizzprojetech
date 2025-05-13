<?php

$pageTitle = "Connexion";
require_once 'includes/header.php';
?>

<div class="login-bg"></div>
<div class="auth-container">
  <div class="auth-card">
    <h2 class="auth-header neon-text">Se connecter</h2>
    <form method="POST" action="login.php">
      <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input class="form-control" type="email" id="email" name="email" placeholder="votre.email@exemple.com" required>
      </div>
      <div class="form-group">
        <label class="form-label" for="password">Mot de passe</label>
        <input class="form-control" type="password" id="password" name="password" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
    <div class="auth-footer">
      Pas encore de compte ? <a href="register.php">Inscrivez-vous</a>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<style>
  
  .login-bg {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(45deg,rgb(106, 13, 111),rgb(211, 99, 242),rgb(126, 86, 247),rgb(141, 2, 125));
    background-size: 400% 400%;
    animation: neonGradient 15s ease infinite;
    z-index: -1;
  }
  @keyframes neonGradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }


.auth-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
  padding: 30px;
}
.auth-card {
  background: rgba(0,0,0,0.8);
  padding: 30px 30px;
  border-radius: 15px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.6);
  width: 100%;
  max-width: 400px;
}
.auth-header {
  text-align: center;
  margin-bottom: var(--space-lg);
  font-family: 'Orbitron', sans-serif;
}
.form-group {
  margin-bottom: var(--space-md);
}
.form-label {
  display: block;
  margin-bottom: var(--space-xs);
  color: var(--color-text-secondary);
}
.form-control {
  width: 100%;
  padding: var(--space-sm) var(--space-md);
  background: #111;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  color: #fff;
}
.btn-primary {
  width: 100%;
  padding: var(--space-md);
  font-size: var(--font-size-md);
  text-transform: uppercase;
  margin-top: var(--space-md);
}
.auth-footer {
  text-align: center;
  margin-top: var(--space-md);
  color: var(--color-text-secondary);
}
.auth-footer a {
  color: var(--color-primary);
  text-decoration: none;
}
.auth-footer a:hover {
  text-decoration: underline;
}
</style>
