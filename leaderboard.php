<?php
$pageTitle = "Leaderboard";
require_once 'includes/header.php';

// Get filter parameter
$filter_quiz = isset($_GET['quiz']) ? intval($_GET['quiz']) : null;

// Get all quizzes for filter dropdown
$quizzes = [];
$result = $conn->query("SELECT id, title FROM quizzes ORDER BY title ASC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $quizzes[] = $row;
    }
}

// Get leaderboard data
$leaderboard = getLeaderboard($filter_quiz, 100);
?>

<div class="container">
    <div class="leaderboard-container">
        <div class="leaderboard-header">
            <h1>Leaderboard</h1>
            <p>See how you compare to other quiz takers</p>
        </div>
        
        <div class="leaderboard-filters">
            <div class="filter-group">
                <label for="quiz-filter">Filter by Quiz:</label>
                <select id="quiz-filter" class="form-control" onchange="window.location.href='leaderboard.php?quiz='+this.value">
                    <option value="">All Quizzes</option>
                    <?php foreach ($quizzes as $quiz): ?>
                        <option value="<?= $quiz['id'] ?>" <?= ($filter_quiz == $quiz['id']) ? 'selected' : '' ?>>
                            <?= $quiz['title'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <?php if ($filter_quiz): ?>
                <a href="leaderboard.php" class="btn btn-outline">Clear Filter</a>
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
                            $rank_class = "rank-badge";
                            if ($rank <= 3) {
                                $rank_class .= " rank-" . $rank;
                            }
                            ?>
                            <tr>
                                <td><span class="<?= $rank_class ?>"><?= $rank ?></span></td>
                                <td><?= $entry['username'] ?></td>
                                <