<?php

namespace Engine\Core\Template;

use Engine\DI\DI;
class Menu
{
    protected static $di;

    protected static $menuRepository;

    protected static $menuItemRepository;

    public function __construct($di)
    {
        self::$di = $di;
        //self::$menuRepository = new MenuRepository(self::$di);
        //self::$menuItemRepository = new MenuItemRepository(self::$di);
    }

    public static function getItems($menuId)
    {
        return self::$menuItemRepository->getItems($menuId);
    }


}