<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizModel extends Model
{
    protected $table = 'quizzes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = ['category_id', 'title', 'description', 'image', 'time_limit', 'is_active', 'is_live', 'created_at', 'updated_at'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Récupère un quiz avec sa catégorie
    public function getQuizWithCategory($quizId)
    {
        return $this->select('quizzes.*, categories.name as category_name')
                    ->join('categories', 'categories.id = quizzes.category_id')
                    ->where('quizzes.id', $quizId)
                    ->first();
    }
    
    // Liste des quiz avec leurs catégories
    public function getQuizzesWithCategories()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('quizzes');
        $builder->select('quizzes.*, categories.name as category_name');
        $builder->join('categories', 'categories.id = quizzes.category_id');
        $builder->orderBy('quizzes.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    // Récupère un quiz avec toutes ses questions et options
    public function getQuizWithQuestions($quiz_id)
    {
        $db = \Config\Database::connect();
        
        // Récupérer le quiz
        $le_quiz = $this->find($quiz_id);
        
        if (!$le_quiz) {
            return null;
        }
        
        // Récupérer la catégorie
        $catModel = new CategoryModel();
        $categorie = $catModel->find($le_quiz['category_id']);
        $le_quiz['category_name'] = $categorie ? $categorie['name'] : '';
        
        // Récupérer les questions
        $questionBuilder = $db->table('questions');
        $questionBuilder->where('quiz_id', $quiz_id);
        $questionBuilder->orderBy('question_order', 'ASC');
        $le_quiz['questions'] = $questionBuilder->get()->getResultArray();
        
        // Récupérer les options pour chaque question
        // TODO: optimiser ça un jour, ça fait beaucoup de requêtes
        foreach ($le_quiz['questions'] as &$question) {
            $optionBuilder = $db->table('options');
            $optionBuilder->where('question_id', $question['id']);
            $optionBuilder->orderBy('option_order', 'ASC');
            $question['options'] = $optionBuilder->get()->getResultArray();
        }
        
        return $le_quiz;
    }
    
    // Récupère le quiz en cours (live)
    public function getLiveQuiz()
    {
        return $this->where('is_live', 1)->first();
    }
    
    // Création complète d'un quiz avec toutes ses questions
    public function createFullQuiz($quiz_data, $questions)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        // Insérer le quiz
        $this->insert($quiz_data);
        $quiz_id = $this->getInsertID();
        
        // Insérer les questions et options
        $questionModel = new QuestionModel();
        $optionModel = new OptionModel();
        
        foreach ($questions as $num => $question) {
            $quest_data = [
                'quiz_id' => $quiz_id,
                'question_text' => $question['text'],
                'question_order' => $num + 1,
                'points' => $question['points'] ?? 1
            ];
            
            $questionModel->insert($quest_data);
            $question_id = $questionModel->getInsertID();
            
            foreach ($question['options'] as $opt_num => $option) {
                $opt_data = [
                    'question_id' => $question_id,
                    'option_text' => $option['text'],
                    'is_correct' => $option['is_correct'] ?? 0,
                    'option_order' => $opt_num + 1
                ];
                
                $optionModel->insert($opt_data);
            }
        }
        
        $db->transComplete();
        
        return $db->transStatus() === false ? false : $quiz_id;
    }
    
    // Mise à jour complète d'un quiz
    // Pas très optimal, on supprime tout et on recrée...
    public function updateFullQuiz($quiz_id, $quiz_data, $questions)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        // Mettre à jour le quiz
        $this->update($quiz_id, $quiz_data);
        
        // Supprimer les anciennes questions et options
        $questionModel = new QuestionModel();
        $old_questions = $questionModel->where('quiz_id', $quiz_id)->findAll();
        
        foreach ($old_questions as $old_q) {
            $optionModel = new OptionModel();
            $optionModel->where('question_id', $old_q['id'])->delete();
        }
        
        $questionModel->where('quiz_id', $quiz_id)->delete();
        
        // Insérer les nouvelles questions et options
        foreach ($questions as $num => $question) {
            $quest_data = [
                'quiz_id' => $quiz_id,
                'question_text' => $question['text'],
                'question_order' => $num + 1,
                'points' => $question['points'] ?? 1
            ];
            
            $questionModel->insert($quest_data);
            $question_id = $questionModel->getInsertID();
            
            foreach ($question['options'] as $opt_num => $option) {
                $opt_data = [
                    'question_id' => $question_id,
                    'option_text' => $option['text'],
                    'is_correct' => $option['is_correct'] ?? 0,
                    'option_order' => $opt_num + 1
                ];
                
                $optionModel = new OptionModel();
                $optionModel->insert($opt_data);
            }
        }
        
        $db->transComplete();
        
        return $db->transStatus() === false ? false : true;
    }
    
    // Mettre un quiz en mode live
    public function setQuizLive($quiz_id)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        // Désactiver tous les quiz live existants
        $this->where('is_live', 1)->set('is_live', 0)->update();
        
        // Définir le nouveau quiz comme live
        $this->update($quiz_id, ['is_live' => 1]);
        
        $db->transComplete();
        
        return $db->transStatus() === false ? false : true;
    }
    
    // Récupère les quiz populaires
    public function getPopularQuizzes($limit = 3)
    {
        // Dans une vraie application, on pourrait ordonner par nombre de participants
        return $this->orderBy('id', 'DESC')
                    ->limit($limit)
                    ->find();
    }
    
    // Récupère les quiz originaux
    public function getOriginalQuizzes($limit = 3)
    {
        // Dans une vraie application, on pourrait filtrer par un tag "original"
        // Pour l'instant, on simule en prenant les quiz les plus récents
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->find();
    }
    
    // Récupère les quiz à venir pour le planning de la semaine
    public function getUpcomingQuizzes()
    {
        // Vérifier si la colonne scheduled_date existe
        $db = \Config\Database::connect();
        $tableExists = $db->tableExists('quizzes');
        
        if ($tableExists) {
            // Vérifier si la colonne scheduled_date existe
            $fields = $db->getFieldData('quizzes');
            $hasScheduledDate = false;
            
            foreach ($fields as $field) {
                if ($field->name === 'scheduled_date') {
                    $hasScheduledDate = true;
                    break;
                }
            }
            
            // Si la colonne n'existe pas, on simule des données
            if (!$hasScheduledDate) {
                // Générer des données simulées pour les quiz planifiés
                $allQuizzes = $this->findAll();
                $upcomingQuizzes = [];
                
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $times = ['10:00', '14:00', '16:00', '18:00'];
                
                foreach ($allQuizzes as $index => $quiz) {
                    // Limiter à 7 quiz maximum (un par jour)
                    if ($index < 7) {
                        $day = $days[$index % count($days)];
                        $time = $times[$index % count($times)];
                        
                        // Calculer la date du prochain jour de la semaine
                        $date = date('Y-m-d', strtotime("next $day"));
                        $dateTime = "$date $time:00";
                        
                        $quiz['scheduled_date'] = $dateTime;
                        $upcomingQuizzes[] = $quiz;
                    }
                }
                
                return $upcomingQuizzes;
            }
            
            // Si la colonne existe, on récupère les vraies données
            return $this->where('scheduled_date >=', date('Y-m-d H:i:s'))
                        ->orderBy('scheduled_date', 'ASC')
                        ->findAll();
        }
        
        // Si la table n'existe pas (ce qui est improbable), on retourne un tableau vide
        return [];
    }
}