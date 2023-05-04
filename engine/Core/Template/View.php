<?php

namespace Engine\Core\Template;

use Engine\DI\DI;
class View
{
    public DI $di;

    protected $theme;

    protected $setting;

    protected $menu;

    /**
     * @param DI $di
     * @param $theme
     * @param $setting
     * @param $menu
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
        $this->theme = new Theme();
        $this->setting = new Setting($di);
        $this->menu = new Menu($di);
    }

    public function render($template, $data = [])
    {
        $functions = Theme::getThemePath() . '/functions.php';

        if (file_exists($functions)) {
            include_once $functions;
        }

        $templatePath = $this->getTemplatePath($template, 'Admin');

        if (!is_file($templatePath)) {
            throw new \InvalidArgumentException(
                sprintf('Template %s not found in "%s"', $template, $templatePath)
            );
        }

        $data['lang'] = $this->di->get('language');

        $this->theme->setData($data);

        extract($data);
        ob_start();
        ob_implicit_flush(0);

        try {
            require ($templatePath);
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        echo ob_get_clean();
    }

    private function getTemplatePath($tempalte, $env = null)
    {
        if ($env === 'Cms') {
            return ROOT_DIR . '/content/themes/default' . $tempalte . '.php';
        }

        return ROOT_DIR . '/content/themes/default' . '/' . $tempalte . '.php';
    }




}