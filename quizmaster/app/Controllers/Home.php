<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\QuizModel;
use App\Models\LeaderboardModel;

class Home extends BaseController
{
    protected $categoryModel;
    protected $quizModel;
    protected $leaderboardModel;
    
    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->quizModel = new QuizModel();
        $this->leaderboardModel = new LeaderboardModel();
    }
    
    // Page d'accueil
    public function index()
    {
        // Récupérer les catégories populaires
        $categories = $this->categoryModel->findAll(3);
        
        // Récupérer les quiz du moment
        $currentQuizzes = $this->quizModel->getPopularQuizzes(3);
        
        // Récupérer le classement des meilleurs joueurs
        $topPlayers = $this->leaderboardModel->getGlobalLeaderboard(5);
        
        // Récupérer le quiz en direct (si disponible)
        $liveQuiz = $this->quizModel->getLiveQuiz();
        
        $data = [
            'title' => 'Accueil',
            'categories' => $categories,
            'currentQuizzes' => $currentQuizzes,
            'topPlayers' => $topPlayers,
            'isLoggedIn' => is_logged_in(),
            'liveQuiz' => $liveQuiz
        ];
        
        return view('templates/header', $data)
            . view('home')
            . view('templates/footer');
    }
}