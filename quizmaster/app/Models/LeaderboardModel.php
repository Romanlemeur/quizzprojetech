<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaderboardModel extends Model
{
    protected $table = 'user_scores';
    
    /**
     * Get top scores for all quizzes
     * 
     * @param int $limit
     * @return array
     */
    public function getTopScores($limit = 10)
    {
        return $this->select('user_scores.*, users.username, quizzes.title as quiz_title, quizzes.id as quiz_id')
                    ->join('users', 'users.id = user_scores.user_id')
                    ->join('quizzes', 'quizzes.id = user_scores.quiz_id')
                    ->orderBy('user_scores.score', 'DESC')
                    ->orderBy('user_scores.completed_at', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }
    
    /**
     * Get top scores for a specific quiz
     * 
     * @param int $quizId
     * @param int $limit
     * @return array
     */
    public function getTopScoresByQuiz($quizId, $limit = 10)
    {
        return $this->select('user_scores.*, users.username, quizzes.title as quiz_title, quizzes.id as quiz_id')
                    ->join('users', 'users.id = user_scores.user_id')
                    ->join('quizzes', 'quizzes.id = user_scores.quiz_id')
                    ->where('user_scores.quiz_id', $quizId)
                    ->orderBy('user_scores.score', 'DESC')
                    ->orderBy('user_scores.completed_at', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }
}