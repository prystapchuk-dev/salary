<?php
namespace Engine;
use Engine\Core\Config\Repository;
use Engine\Core\Router\DispatchedRoute;
use Engine\Core\Router\Router;
use Engine\DI\DI;
use Engine\Helper\Common;


class Cms
{
     private DI $di;
     private Router $router;

    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
        $this->router = $this->di->get('router');
    }

    public function run() 
    {
        require_once __DIR__ . '/Core/Config/' . 'route.php';
        /*var_dump($this->di);
        $pdo = $this->di->get('db');
        $conf = $this->di->get('config');
        $row =  $pdo->query('SHOW VARIABLES like "version"');
        var_dump($row);*/

        $routerDispatch = $this->router->dispatch(Common::getMethod(), Common::getPatchUrl());

        if ($routerDispatch === null) {
            $routerDispatch = new DispatchedRoute('ErrorController:page404');
        }

        list($class, $action) = explode(':', $routerDispatch->getController(), 2);
        
        $controller = "\\Cms\\Controller\\$class";
          
        //$controller1 = new $controller;
        //$controller2 = new HomeController();
        //var_dump($controller, $controller2);
       // $controller->$action('Hello');
        $parameters = $routerDispatch->getParameters();
        
        call_user_func_array([new $controller($this->di), $action], $parameters);

        
        //var_dump($routerDispatch);
        //var_dump($_SERVER);
    }


}