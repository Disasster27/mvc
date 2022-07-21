<?php

namespace application\core;

class View
{
    public $path;
    public $route;
    public $layout = "default";

    public function __construct($route) {
        $this->route = $route;
        $this->path = $route['controller'].'/'.$route['action'];
    }

    public function render($title, $vars = []) {
        extract($vars);
        $path = DIR . 'application/views/'.$this->path.'.php';
        if(!file_exists($path)){
            throw new \Exception('File not found', 404);
        } else {
            ob_start();
            require $path;
            $content = ob_get_clean();
            require DIR . 'application/views/layouts/' . $this->layout . '.php';
        }
    }

    public static function json($data = [], string $status = 'ok', int $code = 200) {
        $returnData = [
          'status' => $status,
            'body' => $data,
            'code' => $code
        ];
        header('Content-type:application/json;charset=utf-8');
        http_response_code($code);
        echo json_encode($returnData, JSON_UNESCAPED_UNICODE);
    }

    public static function redirect($url) {
        header('Location: '.$url );
        exit();
    }
}
