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
require_once 'controllers/Admins.php';
require_once 'controllers/AuthController.php';


$router = new Router();

$router->addRoute('GET', '/admin', 'Admins', 'index');
$router->addRoute('GET', '/admin/missions', 'Admins', 'missions');
$router->addRoute('GET', '/admin/missions(\?page=\d+)?$', 'Admins', 'missions');
$router->addRoute('POST', '/admin/mission/modify', 'Admins', 'processSelection');
$router->addRoute('POST', '/admin/mission/post/(\d+)', 'Admins', 'processMissionForm');
$router->addRoute('GET', '/admin/mission/modify/(\d+)', 'Admins', 'createMissionForm');
$router->addRoute('POST', '/admin/mission/delete/(\d+)', 'Admins', 'processMissionDelete');
$router->addRoute('GET', '/admin/agents(\?page=\d+)?$', 'Admins', 'agents');
$router->addRoute('GET', '/admin/agent/modify/(\d+)', 'Admins', 'createAgentForm');
$router->addRoute('POST', '/admin/agent/post/(\d+)', 'Admins', 'processAgentForm');
$router->addRoute('POST', '/admin/agent/delete/(\d+)', 'Admins', 'processAgentDelete');
$router->addRoute('GET', '/admin/contacts(\?page=\d+)?$', 'Admins', 'contacts');
$router->addRoute('GET', '/admin/targets(\?page=\d+)?$', 'Admins', 'targets');
$router->addRoute('GET', '/admin/target/modify/(\d+)', 'Admins', 'createTargetForm');
$router->addRoute('GET', '/admin/contact/modify/(\d+)', 'Admins', 'createContactForm');
$router->addRoute('POST', '/admin/contact/post/(\d+)', 'Admins', 'processContactForm');
$router->addRoute('POST', '/admin/contact/delete/(\d+)', 'Admins', 'processContactDelete');
$router->addRoute('POST', '/admin/target/delete/(\d+)', 'Admins', 'processTargetDelete');
$router->addRoute('POST', '/admin/target/post/(\d+)', 'Admins', 'processTargetForm');

$router->addRoute('GET', '/home', 'Home', 'index');

$router->addRoute('GET', '/login', 'Login', 'index');

$router->addRoute('GET', '/agents(\?page=\d+)?$', 'Agents', 'index');
$router->addRoute('GET', '/agents/add', 'Agents', 'createForm');
$router->addRoute('POST', '/agents/post', 'Agents', 'processForm')
;
$router->addRoute('GET', '/contacts(\?page=\d+)?$', 'Contacts', 'index');
$router->addRoute('GET', '/contacts/add', 'Contacts', 'createForm');
$router->addRoute('POST', '/contacts/post', 'Contacts', 'processForm');

$router->addRoute('GET', '/targets(\?page=\d+)?$', 'Targets', 'index');
$router->addRoute('GET', '/targets/add', 'Targets', 'createForm');
$router->addRoute('POST', '/targets/post', 'Targets', 'processForm');

$router->addRoute('GET', '/missions/(\d+)', 'Missions', 'details');
$router->addRoute('GET', '/missions(\?page=\d+)?$', 'Missions', 'index');
$router->addRoute('GET', '/missions/add', 'Missions', 'createForm');
$router->addRoute('POST', '/missions/post', 'Missions', 'processSelection');
$router->addRoute('POST', '/mission/post', 'Missions', 'processForm');

$router->addRoute('POST', '/login', 'AuthController', 'login');
$router->addRoute('GET', '/logout', 'AuthController', 'logout');

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$handler = $router->getHandler($method, $uri);

if ($handler == null) {
    echo "La page demandée n'existe pas.";
    echo "URL: " . $_SERVER['REQUEST_URI'];
    exit();
}

$controller = new $handler['controller']();
$action = $handler['action'];
$controller->$action(isset($handler['params']['id']) ? $handler['params']['id'] : null);

?>