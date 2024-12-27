<?php

namespace App\Controllers;

use App\Models\AccountModel;
use App\Helpers\View;

class AccountController {
    public function index() {
        session_start();
        if (!isset($_SESSION['login'])) {
            header('Location: /connect');
            exit();
        }

        $data['title'] = "Mon compte";
        $model = new AccountModel();
        $data['informations'] = $model->getInformations($_SESSION['login']);

        View::render('account', $data);
    }

    public function changeInformations() {
        session_start();
        if (!isset($_SESSION['login'])) {
            header('Location: /connect');
            exit();
        }
        $data['title'] = "Mon compte";

        try {    
            $model = new AccountModel();
            $model->changeInformations($_SESSION['login'], $_POST);
            $data['success'] = "Informations modifiées avec succès";
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();
        }

        
        $data['informations'] = $model->getInformations($_SESSION['login']);

        View::render('account', $data);
    }
}