<?php

namespace App\Controllers;

use App\Models\LeaderboardModel;
use App\Models\QuizModel;

class Leaderboard extends BaseController
{
    protected $leaderboardModel;
    protected $quizModel;

    public function __construct()
    {
        $this->leaderboardModel = new LeaderboardModel();
        $this->quizModel = new QuizModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Leaderboard',
            'quizzes' => $this->quizModel->findAll(),
            'leaderboard' => $this->leaderboardModel->getTopScores(100),
            'filter_quiz' => null
        ];

        return view('templates/header', $data)
            . view('leaderboard')
            . view('templates/footer');
    }

    public function filter($quizId)
    {
        $quiz = $this->quizModel->find($quizId);

        if (!$quiz) {
            return redirect()->to('leaderboard');
        }

        $data = [
            'title' => $quiz['title'] . ' Leaderboard',
            'quizzes' => $this->quizModel->findAll(),
            'leaderboard' => $this->leaderboardModel->getTopScoresByQuiz($quizId, 100),
            'filter_quiz' => $quizId
        ];

        return view('templates/header', $data)
            . view('leaderboard')
            . view('templates/footer');
    }
}