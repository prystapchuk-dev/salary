<?php

namespace Engine\Core\Config;

class Config
{
    public static function item($key, $group = 'main') 
    {
        if (Repository::retrieve($group, $key)) {
            self::file($group);
        }

        return Repository::retrieve($group, $key);
    }

    public static function group($group)
    {
       if (!Repository::retrieveGroup($group)) {
           self::file($group);
       }

       return Repository::retrieveGroup($group);
    }

    public static function file($group = 'main') : bool|\Exception
    {
        $path  =  __DIR__ . DS .  $group . '.php';
        
        if (file_exists($path)) {

            $items = include $path;

            if (is_array($items)) {
                foreach ($items as $key => $value) {
                    Repository::store($group, $key, $value);
                }

                return true;
            } else {
                throw new \Exception(
                    sprintf(
                        'Config file <strong>%s</strong> is not a valid array.',
                        $path
                    )
                );
            }
        } else {
            throw new \Exception(
                sprintf(
                    'Cannot load config file, file <strong>%s</strong> does not exist.',
                    $path
                )
            );
        }

        return false;
    }
}