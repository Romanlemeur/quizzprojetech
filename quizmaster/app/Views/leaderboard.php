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
                        <option value="<?= base_url('leaderboard/filter/' . $quiz['id']) ?>" <?= ($filter_quiz == $quiz['id']) ? 'selected' : '' ?>>
                            <?= $quiz['title'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <?php if ($filter_quiz): ?>
                <a href="<?= base_url('leaderboard') ?>" class="btn btn-outline">Clear Filter</a>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Player</th>
                        <th>Quiz</th>
                        <th>Score</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($leaderboard)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No scores available yet. Be the first to take a quiz!</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($leaderboard as $index => $entry): ?>
                            <?php
                            $rank = $index + 1;
                            $rankClass = "rank-badge";
                            if ($rank <= 3) {
                                $rankClass .= " rank-" . $rank;
                            }
                            ?>
                            <tr>
                                <td><span class="<?= $rankClass ?>"><?= $rank ?></span></td>
                                <td><?= $entry['username'] ?></td>
                                <td><?= $entry['quiz_title'] ?></td>
                                <td class="user-score"><?= $entry['score'] ?></td>
                                <td><?= format_date($entry['completed_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>