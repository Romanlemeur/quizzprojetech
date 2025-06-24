<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table            = 'question';
    protected $primaryKey       = 'idQuestion';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['libelle', 'idQuiz'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [
        'libelle' => 'required|min_length[10]',
        'idQuiz' => 'required|integer|is_not_unique[quiz.idQuiz]'
    ];
    protected $validationMessages   = [
        'libelle' => [
            'required' => 'Le libellé de la question est requis',
            'min_length' => 'Le libellé doit contenir au moins 10 caractères'
        ],
        'idQuiz' => [
            'required' => 'L\'ID du quiz est requis',
            'integer' => 'L\'ID du quiz doit être un entier',
            'is_not_unique' => 'Le quiz n\'existe pas'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getQuestionsWithReponses($idQuiz)
    {
        $questions = $this->where('idQuiz', $idQuiz)->findAll();
        
        $reponseModel = new ReponseModel();
        
        foreach ($questions as &$question) {
            $question['reponses'] = $reponseModel->where('idQuestion', $question['idQuestion'])->findAll();
        }
        
        return $questions;
    }

    public function getQuestionWithReponses($idQuestion)
    {
        $question = $this->find($idQuestion);
        
        if ($question) {
            $reponseModel = new ReponseModel();
            $question['reponses'] = $reponseModel->where('idQuestion', $idQuestion)->findAll();
        }
        
        return $question;
    }

    public function countQuestionsByQuiz($idQuiz)
    {
        return $this->where('idQuiz', $idQuiz)->countAllResults();
    }

    public function getQuestionsOrdered($idQuiz)
    {
        return $this->where('idQuiz', $idQuiz)
                   ->orderBy('idQuestion', 'ASC')
                   ->findAll();
    }
}
