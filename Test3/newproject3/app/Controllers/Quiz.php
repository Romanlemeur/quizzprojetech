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
            return redirect()->to('quiz')->with('error', 'Ce quiz n\'est pas en direct ou n\'existe pas.');
        }

        $user_id = session()->get('user_id');

        // Vérifier que le joueur est dans ce quiz, sinon l'inscrire
        $participation = $this->scoreModel->where('user_id', $user_id)
                                         ->where('quiz_id', $quiz_id)
                                         ->where('is_live', 1)
                                         ->first();

        if (!$participation) {
            $this->scoreModel->joinLiveQuiz($user_id, $quiz_id);
        }
        
        $data = [
            'title' => 'Quiz en Direct: ' . $quiz['title'],
            'quiz' => $this->quizModel->getQuizWithQuestions($quiz_id),
            'liveSession' => true
        ];

        return view('templates/header', $data)
            . view('quiz/live')
            . view('templates/footer');
    }
    
    // API pour le quiz live : récupérer la question en cours
    public function getCurrentQuestion()
    {
        $quizId = $this->request->getGet('quiz_id');
        $quiz = $this->quizModel->find($quizId);

        if (!$quiz || $quiz['is_live'] != 1) {
            return $this->response->setJSON(['success' => false, 'message' => 'Quiz non trouvé ou non en direct']);
        }
        
        $quizSessionModel = new \App\Models\QuizSessionModel();
        $session = $quizSessionModel->where('quiz_id', $quizId)->where('is_active', 1)->first();

        if (!$session) {
            return $this->response->setJSON(['status' => 'waiting', 'message' => 'Le quiz n\'a pas encore commencé.']);
        }

        $question = $this->questionModel->find($session['current_question_id']);
        if ($question) {
            $options = $this->optionModel->where('question_id', $question['id'])->findAll();
            $question['options'] = $options;
        }

        $totalQuestions = $this->questionModel->where('quiz_id', $quizId)->countAllResults();
        $currentQuestionIndex = $this->questionModel->where('quiz_id', $quizId)->where('id <=', $session['current_question_id'])->countAllResults();

        return $this->response->setJSON([
            'status' => 'in_progress',
            'question' => $question,
            'currentQuestionIndex' => $currentQuestionIndex,
            'totalQuestions' => $totalQuestions,
            'ends_at' => $session['question_ends_at']
        ]);
    }
    
    // Soumettre une réponse pour le quiz live
    public function submitLiveAnswer()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Requête invalide']);
        }
        
        $quiz_id = $this->request->getPost('quiz_id');
        $question_id = $this->request->getPost('question_id');
        $option_id = $this->request->getPost('option_id');
        $user_id = session()->get('user_id');

        // Vérifier que la session est active
        $quizSessionModel = new \App\Models\QuizSessionModel();
        $session = $quizSessionModel->where('quiz_id', $quiz_id)->where('is_active', 1)->first();

        if (!$session || $session['current_question_id'] != $question_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Vous ne pouvez plus répondre à cette question.']);
        }

        // Vérifier le temps
        if (time() > strtotime($session['question_ends_at'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Temps écoulé pour cette question.']);
        }
        
        // Vérifier la réponse
        $option = $this->optionModel->find($option_id);
        $score = 0;
        if ($option && $option['is_correct']) {
            $question = $this->questionModel->find($question_id);
            $score = $question ? $question['points'] : 0;
        }

        // Mettre à jour le score du joueur
        $scoreModel = new ScoreModel();
        $scoreModel->updateLiveScore($user_id, $quiz_id, $score);

        return $this->response->setJSON(['success' => true, 'message' => 'Réponse enregistrée.']);
    }
    
    // API pour le quiz live : récupérer le leaderboard
    public function getLiveLeaderboard()
    {
        $quizId = $this->request->getGet('quiz_id');
        $quiz = $this->quizModel->find($quizId);
        
        if (!$quiz || $quiz['is_live'] != 1) {
            return $this->response->setJSON(['success' => false, 'message' => 'Quiz non trouvé ou non en direct']);
        }

        $leaderboard = $this->scoreModel->getLiveLeaderboard($quizId);
        return $this->response->setJSON(['success' => true, 'leaderboard' => $leaderboard]);
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