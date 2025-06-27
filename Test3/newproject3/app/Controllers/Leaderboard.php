<?php

namespace App\Controllers;

use App\Models\LeaderboardModel;
use App\Models\QuizModel;
use App\Models\UserModel;
use App\Models\ScoreModel;

class Leaderboard extends BaseController
{
    protected $leaderboardModel;
    protected $quizModel;
    protected $userModel;
    protected $scoreModel;

    public function __construct()
    {
        $this->leaderboardModel = new LeaderboardModel();
        $this->quizModel = new QuizModel();
        $this->userModel = new UserModel();
        $this->scoreModel = new ScoreModel();
    }

    // Classement global
    public function index()
    {
        $data = [
            'title' => 'Classement général',
            'leaderboard' => $this->leaderboardModel->getGlobalLeaderboard(),
            'quizzes' => $this->quizModel->findAll(), // pour le filtre
            'filter_quiz' => null
        ];

        return view('templates/header', $data)
            . view('leaderboard')
            . view('templates/footer');
    }

    // Classement pour un quiz spécifique
    public function quizLeaderboard($quiz_id)
    {
        $quiz = $this->quizModel->find($quiz_id);

        if (!$quiz) {
            return redirect()->to('leaderboard');
        }

        // Récupérer les scores pour ce quiz (incluant les scores live)
        $data = [
            'title' => 'Classement: ' . $quiz['title'],
            'quiz' => $quiz,
            'leaderboard' => $this->leaderboardModel->getQuizLeaderboard($quiz_id),
            'liveLeaderboard' => $this->leaderboardModel->getLiveQuizLeaderboard($quiz_id),
            'classicLeaderboard' => $this->leaderboardModel->getClassicQuizLeaderboard($quiz_id),
            'quizzes' => $this->quizModel->findAll(), // pour le filtre
            'filter_quiz' => $quiz_id
        ];

        return view('templates/header', $data)
            . view('leaderboard')
            . view('templates/footer');
    }
    
    // Filtre par quiz (route alternative compatible avec le formulaire)
    public function filter()
    {
        $quiz_id = $this->request->getGet('quiz_id');
        
        if ($quiz_id) {
            return redirect()->to("leaderboard/quiz/$quiz_id");
        }
        
        return redirect()->to('leaderboard');
    }
    
    // Profil et historique utilisateur (intégré depuis leaderboard.php)
    public function profile()
    {
        if (!is_logged_in()) {
            return redirect()->to('login?redirect=profile');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/');
        }
        
        // Récupérer l'historique des quiz
        $history = $this->scoreModel->getUserScores($userId);
        
        // Récupérer les meilleurs joueurs pour l'affichage
        $topPlayers = $this->scoreModel->getLeaderboard(null, 9); // Limité à 9 joueurs
        
        $data = [
            'title' => 'Mon Profil',
            'user' => $user,
            'history' => $history,
            'topPlayers' => $topPlayers,
            'message' => session()->getFlashdata('message')
        ];
        
        // Traiter la mise à jour du profil si besoin
        if ($this->request->getMethod() === 'post') {
            $username = $this->request->getPost('username');
            
            if (!empty($username) && $username !== $user['username']) {
                // Vérifier si le nom est unique
                $existingUser = $this->userModel->where('username', $username)->first();
                
                if (!$existingUser) {
                    $this->userModel->update($userId, ['username' => $username]);
                    session()->set('username', $username);
                    $data['message'] = "Nom d'utilisateur mis à jour !";
                } else {
                    $data['error'] = "Ce nom d'utilisateur est déjà pris.";
                }
            }
        }

        return view('templates/header', $data)
            . view('auth/profile_extended')
            . view('templates/footer');
    }
}