<div class="container">
    <div class="quiz-container">
        <div class="quiz-header">
            <h1><?= $quiz['title'] ?></h1>
            <p><?= $quiz['description'] ?></p>
        </div>
        
        <div class="quiz-progress">
            <div class="progress-bar">
                <div class="progress-fill" style="width: 0%"></div>
            </div>
        </div>
        
        <form id="quiz-form" method="POST" action="<?= base_url('quiz/submit') ?>">
            <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">
            
            <?php 
            $questionNumber = 1;
            $totalQuestions = count($questions);
            
            foreach ($questions as $question): 
                $options = $question['options'];
            ?>
            
            <div class="question-container" style="display: <?= $questionNumber === 1 ? 'block' : 'none' ?>">
                <div class="question-number">Question <?= $questionNumber ?> of <?= $totalQuestions ?></div>
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
            $questionNumber++;
            endforeach; 
            ?>
            
            <div class="quiz-controls">
                <button type="button" id="prev-question" class="btn btn-outline" disabled>Previous</button>
                <button type="button" id="next-question" class="btn btn-primary">Next</button>
            </div>
        </form>
    </div>
</div>