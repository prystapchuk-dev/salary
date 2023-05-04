<?php

namespace Engine\Core\Template;

class Theme
{
    const RULES_NAME_FILE = [
        'header' => 'header-%s',
        'footer' => 'footer-%s',
        'sidebar' => 'sidebar-%s',
    ];

    const URL_THEME_MASK = '%s/content/themes%s';

    protected static string $url = '';
    protected static array $data = [];

    public $asset;

    public $theme;

    /**
     * @param $asset
     * @param $theme
     */
    public function __construct()
    {
        $this->asset = new Asset();
        $this->theme = $this;
    }

    public static function getUrl()
    {
        $currentTheme = Setting::get('defaultTheme', 'main');
        $baseUrl = Setting::get('baseUrl', 'main');

        return sprintf(self::URL_THEME_MASK, $baseUrl, $currentTheme);
    }

    public static function title()
    {
        $nameSite = Setting::get('name_site');
        $description = Setting::get('description');

        echo $nameSite . '|' . $description;
    }

    public static function header($name = null)
    {
        $name = (string) $name;
        $file = self::detectNameFile($name, __FUNCTION__);

        Component::load($file);
    }
    public static function footer($name = '')
    {
        $name = (string) $name;
        $file = self::detectNameFile($name, __FUNCTION__);

        Component::load($file);
    }

    public static function sidebar($name = '')
    {
        $name = (string) $name;
        $file = self::detectNameFile($name, __FUNCTION__);

        Component::load($file);
    }
    public static function block($name = '', $data = [])
    {
        $name = (string) $name;

        if ($name !== '') {
            Component::load($name, $data);
        }
    }

    private static function detectNameFile($name, $function)
    {
        return empty(trim($name)) ? $function : sprintf(self::RULES_NAME_FILE[$function], $name);
    }

    public static function getData()
    {
        return static::$data;
    }

    public static function setData($data)
    {
        static::$data = $data;
    }


    public static function getThemePath()
    {
        return ROOT_DIR . '/content/themes/default';
    }


}