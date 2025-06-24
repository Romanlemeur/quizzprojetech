<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UtilisateurModel;
use App\Models\QuizModel;
use App\Models\QuestionModel;
use App\Models\ReponseModel;
use App\Models\SessionQuizModel;
use App\Models\ParticipationModel;
use App\Models\ReponseJoueurModel;

class Admin extends BaseController
{
    protected $utilisateurModel;
    protected $quizModel;
    protected $questionModel;
    protected $reponseModel;
    protected $sessionQuizModel;
    protected $participationModel;
    protected $reponseJoueurModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->quizModel = new QuizModel();
        $this->questionModel = new QuestionModel();
        $this->reponseModel = new ReponseModel();
        $this->sessionQuizModel = new SessionQuizModel();
        $this->participationModel = new ParticipationModel();
        $this->reponseJoueurModel = new ReponseJoueurModel();
    }

    public function index()
    {
        //
    }

    public function dashboard()
    {
        // Vérifier l'authentification admin
        $auth = new Auth();
        $auth->requireAdmin();

        $session = session();
        $idAdmin = $session->get('idUtilisateur');

        $data = [
            'quiz' => $this->quizModel->getQuizByAdmin($idAdmin),
            'sessionActive' => $this->sessionQuizModel->getSessionActive(),
            'participants' => []
        ];

        if ($data['sessionActive']) {
            $data['participants'] = $this->participationModel->getParticipantsBySession($data['sessionActive']['idSession']);
        }

        return view('admin/dashboard', $data);
    }

    // Gestion des administrateurs
    public function admins()
    {
        $auth = new Auth();
        $auth->requireAdmin();

        $data['admins'] = $this->utilisateurModel->getAdmins();
        return view('admin/admins', $data);
    }

    public function addAdmin()
    {
        $auth = new Auth();
        $auth->requireAdmin();

        if ($this->request->getMethod() === 'post') {
            $data = [
                'email' => $this->request->getPost('email'),
                'motDePasse' => $this->request->getPost('password'),
                'pseudo' => $this->request->getPost('pseudo'),
                'role' => 'admin'
            ];

            if ($this->utilisateurModel->save($data)) {
                return redirect()->to('/admin/admins')->with('success', 'Administrateur ajouté avec succès');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->utilisateurModel->errors());
            }
        }

        return view('admin/add_admin');
    }

    public function deleteAdmin($id)
    {
        $auth = new Auth();
        $auth->requireAdmin();

        $session = session();
        if ($id == $session->get('idUtilisateur')) {
            return redirect()->to('/admin/admins')->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        if ($this->utilisateurModel->delete($id)) {
            return redirect()->to('/admin/admins')->with('success', 'Administrateur supprimé avec succès');
        } else {
            return redirect()->to('/admin/admins')->with('error', 'Erreur lors de la suppression');
        }
    }

    // Gestion des quiz
    public function quiz()
    {
        $auth = new Auth();
        $auth->requireAdmin();

        $session = session();
        $idAdmin = $session->get('idUtilisateur');

        $data['quiz'] = $this->quizModel->getQuizWithAdmin();
        return view('admin/quiz', $data);
    }

    public function addQuiz()
    {
        $auth = new Auth();
        $auth->requireAdmin();

        if ($this->request->getMethod() === 'post') {
            $session = session();
            $idAdmin = $session->get('idUtilisateur');

            $data = [
                'nomQuiz' => $this->request->getPost('nomQuiz'),
                'description' => $this->request->getPost('description'),
                'pointsParBonneReponse' => $this->request->getPost('pointsParBonneReponse'),
                'idAdmin' => $idAdmin
            ];

            // Gestion de l'upload d'image
            $image = $this->request->getFile('image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $data['image'] = $this->quizModel->uploadImage($image);
            }

            if ($this->quizModel->save($data)) {
                return redirect()->to('/admin/quiz')->with('success', 'Quiz créé avec succès');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->quizModel->errors());
            }
        }

        return view('admin/add_quiz');
    }

    public function editQuiz($id)
    {
        $auth = new Auth();
        $auth->requireAdmin();

        $quiz = $this->quizModel->find($id);
        if (!$quiz) {
            return redirect()->to('/admin/quiz')->with('error', 'Quiz non trouvé');
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'idQuiz' => $id,
                'nomQuiz' => $this->request->getPost('nomQuiz'),
                'description' => $this->request->getPost('description'),
                'pointsParBonneReponse' => $this->request->getPost('pointsParBonneReponse')
            ];

            // Gestion de l'upload d'image
            $image = $this->request->getFile('image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                // Supprimer l'ancienne image
                if ($quiz['image']) {
                    $this->quizModel->deleteImage($quiz['image']);
                }
                $data['image'] = $this->quizModel->uploadImage($image);
            }

            if ($this->quizModel->save($data)) {
                return redirect()->to('/admin/quiz')->with('success', 'Quiz modifié avec succès');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->quizModel->errors());
            }
        }

        $data['quiz'] = $quiz;
        return view('admin/edit_quiz', $data);
    }

    public function deleteQuiz($id)
    {
        $auth = new Auth();
        $auth->requireAdmin();

        $quiz = $this->quizModel->find($id);
        if ($quiz && $quiz['image']) {
            $this->quizModel->deleteImage($quiz['image']);
        }

        if ($this->quizModel->delete($id)) {
            return redirect()->to('/admin/quiz')->with('success', 'Quiz supprimé avec succès');
        } else {
            return redirect()->to('/admin/quiz')->with('error', 'Erreur lors de la suppression');
        }
    }

    // Gestion des questions
    public function questions($idQuiz)
    {
        $auth = new Auth();
        $auth->requireAdmin();

        $quiz = $this->quizModel->find($idQuiz);
        if (!$quiz) {
            return redirect()->to('/admin/quiz')->with('error', 'Quiz non trouvé');
        }

        $data = [
            'quiz' => $quiz,
            'questions' => $this->questionModel->getQuestionsWithReponses($idQuiz)
        ];

        return view('admin/questions', $data);
    }

    public function addQuestion($idQuiz)
    {
        $auth = new Auth();
        $auth->requireAdmin();

        if ($this->request->getMethod() === 'post') {
            // Sauvegarder la question
            $questionData = [
                'libelle' => $this->request->getPost('libelle'),
                'idQuiz' => $idQuiz
            ];

            if ($this->questionModel->save($questionData)) {
                $idQuestion = $this->questionModel->insertID();

                // Sauvegarder les réponses
                $reponses = $this->request->getPost('reponses');
                $bonneReponse = $this->request->getPost('bonneReponse');

                foreach ($reponses as $index => $texte) {
                    if (!empty($texte)) {
                        $reponseData = [
                            'idQuestion' => $idQuestion,
                            'texte' => $texte,
                            'estBonne' => ($index == $bonneReponse) ? 1 : 0
                        ];
                        $this->reponseModel->save($reponseData);
                    }
                }

                return redirect()->to("/admin/questions/$idQuiz")->with('success', 'Question ajoutée avec succès');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->questionModel->errors());
            }
        }

        $data['quiz'] = $this->quizModel->find($idQuiz);
        return view('admin/add_question', $data);
    }

    public function deleteQuestion($idQuestion)
    {
        $auth = new Auth();
        $auth->requireAdmin();

        $question = $this->questionModel->find($idQuestion);
        if (!$question) {
            return redirect()->to('/admin/quiz')->with('error', 'Question non trouvée');
        }

        // Supprimer les réponses associées
        $this->reponseModel->deleteReponsesByQuestion($idQuestion);

        if ($this->questionModel->delete($idQuestion)) {
            return redirect()->to("/admin/questions/{$question['idQuiz']}")->with('success', 'Question supprimée avec succès');
        } else {
            return redirect()->to("/admin/questions/{$question['idQuiz']}")->with('error', 'Erreur lors de la suppression');
        }
    }

    // Gestion des sessions
    public function startSession($idQuiz)
    {
        $auth = new Auth();
        $auth->requireAdmin();

        if ($this->sessionQuizModel->startSession($idQuiz)) {
            return redirect()->to('/admin/dashboard')->with('success', 'Session de quiz démarrée');
        } else {
            return redirect()->to('/admin/dashboard')->with('error', 'Erreur lors du démarrage de la session');
        }
    }

    public function stopSession($idSession)
    {
        $auth = new Auth();
        $auth->requireAdmin();

        if ($this->sessionQuizModel->stopSession($idSession)) {
            return redirect()->to('/admin/dashboard')->with('success', 'Session de quiz arrêtée');
        } else {
            return redirect()->to('/admin/dashboard')->with('error', 'Erreur lors de l\'arrêt de la session');
        }
    }

    public function liveSession($idSession)
    {
        $auth = new Auth();
        $auth->requireAdmin();

        $session = $this->sessionQuizModel->getSessionWithQuiz($idSession);
        if (!$session) {
            return redirect()->to('/admin/dashboard')->with('error', 'Session non trouvée');
        }

        $data = [
            'session' => $session,
            'participants' => $this->participationModel->getParticipantsBySession($idSession),
            'questions' => $this->questionModel->getQuestionsOrdered($session['idQuiz'])
        ];

        return view('admin/live_session', $data);
    }

    public function updateScore()
    {
        $auth = new Auth();
        $auth->requireAdmin();

        if ($this->request->getMethod() === 'post') {
            $idParticipation = $this->request->getPost('idParticipation');
            $newScore = $this->request->getPost('score');

            if ($this->participationModel->updateScore($idParticipation, $newScore)) {
                return redirect()->back()->with('success', 'Score mis à jour');
            } else {
                return redirect()->back()->with('error', 'Erreur lors de la mise à jour du score');
            }
        }

        return redirect()->back();
    }

    public function nextQuestion($idSession)
    {
        $auth = new Auth();
        $auth->requireAdmin();

        // Logique pour passer à la question suivante
        // Cette fonctionnalité sera implémentée plus tard
        return redirect()->back()->with('success', 'Question suivante');
    }
}
