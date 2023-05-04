<?php

namespace Engine\Service;

use Engine\DI\DI;
abstract class AbstractProvider
{
    protected DI $di;

    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }

    abstract function init();

}