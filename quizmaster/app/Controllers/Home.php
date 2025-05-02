<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\LeaderboardModel;

class Home extends BaseController
{
    protected $categoryModel;
    protected $leaderboardModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->leaderboardModel = new LeaderboardModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Home',
            'categories' => $this->categoryModel->findAll(),
            'leaderboard' => $this->leaderboardModel->getTopScores(5)
        ];

        return view('templates/header', $data)
            . view('home')
            . view('templates/footer');
    }
}