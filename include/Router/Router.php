<?php

namespace App\Router;

use App\Helpers\View;

/**
 * Class Router qui permet de gérer les routes
 * 
 * @package App\Router
 * @author Maximilien ANTOINE
 */
class Router {
    /**
     * URL de la requête
     *
     * @var string
     */
    private string $url;

    /**
     * Tableau des routes
     *
     * @var array
     */
    private array $routes = [];

    /**
     * Constructeur de la classe Router
     *
     * @param string $url
     */
    public function __construct($url) {
        $this->url = parse_url($url, PHP_URL_PATH);
        $this->url = trim($this->url, '/');
    }

    /**
     * Ajoute une route GET
     *
     * @param string $path
     * @param string $controller
     * @param mixed $callback
     */
    public function get(string $path, string $controller, mixed $callback) {
        $this->addRoute('GET', $path, $controller, $callback);
    }


    /**
     * Ajoute une route POST
     *
     * @param string $path
     * @param string $controller
     * @param mixed $callback
     */
    public function post(string $path, string $controller, mixed $callback) {
        $this->addRoute('POST', $path, $controller, $callback);
    }

    /**
     * Ajoute une route
     *
     * @param string $method
     * @param string $path
     * @param mixed $callback
     */
    private function addRoute(string $method, string $path, string $controller, mixed $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => trim($path, '/'),
            'controller' => $controller,
            'callback' => $callback,
        ];
    }

    /**
     * Exécute le routeur
     */
    public function run() {
        foreach ($this->routes as $route) {
            if ($this->match($route)) {
                return $this->call($route['controller'], $route['callback']);
            }
        }

        // TODO: Mettre une route pour l'erreur 404
        http_response_code(404);
        View::render('homepage', ['title' => '404 Not Found']);
    }

    /**
     * Vérifie si la route correspond à la requête
     *
     * @param array $route
     * @return void
     */
    private function match(array $route) {
        return $this->url === $route['path'] && $_SERVER['REQUEST_METHOD'] === $route['method'];
    }

    /**
     * Appelle la fonction ou la méthode du contrôleur
     *
     * @param string $controller
     * @param mixed $callback
     * @return void
     */
    private function call(string $controller, mixed $callback) {
        if (is_string($callback)) {
            $controller = new $controller();
            return call_user_func_array([$controller, $callback], []);
        }
        return call_user_func($callback);
    }
}
