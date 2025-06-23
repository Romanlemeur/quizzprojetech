<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizSessionModel extends Model
{
    protected $table = 'quiz_sessions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'quiz_id',
        'current_question_id',
        'start_time',
        'end_time',
        'is_active',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = false;
} 