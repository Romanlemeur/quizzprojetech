<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQuizSessions extends Migration
{
    public function up()
    {
        // Table pour gérer les sessions de quiz en direct
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'quiz_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'current_question' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'start_time' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'end_time' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('quiz_id', 'quizzes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('quiz_sessions');
        
        // Ajouter des colonnes à la table user_scores pour les quiz en direct
        $this->forge->addColumn('user_scores', [
            'is_live' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'completed_at'
            ],
            'current_question' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'is_live'
            ],
        ]);
        
        // Ajouter les colonnes pour les quiz en direct à la table quizzes
        $this->forge->addColumn('quizzes', [
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'after' => 'time_limit'
            ],
            'is_live' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'is_active'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('quiz_sessions');
        $this->forge->dropColumn('user_scores', 'is_live');
        $this->forge->dropColumn('user_scores', 'current_question');
        $this->forge->dropColumn('quizzes', 'is_active');
        $this->forge->dropColumn('quizzes', 'is_live');
    }
} 