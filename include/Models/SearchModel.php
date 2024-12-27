<?php 

namespace App\Models;

use App\Controllers\Database;
use App\Helpers\Utils;
use PDO;

/**
 * Class SearchModel
 * Elle sert à récupérer les informations pour rechercher les recettes.
 */
class SearchModel {
    private $pdo;

    /**
     * Constructeur de la classe SearchModel
     */
    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection();
        $db->connectToDatabase();
    }

    /**
     * Récupère les ingrédients (et ses descendendants) à partir d'un terme
     *
     * @param [int] $id_aliment
     * @return array
     */
    private function getIngredientHierarchy($id_aliment) {
        $result = [$id_aliment];
        $queue = [$id_aliment];
        
        while (!empty($queue)) {
            $current = array_shift($queue);
            $stmt = $this->pdo->prepare("SELECT id_sous FROM hierarchie WHERE id_super = ?");
            $stmt->execute([$current]);
            $children = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($children as $child) {
                if (!in_array($child, $result)) {
                    $result[] = $child;
                    $queue[] = $child;
                }
            }
        }
        
        return array_unique($result);
    }

    /**
     * Récupère tous les ingrédients et leurs descendants à partir d'un tableau d'ids
     *
     * @param array $array
     * @return array
     */
    private function getAllIngredients($array) {
        $result = [];
        foreach ($array as $id) {
            $result = array_merge($result, $this->getIngredientHierarchy($id));
        }
        return array_unique(array_merge($result, $array));
    }

    /**
     * Recherche les recettes en fonction des ingrédients inclus et exclus
     * Pour le moment, on ne prend pas en compte les recettes qui contiennent au moins un ingrédient inclu
     *
     * @param array $included 
     * @param array $excluded
     * @return array
     */
    public function searchRecipes($included, $excluded) {
        $includedIds = $this->getAllIngredients($included);
    
        $excludedIds = $this->getAllIngredients($excluded);
    
        if (empty($includedIds)) {
            $query = "SELECT r.* FROM recettes r";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $res = $this->addIngredients($res);
            $res = $this->addImagePath($res);

            return $res;
        }
    

        $includedPlaceholders = implode(',', array_fill(0, count($includedIds), '?'));
        $excludedPlaceholders = !empty($excludedIds) ? implode(',', array_fill(0, count($excludedIds), '?')) : null;
    
        $query = "
            SELECT r.*,
                COUNT(DISTINCT CASE WHEN i.id_aliment IN ($includedPlaceholders) 
                    THEN i.id_aliment END) as matched_ingredients,
                COUNT(DISTINCT i.id_aliment) as total_ingredients
            FROM recettes r
            JOIN ingredients i ON r.id_recette = i.id_recette
            JOIN aliments a ON i.id_aliment = a.id_aliment
        ";
    
        if ($excludedPlaceholders) {
            $query .= "
                WHERE NOT EXISTS (
                    SELECT 1 FROM ingredients i2 
                    WHERE i2.id_recette = r.id_recette 
                    AND i2.id_aliment IN ($excludedPlaceholders)
                )
            ";
        }
    
        $query .= "
            GROUP BY r.id_recette
            HAVING COUNT(DISTINCT CASE WHEN i.id_aliment IN ($includedPlaceholders) 
                    THEN i.id_aliment END) > 0
            ORDER BY COUNT(DISTINCT CASE WHEN i.id_aliment IN ($includedPlaceholders) 
                    THEN i.id_aliment END)/COUNT(DISTINCT i.id_aliment) DESC
        ";
    
        if ($excludedPlaceholders) {
            $params = array_merge($includedIds, $excludedIds, $includedIds, $includedIds);
        } else {
            $params =  array_merge($includedIds, $includedIds, $includedIds);
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
    
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $res = $this->addIngredients($res);
        $res = $this->addImagePath($res);

        return $res;
    }

    private function addIngredients($res) {
        $query = "SELECT a.*, i.* FROM aliments a JOIN ingredients i ON a.id_aliment = i.id_aliment JOIN recettes r ON i.id_recette = r.id_recette";
        $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $res2 = $stmt->fetchAll(PDO::FETCH_ASSOC);


            $res3 = [];
            foreach ($res as $r) {
                $aliments = [];
                foreach ($res2 as $r2) {
                    if ($r['id_recette'] == $r2['id_recette']) {
                        $aliments[] = $r2['nom'];
                    }
                }
                $r['aliments'] = $aliments;
                $res3[] = $r;
            }

        return $res3;
    }

    private function addImagePath($res) {
        for ($i=0; $i < sizeof($res); $i++) { 
            $res[$i]['image'] = Utils::getImage($res[$i]['nom']);
        }

        return $res;
    }


    
    /**
     * Recherche les ingrédients en fonction d'un terme
     * Retourne l'ingrédient et son parent
     * @param string $term
     * @return array
     */
    public function searchIngredients($term) {
        $stmt = $this->pdo->prepare("
            SELECT a.id_aliment, a.nom,
                (SELECT GROUP_CONCAT(a2.nom SEPARATOR ' > ')
                 FROM aliments a2
                 JOIN hierarchie h ON h.id_super = a2.id_aliment
                 WHERE h.id_sous = a.id_aliment
                 ORDER BY h.id_super) as path
            FROM aliments a
            WHERE a.nom LIKE ?
            LIMIT 5
        ");
        $stmt->execute(["%$term%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchId($name) {
        $stmt = $this->pdo->prepare("
            SELECT a.id_aliment, a.nom,
                (SELECT GROUP_CONCAT(a2.nom SEPARATOR ' > ')
                 FROM aliments a2
                 JOIN hierarchie h ON h.id_super = a2.id_aliment
                 WHERE h.id_sous = a.id_aliment
                 ORDER BY h.id_super) as path
            FROM aliments a
            WHERE a.nom = ?
        ");
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}