<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaderboardModel extends Model
{
    // Récupérer le classement global (incluant les scores live et classiques)
    public function getGlobalLeaderboard($limit = null)
    {
        $db = \Config\Database::connect();
        
        // Utiliser la table user_scores pour tous les types de scores
        $builder = $db->table('user_scores')
                    ->select('user_scores.id, user_scores.score, user_scores.completed_at, user_scores.is_live, users.username, quizzes.title as quiz_title')
                    ->join('users', 'users.id = user_scores.user_id')
                    ->join('quizzes', 'quizzes.id = user_scores.quiz_id')
                    ->where('user_scores.completed_at IS NOT NULL') // Seulement les quiz terminés
                    ->orderBy('user_scores.score', 'DESC')
                    ->orderBy('user_scores.completed_at', 'ASC');
        
        if ($limit !== null) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
    
    // Récupère le classement pour un quiz spécifique (incluant les scores live)
    public function getQuizLeaderboard($quiz_id, $limit = 10)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('user_scores')
                    ->select('users.id, users.username, user_scores.score, user_scores.completed_at, user_scores.is_live')
                    ->join('users', 'users.id = user_scores.user_id')
                    ->where('user_scores.quiz_id', $quiz_id)
                    ->where('user_scores.completed_at IS NOT NULL') // Seulement les quiz terminés
                    ->orderBy('user_scores.score', 'DESC')
                    ->orderBy('user_scores.completed_at', 'ASC');
        
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }
    
    // Récupérer le classement des quiz live terminés
    public function getLiveQuizLeaderboard($quiz_id, $limit = 10)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('user_scores')
                    ->select('users.id, users.username, user_scores.score, user_scores.completed_at')
                    ->join('users', 'users.id = user_scores.user_id')
                    ->where('user_scores.quiz_id', $quiz_id)
                    ->where('user_scores.is_live', 1)
                    ->where('user_scores.completed_at IS NOT NULL')
                    ->orderBy('user_scores.score', 'DESC')
                    ->orderBy('user_scores.completed_at', 'ASC');
        
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }
    
    // Récupérer le classement des quiz classiques uniquement
    public function getClassicQuizLeaderboard($quiz_id, $limit = 10)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('user_scores')
                    ->select('users.id, users.username, user_scores.score, user_scores.completed_at')
                    ->join('users', 'users.id = user_scores.user_id')
                    ->where('user_scores.quiz_id', $quiz_id)
                    ->where('user_scores.is_live', 0)
                    ->where('user_scores.completed_at IS NOT NULL')
                    ->orderBy('user_scores.score', 'DESC')
                    ->orderBy('user_scores.completed_at', 'ASC');
        
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }
}