<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = ['username', 'email', 'password'];
    
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = '';
    protected $deletedField = '';
    
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}