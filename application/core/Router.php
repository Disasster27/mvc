<?php

namespace application\core;

class Router extends Controller
{
    protected $routes = [];
    protected $params = [];

    public function __construct()
    {
        $arr = require DIR . 'application/config/routes.php';
        foreach ($arr as $key => $val) {
            $this->add($val['url'], $val);
        }
    }

    public function add($route, $params)
    {
        $route = preg_replace('/{([a-z]+):([^\}]+)}/', '(?P<\1>\2)', $route);
        $params['url'] = '#^' . $route . '$#';
        $this->routes[] = $params;
    }

    public function match()
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        $pos = strripos($url, "?");
        if ($pos !== false) {
            $url = stristr($url, '?', true);
        }

        foreach ($this->routes as $params) {
            if (preg_match($params['url'], $url, $matches)) {
                if (strtoupper($params['method']) === $this->getMethod()) {
                    $this->params = $params;
                    return true;
                }
            }
        }
        return false;
    }

    public function run()
    {
        if ($this->match()) {
            $path = 'application\controllers\\' . ucfirst($this->params['controller']) . 'Controller';
            if (class_exists($path)) {
                $action = $this->params['action'] . 'Action' . ucfirst(mb_strtolower($this->params['method']));
                if (method_exists($path, $action)) {
                    $controller = new $path($this->params);
                    $response = $controller->$action();
                    if ($response) {
                        $response->generateResponse();
                    }
                } else {
                    View::json('Action not found', 'error', 404);
                }
            } else {
                View::json('Controller not found', 'error', 404);
            }
        } else {
            View::json('Url not found', 'error', 404);
        }
    }
}
