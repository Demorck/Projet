<?php

namespace App\Helpers;

use App\Helpers\Constants as Constants;
use App\Controllers\Database as Database;
use Exception;
use \PDO;
use PDOException;

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
        try {
            $this->dropDatabase(Constants::DB_NAME);
            $this->createDatabase(Constants::DB_NAME);
            $this->database->connectToDatabase();

            $this->dropTables();
            $this->createTables();

            $this->insertAliments();
            $this->insertHierarchie();
            $this->insertRecettes();
            $this->insertIngredients();

            $this->database->closeConnection();
        } catch (\PDOException $e) {
            throw new PDOException("Install database error: <br>" . $e->getMessage());
        }
    }

    private function dropDatabase(string $name) {
        $sql = "DROP DATABASE IF EXISTS $name";
        $this->pdo->exec($sql);
    }

    private function createDatabase(string $name) {
        $sql = "CREATE DATABASE IF NOT EXISTS $name CHARACTER SET utf8";
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
            try {
                $this->pdo->exec($sql);
            } catch (\PDOException $e) {
                throw new PDOException("Install  database, create tables (" . $sql . ") error: <b>" . $e->getMessage() . "</b>");
            }
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

    private function insertRecettes() {
        foreach ($this->recettes as $recette) {
            $sql = "INSERT INTO recettes (nom, description, ingredients) VALUES (:nom, :description, :ingredients)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([":nom" => $recette["titre"], ":description" => $recette["preparation"], ":ingredients" => $recette["ingredients"]]);
        }
    }

    private function insertIngredients() {
        try {
            foreach ($this->recettes as $recette) {
                $ingredients = array_unique($recette["index"]);
                var_dump($ingredients);
                foreach ($ingredients as $ingredient) {
                    $sqlRecette = "SELECT id_recette FROM recettes WHERE nom = :nom";
                    $stmt = $this->pdo->prepare($sqlRecette);
                    $stmt->execute([":nom" => $recette["titre"]]);
                    $resRecette = $stmt->fetchAll()[0];

                    $sqlIngredient = "SELECT id_aliment FROM aliments WHERE nom = :nom";
                    $stmt = $this->pdo->prepare($sqlIngredient);
                    $stmt->execute([":nom" => $ingredient]);
                    $resIngredient = $stmt->fetchAll()[0];

                    $sql = "INSERT INTO ingredients (id_recette, id_aliment) VALUES (:id_recette, :id_aliment)";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([":id_recette" => $resRecette["id_recette"], ":id_aliment" => $resIngredient["id_aliment"]]);
                }
            }
        } catch (\PDOException $e) {
            throw new PDOException("Install database, insert ingredients error: <b>" . $e->getMessage() . "</b>");
        }
    }
}