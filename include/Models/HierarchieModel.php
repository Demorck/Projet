<?php 

namespace App\Models;

use App\Controllers\Database;
use PDO;

/**
 * Class SearchModel
 * Elle sert à récupérer les informations pour rechercher les recettes.
 */
class HierarchieModel {
    private $pdo;

    /**
     * Constructeur de la classe SearchModel
     */
    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection();
        $db->connectToDatabase();
    }

    public function getHierarchie() {
        $sql = "SELECT id_aliment FROM aliments WHERE nom = 'Aliment'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $id = $stmt->fetch(PDO::FETCH_ASSOC)['id_aliment'];

        return $this->createHierarchie($this->pdo, $id);
    }

    /**
     * Récupère les ingrédients (et ses descendendants) à partir d'un terme
     *
     * @param [int] $id_aliment
     * @return array
     */
    private function createHierarchie(PDO $pdo, int $id_super): array {
        $sql = "SELECT id_sous FROM hierarchie WHERE id_super = :id_super";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id_super", $id_super);
        $stmt->execute();
    
        $hierarchieBase = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $nomSuper = $this->getNom($pdo, $id_super);
    
        if (empty($hierarchieBase)) {
            return [$nomSuper];
        }
    
        $enfants = [];
        foreach ($hierarchieBase as $value) {
            $id_sous = $value['id_sous'];
            $sousHierarchie = $this->createHierarchie($pdo, $id_sous);

            if (count($sousHierarchie) === 1 && is_string(reset($sousHierarchie))) {
                $enfants[] = reset($sousHierarchie);
            } else {
                $enfants[key($sousHierarchie)] = reset($sousHierarchie);
            }
        }
    
        return [$nomSuper => $enfants];
    }
    
    
    
    

    private function getNom(PDO $pdo, int $id) {
        $sql = "SELECT nom FROM aliments WHERE id_aliment = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['nom'];
    }
}