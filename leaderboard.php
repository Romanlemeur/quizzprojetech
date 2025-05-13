<?php

$pageTitle = "Mon Compte";
require_once 'includes/header.php';


$user = [
    'username' => isset($_POST['username']) ? $_POST['username'] : 'Alice',
    'email'    => 'alice@example.com',
];

$history = [
    ['quiz' => 'Quiz Space', 'score' => 95, 'date' => '2025-05-01'],
    ['quiz' => 'Quiz Food',  'score' => 88, 'date' => '2025-05-03'],
    ['quiz' => 'Quiz Tech',  'score' => 90, 'date' => '2025-05-05'],
];


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
  .leaderboard-preview .card {
  width: 100%;
  overflow-x: auto;        
  padding: var(--space-lg);
  background: rgba(0,0,0,0.6);
  border: 2px solid var(--color-accent);
  border-radius: var(--radius-sm);
  box-shadow: 0 0 12px var(--color-accent);
  margin-top: var(--space-md);
}


.leaderboard-table {
  width: 100% !important;
  border-collapse: separate;
  border-spacing: 0 8px;  
}
  
  
  .leaderboard-table thead th {
    font-family: var(--font-family-sans);
    color: var(--color-primary);
    text-shadow: 0 0 4px var(--color-primary);
    padding-bottom: var(--space-sm);
    border-bottom: 1px solid var(--color-border);
    text-align: center;
  }
  
  
  .leaderboard-table tbody tr:nth-child(odd) {
    background: rgba(255,255,255,0.05);
  }
  .leaderboard-table tbody tr:hover {
    background: rgba(57,255,20,0.1);
  }
  
  
  .leaderboard-table th{
    padding: 10px;
    padding-right: 20px;
    color: var(--color-text-primary);
    font-family: var(--font-family-mono);
    font-size: var(--font-size-md);
  }

  .leaderboard-table td {
    padding: 10px;
    padding-left: 120px;
    color: var(--color-text-primary);
    font-family: var(--font-family-mono);
    font-size: var(--font-size-md);
  }
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

<section class="leaderboard-preview">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Meilleurs Joueurs</h2>
        </div>
        <div class="card">
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Rang</th>
                        <th>Joueur</th>
                        <th>Quiz</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="rank-badge rank-1">1</span></td>
                        <td>Alice</td>
                        <td>Quiz Space</td>
                        <td class="user-score">95</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge rank-2">2</span></td>
                        <td>Bob</td>
                        <td>Quiz Tech</td>
                        <td class="user-score">90</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge rank-3">3</span></td>
                        <td>Charlie</td>
                        <td>Quiz Food</td>
                        <td class="user-score">88</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge">4</span></td>
                        <td>Diana</td>
                        <td>Quiz Histoire</td>
                        <td class="user-score">85</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge">5</span></td>
                        <td>Ethan</td>
                        <td>Quiz Science</td>
                        <td class="user-score">82</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge">6</span></td>
                        <td>Ethan</td>
                        <td>Quiz Science</td>
                        <td class="user-score">82</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge">7</span></td>
                        <td>Ethan</td>
                        <td>Quiz Science</td>
                        <td class="user-score">82</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge">8</span></td>
                        <td>Ethan</td>
                        <td>Quiz Science</td>
                        <td class="user-score">82</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge">9</span></td>
                        <td>Ethan</td>
                        <td>Quiz Science</td>
                        <td class="user-score">82</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge">10</span></td>
                        <td>Ethan</td>
                        <td>Quiz Science</td>
                        <td class="user-score">82</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
