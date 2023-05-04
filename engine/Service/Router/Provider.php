<?php

namespace Engine\Service\Router;

use Engine\Core\Router\Router;
use Engine\Service\AbstractProvider;

class Provider extends AbstractProvider
{
    public string $serviceName = 'router';

    function init()
    {
        $router = new Router('127.0.0.1:8080');

        $this->di->set($this->serviceName, $router);
    }


}