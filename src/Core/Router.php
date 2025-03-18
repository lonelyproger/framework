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
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {
            return "404";
        }
        var_dump($GLOBALS['app']->basePath());
        if (gettype($callback) == 'string') {
            $ns = substr($callback, 0, strrpos($callback, '\\'));
            return call_user_func($callback);
        } else {
            return call_user_func($callback);
        }
    }
}
