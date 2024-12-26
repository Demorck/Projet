<?php

namespace App\Controllers;

use App\Helpers\View;
use PDO;

class HierarchieController {
    public function index() {
        $data = ['title' => 'Hierarchie'];

        View::render('rechercher', $data);
    }

    
}