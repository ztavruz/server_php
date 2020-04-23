<?php


namespace Engine\Router;


class Router
{
    private $routes = [];
    private $accessLevel = [];

    public function add($url, $controller, $method, $level_access = "A")
    {
        $this->routes[$url] = [
            '_controller' => $controller,
            '_method' => $method,
            '_access' => $level_access
        ];

        $this->accessLevel[] = $level_access;
    }

    public function match(string $url)
    {

        foreach ($this->routes as $route => $data){
            if($route == $url){
                return $data;
            }
        }
    }

    public function addAccessLevel(string $level)
    {
        $this->accessLevel[] = $level;
    }

    public function deleteAccessLevel(string $delete)
    {
        foreach ($this->accessLevel as $key => $level){
            if($level == $delete)
            {
                unset($this->accessLevel[$key]);
            }
        }
    }
}