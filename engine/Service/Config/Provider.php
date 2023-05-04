<?php

namespace Engine\Service\Config;

use Engine\Core\Config\Config;
use Engine\Service\AbstractProvider;

class Provider extends AbstractProvider
{
    public string $serviceName = 'config';

    function init()
    {
       // $config['main'] = Config::file('main');
        $config['database'] = Config::file('database');

        $this->di->set($this->serviceName, $config);
    }


}