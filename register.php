<?php
// Page d'inscription pour le site principal (hors CodeIgniter)
session_start();

// Redirection si déjà connecté
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

// Traitement de l'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation basique
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Tous les champs sont obligatoires';
    } elseif ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas';
    } elseif (strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères';
    } else {
        // Connexion à la BDD
        require_once 'includes/db_connect.php';
        
        try {
            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $error = 'Cet email est déjà utilisé';
            } else {
                // Vérifier si le nom d'utilisateur existe déjà
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
                $stmt->execute([$username]);
                if ($stmt->fetchColumn() > 0) {
                    $error = 'Ce nom d\'utilisateur est déjà pris';
                } else {
                    // Inscription de l'utilisateur
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, 'user', NOW())");
                    $stmt->execute([$username, $email, $hashed_password]);
                    
                    $success = 'Inscription réussie! Vous pouvez maintenant vous connecter.';
                }
            }
        } catch (PDOException $e) {
            $error = 'Erreur lors de l\'inscription';
            // Pour debug
            // echo $e->getMessage();
        }
    }
}

// Inclure l'entête
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Inscription</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                        <p class="text-center">
                            <a href="login.php" class="btn btn-primary">Se connecter</a>
                        </p>
                    <?php else: ?>
                        <form action="register.php" method="post">
                            <div class="form-group mb-3">
                                <label for="username">Nom d'utilisateur</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="password">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="form-text text-muted">Au moins 6 caractères</small>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="confirm_password">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">S'inscrire</button>
                            </div>
                        </form>
                    <?php endif; ?>
                    
                    <div class="mt-3 text-center">
                        <p>Déjà inscrit? <a href="login.php">Se connecter</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
