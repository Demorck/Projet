<?php

namespace App\Helpers;

/**
 * Class View qui permet d'afficher une vue
 * 
 * @package App\Helpers
 * @author Maximilien ANTOINE
 */
class View {
    public static function render(string $view, array $data = []) {
        extract($data);

        $viewPath = $_SERVER['DOCUMENT_ROOT'] . '/views/' . $view . ".php";

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "La vue " . $view . " n'existe pas";
        }
    }
}