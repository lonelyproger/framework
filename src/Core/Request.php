<?php

namespace Lonelyproger\Framework\Core;

class Request
{
    public $post;
    public $get;
    public $files;
    public $session;
    public $cookie;
    public $env;
    public $request;
    public $server;
    public $path;
    public $method;
    public $query;

    public function __construct()
    {
        $this->post = $_POST;
        $this->get = $_GET;
        $this->files = $_FILES;
        $this->session = $_SESSION;
        $this->cookie = $_COOKIE;
        $this->env = $_ENV;
        $this->request = $_REQUEST;
        $this->server = $_SERVER;
        $this->path = $this->getPath();
        $this->method = $this->getMethod();
        $this->query = $this->getQuery();
    }
    public function getPath()
    {
        $path = parse_url(trim($_SERVER['REQUEST_URI'], '/'))['path'] ?? '';
        return $path;
    }
    public function getQuery()
    {
        $query = parse_url(trim($_SERVER['REQUEST_URI'], '/'), PHP_URL_QUERY) ?? '';
        parse_str($query, $query);
        return $query;
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}
