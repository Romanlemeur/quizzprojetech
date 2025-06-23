<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = ['username', 'email', 'password', 'role', 'created_at'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = '';
    
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Vérifie mail
    public function emailExists($email)
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }
    
    // Vérifie pseudo
    public function usernameExists($username)
    {
        return $this->where('username', $username)->countAllResults() > 0;
    }
    
    // Récupérer l'historique des quiz d'un user
    public function getUserQuizHistory($userId)
    {
        $db = \Config\Database::connect();
        
        // Vérifier si la table 'scores' existe
        $scoresTableExists = $db->tableExists('scores');
        
        if ($scoresTableExists) {
            // Utiliser la table scores
            $builder = $db->table('scores');
            $builder->select('scores.*, quizzes.title as quiz_title, categories.name as category_name');
            $builder->join('quizzes', 'quizzes.id = scores.quiz_id');
            $builder->join('categories', 'categories.id = quizzes.category_id');
            $builder->where('scores.user_id', $userId);
            $builder->orderBy('scores.completed_at', 'DESC');
        } else {
            // Utiliser la table user_scores
            $builder = $db->table('user_scores');
            $builder->select('user_scores.*, quizzes.title as quiz_title, categories.name as category_name');
            $builder->join('quizzes', 'quizzes.id = user_scores.quiz_id');
            $builder->join('categories', 'categories.id = quizzes.category_id');
            $builder->where('user_scores.user_id', $userId);
            $builder->orderBy('user_scores.completed_at', 'DESC');
        }
        
        return $builder->get()->getResultArray();
    }
    
    // Liste des admins
    public function getAdmins()
    {
        return $this->where('role', 'admin')->findAll();
    }
}