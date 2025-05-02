<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = ['name', 'description', 'image'];
    
    protected $useTimestamps = false;
    
    /**
     * Get quiz count for a category
     * 
     * @param int $categoryId
     * @return int
     */
    public function getQuizCount($categoryId)
    {
        $quizModel = new QuizModel();
        return $quizModel->where('category_id', $categoryId)->countAllResults();
    }
}