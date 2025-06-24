<?php

namespace App\Models;

use CodeIgniter\Model;

class ParticipationModel extends Model
{
    protected $table            = 'participation';
    protected $primaryKey       = 'idParticipation';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idSession', 'idUtilisateur', 'dateHeureConnexion', 'scoreActuel'];

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
        'idSession' => 'required|integer|is_not_unique[session_quiz.idSession]',
        'idUtilisateur' => 'required|integer|is_not_unique[utilisateur.idUtilisateur]',
        'dateHeureConnexion' => 'required|valid_date',
        'scoreActuel' => 'required|integer|greater_than_equal_to[0]'
    ];
    protected $validationMessages   = [
        'idSession' => [
            'required' => 'L\'ID de la session est requis',
            'integer' => 'L\'ID de la session doit être un entier',
            'is_not_unique' => 'La session n\'existe pas'
        ],
        'idUtilisateur' => [
            'required' => 'L\'ID de l\'utilisateur est requis',
            'integer' => 'L\'ID de l\'utilisateur doit être un entier',
            'is_not_unique' => 'L\'utilisateur n\'existe pas'
        ],
        'dateHeureConnexion' => [
            'required' => 'La date et heure de connexion sont requises',
            'valid_date' => 'La date et heure de connexion doivent être valides'
        ],
        'scoreActuel' => [
            'required' => 'Le score actuel est requis',
            'integer' => 'Le score doit être un entier',
            'greater_than_equal_to' => 'Le score doit être supérieur ou égal à 0'
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

    public function getParticipantsBySession($idSession)
    {
        $builder = $this->db->table('participation p');
        $builder->select('p.*, u.pseudo, u.email');
        $builder->join('utilisateur u', 'u.idUtilisateur = p.idUtilisateur');
        $builder->where('p.idSession', $idSession);
        $builder->orderBy('p.scoreActuel', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getParticipationByUserAndSession($idUtilisateur, $idSession)
    {
        return $this->where('idUtilisateur', $idUtilisateur)
                   ->where('idSession', $idSession)
                   ->first();
    }

    public function joinSession($idUtilisateur, $idSession)
    {
        // Vérifier si l'utilisateur participe déjà
        $existing = $this->getParticipationByUserAndSession($idUtilisateur, $idSession);
        
        if ($existing) {
            return $existing['idParticipation'];
        }
        
        // Créer une nouvelle participation
        $data = [
            'idSession' => $idSession,
            'idUtilisateur' => $idUtilisateur,
            'dateHeureConnexion' => date('Y-m-d H:i:s'),
            'scoreActuel' => 0
        ];
        
        return $this->insert($data);
    }

    public function updateScore($idParticipation, $newScore)
    {
        return $this->update($idParticipation, ['scoreActuel' => $newScore]);
    }

    public function getClassementBySession($idSession)
    {
        return $this->getParticipantsBySession($idSession);
    }

    public function countParticipantsBySession($idSession)
    {
        return $this->where('idSession', $idSession)->countAllResults();
    }
}
