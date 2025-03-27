<?php

namespace Lonelyproger\Framework\Core;

class Router
{
    protected array $routes = [];
    public Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }


    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path[0]] ?? false;
        if ($callback === false) {
            return "404";
        }
        if (gettype($callback) == 'string') {
            $cb = new $callback;
            // var_dump($cb->content->template_content);
            return $cb->content;
        } else {
            return call_user_func($callback, $path);
        }
    }
}
