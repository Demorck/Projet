<?php

namespace App\Helpers;

use App\Helpers\Constants as Constants;
use App\Controllers\Database as Database;
use \PDO;

/**
 * Class InstallDatabase qui permet d'installer la base de données de zéro
 * 
 */
class InstallDatabase {

    private Database $database;
    private PDO $pdo;
    private Array $hierarchie;
    private Array $recettes;

    /**
     * Constructeur de la classe InstallDatabase
     */
    public function __construct() {
        $this->database = new Database();
        $this->pdo = $this->database->getConnection();
        include_once($_SERVER['DOCUMENT_ROOT'] . "/include/Donnees.inc.php");
        
        $this->hierarchie = $Hierarchie;
        $this->recettes = $Recettes;
    }

    /**
     * Installation complète de la base de données
     *
     */
    public function fullInstall() {
        $this->dropDatabase(Constants::DB_NAME);
        $this->createDatabase(Constants::DB_NAME);
        $this->database->connectToDatabase();

        $this->dropTables();
        $this->createTables();

        $this->insertAliments();
        $this->insertHierarchie();

        $this->database->closeConnection();
    }

    private function dropDatabase(string $name) {
        $sql = "DROP DATABASE IF EXISTS $name";
        $this->pdo->exec($sql);
    }

    private function createDatabase(string $name) {
        $sql = "CREATE DATABASE IF NOT EXISTS $name CHARACTER SET utf8mb4";
        $this->pdo->exec($sql);
    }

    private function dropTables(bool $force = false) {
        foreach(Constants::DB_TABLES as $table) {
            if ($force)
                $sql = "DROP TABLE $table";
            else
                $sql = "DROP TABLE IF EXISTS $table";
       
                $this->pdo->exec($sql);
        }	
    }

    private function createTables() {
        foreach(Constants::SQL_TABLES as $sql) {
            $this->pdo->exec($sql);
        }
    }

    private function insertAliments() {
        foreach ($this->hierarchie as $key => $aliment) {    
            $sql = "INSERT INTO aliments (nom) VALUES (:nom)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([":nom" => $key]);
        }
    }

    private function insertHierarchie() {
        foreach ($this->hierarchie as $key => $aliment) {    
            if (isset($aliment["sous-categorie"]))
            {
                foreach ($aliment["sous-categorie"] as $sousCategorie) {
                    $sqlSuper = "SELECT id_aliment FROM aliments WHERE nom = :nom";
                    $stmt = $this->pdo->prepare($sqlSuper);
                    $stmt->execute([":nom" => $key]);
                    $resSuper = $stmt->fetchAll()[0];
        
                    $sqlSous = "SELECT id_aliment FROM aliments WHERE nom = :nom";
                    $stmt = $this->pdo->prepare($sqlSous);
                    $stmt->execute([":nom" => $sousCategorie]);
                    $resSous = $stmt->fetchAll()[0];

                    $sql = "INSERT INTO hierarchie (id_super, id_sous) VALUES (:id_super, :id_sous)";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([":id_super" => $resSuper["id_aliment"], ":id_sous" => $resSous["id_aliment"]]);
                }
            }
        }
    }
}