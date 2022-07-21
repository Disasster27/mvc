<?php
declare(strict_types=1);

define("DIR", dirname(__DIR__) . '/');
// require DIR . 'application/lib/dev.php';
require DIR . 'vendor/autoload.php';

//file_put_contents(DIR . 'log/requesets.log', json_encode($_REQUEST, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);

use application\core\Router;

//file_put_contents(DIR . 'public/log.log', file_get_contents("php://input"), FILE_APPEND);


spl_autoload_register(function ($class)
{
    $path = str_replace('\\', '/', $class.'.php');
    if (file_exists(DIR . $path))
        require_once DIR . $path;
    else
        echo 'Not found: '.$path;
});

$router = new Router;
try {
    $router->run();
} catch (Exception $exception) {
    $code = $exception->getCode() !== 0 ? $exception->getCode() : 500;
    \application\core\View::json($exception->getMessage(), 'error', $code);
}