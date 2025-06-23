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
        
        // Mode direct
        $result = $this->quizModel->setQuizLive($id);
        
        if (!$result) {
            return redirect()->to('admin/quizzes')->with('error', 'Erreur lors du lancement du quiz en direct');
        }
        
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
        
        $current = $this->request->getPost('current_question');
        $quiz_id = $this->request->getPost('quiz_id');
        
        
        // Check si le quiz est en direct
        $liveQuiz = $this->quizModel->getLiveQuiz();
        if (!$liveQuiz || $liveQuiz['id'] != $quiz_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Quiz non trouvé ou non en direct1'.$quiz_id]);
        }
        
        // Update de la question courante
        $quiz = $this->quizModel->getQuizWithQuestions($quiz_id);
        $total = count($quiz['questions']);
        
        if ($current >= $total) {
            // Fin
            return $this->response->setJSON([
                'success' => true, 
                'finished' => true,
                'message' => 'Quiz terminé'
            ]);
        }
        
        $next = $current + 1;
        
        // Infos pour la suite
        return $this->response->setJSON([
            'success' => true,
            'finished' => false,
            'current_question' => $next,
            'total_questions' => $total
        ]);
    }
    
    // API pour les participants en temps réel
    public function getLiveParticipants()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Requête invalide']);
        }
        
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
        
        // On désactive le mode live
        $this->quizModel->update($id, ['is_live' => 0]);
        
        return redirect()->to('admin/quizzes')->with('message', 'Quiz arrêté avec succès');
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
} 