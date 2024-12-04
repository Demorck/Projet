<?php

namespace App\Controllers;

use App\Helpers\View;
use PDO;

class HierarchieController {
    public function index() {
        $data = ['title' => 'Hierarchie'];
        
        $db = new Database();
        $pdo = $db->getConnection();
        $db->connectToDatabase();

        $sql = "SELECT id_aliment FROM aliments WHERE nom = 'Aliment'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $id = $stmt->fetch(PDO::FETCH_ASSOC)['id_aliment'];

        $array = $this->createHierarchie($pdo, $id);

        $data['hierarchie'] = $array;


        View::render('hierarchie_tabs', $data);
    }

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