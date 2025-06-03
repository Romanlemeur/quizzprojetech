<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\QuizModel;
use App\Models\QuestionModel;
use App\Models\OptionModel;
use App\Models\ScoreModel;

class Quiz extends BaseController
{
    protected $categoryModel;
    protected $quizModel;
    protected $questionModel;
    protected $optionModel;
    protected $scoreModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->quizModel = new QuizModel();
        $this->questionModel = new QuestionModel();
        $this->optionModel = new OptionModel();
        $this->scoreModel = new ScoreModel();
    }

    public function index()
    {
        // Check si connecté
        if (!is_logged_in()) {
            return redirect()->to('login?redirect=quiz');
        }

        $data = [
            'title' => 'Catégories de Quiz',
            'categories' => $this->categoryModel->findAll(),
            'liveQuiz' => $this->quizModel->getLiveQuiz()
        ];

        return view('templates/header', $data)
            . view('quiz/categories')
            . view('templates/footer');
    }

    public function category($cat_id)
    {
        // Check si connecté
        if (!is_logged_in()) {
            return redirect()->to('login?redirect=quiz');
        }

        $category = $this->categoryModel->find($cat_id);

        if (!$category) {
            return redirect()->to('quiz');
        }

        $data = [
            'title' => 'Quiz ' . $category['name'],
            'category' => $category,
            'quizzes' => $this->quizModel->where('category_id', $cat_id)->findAll()
        ];

        return view('templates/header', $data)
            . view('quiz/quizzes')
            . view('templates/footer');
    }

    public function start($quizId)
    {
        $quizModel = new QuizModel();
        $quiz = $quizModel->getQuizWithQuestions($quizId);
        
        if (!$quiz) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Quiz non trouvé');
        }
        
        if (!$quiz['is_active']) {
            return redirect()->to('/quiz')->with('error', 'Ce quiz n\'est pas disponible');
        }

        return view('quiz/start', ['quiz' => $quiz]);
    }

    public function submit()
    {
        // Check si connecté
        if (!is_logged_in()) {
            return redirect()->to('login?redirect=quiz');
        }

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('quiz');
        }

        $quiz_id = $this->request->getPost('quiz_id');
        $reponses = [];
        
        // Récupérer les réponses
        foreach ($this->request->getPost() as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $question_id = substr($key, strlen('question_'));
                $reponses[$question_id] = $value;
            }
        }

        // Calculer le score
        $score = 0;
        $bonnes_reponses = 0;
        $total_questions = 0;

        $questions = $this->questionModel->where('quiz_id', $quiz_id)->findAll();
        $total_questions = count($questions);

        // TODO: optimiser ça, on pourrait tout faire en une requête
        foreach ($questions as $q) {
            $question_id = $q['id'];
            
            if (isset($reponses[$question_id])) {
                $option_id = $reponses[$question_id];
                
                // Vérifier si bonne réponse
                $option = $this->optionModel->where('id', $option_id)->where('question_id', $question_id)->first();
                
                if ($option && $option['is_correct'] == 1) {
                    $score += $q['points'];
                    $bonnes_reponses++;
                }
            }
        }

        $pourcentage = ($total_questions > 0) ? round(($bonnes_reponses / $total_questions) * 100) : 0;

        // Sauvegarder le score
        $user_id = session()->get('user_id');
        $score_data = [
            'user_id' => $user_id,
            'quiz_id' => $quiz_id,
            'score' => $score,
            'completed_at' => date('Y-m-d H:i:s'),
            'is_live' => 0
        ];
        
        $this->scoreModel->insert($score_data);

        // Aller à la page de résultats
        return redirect()->to("quiz/result/$quiz_id/$score/$bonnes_reponses/$total_questions");
    }

    public function result($quiz_id, $score, $correct, $total)
    {
        // Check si connecté
        if (!is_logged_in()) {
            return redirect()->to('login?redirect=quiz');
        }

        $quiz = $this->quizModel->find($quiz_id);

        if (!$quiz) {
            return redirect()->to('quiz');
        }

        $pourcentage = ($total > 0) ? round(($correct / $total) * 100) : 0;

        // Message selon la performance
        $message = '';
        if ($pourcentage >= 90) {
            $message = 'Excellent ! Vous êtes un maître sur ce sujet !';
        } elseif ($pourcentage >= 70) {
            $message = 'Très bien ! Vous avez une bonne compréhension de ce sujet !';
        } elseif ($pourcentage >= 50) {
            $message = 'Bon effort ! Vous connaissez les bases, mais il y a place à l\'amélioration.';
        } else {
            $message = 'Continuez à vous entraîner ! Ce sujet nécessite un peu plus d\'étude.';
        }

        $data = [
            'title' => 'Résultats du Quiz',
            'quiz' => $quiz,
            'score' => $score,
            'correct' => $correct,
            'total' => $total,
            'percentage' => $pourcentage,
            'message' => $message
        ];

        return view('templates/header', $data)
            . view('quiz/result')
            . view('templates/footer');
    }
    
    // Rejoindre un quiz live
    public function joinLive()
    {
        // Check si connecté
        if (!is_logged_in()) {
            return redirect()->to('login?redirect=quiz/live');
        }

        // On vérifie s'il y a un quiz en live
        $liveQuiz = $this->quizModel->getLiveQuiz();
        
        if (!$liveQuiz) {
            return redirect()->to('quiz')->with('error', 'Aucun quiz n\'est actuellement en direct');
        }
        
        // On ajoute le joueur à ce quiz
        $user_id = session()->get('user_id');
        $this->scoreModel->joinLiveQuiz($user_id, $liveQuiz['id']);
        
        return redirect()->to('quiz/live/' . $liveQuiz['id']);
    }
    
    // Page du quiz live
    public function livePage($quiz_id)
    {
        // Check si connecté
        if (!is_logged_in()) {
            return redirect()->to('login?redirect=quiz/live/' . $quiz_id);
        }

        // Vérifier que le quiz est en mode live
        $quiz = $this->quizModel->find($quiz_id);
        
        if (!$quiz || $quiz['is_live'] != 1) {
            return redirect()->to('quiz')->with('error', 'Ce quiz n\'est pas en direct');
        }
        
        $quiz = $this->quizModel->getQuizWithQuestions($quiz_id);
        $user_id = session()->get('user_id');
        
        // Vérifier que le joueur est dans ce quiz
        $participation = $this->scoreModel->where('user_id', $user_id)
                                         ->where('quiz_id', $quiz_id)
                                         ->where('is_live', 1)
                                         ->first();
        
        if (!$participation) {
            // Inscription auto
            $this->scoreModel->joinLiveQuiz($user_id, $quiz_id);
        }
        
        $data = [
            'title' => 'Quiz en Direct: ' . $quiz['title'],
            'quiz' => $quiz,
            'totalQuestions' => count($quiz['questions']),
            'liveSession' => true
        ];

        return view('templates/header', $data)
            . view('quiz/live')
            . view('templates/footer');
    }
    
    // API: question actuelle
    public function getCurrentQuestion()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Requête invalide']);
        }
        
        $quiz_id = $this->request->getGet('quiz_id');
        $liveQuiz = $this->quizModel->getLiveQuiz();
        
        if (!$liveQuiz || $liveQuiz['id'] != $quiz_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Quiz non trouvé ou non en direct']);
        }
        
        // On récupère la session pour ce quiz
        // TODO: créer une vraie table session_quiz, là on fait avec ce qu'on a
        $db = \Config\Database::connect();
        $builder = $db->table('quiz_sessions');
        $session = $builder->where('quiz_id', $quiz_id)->get()->getRowArray();
        
        if (!$session) {
            return $this->response->setJSON(['success' => false, 'message' => 'Session non trouvée']);
        }
        
        $current = $session['current_question'];
        
        // Infos de la question
        $quiz = $this->quizModel->getQuizWithQuestions($quiz_id);
        $questions = $quiz['questions'];
        
        if ($current >= count($questions)) {
            return $this->response->setJSON([
                'success' => true,
                'finished' => true,
                'message' => 'Quiz terminé'
            ]);
        }
        
        $question = $questions[$current];
        
        // On sauvegarde où en est le user
        $user_id = session()->get('user_id');
        $this->scoreModel->updateCurrentQuestion($user_id, $quiz_id, $current);
        
        return $this->response->setJSON([
            'success' => true,
            'current_question' => $current,
            'total_questions' => count($questions),
            'question' => $question,
            'time_limit' => 10 // secondes pour répondre
        ]);
    }
    
    // API: soumettre une réponse en direct
    public function submitLiveAnswer()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Requête invalide']);
        }
        
        $quiz_id = $this->request->getPost('quiz_id');
        $question_id = $this->request->getPost('question_id');
        $option_id = $this->request->getPost('option_id');
        $temps = $this->request->getPost('time_spent'); // temps pour répondre
        
        $user_id = session()->get('user_id');
        
        // On vérifie si c'est la bonne réponse
        $option = $this->optionModel->find($option_id);
        $question = $this->questionModel->find($question_id);
        
        if (!$option || !$question || $option['question_id'] != $question_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Données invalides']);
        }
        
        // La participation de ce joueur
        $participation = $this->scoreModel->where('user_id', $user_id)
                                         ->where('quiz_id', $quiz_id)
                                         ->where('is_live', 1)
                                         ->first();
        
        if (!$participation) {
            return $this->response->setJSON(['success' => false, 'message' => 'Participation non trouvée']);
        }
        
        // Calcul des points gagnés
        $points = 0;
        if ($option['is_correct'] == 1) {
            // Points de base
            $pts_base = $question['points'] ?? 1;
            
            // Bonus de rapidité (on donne jusqu'à 50% en plus si réponse rapide)
            $bonus_temps = max(0, 1 - ($temps / 10));
            $points = $pts_base * (1 + ($bonus_temps * 0.5));
            
            // On arrondit
            $points = round($points);
        }
        
        // Mise à jour
        $nouveau_score = $participation['score'] + $points;
        $this->scoreModel->updateScore($user_id, $quiz_id, $nouveau_score);
        
        return $this->response->setJSON([
            'success' => true,
            'is_correct' => $option['is_correct'] == 1,
            'points_earned' => $points,
            'new_score' => $nouveau_score
        ]);
    }
    
    // API: classement live
    public function getLiveLeaderboard()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Requête invalide']);
        }
        
        $quiz_id = $this->request->getGet('quiz_id');
        
        $classement = $this->scoreModel->getLiveLeaderboard($quiz_id);
        
        return $this->response->setJSON([
            'success' => true,
            'leaderboard' => $classement
        ]);
    }

    // Affiche la page des quiz populaires et de la semaine (intégration de quiz.php)
    public function popular()
    {
        $data = [
            'title' => 'Quiz Populaires',
            'popularQuizzes' => $this->quizModel->getPopularQuizzes(3),
            'originalQuizzes' => $this->quizModel->getOriginalQuizzes(3),
            'weekSchedule' => $this->getWeeklySchedule()
        ];
        
        return view('templates/header', $data)
            . view('quiz/popular')
            . view('templates/footer');
    }
    
    // Méthode pour récupérer le planning de la semaine pour les quiz
    private function getWeeklySchedule()
    {
        // Version avec données dynamiques depuis la BDD
        $upcomingQuizzes = $this->quizModel->getUpcomingQuizzes();
        
        // Initialiser le tableau des jours
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $planning = [];
        
        foreach ($jours as $jour) {
            $planning[$jour] = [];
        }
        
        // Remplir avec les quiz planifiés
        foreach ($upcomingQuizzes as $quiz) {
            $jour_semaine = date('l', strtotime($quiz['scheduled_date']));
            $jour_fr = $this->getJourFrancais($jour_semaine);
            
            if (isset($planning[$jour_fr])) {
                $planning[$jour_fr][] = [
                    'title' => $quiz['title'],
                    'time' => date('H\hi', strtotime($quiz['scheduled_date']))
                ];
            }
        }
        
        return $planning;
    }
    
    // Conversion des jours anglais en français
    private function getJourFrancais($jour_en)
    {
        $jours = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche'
        ];
        
        return $jours[$jour_en] ?? 'Lundi'; // Par défaut lundi si non trouvé
    }
}