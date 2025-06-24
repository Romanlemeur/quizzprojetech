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

class Joueur extends BaseController
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
        // Vérifier l'authentification joueur
        $auth = new Auth();
        $auth->requireJoueur();

        $session = session();
        $idJoueur = $session->get('idUtilisateur');

        $data = [
            'sessionActive' => $this->sessionQuizModel->getSessionActive(),
            'participation' => null
        ];

        if ($data['sessionActive']) {
            $data['participation'] = $this->participationModel->getParticipationByUserAndSession(
                $idJoueur, 
                $data['sessionActive']['idSession']
            );
        }

        return view('joueur/dashboard', $data);
    }

    public function joinSession($idSession)
    {
        $auth = new Auth();
        $auth->requireJoueur();

        $session = session();
        $idJoueur = $session->get('idUtilisateur');

        // Vérifier que la session existe et est active
        $sessionQuiz = $this->sessionQuizModel->find($idSession);
        if (!$sessionQuiz || !$sessionQuiz['enCours']) {
            return redirect()->to('/joueur/dashboard')->with('error', 'Session non disponible');
        }

        // Rejoindre la session
        $idParticipation = $this->participationModel->joinSession($idJoueur, $idSession);

        if ($idParticipation) {
            return redirect()->to("/joueur/play/$idSession")->with('success', 'Vous avez rejoint la session');
        } else {
            return redirect()->to('/joueur/dashboard')->with('error', 'Erreur lors de la connexion à la session');
        }
    }

    public function play($idSession)
    {
        $auth = new Auth();
        $auth->requireJoueur();

        $session = session();
        $idJoueur = $session->get('idUtilisateur');

        // Vérifier que la session existe et est active
        $sessionQuiz = $this->sessionQuizModel->getSessionWithQuiz($idSession);
        if (!$sessionQuiz || !$sessionQuiz['enCours']) {
            return redirect()->to('/joueur/dashboard')->with('error', 'Session non disponible');
        }

        // Vérifier que le joueur participe à cette session
        $participation = $this->participationModel->getParticipationByUserAndSession($idJoueur, $idSession);
        if (!$participation) {
            return redirect()->to('/joueur/dashboard')->with('error', 'Vous ne participez pas à cette session');
        }

        // Récupérer les questions du quiz
        $questions = $this->questionModel->getQuestionsOrdered($sessionQuiz['idQuiz']);
        
        // Déterminer la question actuelle (pour l'instant, on commence par la première)
        $questionActuelle = null;
        $questionIndex = 0;
        
        if (!empty($questions)) {
            $questionActuelle = $this->questionModel->getQuestionWithReponses($questions[0]['idQuestion']);
        }

        $data = [
            'session' => $sessionQuiz,
            'participation' => $participation,
            'questionActuelle' => $questionActuelle,
            'questionIndex' => $questionIndex,
            'totalQuestions' => count($questions),
            'hasRepondu' => $questionActuelle ? $this->reponseJoueurModel->hasRepondu($participation['idParticipation'], $questionActuelle['idQuestion']) : false
        ];

        return view('joueur/play', $data);
    }

    public function answer()
    {
        $auth = new Auth();
        $auth->requireJoueur();

        if ($this->request->getMethod() !== 'post') {
            return redirect()->back();
        }

        $session = session();
        $idJoueur = $session->get('idUtilisateur');

        $idParticipation = $this->request->getPost('idParticipation');
        $idQuestion = $this->request->getPost('idQuestion');
        $idReponseChoisie = $this->request->getPost('reponse');
        $tempsReponse = $this->request->getPost('tempsReponse') ?? 5; // Par défaut 5 secondes

        // Vérifier que la participation appartient au joueur
        $participation = $this->participationModel->find($idParticipation);
        if (!$participation || $participation['idUtilisateur'] != $idJoueur) {
            return redirect()->back()->with('error', 'Accès non autorisé');
        }

        // Sauvegarder la réponse
        if ($this->reponseJoueurModel->saveReponse($idParticipation, $idQuestion, $idReponseChoisie, $tempsReponse)) {
            // Calculer le score
            $reponse = $this->reponseModel->find($idReponseChoisie);
            $question = $this->questionModel->find($idQuestion);
            $quiz = $this->quizModel->find($question['idQuiz']);
            
            $pointsGagnes = 0;
            if ($reponse && $reponse['estBonne']) {
                $pointsGagnes = $quiz['pointsParBonneReponse'];
            }

            // Mettre à jour le score
            $nouveauScore = $participation['scoreActuel'] + $pointsGagnes;
            $this->participationModel->updateScore($idParticipation, $nouveauScore);

            return redirect()->back()->with('success', 'Réponse enregistrée ! Points gagnés : ' . $pointsGagnes);
        } else {
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement de la réponse');
        }
    }

    public function results($idSession)
    {
        $auth = new Auth();
        $auth->requireJoueur();

        $session = session();
        $idJoueur = $session->get('idUtilisateur');

        // Vérifier que la session existe
        $sessionQuiz = $this->sessionQuizModel->getSessionWithQuiz($idSession);
        if (!$sessionQuiz) {
            return redirect()->to('/joueur/dashboard')->with('error', 'Session non trouvée');
        }

        // Vérifier que le joueur participe à cette session
        $participation = $this->participationModel->getParticipationByUserAndSession($idJoueur, $idSession);
        if (!$participation) {
            return redirect()->to('/joueur/dashboard')->with('error', 'Vous ne participez pas à cette session');
        }

        $data = [
            'session' => $sessionQuiz,
            'participation' => $participation,
            'reponses' => $this->reponseJoueurModel->getReponsesByParticipation($participation['idParticipation']),
            'classement' => $this->participationModel->getClassementBySession($idSession)
        ];

        return view('joueur/results', $data);
    }

    public function profile()
    {
        $auth = new Auth();
        $auth->requireJoueur();

        $session = session();
        $idJoueur = $session->get('idUtilisateur');

        $data['joueur'] = $this->utilisateurModel->find($idJoueur);
        $data['participations'] = $this->participationModel->where('idUtilisateur', $idJoueur)->findAll();

        return view('joueur/profile', $data);
    }

    public function updateProfile()
    {
        $auth = new Auth();
        $auth->requireJoueur();

        if ($this->request->getMethod() === 'post') {
            $session = session();
            $idJoueur = $session->get('idUtilisateur');

            $data = [
                'idUtilisateur' => $idJoueur,
                'pseudo' => $this->request->getPost('pseudo'),
                'email' => $this->request->getPost('email')
            ];

            // Si un nouveau mot de passe est fourni
            $nouveauMotDePasse = $this->request->getPost('nouveauMotDePasse');
            if (!empty($nouveauMotDePasse)) {
                $data['motDePasse'] = $nouveauMotDePasse;
            }

            if ($this->utilisateurModel->save($data)) {
                // Mettre à jour la session
                $session->set([
                    'email' => $data['email'],
                    'pseudo' => $data['pseudo']
                ]);

                return redirect()->to('/joueur/profile')->with('success', 'Profil mis à jour avec succès');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->utilisateurModel->errors());
            }
        }

        return redirect()->to('/joueur/profile');
    }
}
