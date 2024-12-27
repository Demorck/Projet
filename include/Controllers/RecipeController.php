<?php

namespace App\Controllers;

use App\Models\RecipeModel;
use App\Models\FavoriteModel;

class RecipeController {
    private RecipeModel $recipeModel;
    private FavoriteModel $favoriteModel;

    public function __construct() {
        $this->recipeModel = new RecipeModel();
        $this->favoriteModel = new FavoriteModel();
    }

    public function getFavorite() {
        session_start();
        $username = $_SESSION['login'] ?? 0;

        if ($username === 0) {
            echo json_encode([]);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($this->favoriteModel->getFavoritesByUser($username));
    }

    public function addToFavorites(): void {
        session_start();
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $_SESSION['login'] ?? 0;
        $recipeId = $data['id'] ?? 0;

        $this->favoriteModel->addFavorite($username, $recipeId);

    }

    public function removeFromFavorites(): void {
        session_start();
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $_SESSION['login'] ?? 0;
        $recipeId = $data['id'] ?? 0;

        $this->favoriteModel->removeFavorite($username, $recipeId);

    }
}
