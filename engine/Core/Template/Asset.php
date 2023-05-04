<?php

namespace Engine\Core\Template;

class Asset
{
    const EXT_JS = '.js';
    const EXT_CSS = '.css';
    const EXT_LESS = '.less';

    const JS_SCRIPT_MASK = '<script src="%s" type="text/javascript"></script>';
    const CSS_LINK_MASK = '<link rel="stylesheet" href="%s">';

    public static array $container = [];

    public static function css($link)
    {
        $file = Theme::getThemePath() . DS . $link . self::EXT_CSS;
        if (is_file($file)) {
            self::$container['css'][] = [
                'file' => Theme::getUrl() . DS . $link . self::EXT_CSS
            ];
        }
    }

}