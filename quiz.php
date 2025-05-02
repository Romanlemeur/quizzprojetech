<?php
$pageTitle = "Quiz";
require_once 'includes/header.php';

// Check if user is logged in
if (!isLoggedIn()) {
    echo '<script>window.location.href = "login.php?redirect=quiz.php";</script>';
    exit;
}

// Get categories
$categories = getQuizCategories();

// Determine which quiz to show
$quiz = null;
$questions = [];

if (isset($_GET['quiz_id'])) {
    // Load specific quiz
    $quiz_id = $_GET['quiz_id'];
    $quiz = getQuizById($quiz_id);
    
    if ($quiz) {
        $questions = getQuizQuestions($quiz_id);
    }
} elseif (isset($_GET['category'])) {
    // Show quizzes in a category
    $category_id = $_GET['category'];
    $quizzes = getQuizzesByCategory($category_id);
} else {
    // Show all categories
    $show_categories = true;
}

// Handle quiz submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_quiz'])) {
    $quiz_id = $_POST['quiz_id'];
    $answers = [];
    
    // Get answers from form
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $question_id = substr($key, strlen('question_'));
            $answers[$question_id] = $value;
        }
    }
    
    // Calculate score
    $result = calculateQuizScore($quiz_id, $answers);
    
    // Save score
    if (isLoggedIn()) {
        saveQuizScore($_SESSION['user_id'], $quiz_id, $result['score']);
    }
    
    // Redirect to results page
    echo '<script>window.location.href = "quiz_result.php?quiz_id=' . $quiz_id . '&score=' . $result['score'] . '&total=' . $result['total_questions'] . '&correct=' . $result['correct_answers'] . '&percentage=' . $result['percentage'] . '";</script>';
    exit;
}
?>

<div class="container">
    <div class="quiz-container">
        <?php if (isset($show_categories) && $show_categories): ?>
            <div class="quiz-header">
                <h1>Select a Category</h1>
            </div>
            
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                    <div class="category-card card">
                        <h3><?= $category['name'] ?></h3>
                        <p><?= $category['description'] ?></p>
                        <a href="quiz.php?category=<?= $category['id'] ?>" class="btn btn-primary">Select</a>
                    </div>
                <?php endforeach; ?>
            </div>
            
        <?php elseif (isset($_GET['category']) && isset($quizzes)): ?>
            <?php
            $category_id = $_GET['category'];
            $category_name = '';
            
            foreach ($categories as $cat) {
                if ($cat['id'] == $category_id) {
                    $category_name = $cat['name'];
                    break;
                }
            }
            ?>
            
            <div class="quiz-header">
                <h1><?= $category_name ?> Quizzes</h1>
                <p>Select a quiz to begin</p>
            </div>
            
            <div class="quizzes-grid">
                <?php if (empty($quizzes)): ?>
                    <p>No quizzes available in this category yet.</p>
                    <a href="quiz.php" class="btn btn-primary">Back to Categories</a>
                <?php else: ?>
                    <?php foreach ($quizzes as $q): ?>
                        <div class="quiz-card card">
                            <div class="card-body">
                                <h3><?= $q['title'] ?></h3>
                                <p><?= $q['description'] ?></p>
                                <?php if ($q['time_limit'] > 0): ?>
                                    <p><i class="fas fa-clock"></i> <?= floor($q['time_limit'] / 60) ?> minutes</p>
                                <?php endif; ?>
                                <a href="quiz.php?quiz_id=<?= $q['id'] ?>" class="btn btn-primary">Start Quiz</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
        <?php elseif ($quiz && !empty($questions)): ?>
            <div class="quiz-header">
                <h1><?= $quiz['title'] ?></h1>
                <p><?= $quiz['description'] ?></p>
            </div>
            
            <div class="quiz-progress">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%"></div>
                </div>
            </div>
            
            <form id="quiz-form" method="POST" action="quiz.php">
                <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">
                
                <?php 
                $question_number = 1;
                $total_questions = count($questions);
                
                foreach ($questions as $question): 
                    $options = getQuestionOptions($question['id']);
                ?>
                
                <div class="question-container" style="display: <?= $question_number === 1 ? 'block' : 'none' ?>">
                    <div class="question-number">Question <?= $question_number ?> of <?= $total_questions ?></div>
                    <div class="question-text"><?= $question['question_text'] ?></div>
                    
                    <div class="options-list">
                        <?php foreach ($options as $option): ?>
                            <div class="option-item">
                                <input type="radio" id="option_<?= $option['id'] ?>" name="question_<?= $question['id'] ?>" value="<?= $option['id'] ?>" required>
                                <label for="option_<?= $option['id'] ?>"><?= $option['option_text'] ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?php 
                $question_number++;
                endforeach; 
                ?>
                
                <div class="quiz-controls">
                    <button type="button" id="prev-question" class="btn btn-outline" disabled>Previous</button>
                    <button type="button" id="next-question" class="btn btn-primary">Next</button>
                </div>
                
                <input type="hidden" name="submit_quiz" value="1">
            </form>
            
        <?php else: ?>
            <div class="quiz-header">
                <h1>Quiz Not Found</h1>
                <p>The quiz you're looking for doesn't exist or has been removed.</p>
                <a href="quiz.php" class="btn btn-primary">Back to Categories</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>