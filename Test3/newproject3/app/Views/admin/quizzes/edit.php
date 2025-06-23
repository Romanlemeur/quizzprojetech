<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier le Quiz: <?= esc($quiz['title']) ?></h1>
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

    <form action="/admin/quiz/update/<?= $quiz['id'] ?>" method="post" enctype="multipart/form-data" id="quizForm">
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
                                   value="<?= old('title', $quiz['title']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Catégorie *</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Choisir une catégorie</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" 
                                        <?= (old('category_id', $quiz['category_id']) == $cat['id']) ? 'selected' : '' ?>>
                                        <?= esc($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required><?= old('description', $quiz['description']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="time_limit" class="form-label">Temps par question (secondes)</label>
                            <input type="number" class="form-control" id="time_limit" name="time_limit" 
                                   value="<?= old('time_limit', $quiz['time_limit']) ?>" min="5" max="60">
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image du Quiz</label>
                            <?php if (!empty($quiz['image'])): ?>
                                <div class="mb-2">
                                    <img src="/uploads/quiz/<?= $quiz['image'] ?>" alt="Image actuelle" 
                                         class="img-thumbnail" style="max-height: 100px;">
                                    <div class="form-text">Image actuelle</div>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text">Laissez vide pour conserver l'image actuelle. Formats acceptés: JPG, PNG, GIF (max 2MB)</div>
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
                            <!-- Les questions seront ajoutées ici -->
                        </div>
                        <div class="text-muted">
                            <small>Au moins 3 questions avec 4 options chacune</small>
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
                            <i class="fas fa-save"></i> Sauvegarder
                        </button>
                        <a href="/admin/quizzes" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        
                        <hr>
                        <div class="text-center">
                            <small class="text-muted">
                                Questions: <span id="question-count">0</span>
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
const existingQuestions = <?= json_encode($quiz['questions'] ?? []) ?>;

function addQuestion(questionData = null) {
    const container = document.getElementById('questions-container');
    const isExisting = questionData !== null;
    
    // Valeurs par défaut ou depuis les données existantes
    const questionText = questionData ? questionData.question_text : '';
    const questionPoints = questionData ? questionData.points : 1;
    const questionId = questionData ? questionData.id : '';
    
    const questionHtml = `
        <div class="question-block mb-4 border rounded p-3" data-index="${questionIndex}">
            ${isExisting ? `<input type="hidden" name="existing_question_id[]" value="${questionId}">` : ''}
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Question ${questionIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeQuestion(${questionIndex})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Texte de la question *</label>
                <input type="text" class="form-control" name="question_text[]" value="${questionText}" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Points attribués</label>
                <input type="number" class="form-control" name="question_points[]" value="${questionPoints}" min="1" max="10">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Options de réponse *</label>
                <div class="options-container">
                    ${generateOptions(questionIndex, questionData)}
                </div>
                <div class="form-text">Sélectionnez la bonne réponse ci-dessus</div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', questionHtml);
    questionIndex++;
    updateQuestionCount();
}

function generateOptions(qIndex, questionData = null) {
    let optionsHtml = '';
    const options = questionData ? questionData.options : [];
    
    for (let i = 0; i < 4; i++) {
        const optionText = options[i] ? options[i].option_text : '';
        const isCorrect = options[i] ? options[i].is_correct == 1 : false;
        const optionId = options[i] ? options[i].id : '';
        
        optionsHtml += `
            <div class="input-group mb-2">
                ${optionId ? `<input type="hidden" name="existing_option_id_${qIndex}[]" value="${optionId}">` : ''}
                <div class="input-group-text">
                    <input type="radio" name="correct_option_${qIndex}" value="${i}" ${isCorrect ? 'checked' : ''} required>
                </div>
                <input type="text" class="form-control" name="option_text_${qIndex}[]" 
                       placeholder="Option ${i + 1}" value="${optionText}" required>
            </div>
        `;
    }
    return optionsHtml;
}

function removeQuestion(index) {
    const questionBlock = document.querySelector(`[data-index="${index}"]`);
    if (questionBlock) {
        // Si c'est une question existante, marquer pour suppression
        const existingId = questionBlock.querySelector('input[name="existing_question_id[]"]');
        if (existingId) {
            questionBlock.innerHTML = `
                <input type="hidden" name="delete_question_id[]" value="${existingId.value}">
                <div class="alert alert-warning">Cette question sera supprimée lors de la sauvegarde</div>
            `;
            questionBlock.style.opacity = '0.5';
        } else {
            questionBlock.remove();
        }
        updateQuestionCount();
        renumberQuestions();
    }
}

function updateQuestionCount() {
    const count = document.querySelectorAll('.question-block:not([style*="opacity"])').length;
    document.getElementById('question-count').textContent = count;
}

function renumberQuestions() {
    const questions = document.querySelectorAll('.question-block:not([style*="opacity"])');
    questions.forEach((block, index) => {
        const title = block.querySelector('h6');
        if (title) title.textContent = `Question ${index + 1}`;
    });
}

// Charger les questions existantes
document.addEventListener('DOMContentLoaded', function() {
    if (existingQuestions.length > 0) {
        existingQuestions.forEach(question => {
            addQuestion(question);
        });
    } else {
        // Ajouter 3 questions vides si aucune existante
        addQuestion();
        addQuestion();
        addQuestion();
    }
});

// Validation avant soumission
document.getElementById('quizForm').addEventListener('submit', function(e) {
    const questionCount = document.querySelectorAll('.question-block:not([style*="opacity"])').length;
    if (questionCount < 3) {
        e.preventDefault();
        alert('Vous devez avoir au moins 3 questions');
        return false;
    }
    
    // Vérifier que chaque question visible a une bonne réponse sélectionnée
    let hasError = false;
    document.querySelectorAll('.question-block:not([style*="opacity"])').forEach(function(block) {
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