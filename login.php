<?php
// Page de connexion pour le site principal (hors CodeIgniter)
session_start();
require_once 'includes/config.php';

// Redirection si déjà connecté
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

// Traitement de la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        // Connexion à la BDD
        require_once 'includes/db_connect.php';
        
        try {
            // Récupérer l'utilisateur par email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'] ?? 'user';
                $_SESSION['logged_in'] = true;
                
                // Redirection
                if (isset($_POST['redirect']) && !empty($_POST['redirect'])) {
                    header('Location: ' . $_POST['redirect']);
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $error = 'Email ou mot de passe incorrect';
            }
        } catch (PDOException $e) {
            $error = 'Erreur de connexion à la base de données';
        }
    }
}

// Récupérer la redirection éventuelle
$redirect = $_GET['redirect'] ?? '';

// Inclure l'entête
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Connexion</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="login.php" method="post">
                        <?php if (!empty($redirect)): ?>
                            <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
                        <?php endif; ?>
                        
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="password">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <p>Pas encore inscrit? <a href="register.php">Créer un compte</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
