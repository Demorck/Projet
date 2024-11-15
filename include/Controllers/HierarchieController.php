<?php

namespace App\Controllers;

use App\Helpers\View;

class HierarchieController {
    public function index() {
        $data = ['title' => 'Accueil'];

        View::render('hierarchie_tabs', $data);
    }
}