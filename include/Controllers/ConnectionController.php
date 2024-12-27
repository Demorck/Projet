<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Controllers\Database;

class ConnectionController {
    public function index() {
        $data = ['title' => 'Connexion'];
        $data += ['script' => 'js/connection.js'];

        View::render('connection', $data);
    }

    public function register(){
        $data = ['title' => 'Connexion'];
        $data += ['script' => 'js/connection.js'];

        if($this->checkUser()){
            $data += ['code' => 401];
            $data += ['error' => 'Utilisateur déjà existant'];
        } else {
            $this->registerUser();
            $data += ['code' => 200];
            $data += ['success' => 'Utilisateur créé avec succès'];
        }

        View::render('connection', $data);

    }

    public function login(){
        $data = ['script' => 'js/connection.js'];
        
        if($this->loginUser()){
            $data += ['title' => 'Accueil'];
            $data += ['code' => 200];
            $data += ['success' => 'Connexion réussie'];

            session_start();
            $_SESSION['login'] = $_POST['login'];
            View::render('homepage', $data);

        } else {
            $data += ['title' => 'Connexion'];
            $data += ['code' => 401];
            $data += ['error' => 'Identifiants incorrects'];
            
            View::render('connection', $data);
        }

    }

    public function disconnect(){
        session_start();
        session_destroy();
        header('Location: /');
    }
    
    public function getSession(){
        session_start();
        if(isset($_SESSION['login'])){
            $data = ['code' => 200];
            $data += ['login' => $_SESSION['login']];
        } else {
            $data = ['code' => 401];
        }

        echo json_encode($data);
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

        $sql = "INSERT INTO utilisateurs (login, password, nom, prenom, genre, email, adresse, code_postal, ville) VALUES (:login, :password, :nom, :prenom, :genre, :email, :adresse, :code_postal, :ville)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $_POST['login']);
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hash);
        $stmt->bindParam(':nom', $_POST['last_name']);
        $stmt->bindParam(':prenom', $_POST['first_name']);
        $stmt->bindParam(':genre', $_POST['gender']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':adresse', $_POST['adresse']);
        $stmt->bindParam(':code_postal', $_POST['zipcode']);
        $stmt->bindParam(':ville', $_POST['ville']);
        $stmt->execute();
    }

    private function loginUser(){
        $db = new Database();
        $pdo = $db->getConnection();
        $db->connectToDatabase();

        $sql = "SELECT * FROM utilisateurs WHERE login = :login";
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