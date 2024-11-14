<?php

class Helpers {
    public static function getImage($titre) {
        $images = "./assets/img/";
        $titre = strtolower($titre);
        $titre = ucfirst($titre);
        $titre = str_replace(" ", "_", $titre);
        $src = $images.$titre.".jpg";
    
        if (!file_exists($src)) {
            $src = $images."default.png";
        }
    
        return $src;
    }
}


?>