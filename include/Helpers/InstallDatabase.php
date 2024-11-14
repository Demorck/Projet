<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/Controllers/Database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/Helpers/Constants.php");

class InstallDatabase {

    private Database $database;
    private PDO $pdo;
    private Array $hierarchie;
    private Array $recettes;


    public function __construct() {
        $this->database = new Database("localhost", "recettes", "root", "");
        $this->pdo = $this->database->getConnection();
        include_once($_SERVER['DOCUMENT_ROOT'] . "/include/Donnees.inc.php");
        
        $this->hierarchie = $Hierarchie;
        $this->recettes = $Recettes;
    }

    public function dropTables() {
        foreach(Constants::$DB_TABLES as $table) {
            $sql = "DROP TABLE IF EXISTS $table";
            $this->pdo->exec($sql);
        }	
    }

    public function createTables() {
        foreach(Constants::$SQL_TABLES as $sql) {
            $this->pdo->exec($sql);
        }
    }

    public function insertAliments() {
        foreach ($this->hierarchie as $key => $aliment) {    
            $sql = "INSERT INTO aliments (nom) VALUES (:nom)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([":nom" => $key]);
        }
    }

    public function insertHierarchie() {
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