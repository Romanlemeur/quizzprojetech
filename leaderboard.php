<?php
// profile.php
$pageTitle = "Mon Compte";
require_once 'includes/header.php';

// Simulated user data
$user = [
    'username' => isset($_POST['username']) ? $_POST['username'] : 'Alice',
    'email'    => 'alice@example.com',
];
// Simulated quiz history
$history = [
    ['quiz' => 'Quiz Space', 'score' => 95, 'date' => '2025-05-01'],
    ['quiz' => 'Quiz Food',  'score' => 88, 'date' => '2025-05-03'],
    ['quiz' => 'Quiz Tech',  'score' => 90, 'date' => '2025-05-05'],
];

// Handle name update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $user['username'] = trim($_POST['username']);
    $message = "Nom d'utilisateur mis à jour !";
}
?>

<style>
  .profile-page {
    padding: var(--space-xxl) 0;
    max-width: 800px;
    margin: 0 auto;
  }
  .profile-page h1.section-title,
  .profile-page h2.section-title { margin-bottom: var(--space-xl); }
  .alert.success {
    margin-bottom: var(--space-lg);
    padding: var(--space-md);
    background: rgba(34,34,34,0.8);
    border-radius: var(--radius-sm);
    color: var(--color-success);
    text-align: center;
  }
  .profile-card {
    background: #000;
    padding: var(--space-xxl) var(--space-lg);
    border-radius: var(--radius-md);
    box-shadow: 0 4px 16px rgba(0,0,0,0.6);
    margin-bottom: 70px;
  }
  .profile-form .form-group { margin-bottom: var(--space-lg); }
  .profile-form label {
    display: block;
    margin-bottom: var(--space-xs);
    font-weight: 500;
    color: var(--color-text-secondary);
  }
  .profile-form .form-control {
    width: 100%;
    padding: var(--space-md);
    background: #111;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-sm);
    color: #fff;
  }
  .profile-form .btn-primary {
    margin-top: var(--space-md);
    padding: var(30px) var(30px);
    font-size: var(--font-size-md);
  }
  .leaderboard-card {
    background: #000;
    padding: var(--space-xxl) var(--space-lg);
    border-radius: var(--radius-md);
    box-shadow: 0 4px 16px rgba(0,0,0,0.6);
    margin-bottom: 70px;
    margin-top: 20px;
  }
  .leaderboard-table th,
  .leaderboard-table td { padding: var(--space-md) var(--space-lg); }
  .leaderboard-table th {
    background: #111;
    color: var(--color-text-secondary);
    font-family: var(--font-family-sans);
  }
  .leaderboard-table tbody tr:nth-child(even) { background: rgba(255,255,255,0.05); }
  .leaderboard-table tbody tr:hover { background: rgba(57,255,20,0.1); }
</style>

<div class="container profile-page">
  <h1 class="section-title neon-text">Mon Compte</h1>

  <?php if (!empty($message)): ?>
    <div class="alert success neon-border"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <div class="profile-card card neon-border">
    <form method="POST" class="profile-form">
      <div class="form-group">
        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="form-control">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" readonly>
      </div>
      <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
  </div>

  <h2 class="section-title neon-text">Historique des Quiz</h2>
  <div class="card neon-border leaderboard-card">
    <table class="leaderboard-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Quiz</th>
          <th>Score</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($history as $entry): ?>
          <tr>
            <td><?= htmlspecialchars($entry['date']) ?></td>
            <td><?= htmlspecialchars($entry['quiz']) ?></td>
            <td class="user-score"><?= htmlspecialchars($entry['score']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
