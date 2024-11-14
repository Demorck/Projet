<?php

class Database {
    public function __construct(
        private string $host,
        private string $dbname,
        private string $username,
        private string $password
    ) {}

    public function getConnection() : PDO {
        $dsn = "mysql:host=$this->host;dbname=$this->dbname";
        $this->pdo = new PDO($dsn, $this->root, $this->password);
    }


}