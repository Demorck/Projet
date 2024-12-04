<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Controllers\Database;

class ConnectionController {
    public function index() {
        $data = ['title' => 'Connexion'];

        View::render('connection', $data);
    }

    public function register(){
        $data = ['title' => 'Register'];

        if($this->checkUser()){
            $data['erreur'] = 'Utilisateur existant';
        } else {
            $this->registerUser();
            $data['succès'] = 'Utilisateur créé';
        }

        View::render('connection', $data);

    }

    private function checkUser(){
        $db = new Database();
        $pdo = $db->getConnection();
        $db->connectToDatabase();

        $sql = "SELECT * FROM utilisateurs WHERE login = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $_POST['login']);
        $stmt->execute();

        $user = $stmt->fetch();

        if($user){
            return true;
        } else {
            return false;
        }
    }

    private function registerUser(){
        $db = new Database();
        $pdo = $db->getConnection();
        $db->connectToDatabase();

        $sql = "INSERT INTO utilisateurs (login, password, nom, prenom, genre, email) VALUES (:login, :password, :nom, :prenom, :genre, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $_POST['login']);
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hash);
        $stmt->bindParam(':nom', $_POST['last_name']);
        $stmt->bindParam(':prenom', $_POST['first_name']);
        $stmt->bindParam(':genre', $_POST['gender']);
        $stmt->bindParam(':email', $_POST['last_name']);
        $stmt->execute();
    }

    private function loginUser(){
        $db = new Database();
        $pdo = $db->getConnection();
        $db->connectToDatabase();

        $sql = "SELECT * FROM users WHERE login = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $_POST['login']);
        $stmt->execute();

        $user = $stmt->fetch();

        if($user && password_verify($_POST['password'], $user['password'])){
            return true;
        } else {
            return false;
        }
    }
}