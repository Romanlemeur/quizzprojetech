<?php

namespace App\Models;

use CodeIgniter\Model;

class OptionModel extends Model
{
    protected $table = 'options';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    // options de réponse (faudra ajouter d'autres types que text plus tard)
    protected $allowedFields = ['question_id', 'option_text', 'is_correct', 'option_order'];
    
    protected $useTimestamps = false;
}