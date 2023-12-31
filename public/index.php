<?php

use Delight\Auth\Auth;
use League\Plates\Engine;
use function Tamtamchik\SimpleFlash\flash;

if( !session_id() ) {
    session_start();
}

require '../vendor/autoload.php';
use DI\ContainerBuilder;
$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions([
    Engine::class => function() {
        return new Engine('../app/views');
    },

    PDO::class => function() {
        $driver = 'mysql';
        $host = 'localhost';
        $database_name = 'components';
        $username = 'root';
        $password = '';

        return new PDO("$driver:host=$host;dbname=$database_name", $username, $password);
    },

    Auth::class => function($container) {
        return new Auth($container->get('PDO'));
    }
]);
$container = $containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\controllers\UserController', 'index']);
    $r->addRoute('GET', '/login', ['App\controllers\LoginController', 'showLoginPage']);
    $r->addRoute('POST', '/login', ['App\controllers\LoginController', 'login']);
    $r->addRoute('GET', '/logout', ['App\controllers\LogOutController', 'logout']);
    $r->addRoute('GET', '/register', ['App\controllers\RegisterController', 'showRegisterPage']);
    $r->addRoute('POST', '/register', ['App\controllers\RegisterController', 'registerUser']);
    $r->addRoute('GET', '/create', ['App\controllers\UserController', 'showCreatePage']);
    $r->addRoute('POST', '/create', ['App\controllers\UserController', 'createNewUser']);
    $r->addRoute('GET', '/edit/{id:\d+}', ['App\controllers\EditController', 'showEditPage']);
    $r->addRoute('POST', '/edit/{id:\d+}', ['App\controllers\EditController', 'editUser']);
    $r->addRoute('GET', '/profile/{id:\d+}', ['App\controllers\UserController', 'showProfilePage']);
    $r->addRoute('GET', '/security/{id:\d+}', ['App\controllers\SecurityController', 'showSecurityPage']);
    $r->addRoute('POST', '/security/{id:\d+}', ['App\controllers\SecurityController', 'editSecurityInfoOfUser']);
    $r->addRoute('GET', '/status/{id:\d+}', ['App\controllers\StatusController', 'showStatusPage']);
    $r->addRoute('POST', '/status/{id:\d+}', ['App\controllers\StatusController', 'editStatus']);
    $r->addRoute('GET', '/media/{id:\d+}', ['App\controllers\MediaController', 'showMediaPage']);
    $r->addRoute('POST', '/media/{id:\d+}', ['App\controllers\MediaController', 'editAvatar']);
    $r->addRoute('GET', '/delete/{id:\d+}', ['App\controllers\UserController', 'deleteUser']);

});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo '404';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo '405';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->call($routeInfo[1], $routeInfo[2]);

        break;
}



