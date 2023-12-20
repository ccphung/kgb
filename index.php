<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/Controller.php';
require_once 'models/Router.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/LoginController.php';
require_once 'controllers/MissionsController.php';

$router = new Router();

$router->addRoute('GET', '/home', 'HomeController', 'index');
$router->addRoute('GET', '/login', 'LoginController', 'index');
$router->addRoute('GET', '/missions', 'MissionsController', 'index');

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$handler = $router->getHandler($method, $uri);

if ($handler == null ) { 
    exit();
}

$controller = new $handler['controller']();
$action = $handler['action'];
$controller->$action();

?>