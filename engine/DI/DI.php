<?php

namespace Engine\DI;

class DI
{
    private array $container = [];

    public function set($key, $value) : void
    {
       $this->container[$key] = $value;
    }

    public function get($key) : mixed
    {
        return $this->has($key);
    }

    public function has($key) : mixed
    {
      return $this->container[$key] ?? null;
    }
}