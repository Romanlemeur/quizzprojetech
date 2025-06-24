<?php

namespace App\Models;

use CodeIgniter\Model;

class ReponseJoueurModel extends Model
{
    protected $table = 'reponse_joueur';
    protected $primaryKey = ['idParticipation', 'idQuestion'];
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['idParticipation', 'idQuestion', 'idReponseChoisie', 'tempsReponse'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'idParticipation' => 'required|integer|is_not_unique[participation.idParticipation]',
        'idQuestion' => 'required|integer|is_not_unique[question.idQuestion]',
        'idReponseChoisie' => 'required|integer|is_not_unique[reponse.idReponse]',
        'tempsReponse' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[10]'
    ];
    protected $validationMessages = [
        'idParticipation' => [
            'required' => 'L\'ID de la participation est requis',
            'integer' => 'L\'ID de la participation doit être un entier',
            'is_not_unique' => 'La participation n\'existe pas'
        ],
        'idQuestion' => [
            'required' => 'L\'ID de la question est requis',
            'integer' => 'L\'ID de la question doit être un entier',
            'is_not_unique' => 'La question n\'existe pas'
        ],
        'idReponseChoisie' => [
            'required' => 'L\'ID de la réponse choisie est requis',
            'integer' => 'L\'ID de la réponse choisie doit être un entier',
            'is_not_unique' => 'La réponse choisie n\'existe pas'
        ],
        'tempsReponse' => [
            'required' => 'Le temps de réponse est requis',
            'integer' => 'Le temps de réponse doit être un entier',
            'greater_than_equal_to' => 'Le temps de réponse doit être supérieur ou égal à 0',
            'less_than_equal_to' => 'Le temps de réponse ne peut pas dépasser 10 secondes'
        ]
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function saveReponse($idParticipation, $idQuestion, $idReponseChoisie, $tempsReponse)
    {
        // Vérifier si le joueur a déjà répondu à cette question
        $existing = $this->where('idParticipation', $idParticipation)
                        ->where('idQuestion', $idQuestion)
                        ->first();
        
        if ($existing) {
            // Mettre à jour la réponse existante
            return $this->where('idParticipation', $idParticipation)
                       ->where('idQuestion', $idQuestion)
                       ->set([
                           'idReponseChoisie' => $idReponseChoisie,
                           'tempsReponse' => $tempsReponse
                       ])
                       ->update();
        } else {
            // Créer une nouvelle réponse
            $data = [
                'idParticipation' => $idParticipation,
                'idQuestion' => $idQuestion,
                'idReponseChoisie' => $idReponseChoisie,
                'tempsReponse' => $tempsReponse
            ];
            
            return $this->insert($data);
        }
    }

    public function getReponsesByParticipation($idParticipation)
    {
        $builder = $this->db->table('reponse_joueur rj');
        $builder->select('rj.*, q.libelle as question, r.texte as reponseChoisie, r.estBonne');
        $builder->join('question q', 'q.idQuestion = rj.idQuestion');
        $builder->join('reponse r', 'r.idReponse = rj.idReponseChoisie');
        $builder->where('rj.idParticipation', $idParticipation);
        $builder->orderBy('rj.idQuestion', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function hasRepondu($idParticipation, $idQuestion)
    {
        return $this->where('idParticipation', $idParticipation)
                   ->where('idQuestion', $idQuestion)
                   ->countAllResults() > 0;
    }

    public function getReponseByParticipationAndQuestion($idParticipation, $idQuestion)
    {
        return $this->where('idParticipation', $idParticipation)
                   ->where('idQuestion', $idQuestion)
                   ->first();
    }

    public function getStatistiquesBySession($idSession)
    {
        $builder = $this->db->table('reponse_joueur rj');
        $builder->select('q.libelle as question, r.texte as reponse, COUNT(*) as nbChoix, r.estBonne');
        $builder->join('participation p', 'p.idParticipation = rj.idParticipation');
        $builder->join('question q', 'q.idQuestion = rj.idQuestion');
        $builder->join('reponse r', 'r.idReponse = rj.idReponseChoisie');
        $builder->where('p.idSession', $idSession);
        $builder->groupBy('rj.idQuestion, rj.idReponseChoisie');
        $builder->orderBy('q.idQuestion', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}
