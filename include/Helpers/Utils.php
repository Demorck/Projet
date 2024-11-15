<?php

namespace App\Helpers;

/**
 * Class Utils qui permet de gérer des fonctions utilitaires
 */
class Utils {

    /**
     * Fonction qui permet de récupérer l'image d'une boisson via son titre
     *
     * @param string $titre
     * @return string le chemin de l'image
     */
    public static function getImage($titre) {
        $images = "./assets/img/";
        $titre = strtolower($titre);
        $titre = ucfirst($titre);
        $titre = str_replace(" ", "_", $titre);
        $src = $images.$titre.".jpg";
    
        if (!file_exists($src)) {
            $src = $images . "default.png";
        }
    
        return $src;
    }
}


?>