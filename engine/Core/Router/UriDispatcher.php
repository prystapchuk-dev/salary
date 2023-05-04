<?php

namespace Engine\Core\Router;

class UriDispatcher
{
    private array $methods = [
        'GET',
        'POST'
    ];

    private array $routes = [
        'GET' => [],
        'POST' => []
    ];

    private array $pattrens = [
        'int' => '[a-zA-Z\.\-_%]]+',
        'str' => '[a-zA-Z\.\-_%]+',
        'any' => '[a-zA-Z0-9\.\-_%]]+'
    ];


    public function addPattern($key, $pattern): void
    {
        $this->pattrens[$key] = $pattern;
    }

    private function routes($method): array
    {
        return $this->routes[$method] ?? [];
    }

    public function register($method, $pattern, $controller): void
    {
        $convert = $this->convertPattern($pattern);
        $this->routes[strtoupper($method)][$convert] = $controller;
    }

    private function convertPattern($pattern)
    {
        if (!str_contains($pattern, '(')) {
            return $pattern;
        }

        return preg_replace_callback('#\((\w+):(\w)\)#', [$this, 'replacePattern'], $pattern);
    }

    private function replacePattern($matches): string
    {
        return '(?<' . $matches[1] . '>' . strtr($matches[2], $this->pattrens) . ')';
    }

    private function processParam($parameters)
    {
        foreach ($parameters as $key => $value) {
           
            if (is_int($key)) {
                unset($parameters[$key]);
            }
        }

        return $parameters;
    }

    public function dispatch($method, $uri)
    {
        $routes = $this->routes(strtoupper($method));

        if (array_key_exists($uri, $routes)) {
            return new DispatchedRoute($routes[$uri], $_GET);
        }
        return $this->doDispatch($method, $uri);
    }

    private function doDispatch($method, $uri)
    {
        foreach ($this->routes($method) as $route => $controller) {
            $pattern = '#^' . $route . '$#s';
            
            if (preg_match($pattern, $uri, $parameters)) {
                return new DispatchedRoute($controller, $this->processParam($parameters));
            }
        }
    }
}