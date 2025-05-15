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
        $callback = $this->routes[$this->request->method][$this->request->path] ?? false;
        if ($callback === false) {
            return Render::view(404);
        }
        switch (gettype($callback) == 'string') {
            case 'string':
                $cb = new $callback;
                return $cb();
                break;
            case 'array':
                $cb = new $callback[0];
                return $cb->{$callback[1]}($this->request);
                break;
            default:
                return call_user_func($callback, $this->request);
                break;
        }
    }
}
