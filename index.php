<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/autoload.php";

use App\Router\Router;

$router = new Router($_SERVER['REQUEST_URI']);


$router->get('/', App\Controllers\HomeController::class, 'index');

$router->get('/install', App\Controllers\InstallDatabaseController::class, 'index');
$router->post('/install', App\Controllers\InstallDatabaseController::class, 'installDatabase');

$router->get('/rechercher', App\Controllers\HierarchieController::class, 'index');
$router->get('/recherche', App\Controllers\SearchController::class, 'index');

$router->get('/search/searchIngredients', App\Controllers\SearchController::class, 'searchIngredients');
$router->post('/search/searchRecipes', App\Controllers\SearchController::class, 'searchRecipes');

$router->get('/connection', App\Controllers\ConnectionController::class, 'index');
$router->post('/register', App\Controllers\ConnectionController::class, 'register');
$router->post('/login', App\Controllers\ConnectionController::class, 'login');

$router->run();