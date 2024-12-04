<?php
namespace App\Controllers;

use App\Helpers\Constants as Constants;
use \PDO;

/**
 * Class Database qui permet la connexion à la base de données et de la gérer
 * 
 */
class Database {
    /**
     * Host de la base de données
     *
     * @var string
     */
    private string $host;

    /**
     * Nom de la base de données actuelle
     *
     * @var string
     */
    private string $dbname;

    /**
     * Nom d'utilisateur de la base de données
     *
     * @var string
     */
    private string $username;

    /**
     * Mot de passe de la base de données
     *
     * @var string
     */
    private string $password;

    /**
     * Objet PDO de la connexion
     *
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Constructeur de la classe Database
     *
     * @param string $host
     * @param string $dbname
     * @param string $username
     * @param string $password
     */
    public function __construct(
        string $host = Constants::DB_HOST,
        string $dbname = Constants::DB_NAME,
        string $username = Constants::DB_USERNAME,
        string $password = Constants::DB_PASSWORD
    ) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Retourne une connexion à la base de données
     * Attention, il faut appeler la méthode connectToDatabase() après avoir récupéré la connexion pour se connecter
     *
     * @return PDO
     */
    public function getConnection() : PDO {
        $dsn = "mysql:host=$this->host";
        $this->pdo = new PDO($dsn, $this->username, $this->password);

        return $this->pdo;
    }

    /**
     * Change la base de données actuelle
     *
     * @param string $dbname
     */
    public function switchDatabase(string $dbname) {
        $this->dbname = $dbname;
    }

    /**
     * Se connecte à la base de données actuelle
     */
    public function connectToDatabase() {
        $this->pdo->exec("USE $this->dbname");
        $this->pdo->exec("SET CHARACTER SET 'utf8'; SET NAMES 'utf8'");
    }

    /**
     * Ferme la connexion à la base de données
     */
    public function closeConnection() {
        // $this->pdo = null;
    }

}