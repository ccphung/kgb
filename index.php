<?php 

require_once 'models/Router.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/LoginController.php';

$router = new Router();

$router->addRoute('GET', '/home', 'HomeController', 'index');
$router->addRoute('GET', '/login', 'LoginController', 'index');

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$handler = $router->getHandler($method, $uri);

if ($handler == null ) { 
    echo("null");
    exit();
}

$controller = new $handler['controller']();
$action = $handler['action'];
$controller->$action();
?>