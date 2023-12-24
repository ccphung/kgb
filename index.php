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
require_once 'controllers/Contacts.php';
require_once 'controllers/Targets.php';


$router = new Router();

$router->addRoute('GET', '/home', 'Home', 'index');
$router->addRoute('GET', '/login', 'Login', 'index');
$router->addRoute('GET', '/agents(\?page=\d+)?$', 'Agents', 'index');
$router->addRoute('GET', '/contacts(\?page=\d+)?$', 'Contacts', 'index');
$router->addRoute('GET', '/targets(\?page=\d+)?$', 'Targets', 'index');
$router->addRoute('GET', '/missions/(\d+)', 'Missions', 'details');
$router->addRoute('GET', '/missions(\?page=\d+)?$', 'Missions', 'index');

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$handler = $router->getHandler($method, $uri);

if ($handler == null) {
    echo "La page demandée n'existe pas.";
    var_dump($_GET);
    echo "URL: " . $_SERVER['REQUEST_URI'];
    exit();
}

$controller = new $handler['controller']();
$action = $handler['action'];
$controller->$action(isset($handler['params']['id']) ? $handler['params']['id'] : null);

?>