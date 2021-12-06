<?php

use App\Application;

use League\Route\RouteGroup;

define("APP_FOLDER", dirname(__FILE__) . '/../');

require dirname(__FILE__) . "/../vendor/autoload.php";

$app = new Application();

$app->router->middleware(new \App\Middleware\SessionMiddleware());

$app->router->group('/', function (RouteGroup $route) {
    require __DIR__ . '/../routes/web.php';
});

return $app;
