<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilisateurModel extends Model
{
    protected $table            = 'utilisateur';
    protected $primaryKey       = 'idUtilisateur';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['email', 'motDePasse', 'pseudo', 'role'];

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
        'email' => 'required|valid_email|is_unique[utilisateur.email,idUtilisateur,{idUtilisateur}]',
        'motDePasse' => 'required|min_length[6]',
        'pseudo' => 'required|min_length[3]|max_length[50]',
        'role' => 'required|in_list[admin,joueur]'
    ];
    protected $validationMessages   = [
        'email' => [
            'required' => 'L\'email est requis',
            'valid_email' => 'L\'email doit être valide',
            'is_unique' => 'Cet email est déjà utilisé'
        ],
        'motDePasse' => [
            'required' => 'Le mot de passe est requis',
            'min_length' => 'Le mot de passe doit contenir au moins 6 caractères'
        ],
        'pseudo' => [
            'required' => 'Le pseudo est requis',
            'min_length' => 'Le pseudo doit contenir au moins 3 caractères',
            'max_length' => 'Le pseudo ne peut pas dépasser 50 caractères'
        ],
        'role' => [
            'required' => 'Le rôle est requis',
            'in_list' => 'Le rôle doit être admin ou joueur'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['motDePasse'])) {
            return $data;
        }

        $data['data']['motDePasse'] = password_hash($data['data']['motDePasse'], PASSWORD_DEFAULT);
        return $data;
    }

    public function authenticate($email, $password)
    {
        $user = $this->where('email', $email)->first();
        
        if ($user && password_verify($password, $user['motDePasse'])) {
            return $user;
        }
        
        return false;
    }

    public function getAdmins()
    {
        return $this->where('role', 'admin')->findAll();
    }

    public function getJoueurs()
    {
        return $this->where('role', 'joueur')->findAll();
    }

    public function isAdmin($idUtilisateur)
    {
        $user = $this->find($idUtilisateur);
        return $user && $user['role'] === 'admin';
    }

    public function isJoueur($idUtilisateur)
    {
        $user = $this->find($idUtilisateur);
        return $user && $user['role'] === 'joueur';
    }
}
