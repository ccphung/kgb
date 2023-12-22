<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_URL', __DIR__);

require_once 'app/Controller.php';
require_once 'models/Router.php';
require_once 'controllers/Home.php';
require_once 'controllers/Login.php';
require_once 'controllers/Missions.php';
require_once 'controllers/Agents.php';

$router = new Router();

$router->addRoute('GET', '/home', 'Home', 'index');
$router->addRoute('GET', '/login', 'Login', 'index');
$router->addRoute('GET', '/agents', 'Agents', 'index');
$router->addRoute('GET', '/missions', 'Missions', 'index');
$router->addRoute('GET', '/missions/(\d+)', 'Missions', 'details');

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$handler = $router->getHandler($method, $uri);

if ($handler == null) {
    echo "La page demandée n'existe pas.";
    exit();
}

$controller = new $handler['controller']();
$action = $handler['action'];
$controller->$action(isset($handler['params']['id']) ? $handler['params']['id'] : null);

?>