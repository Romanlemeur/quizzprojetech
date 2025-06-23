<?php


namespace App\Controllers;

class TestDb extends BaseController  // Changez Test en TestDb
{
    public function index()
    {
       try {
            $db = \Config\Database::connect();
            
            // Test de connexion basique
            if ($db->connect()) {
                echo "1. Connexion basique réussie<br>";
            }
            
            // Test de requête simple
            $query = $db->query('SELECT DATABASE() as current_db');
            $row = $query->getRow();
            echo "2. Base de données actuelle : " . $row->current_db . "<br>";
            
            // Test des tables
            $tables = $db->listTables();
            echo "3. Tables disponibles : <br>";
            foreach ($tables as $table) {
                echo "- $table <br>";
            }
            
        } catch (\Exception $e) {
            echo "ERREUR : " . $e->getMessage();
            echo "<br>Type : " . get_class($e);
            echo "<br>Fichier : " . $e->getFile();
            echo "<br>Ligne : " . $e->getLine();
        }
    }
}