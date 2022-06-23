<?php
session_start();

require 'vendor/autoload.php';

if(! isset($_SESSION['auth']) && $_SERVER['REQUEST_URI'] !== '/login'){
    header('Location: /login');
    return;
}

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    /*$r->addRoute('GET', '/users', 'get_all_users_handler');
    // {id} must be a number (\d+)
    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    // The /{title} suffix is optional
    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');*/
    $r->addRoute('GET', '/',function(){
        $controller = new App\Controller\Main();
        $controller->run();
    });

    $r->addRoute(['GET','POST'], '/login',function(){
        $controller = new App\Controller\Login();
        $controller->run();
    });
    $r->addRoute(['GET','POST'], '/logout',function(){
        $controller = new App\Controller\Login();
        $controller->runLogout();
    });


    #- Accounts routes:
    $r->addRoute('GET', '/accounts',function(){
        $controller = new App\Controller\Accounts();
        $controller->run();
    });
    $r->addRoute(['GET','POST'], '/accounts/add',function(){
        $controller = new App\Controller\Accounts();
        $controller->runAdd ();
    });
    $r->addRoute(['GET','POST'], '/accounts/update',function(){
        $controller = new App\Controller\Accounts();
        $controller->runUpdate ();
    });
    $r->addRoute(['GET','POST'], '/accounts/delete',function(){
        $controller = new App\Controller\Accounts();
        $controller->runDelete();
    });




    #- Tasks routes:
    $r->addRoute('GET', '/tasks',function(){
        $controller = new App\Controller\Tasks();
        $controller->run();
    });
    $r->addRoute(['GET','POST'], '/tasks/add',function(){
        $controller = new App\Controller\Tasks();
        $controller->runAdd ();
    });
    $r->addRoute(['GET','POST'], '/tasks/update',function(){
        $controller = new App\Controller\Tasks();
        $controller->runUpdate ();
    });
    $r->addRoute(['GET','POST'], '/tasks/delete',function(){
        $controller = new App\Controller\Tasks();
        $controller->runDelete();
    });
    

    if(isset($_SESSION['auth']) && (int)$_SESSION['auth']['privilege'] == 1){
        # Controlleruser route:
        $r->addRoute('GET', '/users',function(){
            $controller = new App\Controller\Users();
            $controller->run();
        });
        $r->addRoute(['GET','POST'], '/users/add',function(){
            $controller = new App\Controller\Users();
            $controller->runAdd ();
        });
        $r->addRoute(['GET','POST'], '/users/update',function(){
            $controller = new App\Controller\Users();
            $controller->runUpdate();
        });
        $r->addRoute(['GET','POST'], '/users/delete',function(){
            $controller = new App\Controller\Users();
            $controller->runDelete();
        });
    }
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
        echo 'Rout is not created';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo 'Have a rout but not method';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $handler($vars);
        // ... call $handler with $vars
        break;
    }