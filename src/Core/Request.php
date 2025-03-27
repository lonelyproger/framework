<?php

namespace Lonelyproger\Framework\Core;

class Request
{
    public function getPath()
    {
        $path = trim($_SERVER['REQUEST_URI'], '/') ?? '';
        $path = explode('/', $path);
        $position = strpos($path[count($path) - 1], '?');
        if ($position === false) return $path;
        $path[count($path) - 1] = substr($path[count($path) - 1], 0, $position);
        return $path;
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}
