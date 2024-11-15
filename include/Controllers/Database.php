<?php
namespace App\Controllers;

use App\Helpers\Constants as Constants;
use \PDO;

class Database {
    public function __construct(
        private string $host = Constants::DB_HOST,
        private string $dbname = Constants::DB_NAME,
        private string $username = Constants::DB_USERNAME,
        private string $password = Constants::DB_PASSWORD,
    ) {}

    public function getConnection() : PDO {
        $dsn = "mysql:host=$this->host;dbname=$this->dbname";
        $this->pdo = new PDO($dsn, $this->username, $this->password);

        return $this->pdo;
    }

}