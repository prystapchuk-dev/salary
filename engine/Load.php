<?php

namespace Engine;


use Cms\Controller\HomeController;
use Cms\Model\Menu\Menu;
use Engine\DI\DI;
class Load
{
    const MASK_MODEL_ENTITY = '\%s\Model\%s\%s';
    const MASK_MODEL_REPOSITORY = '\%s\Model\%s\%sRepository';

    const FILE_MASK_LANGUAGE = 'Language/%s/%s.ini';

    public DI $di;

    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;

        return $this;
    }

    public function model($modelName, $modelDir = false, $env = false)
    {

        $modelName = ucfirst($modelDir);
        $modelName = $modelDir ?? $modelName;
        $env       = 'Cms';

        $namespaceModel = sprintf(
            self::MASK_MODEL_REPOSITORY,
            $env, $modelDir, $modelName
        );
        
        $isClassModel = class_exists($namespaceModel);

        if ($isClassModel) {
            
            $modelRegistry = $this->di->get('model') ?: new \stdClass();
            $modelRegistry->{lcfirst($modelName)} = new $namespaceModel($this->di);

            $this->di->set('model', $modelRegistry);
            
        }
        
        return $isClassModel;
    }
}