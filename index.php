<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/autoload.php";

use App\Router\Router;

$router = new Router($_SERVER['REQUEST_URI']);


$router->get('/', App\Controllers\HomeController::class, 'index');

$router->get('/install', App\Controllers\InstallDatabaseController::class, 'index');
$router->post('/install', App\Controllers\InstallDatabaseController::class, 'installDatabase');

$router->get('/recherche', App\Controllers\SearchController::class, 'index');

$router->get('/search/searchIngredients', App\Controllers\SearchController::class, 'searchIngredients');
$router->get('/search/hierarchy', App\Controllers\SearchController::class, 'getHierarchie');
$router->post('/search/searchRecipes', App\Controllers\SearchController::class, 'searchRecipes');
$router->post('/search/id', App\Controllers\SearchController::class, 'searchId');


$router->post('/favorite/add', App\Controllers\RecipeController::class, 'addToFavorites');
$router->post('/favorite/remove', App\Controllers\RecipeController::class, 'removeFromFavorites');
$router->post('/favorite/get', App\Controllers\RecipeController::class, 'getFavorite');

$router->get('/connect', App\Controllers\ConnectionController::class, 'index');
$router->get('/deconnect', App\Controllers\ConnectionController::class, 'disconnect');
$router->post('/register', App\Controllers\ConnectionController::class, 'register');
$router->post('/login', App\Controllers\ConnectionController::class, 'login');
$router->get('/session', App\Controllers\ConnectionController::class, 'getSession');
$router->get('/panier', App\Controllers\FavoriteController::class, 'index');
$router->get('/compte', App\Controllers\AccountController::class, 'index');
$router->post('/change', App\Controllers\AccountController::class, 'changeInformations');

$router->run();