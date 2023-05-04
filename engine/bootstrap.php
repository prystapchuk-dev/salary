<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Engine\DI\DI;
use Engine\Cms;

try {
    $di = new DI();

    $services = require __DIR__ . '/config/service.php';

    foreach ($services as $service) {
        $provider = new $service($di);
        $provider->init();
    }
        $cms = new Cms($di);
        $cms->run();
    
} catch (\ErrorException $e) {
    echo $e->getMessage();
}