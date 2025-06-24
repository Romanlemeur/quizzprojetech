<?php

namespace App\Models;

use CodeIgniter\Model;

class ReponseModel extends Model
{
    protected $table            = 'reponse';
    protected $primaryKey       = 'idReponse';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idQuestion', 'texte', 'estBonne'];

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
        'idQuestion' => 'required|integer|is_not_unique[question.idQuestion]',
        'texte' => 'required|min_length[1]|max_length[255]',
        'estBonne' => 'required|in_list[0,1]'
    ];
    protected $validationMessages   = [
        'idQuestion' => [
            'required' => 'L\'ID de la question est requis',
            'integer' => 'L\'ID de la question doit être un entier',
            'is_not_unique' => 'La question n\'existe pas'
        ],
        'texte' => [
            'required' => 'Le texte de la réponse est requis',
            'min_length' => 'Le texte doit contenir au moins 1 caractère',
            'max_length' => 'Le texte ne peut pas dépasser 255 caractères'
        ],
        'estBonne' => [
            'required' => 'Le statut de la réponse est requis',
            'in_list' => 'Le statut doit être 0 ou 1'
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

    public function getReponsesByQuestion($idQuestion)
    {
        return $this->where('idQuestion', $idQuestion)->findAll();
    }

    public function getBonneReponse($idQuestion)
    {
        return $this->where('idQuestion', $idQuestion)
                   ->where('estBonne', 1)
                   ->first();
    }

    public function countBonneReponses($idQuestion)
    {
        return $this->where('idQuestion', $idQuestion)
                   ->where('estBonne', 1)
                   ->countAllResults();
    }

    public function validateReponses($idQuestion, $reponses)
    {
        // Vérifier qu'il y a exactement 4 réponses
        if (count($reponses) !== 4) {
            return false;
        }

        // Vérifier qu'il y a exactement une bonne réponse
        $bonneReponses = 0;
        foreach ($reponses as $reponse) {
            if (isset($reponse['estBonne']) && $reponse['estBonne']) {
                $bonneReponses++;
            }
        }

        return $bonneReponses === 1;
    }

    public function deleteReponsesByQuestion($idQuestion)
    {
        return $this->where('idQuestion', $idQuestion)->delete();
    }
}
