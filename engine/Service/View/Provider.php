<?php

namespace Engine\Service\View;

use Engine\Core\Template\View;
use Engine\Service\AbstractProvider;

class Provider extends AbstractProvider
{
    public string $serviceName = 'view';

    function init(): void
    {
        $view = new View($this->di);

        $this->di->set($this->serviceName, $view);
    }


}