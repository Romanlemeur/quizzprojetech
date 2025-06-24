<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizModel extends Model
{
    protected $table            = 'quiz';
    protected $primaryKey       = 'idQuiz';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nomQuiz', 'description', 'image', 'pointsParBonneReponse', 'idAdmin'];

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
        'nomQuiz' => 'required|min_length[3]|max_length[100]',
        'description' => 'required|min_length[10]',
        'pointsParBonneReponse' => 'required|integer|greater_than[0]',
        'idAdmin' => 'required|integer|is_not_unique[utilisateur.idUtilisateur]'
    ];
    protected $validationMessages   = [
        'nomQuiz' => [
            'required' => 'Le nom du quiz est requis',
            'min_length' => 'Le nom du quiz doit contenir au moins 3 caractères',
            'max_length' => 'Le nom du quiz ne peut pas dépasser 100 caractères'
        ],
        'description' => [
            'required' => 'La description est requise',
            'min_length' => 'La description doit contenir au moins 10 caractères'
        ],
        'pointsParBonneReponse' => [
            'required' => 'Le nombre de points par bonne réponse est requis',
            'integer' => 'Le nombre de points doit être un entier',
            'greater_than' => 'Le nombre de points doit être supérieur à 0'
        ],
        'idAdmin' => [
            'required' => 'L\'administrateur est requis',
            'integer' => 'L\'ID de l\'administrateur doit être un entier',
            'is_not_unique' => 'L\'administrateur n\'existe pas'
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

    public function getQuizWithAdmin($idQuiz = null)
    {
        $builder = $this->db->table('quiz q');
        $builder->select('q.*, u.pseudo as nomAdmin');
        $builder->join('utilisateur u', 'u.idUtilisateur = q.idAdmin');
        
        if ($idQuiz) {
            $builder->where('q.idQuiz', $idQuiz);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }

    public function getQuizByAdmin($idAdmin)
    {
        return $this->where('idAdmin', $idAdmin)->findAll();
    }

    public function uploadImage($file)
    {
        if (!$file->isValid() || $file->hasMoved()) {
            return false;
        }

        $uploadPath = FCPATH . 'uploads/quiz/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        return $newName;
    }

    public function deleteImage($imageName)
    {
        $imagePath = FCPATH . 'uploads/quiz/' . $imageName;
        if (file_exists($imagePath)) {
            unlink($imagePath);
            return true;
        }
        return false;
    }
}
