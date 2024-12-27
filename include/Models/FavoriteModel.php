<?php

namespace App\Models;

use PDO;
use App\Controllers\Database;

class FavoriteModel {
    private PDO $pdo;

    public function __construct() {
        $pdo = new Database();
        $this->pdo = $pdo->getConnection();
        $pdo->connectToDatabase();
    }

    public function getFavoritesByUser($username): array {
        $stmt = $this->pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE login = ?");
        $stmt->execute([$username]);
        $userId = $stmt->fetch(PDO::FETCH_ASSOC)['id_utilisateur'];

        $stmt = $this->pdo->prepare("SELECT * FROM recettes r JOIN favoris f ON r.id_recette = f.id_recette WHERE id_utilisateur = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addFavorite($username, $recipeId): bool {
        $stmt = $this->pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE login = ?");
        $stmt->execute([$username]);
        $userId = $stmt->fetch(PDO::FETCH_ASSOC)['id_utilisateur'];

        $stmt = $this->pdo->prepare("INSERT INTO favoris (id_utilisateur, id_recette) VALUES (?, ?)");
        return $stmt->execute([$userId, $recipeId]);
    }

    public function removeFavorite($username, $recipeId): bool {
        $stmt = $this->pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE login = ?");
        $stmt->execute([$username]);
        $userId = $stmt->fetch(PDO::FETCH_ASSOC)['id_utilisateur'];
        var_dump($userId, $recipeId);
        $stmt = $this->pdo->prepare("DELETE FROM favoris WHERE id_utilisateur = ? AND id_recette = ?");
        return $stmt->execute([$userId, $recipeId]);
    }
}
