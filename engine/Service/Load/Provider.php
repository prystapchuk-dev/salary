<?php

namespace Engine\Service\Load;

use Engine\Load;
use Engine\Service\AbstractProvider;

class Provider extends AbstractProvider
{
    public string $serviceName = 'load';

    function init()
    {
        $load = new Load($this->di);

        $this->di->set($this->serviceName, $load);

        return $this;
    }


}