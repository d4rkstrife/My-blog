<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use App\Service\Router;
use App\Service\ParseConfig;
use App\Service\Http\Request;


$config = new ParseConfig('../config.ini');
$config->parseFile();


if ($config->getConfig('appEnv') === 'dev') {
    $whoops = new \Whoops\Run();
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
    $whoops->register();
}

$request = new Request($_GET, $_POST, $_FILES, $_SERVER);
$router = new Router($request, $config);
$response = $router->run();
$response->send();
