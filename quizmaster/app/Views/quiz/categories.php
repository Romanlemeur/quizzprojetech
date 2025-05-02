<div class="container">
    <div class="quiz-container">
        <div class="quiz-header">
            <h1>Select a Category</h1>
        </div>
        
        <div class="categories-grid">
            <?php if (empty($categories)): ?>
                <p>No categories available yet.</p>
            <?php else: ?>
                <?php foreach ($categories as $category): ?>
                    <div class="category-card card">
                        <h3><?= $category['name'] ?></h3>
                        <p><?= $category['description'] ?></p>
                        <?php 
                        $categoryModel = new \App\Models\CategoryModel();
                        $quizCount = $categoryModel->getQuizCount($category['id']); 
                        ?>
                        <p class="quiz-count"><?= $quizCount ?> <?= ($quizCount == 1) ? 'Quiz' : 'Quizzes' ?></p>
                        <a href="<?= base_url('quiz/category/' . $category['id']) ?>" class="btn btn-primary">Select</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>