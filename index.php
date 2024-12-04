<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/autoload.php";

use App\Router\Router;

$router = new Router($_SERVER['REQUEST_URI']);


$router->get('/', App\Controllers\HomeController::class, 'index');

$router->get('/install', App\Controllers\InstallDatabaseController::class, 'index');
$router->post('/install', App\Controllers\InstallDatabaseController::class, 'installDatabase');

$router->get('/hierarchie', App\Controllers\HierarchieController::class, 'index');
$router->get('/connection', App\Controllers\ConnectionController::class, 'index');
$router->post('/register', App\Controllers\ConnectionController::class, 'register');

$router->run();