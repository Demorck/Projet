<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Models\SearchModel;
use App\Models\HierarchieModel;

/**
 * Class SearchController
 */
class SearchController {
    private $model;

    public function __construct() {
        $this->model = new SearchModel();
    }

    public function index() {
        $data = ['title' => 'Rechercher'];

        View::render('recherche', $data);
    }

    public function searchIngredients() {
        $term = $_GET['term'] ?? '';
        header('Content-Type: application/json');
        echo json_encode($this->model->searchIngredients($term));
    }

    public function searchRecipes() {
        $data = json_decode(file_get_contents('php://input'), true);
        $included = $data['included'] ?? [];
        $excluded = $data['excluded'] ?? [];
        
        header('Content-Type: application/json');
        echo json_encode($this->model->searchRecipes($included, $excluded));
    }
    
    public function searchId() {
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'] ?? 0;

        header('Content-Type: application/json');
        echo json_encode($this->model->searchId($name));
    }

    public function getHierarchie() {
        $hierarchieModel = new HierarchieModel();
        header('Content-Type: application/json');
        echo json_encode($hierarchieModel->getHierarchie());
    }
}