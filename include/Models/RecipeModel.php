<?php

namespace App\Models;

use App\Controllers\Database;
use PDO;

/**
 * Class RecipeModel
 */
class RecipeModel {
    private PDO $pdo;

    /**
     * Constructeur de la classe RecipeModel
     */
    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection();
        $db->connectToDatabase();
    }

    public function getAllRecipes() {
        $sql = "SELECT * FROM recettes";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}