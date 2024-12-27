<?php

namespace App\Models;

use App\Controllers\Database;
use PDO;

/**
 * Class AccountModel qui permet de gérer les comptes
 * 
 * @package App\Models
 */
class AccountModel {
    private PDO $pdo;

    public function __construct() {
        $pdo = new Database();
        $this->pdo = $pdo->getConnection();
        $pdo->connectToDatabase();
    }

    public function getInformations($username): array {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function changeInformations($username, $data) {
        if ($data['password'] != "") {
            $stmt = $this->pdo->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, genre = ?, email = ?, adresse = ?, code_postal = ?, ville = ?, password = ? WHERE login = ?");
            
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt->execute([$data['last_name'], $data['first_name'], $data['gender'], $data['email'], $data['adresse'], $data['zipcode'], $data['ville'], $hash, $username]);
            return;
        }


        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, genre = ?, email = ?, adresse = ?, code_postal = ?, ville = ? WHERE login = ?");
        $stmt->execute([$data['last_name'], $data['first_name'], $data['gender'], $data['email'], $data['adresse'], $data['zipcode'], $data['ville'], $username]);
    }
}