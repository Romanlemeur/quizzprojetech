<div class="container">
    <div class="leaderboard-container">
        <div class="leaderboard-header">
            <h1>Leaderboard</h1>
            <p>See how you compare to other quiz takers</p>
        </div>
        
        <div class="leaderboard-filters">
            <div class="filter-group">
                <label for="quiz-filter">Filter by Quiz:</label>
                <select id="quiz-filter" class="form-control" onchange="window.location.href=this.value">
                    <option value="<?= base_url('leaderboard') ?>">All Quizzes</option>
                    <?php foreach ($quizzes as $quiz): ?>
                        <option value="<?= base_url('leaderboard/quiz/' . $quiz['id']) ?>" <?= ($filter_quiz == $quiz['id']) ? 'selected' : '' ?>>
                            <?= $quiz['title'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <?php if ($filter_quiz): ?>
                <a href="<?= base_url('leaderboard') ?>" class="btn btn-outline">Clear Filter</a>
            <?php endif; ?>
        </div>
        
        <?php if (isset($filter_quiz) && $filter_quiz): ?>
            <!-- Affichage séparé pour un quiz spécifique -->
            <div class="leaderboard-sections">
                <!-- Scores classiques -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3>Scores classiques</h3>
                    </div>
                    <table class="leaderboard-table">
                        <thead>
                            <tr>
                                <th>Rang</th>
                                <th>Joueur</th>
                                <th>Score</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($classicLeaderboard)): ?>
                                <tr>
                                    <td colspan="4" style="text-align: center;">Aucun score classique disponible.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($classicLeaderboard as $index => $entry): ?>
                                    <?php
                                    $rank = $index + 1;
                                    $rankClass = "rank-badge";
                                    if ($rank <= 3) {
                                        $rankClass .= " rank-" . $rank;
                                    }
                                    ?>
                                    <tr>
                                        <td><span class="<?= $rankClass ?>"><?= $rank ?></span></td>
                                        <td><?= htmlspecialchars($entry['username']) ?></td>
                                        <td class="user-score"><?= htmlspecialchars($entry['score']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($entry['completed_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Scores live -->
                <div class="card">
                    <div class="card-header">
                        <h3>Scores live</h3>
                    </div>
                    <table class="leaderboard-table">
                        <thead>
                            <tr>
                                <th>Rang</th>
                                <th>Joueur</th>
                                <th>Score</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($liveLeaderboard)): ?>
                                <tr>
                                    <td colspan="4" style="text-align: center;">Aucun score live disponible.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($liveLeaderboard as $index => $entry): ?>
                                    <?php
                                    $rank = $index + 1;
                                    $rankClass = "rank-badge";
                                    if ($rank <= 3) {
                                        $rankClass .= " rank-" . $rank;
                                    }
                                    ?>
                                    <tr>
                                        <td><span class="<?= $rankClass ?>"><?= $rank ?></span></td>
                                        <td><?= htmlspecialchars($entry['username']) ?></td>
                                        <td class="user-score"><?= htmlspecialchars($entry['score']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($entry['completed_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <!-- Affichage global -->
            <div class="card">
                <table class="leaderboard-table">
                    <thead>
                        <tr>
                            <th>Rang</th>
                            <th>Joueur</th>
                            <th>Quiz</th>
                            <th>Type</th>
                            <th>Score</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($leaderboard)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">Aucun score disponible. Soyez le premier à participer à un quiz !</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($leaderboard as $index => $entry): ?>
                                <?php
                                $rank = $index + 1;
                                $rankClass = "rank-badge";
                                if ($rank <= 3) {
                                    $rankClass .= " rank-" . $rank;
                                }
                                $typeBadge = $entry['is_live'] ? '<span class="badge bg-warning">Live</span>' : '<span class="badge bg-secondary">Classique</span>';
                                ?>
                                <tr>
                                    <td><span class="<?= $rankClass ?>"><?= $rank ?></span></td>
                                    <td><?= htmlspecialchars($entry['username']) ?></td>
                                    <td><?= htmlspecialchars($entry['quiz_title']) ?></td>
                                    <td><?= $typeBadge ?></td>
                                    <td class="user-score"><?= htmlspecialchars($entry['score']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($entry['completed_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>