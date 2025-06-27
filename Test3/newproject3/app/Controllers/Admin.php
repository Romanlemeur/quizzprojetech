<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\QuizModel;
use App\Models\QuestionModel;
use App\Models\OptionModel;
use App\Models\UserModel;
use App\Models\ScoreModel;

class Admin extends BaseController
{
    protected $categoryModel;
    protected $quizModel;
    protected $questionModel;
    protected $optionModel;
    protected $userModel;
    protected $scoreModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->quizModel = new QuizModel();
        $this->questionModel = new QuestionModel();
        $this->optionModel = new OptionModel();
        $this->userModel = new UserModel();
        $this->scoreModel = new ScoreModel();
    }

    // Check admin avant chaque action
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        if (!is_admin()) {
            return redirect()->to('/');
        }
    }

    // Dashboard admin - page principale
    public function dashboard()
    {
        $data = [
            'title' => 'Tableau de bord administrateur',
            'quizCount' => $this->quizModel->countAll(),
            'userCount' => $this->userModel->countAll(),
            'categoryCount' => $this->categoryModel->countAll(),
            'liveQuiz' => $this->quizModel->getLiveQuiz()
        ];

        return view('admin/templates/header', $data)
            . view('admin/dashboard')
            . view('admin/templates/footer');
    }

    // Liste des quiz
    public function quizzes()
    {
        $data = [
            'title' => 'Gestion des quiz',
            'quizzes' => $this->quizModel->getQuizzesWithCategories()
        ];

        return view('admin/templates/header', $data)
            . view('admin/quizzes/index')
            . view('admin/templates/footer');
    }

    // Formulaire pour créer un quiz
    public function createQuiz()
    {
        $data = [
            'title' => 'Créer un quiz',
            'categories' => $this->categoryModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/templates/header', $data)
            . view('admin/quizzes/create')
            . view('admin/templates/footer');
    }

    // Traitement du formulaire de création
    public function storeQuiz()
    {
        
        
        // Validation du quiz
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'category_id' => 'required|numeric',
            'description' => 'required',
            'time_limit' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Infos du quiz
        $quiz_data = [
            'title' => $this->request->getPost('title'),
            'category_id' => $this->request->getPost('category_id'),
            'description' => $this->request->getPost('description'),
            'time_limit' => $this->request->getPost('time_limit'),
            'is_active' => 1,
            'is_live' => 0
        ];

        // Upload image si fournie
        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $new_name = $img->getRandomName();
            $img->move(FCPATH . 'uploads/quiz', $new_name);
            $quiz_data['image'] = $new_name;
        }

        // Récupérer toutes les questions
        $questions = [];
        $question_texts = $this->request->getPost('question_text');
        $question_points = $this->request->getPost('question_points');
        
        if (is_array($question_texts)) {
            foreach ($question_texts as $i => $text) {
                // On garde que les questions remplies
                if (!empty($text)) {
                    $options = [];
                    $option_texts = $this->request->getPost('option_text_' . $i);
                    $correct_option = $this->request->getPost('correct_option_' . $i);
                    
                    if (is_array($option_texts)) {
                        foreach ($option_texts as $j => $opt_text) {
                            if (!empty($opt_text)) {
                                $options[] = [
                                    'text' => $opt_text,
                                    'is_correct' => ($correct_option == $j) ? 1 : 0
                                ];
                            }
                        }
                    }
                    
                    if (count($options) >= 2) { // Au moins 2 options
                        $questions[] = [
                            'text' => $text,
                            'points' => $question_points[$i] ?? 1,
                            'options' => $options
                        ];
                    }
                }
            }
        }

        if (empty($questions)) {
            return redirect()->back()->withInput()->with('error', 'Vous devez ajouter au moins une question avec des options');
        }

        // Créer le quiz et ses questions
        $quiz_id = $this->quizModel->createFullQuiz($quiz_data, $questions);
        
        if (!$quiz_id) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du quiz');
        }
        
        return redirect()->to('admin/quizzes')->with('message', 'Quiz créé avec succès');
    }

    // Modifier un quiz existant
    public function editQuiz($id)
    {
        $quiz = $this->quizModel->getQuizWithQuestions($id);
        
        if (!$quiz) {
            return redirect()->to('admin/quizzes')->with('error', 'Quiz non trouvé');
        }
        
        $data = [
            'title' => 'Modifier le quiz',
            'quiz' => $quiz,
            'categories' => $this->categoryModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/templates/header', $data)
            . view('admin/quizzes/edit')
            . view('admin/templates/footer');
    }

    // Traitement de la mise à jour
    public function updateQuiz($id)
    {
        // Validation
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'category_id' => 'required|numeric',
            'description' => 'required',
            'time_limit' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Données du quiz
        $quiz_data = [
            'title' => $this->request->getPost('title'),
            'category_id' => $this->request->getPost('category_id'),
            'description' => $this->request->getPost('description'),
            'time_limit' => $this->request->getPost('time_limit')
        ];

        // Image
        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $new_name = $img->getRandomName();
            $img->move(FCPATH . 'uploads/quiz', $new_name);
            $quiz_data['image'] = $new_name;
            
            // Supprimer l'ancienne image
            $old_quiz = $this->quizModel->find($id);
            if ($old_quiz && !empty($old_quiz['image'])) {
                $old_img_path = FCPATH . 'uploads/quiz/' . $old_quiz['image'];
                if (file_exists($old_img_path)) {
                    unlink($old_img_path);
                }
            }
        }

        // Questions
        $questions = [];
        $question_texts = $this->request->getPost('question_text');
        $question_points = $this->request->getPost('question_points');
        
        if (is_array($question_texts)) {
            foreach ($question_texts as $i => $text) {
                // Seulement les questions complètes
                if (!empty($text)) {
                    $options = [];
                    $option_texts = $this->request->getPost('option_text_' . $i);
                    $correct_option = $this->request->getPost('correct_option_' . $i);
                    
                    if (is_array($option_texts)) {
                        foreach ($option_texts as $j => $opt_text) {
                            if (!empty($opt_text)) {
                                $options[] = [
                                    'text' => $opt_text,
                                    'is_correct' => ($correct_option == $j) ? 1 : 0
                                ];
                            }
                        }
                    }
                    
                    if (count($options) >= 2) { // Au moins 2 options
                        $questions[] = [
                            'text' => $text,
                            'points' => $question_points[$i] ?? 1,
                            'options' => $options
                        ];
                    }
                }
            }
        }

        if (empty($questions)) {
            return redirect()->back()->withInput()->with('error', 'Vous devez ajouter au moins une question avec des options');
        }

        // Update
        $result = $this->quizModel->updateFullQuiz($id, $quiz_data, $questions);
        
        if (!$result) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour du quiz');
        }
        
        return redirect()->to('admin/quizzes')->with('message', 'Quiz mis à jour avec succès');
    }

    // Supprimer un quiz
    public function deleteQuiz($id)
    {
        $quiz = $this->quizModel->find($id);
        
        if (!$quiz) {
            return redirect()->to('admin/quizzes')->with('error', 'Quiz non trouvé');
        }
        
        // Suppression de l'image
        if (!empty($quiz['image'])) {
            $img_path = FCPATH . 'uploads/quiz/' . $quiz['image'];
            if (file_exists($img_path)) {
                unlink($img_path);
            }
        }
        
        // Suppression en cascade
        $this->quizModel->delete($id);
        
        return redirect()->to('admin/quizzes')->with('message', 'Quiz supprimé avec succès');
    }

    // Lancer un quiz en direct
    public function startLiveQuiz($id)
    {
        $quiz = $this->quizModel->find($id);
        
        if (!$quiz) {
            return redirect()->to('admin/quizzes')->with('error', 'Quiz non trouvé');
        }
        
        // Activer le mode live sur le quiz
        $result = $this->quizModel->setQuizLive($id);
        
        if (!$result) {
            return redirect()->to('admin/quizzes')->with('error', 'Erreur lors du lancement du quiz en direct');
        }
        
        // Supprimer toutes les anciennes sessions pour ce quiz
        $quizSessionModel = new \App\Models\QuizSessionModel();
        $quizSessionModel->where('quiz_id', $id)->delete();
        
        // Récupérer la première question du quiz
        $quizWithQuestions = $this->quizModel->getQuizWithQuestions($id);
        $questions = $quizWithQuestions['questions'];
        $firstQuestion = null;
        
        if (!empty($questions)) {
            $firstQuestion = $questions[0];
        }
        
        // Créer une nouvelle session propre et active avec la première question
        $sessionData = [
            'quiz_id' => $id,
            'current_question_id' => $firstQuestion ? $firstQuestion['id'] : null,
            'is_active' => 1,
            'start_time' => date('Y-m-d H:i:s')
        ];
        
        $quizSessionModel->insert($sessionData);
        
        log_message('debug', "Nouvelle session live créée pour quiz $id avec première question: " . ($firstQuestion ? $firstQuestion['id'] : 'null'));
        log_message('debug', "Session data: " . json_encode($sessionData));
        
        return redirect()->to('admin/live')->with('message', 'Quiz lancé en direct avec succès');
    }

    // Page de gestion du quiz en direct
    public function liveQuiz()
    {
        $liveQuiz = $this->quizModel->getLiveQuiz();
        
        if (!$liveQuiz) {
            return redirect()->to('admin/quizzes')->with('error', 'Aucun quiz n\'est actuellement en direct');
        }
        
        $quiz = $this->quizModel->getQuizWithQuestions($liveQuiz['id']);
        
        $data = [
            'title' => 'Gestion du quiz en direct',
            'quiz' => $quiz,
            'participantsCount' => $this->scoreModel->getParticipantsCount($quiz['id']),
            'liveSession' => true
        ];

        return view('admin/templates/header', $data)
            . view('admin/live/index')
            . view('admin/templates/footer');
    }
    
    // API pour la question suivante
    public function nextQuestion()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Requête invalide']);
        }
        
        // Debug - voir ce qui est reçu
        $rawInput = $this->request->getBody();
        log_message('debug', "Raw input: " . $rawInput);
        
        // Lire les données JSON
        try {
            $jsonData = $this->request->getJSON();
            log_message('debug', "JSON data: " . json_encode($jsonData));
        } catch (\Exception $e) {
            log_message('error', "JSON parse error: " . $e->getMessage());
            // Essayer de lire les données POST comme fallback
            $quiz_id = $this->request->getPost('quiz_id');
            $currentQuestionId = $this->request->getPost('current_question_id');
            log_message('debug', "Fallback to POST - quiz_id: $quiz_id, current_question_id: " . ($currentQuestionId ?? 'null'));
        }
        
        if (!isset($jsonData)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Données JSON invalides']);
        }
        
        $currentQuestionId = $jsonData->current_question_id ?? null;
        $quiz_id = $jsonData->quiz_id ?? null;
        
        // Debug temporaire
        log_message('debug', "nextQuestion - quiz_id: $quiz_id, current_question_id: " . ($currentQuestionId ?? 'null'));
        
        // Check si le quiz est en direct
        $liveQuiz = $this->quizModel->getLiveQuiz();
        log_message('debug', "getLiveQuiz result: " . json_encode($liveQuiz));
        
        if (!$liveQuiz) {
            return $this->response->setJSON(['success' => false, 'message' => 'Aucun quiz en direct trouvé']);
        }
        
        if ($liveQuiz['id'] != $quiz_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Quiz ID mismatch: attendu ' . $liveQuiz['id'] . ', reçu ' . $quiz_id]);
        }
        
        $quiz = $this->quizModel->getQuizWithQuestions($quiz_id);
        $questions = $quiz['questions'];
        $total = count($questions);
        
        // Si c'est le démarrage (currentQuestionId est null), commencer par la première question
        if ($currentQuestionId === null || $currentQuestionId === 'null') {
            if ($total > 0) {
                $firstQuestion = $questions[0];
                
                // Vérifier que la session existe et est active
                $quizSessionModel = new \App\Models\QuizSessionModel();
                $existingSession = $quizSessionModel->where('quiz_id', $quiz_id)->where('is_active', 1)->first();
                
                log_message('debug', "Existing session check: " . json_encode($existingSession));
                
                if (!$existingSession) {
                    log_message('error', "No active session found for quiz_id: $quiz_id");
                    return $this->response->setJSON(['success' => false, 'message' => 'Aucune session active trouvée']);
                }
                
                $sessionId = $existingSession['id'];
                
                // Mettre à jour la session avec la première question
                $updateData = [
                    'current_question_id' => $firstQuestion['id']
                ];
                
                if (!empty($updateData)) {
                    $result = $quizSessionModel->update($sessionId, $updateData);
                    
                    log_message('debug', "Started quiz with first question: " . $firstQuestion['id']);
                    log_message('debug', "Update result: " . $result);
                    log_message('debug', "Update data: " . json_encode($updateData));
                    
                    // Vérifier que la mise à jour a bien fonctionné
                    $updatedSession = $quizSessionModel->find($sessionId);
                    log_message('debug', "Updated session: " . json_encode($updatedSession));
                    
                    return $this->response->setJSON([
                        'success' => true,
                        'finished' => false,
                        'current_question_id' => $firstQuestion['id'],
                        'current_question_index' => 1,
                        'total_questions' => $total
                    ]);
                } else {
                    log_message('error', "Empty update data for session $sessionId");
                    return $this->response->setJSON(['success' => false, 'message' => 'Aucune donnée à mettre à jour']);
                }
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Aucune question trouvée pour ce quiz']);
            }
        }
        
        // Trouver l'index de la question courante
        $currentIndex = -1;
        foreach ($questions as $i => $q) {
            if ($q['id'] == $currentQuestionId) {
                $currentIndex = $i;
                break;
            }
        }
        
        log_message('debug', "Current index: $currentIndex, Total questions: $total");
        
        // Passer à la question suivante
        $nextIndex = $currentIndex + 1;
        log_message('debug', "Next index: $nextIndex");
        
        // Vérifier si c'est la dernière question
        if ($nextIndex >= $total) {
            log_message('debug', "Quiz finished - finalizing scores");
            
            // Finaliser les scores de tous les participants
            $this->scoreModel->finalizeLiveQuiz($quiz_id);
            
            // Désactiver la session
            $quizSessionModel = new \App\Models\QuizSessionModel();
            $quizSessionModel->where('quiz_id', $quiz_id)->set(['is_active' => 0])->update();
            
            // Désactiver le mode live sur le quiz
            $this->quizModel->update($quiz_id, ['is_live' => 0]);
            
            return $this->response->setJSON([
                'success' => true,
                'finished' => true,
                'message' => 'Quiz terminé ! Tous les scores ont été finalisés.'
            ]);
        }
        
        log_message('debug', "Moving to next question: index $nextIndex");
        $nextQuestion = $questions[$nextIndex];
        
        // Vérifier que la session existe et est active
        $quizSessionModel = new \App\Models\QuizSessionModel();
        $existingSession = $quizSessionModel->where('quiz_id', $quiz_id)->where('is_active', 1)->first();
        
        log_message('debug', "Existing session before next question: " . json_encode($existingSession));
        
        if (!$existingSession) {
            log_message('error', "No active session found for quiz_id: $quiz_id");
            return $this->response->setJSON(['success' => false, 'message' => 'Aucune session active trouvée']);
        }
        
        $sessionId = $existingSession['id'];
        
        // Mettre à jour la session
        $updateData = [
            'current_question_id' => $nextQuestion['id']
        ];
        
        if (!empty($updateData)) {
            $result = $quizSessionModel->update($sessionId, $updateData);
            
            log_message('debug', "Next question update result: " . $result);
            log_message('debug', "Next question update data: " . json_encode($updateData));
            
            // Vérifier que la mise à jour a bien fonctionné
            $updatedSession = $quizSessionModel->find($sessionId);
            log_message('debug', "Updated session after next question: " . json_encode($updatedSession));
            
            return $this->response->setJSON([
                'success' => true,
                'finished' => false,
                'current_question_id' => $nextQuestion['id'],
                'current_question_index' => $nextIndex + 1, // pour affichage humain (1-based)
                'total_questions' => $total
            ]);
        } else {
            log_message('error', "Empty update data for session $sessionId");
            return $this->response->setJSON(['success' => false, 'message' => 'Aucune donnée à mettre à jour']);
        }
    }
    
    // API pour les participants en temps réel
    public function getLiveParticipants()
    {
        
        
        $quiz_id = $this->request->getGet('quiz_id');
        
        $participants = $this->scoreModel->getLiveParticipants($quiz_id);
        
        return $this->response->setJSON([
            'success' => true,
            'participants' => $participants,
            'count' => count($participants)
        ]);
    }
    
    // API pour update score
    public function updateParticipantScore()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Requête invalide']);
        }
        
        $user_id = $this->request->getPost('user_id');
        $quiz_id = $this->request->getPost('quiz_id');
        $score = $this->request->getPost('score');
        
        $result = $this->scoreModel->updateScore($user_id, $quiz_id, $score);
        
        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'Score mis à jour' : 'Erreur lors de la mise à jour du score'
        ]);
    }
    
    // Arrêter un quiz en direct
    public function stopLiveQuiz($id)
    {
        $quiz = $this->quizModel->find($id);
        
        if (!$quiz) {
            return redirect()->to('admin/quizzes')->with('error', 'Quiz non trouvé');
        }
        
        // Finaliser les scores de tous les participants avant d'arrêter
        $this->scoreModel->finalizeLiveQuiz($id);
        
        // Désactiver la session live
        $quizSessionModel = new \App\Models\QuizSessionModel();
        $quizSessionModel->where('quiz_id', $id)->where('is_active', 1)->set(['is_active' => 0])->update();
        
        // Désactiver le mode live sur le quiz
        $this->quizModel->update($id, ['is_live' => 0]);
        
        return redirect()->to('admin/quizzes')->with('message', 'Quiz arrêté et scores finalisés avec succès');
    }
    
    // Liste des utilisateurs
    public function users()
    {
        $data = [
            'title' => 'Gestion des utilisateurs',
            'users' => $this->userModel->findAll(),
            'admins' => $this->userModel->getAdmins()
        ];

        return view('admin/templates/header', $data)
            . view('admin/users/index')
            . view('admin/templates/footer');
    }
    
    // Promouvoir un user en admin
    public function setAdmin($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'Utilisateur non trouvé');
        }
        
        $this->userModel->update($id, ['role' => 'admin']);
        
        return redirect()->to('admin/users')->with('message', 'Utilisateur défini comme administrateur');
    }
    
    // Rétrograder un admin en user
    public function removeAdmin($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'Utilisateur non trouvé');
        }
        
        // On vérifie qu'il reste un admin au moins
        $admins = $this->userModel->getAdmins();
        if (count($admins) <= 1 && $user['role'] === 'admin') {
            return redirect()->to('admin/users')->with('error', 'Impossible de supprimer le dernier administrateur');
        }
        
        $this->userModel->update($id, ['role' => 'user']);
        
        return redirect()->to('admin/users')->with('message', 'Droits d\'administrateur supprimés');
    }
    
    // Supprimer un utilisateur
    public function deleteUser($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'Utilisateur non trouvé');
        }
        
        // Empêcher la suppression de soi-même
        if ($user['id'] == session()->get('user_id')) {
            return redirect()->to('admin/users')->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }
        
        // Vérifier qu'il reste au moins un administrateur si on supprime un admin
        if ($user['role'] === 'admin') {
            $admins = $this->userModel->getAdmins();
            if (count($admins) <= 1) {
                return redirect()->to('admin/users')->with('error', 'Impossible de supprimer le dernier administrateur');
            }
        }
        
        // Supprimer les scores de l'utilisateur
        $this->scoreModel->where('user_id', $id)->delete();
        
        // Supprimer l'utilisateur
        $this->userModel->delete($id);
        
        return redirect()->to('admin/users')->with('message', 'Utilisateur supprimé avec succès');
    }
    
    // Stats diverses
    public function statistics()
    {
        // TODO: ajouter des graphiques avec Chart.js
        $data = [
            'title' => 'Statistiques',
            'totalQuizzes' => $this->quizModel->countAll(),
            'totalUsers' => $this->userModel->countAll(),
            'totalParticipations' => $this->scoreModel->countAll(),
            'popularQuizzes' => $this->scoreModel->getPopularQuizzes(5),
            'topScorers' => $this->scoreModel->getTopScorers(5)
        ];

        return view('admin/templates/header', $data)
            . view('admin/statistics')
            . view('admin/templates/footer');
    }

    // Réinitialiser la session live du quiz
    public function resetLiveSession()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Requête invalide']);
        }
        $quiz_id = $this->request->getPost('quiz_id');
        if (!$quiz_id) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Quiz ID manquant']);
        }
        
        // Récupérer la première question du quiz
        $quiz = $this->quizModel->getQuizWithQuestions($quiz_id);
        $questions = $quiz['questions'];
        $firstQuestion = null;
        
        if (!empty($questions)) {
            $firstQuestion = $questions[0];
        }
        
        $quizSessionModel = new \App\Models\QuizSessionModel();
        // Supprimer les anciennes sessions
        $quizSessionModel->where('quiz_id', $quiz_id)->delete();
        
        // Créer une nouvelle session avec la première question
        $sessionData = [
            'quiz_id' => $quiz_id,
            'current_question_id' => $firstQuestion ? $firstQuestion['id'] : null,
            'is_active' => 1,
            'start_time' => date('Y-m-d H:i:s')
        ];
        
        $quizSessionModel->insert($sessionData);
        log_message('debug', "Session live réinitialisée pour quiz $quiz_id");
        return $this->response->setJSON(['success' => true, 'message' => 'Session réinitialisée']);
    }
}