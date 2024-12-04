<?php

namespace App\Controllers;

use App\Helpers\View;

class ConnectionController {
    public function index() {
        $data = ['title' => 'Connexion'];

        View::render('connection', $data);
    }

    public function register(){
        $data = ['title' => 'Register'];

        View::render('connection', $data);

    }
}