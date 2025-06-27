<div class="container mt-4">
    <div class="admin-stats-card">
        <ul class="admin-stats-list">
            <li>Total des quiz : <span><?= esc($totalQuizzes) ?></span></li>
            <li>Total des utilisateurs : <span><?= esc($totalUsers) ?></span></li>
            <li>Total des participations : <span><?= esc($totalParticipations) ?></span></li>
        </ul>
    </div>

    <div class="admin-stats-card">
        <h2>Quiz les plus populaires</h2>
        <ul class="admin-popular-list">
            <?php if (!empty($popularQuizzes)): ?>
                <?php foreach ($popularQuizzes as $quiz): ?>
                    <li>
                        <?= esc($quiz['title'] ?? 'Quiz inconnu') ?>
                        <span><?= isset($quiz['participations']) ? esc($quiz['participations']) : '0' ?> participations</span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Aucun quiz populaire.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="admin-stats-card">
        <h2>Meilleurs scores</h2>
        <ul class="admin-topscorers-list">
            <?php if (!empty($topScorers)): ?>
                <?php foreach ($topScorers as $scorer): ?>
                    <li>
                        <?= esc($scorer['username'] ?? 'Utilisateur inconnu') ?> :
                        <span><?= isset($scorer['score']) ? esc($scorer['score']) : '0' ?></span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Aucun score enregistré.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>