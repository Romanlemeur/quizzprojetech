<?php

namespace App\Models;

use CodeIgniter\Model;

class ScoreModel extends Model
{
    protected $table = 'user_scores';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = ['user_id', 'quiz_id', 'score', 'completed_at'];
    
    protected $useTimestamps = false;
    
    /**
     * Get user's quiz history
     * 
     * @param int $userId
     * @return array
     */
    public function getUserQuizHistory($userId)
    {
        return $this->select('user_scores.*, quizzes.title as quiz_title')
                    ->join('quizzes', 'quizzes.id = user_scores.quiz_id')
                    ->where('user_scores.user_id', $userId)
                    ->orderBy('user_scores.completed_at', 'DESC')
                    ->findAll();
    }
}