<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizModel extends Model
{
    protected $table = 'quizzes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = ['category_id', 'title', 'description', 'time_limit'];
    
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    
    /**
     * Get quiz with category name
     * 
     * @param int $quizId
     * @return array
     */
    public function getQuizWithCategory($quizId)
    {
        return $this->select('quizzes.*, categories.name as category_name')
                    ->join('categories', 'categories.id = quizzes.category_id')
                    ->where('quizzes.id', $quizId)
                    ->first();
    }
}