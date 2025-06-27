<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="/admin/quizzes" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($validation->hasError('title') || $validation->hasError('category_id') || $validation->hasError('description')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php if ($validation->hasError('title')): ?>
                    <li><?= $validation->getError('title') ?></li>
                <?php endif; ?>
                <?php if ($validation->hasError('category_id')): ?>
                    <li><?= $validation->getError('category_id') ?></li>
                <?php endif; ?>
                <?php if ($validation->hasError('description')): ?>
                    <li><?= $validation->getError('description') ?></li>
                <?php endif; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="/admin/quiz/store" method="post" enctype="multipart/form-data" id="quizForm">
        <?= csrf_field() ?>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Informations du Quiz</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre du Quiz *</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?= old('title') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Catégorie *</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Choisir une catégorie</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>>
                                        <?= esc($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required><?= old('description') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="time_limit" class="form-label">Temps par question (secondes)</label>
                            <input type="number" class="form-control" id="time_limit" name="time_limit" 
                                   value="<?= old('time_limit', 10) ?>" min="5" max="60">
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image du Quiz</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text">Formats acceptés: JPG, PNG, GIF (max 2MB)</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Questions du Quiz</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addQuestion()">
                            <i class="fas fa-plus"></i> Ajouter une question
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="questions-container">
                            <!-- Les questions seront ajoutées ici dynamiquement -->
                        </div>
                        <div class="text-muted">
                            <small>Ajoutez au moins 3 questions avec 4 options chacune</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card sticky-top">
                    <div class="card-header">
                        <h5>Actions</h5>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-save"></i> Créer le Quiz
                        </button>
                        <a href="/admin/quizzes" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        
                        <hr>
                        <div class="text-center">
                            <small class="text-muted">
                                Questions ajoutées: <span id="question-count">0</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let questionIndex = 0;

function addQuestion() {
    const container = document.getElementById('questions-container');
    const questionHtml = `
        <div class="question-block mb-4 border rounded p-3" data-index="${questionIndex}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Question ${questionIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeQuestion(${questionIndex})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Texte de la question *</label>
                <input type="text" class="form-control" name="question_text[]" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Points attribués</label>
                <input type="number" class="form-control" name="question_points[]" value="1" min="1" max="10">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Options de réponse *</label>
                <div class="options-container">
                    ${generateOptions(questionIndex)}
                </div>
                <div class="form-text">Sélectionnez la bonne réponse ci-dessus</div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', questionHtml);
    questionIndex++;
    updateQuestionCount();
}

function generateOptions(qIndex) {
    let optionsHtml = '';
    for (let i = 0; i < 4; i++) {
        optionsHtml += `
            <div class="input-group mb-2">
                <div class="input-group-text">
                    <input type="radio" name="correct_option_${qIndex}" value="${i}" required>
                </div>
                <input type="text" class="form-control" name="option_text_${qIndex}[]" 
                       placeholder="Option ${i + 1}" required>
            </div>
        `;
    }
    return optionsHtml;
}

function removeQuestion(index) {
    const questionBlock = document.querySelector(`[data-index="${index}"]`);
    if (questionBlock) {
        questionBlock.remove();
        updateQuestionCount();
        renumberQuestions();
    }
}

function updateQuestionCount() {
    const count = document.querySelectorAll('.question-block').length;
    document.getElementById('question-count').textContent = count;
}

function renumberQuestions() {
    const questions = document.querySelectorAll('.question-block');
    questions.forEach((block, index) => {
        const title = block.querySelector('h6');
        title.textContent = `Question ${index + 1}`;
    });
}

// Ajouter une première question par défaut
document.addEventListener('DOMContentLoaded', function() {
    addQuestion();
    addQuestion();
    addQuestion(); // 3 questions par défaut
});

// Validation avant soumission
document.getElementById('quizForm').addEventListener('submit', function(e) {
    const questionCount = document.querySelectorAll('.question-block').length;
    if (questionCount < 3) {
        e.preventDefault();
        alert('Vous devez ajouter au moins 3 questions');
        return false;
    }
    
    // Vérifier que chaque question a une bonne réponse sélectionnée
    let hasError = false;
    document.querySelectorAll('.question-block').forEach(function(block, index) {
        const radios = block.querySelectorAll('input[type="radio"]');
        let hasSelection = false;
        radios.forEach(function(radio) {
            if (radio.checked) hasSelection = true;
        });
        
        if (!hasSelection) {
            hasError = true;
            block.style.borderColor = '#dc3545';
        } else {
            block.style.borderColor = '#dee2e6';
        }
    });
    
    if (hasError) {
        e.preventDefault();
        alert('Veuillez sélectionner la bonne réponse pour chaque question');
        return false;
    }
});
</script> 