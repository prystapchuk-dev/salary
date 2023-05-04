<?php

namespace Engine\Core\Config;

class Repository
{
    protected static array $stored = [];

    public static function store($group, $key, $data) : void
    {
        if (!isset(static::$stored[$group]) || !is_array(static::$stored[$group])) {
            static::$stored[$group] = [];
        }

        static::$stored[$group][$key] = $data;
    }

    public static function retrieve($group, $key) 
    {
        return static::$stored[$group][$key] ?? false;
    }

    public static function retrieveGroup($group) 
    {
        return static::$stored[$group] ?? false;
    }

}