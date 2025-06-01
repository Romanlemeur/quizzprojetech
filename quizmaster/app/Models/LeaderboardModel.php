<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaderboardModel extends Model
{
    // Récupérer le classement global
    public function getGlobalLeaderboard($limit = null)
    {
        $db = \Config\Database::connect();
        
        // Vérifier si la table 'scores' existe
        $scoresTableExists = $db->tableExists('scores');
        
        if ($scoresTableExists) {
            $builder = $db->table('scores')
                        ->select('scores.id, scores.score, scores.completed_at, users.username, quizzes.title as quiz_title')
                        ->join('users', 'users.id = scores.user_id')
                        ->join('quizzes', 'quizzes.id = scores.quiz_id')
                        ->orderBy('scores.score', 'DESC')
                        ->orderBy('scores.completed_at', 'ASC');
        } else {
            // Utiliser la table user_scores à la place
            $builder = $db->table('user_scores')
                        ->select('user_scores.id, user_scores.score, user_scores.completed_at, users.username, quizzes.title as quiz_title')
                        ->join('users', 'users.id = user_scores.user_id')
                        ->join('quizzes', 'quizzes.id = user_scores.quiz_id')
                        ->where('user_scores.is_live', 0) // uniquement les parties normales
                        ->orderBy('user_scores.score', 'DESC')
                        ->orderBy('user_scores.completed_at', 'ASC');
        }
        
        if ($limit !== null) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
    
    // Récupère le classement pour un quiz spécifique
    public function getQuizLeaderboard($quiz_id, $limit = 10)
    {
        $db = \Config\Database::connect();
        
        // Vérifier si la table 'scores' existe
        $scoresTableExists = $db->tableExists('scores');
        
        if ($scoresTableExists) {
            $builder = $db->table('scores')
                        ->select('users.id, users.username, scores.score, scores.completed_at')
                        ->join('users', 'users.id = scores.user_id')
                        ->where('scores.quiz_id', $quiz_id)
                        ->orderBy('scores.score', 'DESC');
        } else {
            // Utiliser la table user_scores à la place
            $builder = $db->table('user_scores')
                        ->select('users.id, users.username, user_scores.score, user_scores.completed_at')
                        ->join('users', 'users.id = user_scores.user_id')
                        ->where('user_scores.quiz_id', $quiz_id)
                        ->where('user_scores.is_live', 0) // uniquement les parties normales
                        ->orderBy('user_scores.score', 'DESC');
        }
        
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }
}