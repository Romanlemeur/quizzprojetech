<?php

namespace App\Models;

use CodeIgniter\Model;

class ScoreModel extends Model
{
    protected $table = 'user_scores';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = ['user_id', 'quiz_id', 'score', 'completed_at', 'is_live'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Historique des quiz d'un utilisateur
    public function getUserQuizHistory($userId)
    {
        return $this->select('user_scores.*, quizzes.title as quiz_title')
                    ->join('quizzes', 'quizzes.id = user_scores.quiz_id')
                    ->where('user_scores.user_id', $userId)
                    ->orderBy('user_scores.completed_at', 'DESC')
                    ->findAll();
    }
    
    // Tableau des meilleurs scores pour un quiz
    public function getLeaderboard($quiz_id = null, $limit = 10)
    {
        $db = \Config\Database::connect();
        
        // Vérifier si la table 'scores' existe
        $scoresTableExists = $db->tableExists('scores');
        
        if ($scoresTableExists) {
            $builder = $db->table('scores')
                        ->select('scores.*, users.username, quizzes.title as quiz_title')
                        ->join('users', 'users.id = scores.user_id')
                        ->join('quizzes', 'quizzes.id = scores.quiz_id');
            
            if ($quiz_id) {
                $builder->where('scores.quiz_id', $quiz_id);
            }
            
            $builder->orderBy('scores.score', 'DESC')
                    ->limit($limit);
        } else {
            // Utiliser la table user_scores à la place
            $builder = $db->table('user_scores')
                        ->select('user_scores.*, users.username, quizzes.title as quiz_title')
                        ->join('users', 'users.id = user_scores.user_id')
                        ->join('quizzes', 'quizzes.id = user_scores.quiz_id');
            
            if ($quiz_id) {
                $builder->where('user_scores.quiz_id', $quiz_id);
            }
            
            $builder->where('user_scores.is_live', 0) // uniquement les parties normales
                    ->orderBy('user_scores.score', 'DESC')
                    ->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
    
    // Classement en direct pour un quiz
    public function getLiveLeaderboard($quiz_id)
    {
        // TODO: ajouter un cache? ça risque d'être appelé souvent
        $db = \Config\Database::connect();
        $builder = $db->table('user_scores');
        $builder->select('user_scores.*, users.username');
        $builder->join('users', 'users.id = user_scores.user_id');
        $builder->where('user_scores.quiz_id', $quiz_id);
        $builder->where('user_scores.is_live', 1);
        $builder->orderBy('user_scores.score', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    // Nombre de participants à un quiz live
    public function getParticipantsCount($quiz_id)
    {
        return $this->where('quiz_id', $quiz_id)
                    ->where('is_live', 1)
                    ->countAllResults();
    }
    
    // Liste des participants en direct
    public function getLiveParticipants($quiz_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user_scores');
        $builder->select('user_scores.*, users.username');
        $builder->join('users', 'users.id = user_scores.user_id');
        $builder->where('user_scores.quiz_id', $quiz_id);
        $builder->where('user_scores.is_live', 1);
        
        return $builder->get()->getResultArray();
    }
    
    // Modifier le score d'un joueur
    public function updateScore($user_id, $quiz_id, $score)
    {
        $where = [
            'user_id' => $user_id,
            'quiz_id' => $quiz_id,
            'is_live' => 1
        ];
        
        // Additionner le score au lieu de l'écraser
        return $this->where($where)
            ->set('score', "score + $score", false)
            ->update();
    }
    
    // Ajouter un joueur à un quiz en direct
    public function joinLiveQuiz($user_id, $quiz_id)
    {
        // On vérifie s'il est déjà là
        $joueur_existant = $this->where('user_id', $user_id)
                          ->where('quiz_id', $quiz_id)
                          ->where('is_live', 1)
                          ->first();
        
        if ($joueur_existant) {
            return $joueur_existant['id'];
        }
        
        // Sinon on l'ajoute
        $data = [
            'user_id' => $user_id,
            'quiz_id' => $quiz_id,
            'score' => 0,
            'is_live' => 1,
            'completed_at' => null
        ];
        
        $this->insert($data);
        return $this->getInsertID();
    }
    
    // Finaliser un quiz en direct (marquer comme terminé avec completed_at)
    public function finalizeLiveQuiz($quiz_id)
    {
        $data = [
            'is_live' => 0,
            'completed_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->where('quiz_id', $quiz_id)
                    ->where('is_live', 1)
                    ->set($data)
                    ->update();
    }
    
    // Finaliser le score d'un joueur spécifique dans un quiz live
    public function finalizePlayerScore($user_id, $quiz_id)
    {
        $data = [
            'is_live' => 0,
            'completed_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->where('user_id', $user_id)
                    ->where('quiz_id', $quiz_id)
                    ->where('is_live', 1)
                    ->set($data)
                    ->update();
    }
    
    // Récupérer le score final d'un joueur pour un quiz
    public function getPlayerFinalScore($user_id, $quiz_id)
    {
        return $this->where('user_id', $user_id)
                    ->where('quiz_id', $quiz_id)
                    ->where('completed_at IS NOT NULL')
                    ->orderBy('completed_at', 'DESC')
                    ->first();
    }
    
    // Liste des quiz les plus populaires
    public function getPopularQuizzes($limit = 5)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user_scores');
        $builder->select('quizzes.id, quizzes.title, COUNT(user_scores.id) as participation_count, AVG(user_scores.score) as average_score');
        $builder->join('quizzes', 'quizzes.id = user_scores.quiz_id');
        $builder->groupBy('user_scores.quiz_id');
        $builder->orderBy('participation_count', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }
    
    // Meilleurs joueurs (ceux avec les scores les plus élevés)
    public function getTopScorers($limit = 5)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user_scores');
        $builder->select('users.id, users.username, COUNT(user_scores.id) as participation_count, SUM(user_scores.score) as total_score, AVG(user_scores.score) as average_score');
        $builder->join('users', 'users.id = user_scores.user_id');
        $builder->groupBy('user_scores.user_id');
        $builder->orderBy('total_score', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }
    
    // Récupère les scores d'un utilisateur avec les détails des quiz
    public function getUserScores($user_id)
    {
        $db = \Config\Database::connect();
        
        // Vérifier si la table 'scores' existe
        $scoresTableExists = $db->tableExists('scores');
        
        if ($scoresTableExists) {
            $query = $db->table('scores')
                        ->select('scores.*, quizzes.title as quiz_title')
                        ->join('quizzes', 'quizzes.id = scores.quiz_id')
                        ->where('scores.user_id', $user_id)
                        ->orderBy('scores.completed_at', 'DESC')
                        ->get();
        } else {
            // Utiliser la table user_scores à la place
            $query = $db->table('user_scores')
                        ->select('user_scores.*, quizzes.title as quiz_title')
                        ->join('quizzes', 'quizzes.id = user_scores.quiz_id')
                        ->where('user_scores.user_id', $user_id)
                        ->orderBy('user_scores.completed_at', 'DESC')
                        ->get();
        }
        
        return $query->getResultArray();
    }
    
    public function getTopScores($limit = 10, $category_id = null)
    {
        $builder = $this->select('user_scores.*, users.username, quizzes.title as quiz_title, categories.name as category_name')
                        ->join('users', 'users.id = user_scores.user_id')
                        ->join('quizzes', 'quizzes.id = user_scores.quiz_id')
                        ->join('categories', 'categories.id = quizzes.category_id', 'left')
                        ->orderBy('user_scores.score', 'DESC');
        
        if ($category_id) {
            $builder->where('quizzes.category_id', $category_id);
        }
        
        return $builder->limit($limit)->findAll();
    }
}