<?php

use App\Controllers\FrontController;
use League\Route\RouteGroup;

/** @var RouteGroup $route */

$route->get('/', [FrontController::class, 'main']);
$route->get('/login', [FrontController::class, 'login']);
$route->post('/login', [FrontController::class, 'authenticate']);
$route->post('/logout', [FrontController::class, 'logout']);
$route->get('/tasks/{id}/edit', [FrontController::class, 'edit']);
$route->post('/tasks/{id}/edit', [FrontController::class, 'update']);
$route->get('/tasks/create', [FrontController::class, 'create']);
$route->post('/tasks/create', [FrontController::class, 'store']);
