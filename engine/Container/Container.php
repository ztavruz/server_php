<?php


namespace Engine\Container;


class Container
{
    private $container = [];

    public function set($key, $value)
    {
        $this->container[$key] = $value;
    }

    public function get($key)
    {
//        var_dump($key);
        if(!isset($this->container[$key]))
        {
            throw new \LogicException("Сервис не найден(Service not found)! " . $key);
        }
        if(is_callable($this->container[$key]))
        {
            $this->container[$key] = call_user_func($this->container[$key], $this);
        }

        return $this->container[$key];
    }
}