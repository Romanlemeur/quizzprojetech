<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table = 'questions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    // champs qu'on peut modifier
    protected $allowedFields = ['quiz_id', 'question_text', 'question_order', 'points'];
    
    protected $useTimestamps = false;
}