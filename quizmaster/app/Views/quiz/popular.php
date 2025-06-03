<div class="retro-bg-layer"></div>

<!-- Hero Section avec quiz live -->
<?php if (!empty($liveQuiz)): ?>
    <section class="hero-week" style="background: linear-gradient(135deg, #39FF14 0%, #FF1177 100%);">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <h1 style="color: var(--color-primary); text-shadow: var(--glow); animation: pulse 2s infinite;">
                <i class="fas fa-broadcast-tower"></i> Quiz EN DIRECT !
            </h1>
            <h2><?= esc($liveQuiz['title']) ?></h2>
            <p><?= esc($liveQuiz['description']) ?></p>
            <div class="live-info" style="display: flex; justify-content: center; gap: 32px; margin: 32px 0; font-family: var(--font-family-mono);">
                <div style="text-align: center;">
                    <i class="fas fa-users"></i>
                    <div><span id="live-participants">...</span> participants</div>
                </div>
                <div style="text-align: center;">
                    <i class="fas fa-clock"></i>
                    <div>En cours</div>
                </div>
            </div>
            <a href="/quiz/live" class="btn btn-primary" style="font-size: 1.2rem; padding: 16px 32px;">
                <i class="fas fa-play"></i> Rejoindre Maintenant
            </a>
        </div>
    </section>
<?php endif; ?>

<!-- Planning de la semaine -->
<section class="quizz-week hero-week">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <h2 class="section-title">Quiz de la Semaine</h2>
        <div class="week-calendar">
            
            <div class="day-header">Lundi</div>
            <div class="day-header">Mardi</div>
            <div class="day-header">Mercredi</div>
            <div class="day-header">Jeudi</div>
            <div class="day-header">Vendredi</div>
            <div class="day-header">Samedi</div>
            <div class="day-header">Dimanche</div>

            <?php 
            $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
            foreach ($days as $day): 
            ?>
                <div class="day-cell">
                    <?php if (isset($weekSchedule[$day]) && !empty($weekSchedule[$day])): ?>
                        <?php foreach ($weekSchedule[$day] as $event): ?>
                            <h4><?= esc($event['title']) ?></h4>
                            <p><?= esc($event['time']) ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Quiz populaires -->
<section class="quizz-popular transparent-bg">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-fire" style="color: var(--color-accent);"></i>
            Quizz Populaires
        </h2>
        
        <?php if (empty($popularQuizzes)): ?>
            <div style="text-align: center; padding: 64px 0;">
                <i class="fas fa-star" style="font-size: 3rem; color: var(--color-text-secondary); margin-bottom: 16px;"></i>
                <h3 style="color: var(--color-text-secondary);">Aucun quiz populaire pour le moment</h3>
            </div>
        <?php else: ?>
            <div class="quiz-grids">
                <?php foreach ($popularQuizzes as $quiz): ?>
                    <div class="quiz-cards">
                        <?php if (!empty($quiz['image'])): ?>
                            <img src="/uploads/quiz/<?= $quiz['image'] ?>" 
                                 alt="<?= esc($quiz['title']) ?>"
                                 style="width: 100%; height: 150px; object-fit: cover; border-radius: var(--radius-sm) var(--radius-sm) 0 0; margin-bottom: 20px;">
                        <?php endif; ?>
                        <div class="card-body">
                            <div style="position: absolute; top: 8px; right: 8px;">
                                <span style="background: var(--color-accent); color: var(--color-background); padding: 4px 8px; border-radius: var(--radius-sm); font-size: 0.8rem;">
                                    <i class="fas fa-fire"></i> Populaire
                                </span>
                            </div>
                            <h3><?= esc($quiz['title']) ?></h3>
                            <p><?= esc($quiz['description']) ?></p>
                            <div style="margin: 16px 0; font-size: 0.9rem; color: var(--color-text-secondary);">
                                <span style="background: var(--color-border); padding: 4px 8px; border-radius: var(--radius-sm); margin-right: 8px;">
                                    <?= esc($quiz['category_name'] ?? '') ?>
                                </span>
                                <span>
                                    <i class="fas fa-play"></i> <?= $quiz['play_count'] ?? 0 ?> parties
                                </span>
                            </div>
                            <a href="/quiz/start/<?= $quiz['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-play"></i> Jouer
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 32px;">
            <a href="/quiz" class="btn btn-outline">Voir toutes les catégories</a>
        </div>
    </div>
</section>

<!-- Quiz originaux -->
<section class="quizz-original transparent-bg">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-gem" style="color: var(--color-primary);"></i>
            Quizz Originaux
        </h2>
        
        <?php if (empty($originalQuizzes)): ?>
            <div style="text-align: center; padding: 64px 0;">
                <i class="fas fa-lightbulb" style="font-size: 3rem; color: var(--color-text-secondary); margin-bottom: 16px;"></i>
                <h3 style="color: var(--color-text-secondary);">Aucun quiz original pour le moment</h3>
            </div>
        <?php else: ?>
            <div class="quiz-grids">
                <?php foreach ($originalQuizzes as $quiz): ?>
                    <div class="quiz-cards">
                        <?php if (!empty($quiz['image'])): ?>
                            <img src="/uploads/quiz/<?= $quiz['image'] ?>" 
                                 alt="<?= esc($quiz['title']) ?>"
                                 style="width: 100%; height: 150px; object-fit: cover; border-radius: var(--radius-sm) var(--radius-sm) 0 0; margin-bottom: 20px;">
                        <?php endif; ?>
                        <div class="card-body">
                            <div style="position: absolute; top: 8px; right: 8px;">
                                <span style="background: var(--color-primary); color: var(--color-background); padding: 4px 8px; border-radius: var(--radius-sm); font-size: 0.8rem;">
                                    <i class="fas fa-gem"></i> Original
                                </span>
                            </div>
                            <h3><?= esc($quiz['title']) ?></h3>
                            <p><?= esc($quiz['description']) ?></p>
                            <div style="margin: 16px 0; font-size: 0.9rem; color: var(--color-text-secondary);">
                                <span style="background: var(--color-border); padding: 4px 8px; border-radius: var(--radius-sm); margin-right: 8px;">
                                    <?= esc($quiz['category_name'] ?? '') ?>
                                </span>
                                <span>
                                    <i class="fas fa-calendar"></i> Nouveau
                                </span>
                            </div>
                            <a href="/quiz/start/<?= $quiz['id'] ?>" class="btn btn-outline">
                                <i class="fas fa-play"></i> Découvrir
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Animation pour le quiz live */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Responsive pour la section live */
@media (max-width: 768px) {
    .live-info {
        flex-direction: column !important;
        gap: 16px !important;
    }
}
</style>

<script>
// Mettre à jour le nombre de participants live
<?php if (!empty($liveQuiz)): ?>
function updateLiveParticipants() {
    fetch('/quiz/get-live-leaderboard?quiz_id=<?= $liveQuiz['id'] ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.leaderboard) {
                document.getElementById('live-participants').textContent = data.leaderboard.length;
            }
        })
        .catch(error => console.error('Erreur:', error));
}

// Mettre à jour toutes les 5 secondes
updateLiveParticipants();
setInterval(updateLiveParticipants, 5000);
<?php endif; ?>
</script> 