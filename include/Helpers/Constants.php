<?php

namespace App\Helpers;

class Constants {
    const SITE_ROOT = "http://localhost/";

    const DB_HOST = "localhost";
    
    const DB_NAME = "recettes";
    const DB_USERNAME = "root";
    const DB_PASSWORD = "";

    const DB_TABLES = ["aliments", "hierarchie", "utilisateurs"];

    const SQL_TABLES = [
        "CREATE TABLE aliments (
            id_aliment INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL
        )",

        "CREATE TABLE hierarchie (
            id_super INT,
            id_sous INT,
            PRIMARY KEY (id_super, id_sous),
            FOREIGN KEY (id_super) REFERENCES aliments(id_aliment),
            FOREIGN KEY (id_sous) REFERENCES aliments(id_aliment)
        )",
        
        "CREATE TABLE utilisateurs (
            id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
            login VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            nom VARCHAR(255) NOT NULL,
            prenom VARCHAR(255) NOT NULL,
            genre ENUM('h', 'f', 'v') NOT NULL,
            email VARCHAR(255) NOT NULL,
            date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP

        )"
    ];
}