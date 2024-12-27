<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Helpers\InstallDatabase;
use \PDOException;

class InstallDatabaseController {
    public function index() {
        $data = ['title' => 'Install database'];

        View::render('install', $data);
    }

    public function installDatabase() {
        if (isset($_POST["install"])) {
            try {
                $install = new InstallDatabase();
                $install->fullInstall();
        
                $data["success"] = "Installation terminée";
            } catch (PDOException $e) {
                $data["error"] = "Erreur d'installation: <br>" . $e->getMessage();
            } finally {
                $install = null;
                $_POST = [];
            }
        }

        $data += ['title' => 'Install database'];
        View::render('install', $data);
    }
}