<?php
class Router { 
    private $routes;
    public function __construct() {
    $this->routes = [];
}
    public function addRoute(string $method,string $path, string $controller, string $action)  { 
    $this->routes [] = [
        'method' => $method,
        'path' => $path,
        'controller' =>$controller,
        'action' => $action
    ];    
}
public function getHandler(string $method, string $uri) { 
    foreach ($this->routes as $route) {
        // Vérifiez si la méthode correspond
        if ($route['method'] === $method) {
            // Utilisez une expression régulière pour comparer l'URI avec le chemin de la route
            $pattern = '~^' . $route['path'] . '$~';
            if (preg_match($pattern, $uri)) {
                // Retournez le contrôleur et l'action si la route correspond
                return [
                    'controller' => $route['controller'],
                    'action' => $route['action']
                ];
            }
        }
    }
    return null;
 }
}
