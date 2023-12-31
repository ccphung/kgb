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
    session_start();
    
    foreach ($this->routes as $route) {
        if ($route['method'] === $method) {
            $pattern = '~^' . $route['path'] . '$~';
            if (preg_match($pattern, $uri, $matches)) {
                return [
                    'controller' => $route['controller'],
                    'action' => $route['action'],
                    'params' => isset($matches[1]) ? ['id' => $matches[1]] : []
                ];
            }
        }
    }
    return null;
}
}