<div class="retro-bg-layer"></div>

<section class="transparent-bg">
    <div class="container">
        <!-- Breadcrumb -->
        <nav style="margin-bottom: 32px;">
            <div style="
                background: rgba(34, 34, 34, 0.5); 
                padding: 12px 16px; 
                border-radius: var(--radius-sm); 
                border: 1px solid var(--color-border);
            ">
                <a href="/quiz" style="color: var(--color-primary); text-decoration: none;">
                    <i class="fas fa-home"></i> Quiz
                </a>
                <span style="color: var(--color-text-secondary); margin: 0 8px;">/</span>
                <span style="color: var(--color-text-primary);"><?= esc($category['name']) ?></span>
            </div>
        </nav>

        <!-- Header de catégorie -->
        <h2 class="section-title">
            <i class="fas fa-<?= getCategoryIcon($category['name']) ?>" style="margin-right: 16px;"></i>
            <?= esc($category['name']) ?>
        </h2>
        <p style="text-align: center; color: var(--color-text-secondary); font-size: 1.1rem; margin-bottom: 20px;">
            <?= esc($category['description'] ?? 'Découvrez tous les quiz de cette catégorie') ?>
        </p>
        <div style="text-align: center; margin-bottom: 40px;">
            <span style="color: var(--color-accent); font-size: 1.2rem; font-family: var(--font-family-sans);">
                <?= count($quizzes) ?> quiz disponibles
            </span>
        </div>

        <!-- Liste des quiz -->
        <?php if (empty($quizzes)): ?>
            <div style="text-align: center; padding: 64px 0;">
                <i class="fas fa-question-circle" style="font-size: 3rem; color: var(--color-text-secondary); margin-bottom: 24px;"></i>
                <h3 style="color: var(--color-text-secondary); margin-bottom: 16px;">Aucun quiz dans cette catégorie</h3>
                <p style="color: var(--color-text-secondary); margin-bottom: 24px;">Les administrateurs n'ont pas encore créé de quiz pour cette catégorie.</p>
                <a href="/quiz" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Retour aux catégories
                </a>
            </div>
        <?php else: ?>
            <div class="quiz-grids">
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="quiz-cards">
                        <?php if (!empty($quiz['image'])): ?>
                            <img src="/uploads/quiz/<?= $quiz['image'] ?>" 
                                 alt="<?= esc($quiz['title']) ?>"
                                 style="width: 100%; height: 150px; object-fit: cover; border-radius: var(--radius-sm) var(--radius-sm) 0 0; margin-bottom: 20px;">
                        <?php else: ?>
                            <div style="
                                width: 100%; 
                                height: 150px; 
                                background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
                                display: flex; 
                                align-items: center; 
                                justify-content: center; 
                                border-radius: var(--radius-sm) var(--radius-sm) 0 0;
                                margin-bottom: 20px;
                            ">
                                <i class="fas fa-question-circle" style="font-size: 2rem; color: var(--color-background); opacity: 0.8;"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <!-- Badge de statut -->
                            <div style="position: absolute; top: 8px; right: 8px;">
                                <?php if ($quiz['is_live'] == 1): ?>
                                    <span style="
                                        background: var(--color-primary); 
                                        color: var(--color-background); 
                                        padding: 4px 8px; 
                                        border-radius: var(--radius-sm); 
                                        font-size: 0.8rem;
                                        text-shadow: var(--glow);
                                    ">
                                        <i class="fas fa-broadcast-tower"></i> EN DIRECT
                                    </span>
                                <?php elseif ($quiz['is_active'] == 1): ?>
                                    <span style="
                                        background: var(--color-accent); 
                                        color: var(--color-background); 
                                        padding: 4px 8px; 
                                        border-radius: var(--radius-sm); 
                                        font-size: 0.8rem;
                                    ">
                                        Disponible
                                    </span>
                                <?php else: ?>
                                    <span style="
                                        background: var(--color-border); 
                                        color: var(--color-text-secondary); 
                                        padding: 4px 8px; 
                                        border-radius: var(--radius-sm); 
                                        font-size: 0.8rem;
                                    ">
                                        Indisponible
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <h3><?= esc($quiz['title']) ?></h3>
                            <p><?= esc($quiz['description']) ?></p>
                            
                            <!-- Infos du quiz -->
                            <div style="
                                display: grid; 
                                grid-template-columns: 1fr 1fr; 
                                gap: 16px; 
                                margin-bottom: 20px; 
                                padding: 12px; 
                                background: rgba(34, 34, 34, 0.3); 
                                border-radius: var(--radius-sm);
                            ">
                                <div style="text-align: center;">
                                    <i class="fas fa-question-circle" style="color: var(--color-primary); margin-bottom: 4px;"></i>
                                    <div style="font-size: 0.8rem; color: var(--color-text-secondary);">Questions</div>
                                    <div style="font-weight: bold; color: var(--color-text-primary);"><?= $quiz['question_count'] ?? 0 ?></div>
                                </div>
                                <div style="text-align: center;">
                                    <i class="fas fa-clock" style="color: var(--color-accent); margin-bottom: 4px;"></i>
                                    <div style="font-size: 0.8rem; color: var(--color-text-secondary);">Temps/Q</div>
                                    <div style="font-weight: bold; color: var(--color-text-primary);"><?= $quiz['time_limit'] ?? 10 ?>s</div>
                                </div>
                            </div>
                            
                            <!-- Boutons d'action -->
                            <?php if ($quiz['is_live'] == 1): ?>
                                <a href="/quiz/live" class="btn btn-primary">
                                    <i class="fas fa-play"></i> Rejoindre le Direct
                                </a>
                            <?php elseif ($quiz['is_active'] == 1): ?>
                                <a href="/quiz/start/<?= $quiz['id'] ?>" class="btn btn-primary">
                                    <i class="fas fa-play"></i> Commencer le Quiz
                                </a>
                            <?php else: ?>
                                <button class="btn btn-outline" style="opacity: 0.5;" disabled>
                                    <i class="fas fa-lock"></i> Non disponible
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Actions rapides -->
            <div class="quiz-grid" style="margin-top: 64px;">
                <div class="quiz-card">
                    <h3 style="color: var(--color-accent);">
                        <i class="fas fa-random"></i>
                        Quiz Aléatoire
                    </h3>
                    <p>Commencez un quiz au hasard dans cette catégorie</p>
                    <a href="/quiz/random/<?= $category['id'] ?>" class="btn btn-outline">
                        Surprise !
                    </a>
                </div>
                <div class="quiz-card">
                    <h3 style="color: var(--color-primary);">
                        <i class="fas fa-trophy"></i>
                        Classement Catégorie
                    </h3>
                    <p>Voir les meilleurs scores de cette catégorie</p>
                    <a href="/leaderboard?category=<?= $category['id'] ?>" class="btn btn-primary">
                        Voir le Classement
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Effet hover pour les images de quiz */
.quiz-cards:hover img {
    border-color: var(--color-primary);
    box-shadow: 0 0 8px var(--color-primary);
}

/* Responsive */
@media (max-width: 768px) {
    div[style*="grid-template-columns: 2fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
    
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php
// Fonction helper pour les icônes de catégorie (si pas déjà définie)
if (!function_exists('getCategoryIcon')) {
    function getCategoryIcon($categoryName) {
        $icons = [
            'Science' => 'atom',
            'Histoire' => 'landmark',
            'Géographie' => 'globe',
            'Sport' => 'futbol',
            'Cinéma' => 'film',
            'Musique' => 'music',
            'Littérature' => 'book',
            'Technology' => 'laptop',
            'Art' => 'palette',
            'Culture' => 'theater-masks'
        ];
        
        foreach ($icons as $key => $icon) {
            if (stripos($categoryName, $key) !== false) {
                return $icon;
            }
        }
        
        return 'question-circle';
    }
}
?>