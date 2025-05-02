<div class="container">
    <div class="profile-container">
        <h1>My Profile</h1>
        
        <div class="profile-info card">
            <div class="card-body">
                <h2><?= $user['username'] ?></h2>
                <p><i class="fas fa-envelope"></i> <?= $user['email'] ?></p>
                <p><i class="fas fa-calendar"></i> Member since: <?= format_date($user['created_at']) ?></p>
            </div>
        </div>
        
        <div class="quiz-history">
            <h2>My Quiz History</h2>
            
            <?php if (empty($history)): ?>
                <div class="card">
                    <div class="card-body">
                        <p>You haven't taken any quizzes yet.</p>
                        <a href="<?= base_url('quiz') ?>" class="btn btn-primary">Take a Quiz</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <table class="leaderboard-table">
                        <thead>
                            <tr>
                                <th>Quiz</th>
                                <th>Score</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $entry): ?>
                                <tr>
                                    <td><?= $entry['quiz_title'] ?></td>
                                    <td class="user-score"><?= $entry['score'] ?></td>
                                    <td><?= format_date($entry['completed_at']) ?></td>
                                    <td>
                                        <a href="<?= base_url('quiz/start/' . $entry['quiz_id']) ?>" class="btn btn-outline btn-sm">Try Again</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>