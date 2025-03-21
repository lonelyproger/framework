<?php

namespace Lonelyproger\Framework\Core;

class Request
{
    public function getPath()
    {
        $path = trim($_SERVER['REQUEST_URI'], '/') ?? '';
        $position = strpos($path, '?');

        if ($position === false) return $path;
        return substr($path, 0, $position);
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}
