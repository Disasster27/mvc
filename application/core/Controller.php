<?php

namespace application\core;

use application\http\Request;
use application\http\Response;

abstract class Controller extends Request
{
    private $route;

    private $view;

    public $model;

    private $config;

    private $response;

    public function __construct($route, $autoConnect = true)
    {
        $this->route = $route;
        $this->response = new Response();
        $this->view = new View($route);
        $this->config = require_once DIR . 'application/config/app.cfg.php';
        $_ENV = $this->config;

        if ($autoConnect) {
            $this->model = $this->loadModel($route['controller']);
        }
        parent::__construct();
    }

    public function loadModel($name)
    {
        $name = ucfirst($name);
        $path = 'application\models\\' . $name;
        if (class_exists($path)) {
            return new $path;
        } else
            View::json('Model not found.', 'error', 404);
    }

    /**
     * @return mixed
     */
    public function getConfigItem($name)
    {
        return $this->config[$name];
    }
}
