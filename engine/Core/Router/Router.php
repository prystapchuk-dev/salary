<?php

namespace Engine\Core\Router;

class Router
{
    private array $routes = [];
    private $dispatcher;

    private string $host;

    /**
     * @param string $host
     */
    public function __construct(string $host)
    {
        $this->host = $host;
    }

    public function add($key, string $pattern, $controller, string $method = 'GET')
    {
        $this->routes[$key] = [
            'pattern'    => $pattern,
            'controller' => $controller,
            'method'     => $method
        ];
    }

    public function dispatch($method, $uri)
    {
        return $this->getDispatcher()->dispatch($method, $uri);
    }

    public function getDispatcher()
    {
        if ($this->dispatcher == null) {
            $this->dispatcher = new UriDispatcher();
        }

        foreach ($this->routes as $route) {
            $this->dispatcher->register($route['method'], $route['pattern'], $route['controller']);
        }

        return $this->dispatcher;
    }




}