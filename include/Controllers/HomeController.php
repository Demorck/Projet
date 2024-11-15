<?php

namespace App\Controllers;

use App\Helpers\View;

class HomeController {
    public function index() {
        $data = ['title' => 'Accueil'];

        View::render('homepage', $data);
    }
}