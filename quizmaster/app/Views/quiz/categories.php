<div class="retro-bg-layer"></div>

<section class="transparent-bg">
    <div class="container">
        <!-- Alerte quiz en direct -->
        <?php if ($liveQuiz): ?>
            <div class="card" style="border: 2px solid var(--color-primary); background: rgba(57, 255, 20, 0.1); margin-bottom: 32px;">
                <div style="padding: 24px;">
                    <h3 style="color: var(--color-primary); text-shadow: var(--glow); margin-bottom: 16px;">
                        <i class="fas fa-broadcast-tower"></i>
                        Quiz en Direct Disponible !
                    </h3>
                    <h4 style="color: var(--color-text-primary); margin-bottom: 8px;"><?= esc($liveQuiz['title']) ?></h4>
                    <p style="color: var(--color-text-secondary); margin-bottom: 16px;"><?= esc($liveQuiz['description']) ?></p>
                    <p style="color: var(--color-text-secondary); margin-bottom: 24px;">
                        <i class="fas fa-users"></i>
                        Rejoignez les autres joueurs maintenant !
                    </p>
                    <a href="/quiz/live" class="btn btn-primary">
                        <i class="fas fa-play"></i> Rejoindre le Quiz
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="card" style="border: 2px solid var(--color-border); background: rgba(138, 138, 138, 0.1); margin-bottom: 32px;">
                <div style="padding: 24px; text-align: center;">
                    <i class="fas fa-clock" style="font-size: 2rem; color: var(--color-text-secondary); margin-bottom: 16px;"></i>
                    <h4 style="color: var(--color-text-secondary); margin-bottom: 8px;">Aucun quiz en direct pour le moment</h4>
                    <p style="color: var(--color-text-secondary);">Consultez les quiz disponibles ci-dessous ou revenez plus tard !</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Header -->
        <h2 class="section-title">Catégories de Quiz</h2>
        <p style="text-align: center; color: var(--color-text-secondary); font-size: 1.1rem; margin-bottom: 40px;">
            Choisissez une catégorie et testez vos connaissances
        </p>

        <!-- Catégories -->
        <?php if (empty($categories)): ?>
            <div style="text-align: center; padding: 64px 0;">
                <i class="fas fa-layer-group" style="font-size: 3rem; color: var(--color-text-secondary); margin-bottom: 24px;"></i>
                <h3 style="color: var(--color-text-secondary); margin-bottom: 16px;">Aucune catégorie disponible</h3>
                <p style="color: var(--color-text-secondary);">Les administrateurs n'ont pas encore créé de catégories de quiz.</p>
            </div>
        <?php else: ?>
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                    <div class="category-card">
                        <div style="text-align: center; margin-bottom: 24px;">
                            <div style="
                                width: 80px; 
                                height: 80px; 
                                border-radius: 50%; 
                                background: rgba(57, 255, 20, 0.1); 
                                border: 2px solid var(--color-primary);
                                display: flex; 
                                align-items: center; 
                                justify-content: center; 
                                margin: 0 auto 16px;
                                transition: all 0.2s;
                            ">
                                <i class="fas fa-<?= getCategoryIcon($category['name']) ?>" 
                                   style="font-size: 2rem; color: var(--color-primary);"></i>
                            </div>
                        </div>
                        
                        <h3><?= esc($category['name']) ?></h3>
                        <p><?= esc($category['description'] ?? 'Découvrez les quiz de cette catégorie') ?></p>
                        
                        <div style="margin-bottom: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                                <span style="color: var(--color-text-secondary); font-size: 0.9rem;">
                                    <i class="fas fa-question-circle"></i>
                                    <?= $category['quiz_count'] ?? 0 ?> quiz
                                </span>
                                <span style="
                                    background: var(--color-primary); 
                                    color: var(--color-background); 
                                    padding: 4px 8px; 
                                    border-radius: var(--radius-sm); 
                                    font-size: 0.8rem;
                                ">
                                    <?= ucfirst($category['difficulty'] ?? 'Mixte') ?>
                                </span>
                            </div>
                        </div>
                        
                        <a href="/quiz/category/<?= $category['id'] ?>" class="btn btn-primary">
                            <i class="fas fa-play"></i> Voir les Quiz
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Section rapide -->
        <div class="quiz-grid" style="margin-top: 64px;">
            <div class="quiz-card">
                <h3 style="color: var(--color-accent);">
                    <i class="fas fa-bolt"></i>
                    Défi Rapide
                </h3>
                <p>Pas le temps de choisir ? Lancez-vous dans un quiz aléatoire et testez vos connaissances générales !</p>
                <a href="/quiz/random" class="btn btn-outline">
                    <i class="fas fa-random"></i> Quiz Aléatoire
                </a>
            </div>
            
            <div class="quiz-card">
                <h3 style="color: var(--color-primary);">
                    <i class="fas fa-trophy"></i>
                    Classement
                </h3>
                <p>Consultez le classement des meilleurs joueurs</p>
                <a href="/leaderboard" class="btn btn-primary">
                    <i class="fas fa-medal"></i> Voir le Classement
                </a>
            </div>
        </div>
    </div>
</section>

<?php
// Fonction helper pour les icônes de catégorie
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
    
    return 'question-circle'; // Icône par défaut
}
?>