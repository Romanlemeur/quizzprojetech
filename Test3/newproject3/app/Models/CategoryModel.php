<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = ['name', 'description', 'icon'];
    
    protected $useTimestamps = false;
    
    // Compte les quiz dans une catégorie
    public function getQuizCount($cat_id)
    {
        $quizModel = new QuizModel();
        return $quizModel->where('category_id', $cat_id)->countAllResults();
    }

    // Récupère les catégories avec le nombre de quiz
    public function getCategoriesWithQuizCount()
    {
        return $this->select('categories.*, COUNT(quizzes.id) as quiz_count')
                    ->join('quizzes', 'quizzes.category_id = categories.id', 'left')
                    ->groupBy('categories.id')
                    ->findAll();
    }
}