<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_URL', __DIR__);

require_once 'app/Controller.php';
require_once 'models/Router.php';
require_once 'controllers/Home.php';
require_once 'controllers/Login.php';
require_once 'controllers/Missions.php';

$router = new Router();

$router->addRoute('GET', '/home', 'Home', 'index');
$router->addRoute('GET', '/login', 'Login', 'index');
$router->addRoute('GET', '/missions', 'Missions', 'index');

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