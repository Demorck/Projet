<?php


class Constants {
    public static string $SITE_ROOT = "http://localhost/";

    public static string $DB_HOST = "localhost";
    public static string $DB_NAME = "recettes";
    public static string $DB_USERNAME = "root";
    public static string $DB_PASSWORD = "";

    public static Array $DB_TABLES = ["aliments", "hierarchie"];

    public static Array $SQL_TABLES = [
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
     ];
}