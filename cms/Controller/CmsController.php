<?php

namespace Cms\Controller;

use Engine\Controller;

class CmsController extends Controller
{
    public array $data = [];
    public function __construct($di)
    {
        parent::__construct($di);
    }
}