<?php

namespace Lonelyproger\Framework\Core;

use function Lonelyproger\Framework\Core\join_paths;

class Application
{
    protected $basePath;
    public Router $router;

    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }
        $this->router = new Router();
    }
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');
        return $this;
    }
    public function basePath($path = '')
    {
        return join_paths($this->basePath, $path);
    }

    public function run()
    {
        $result = $this->router->resolve();
        print $result;
    }
}
