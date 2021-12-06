<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Utils\Session;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (Session::has('user')) {
            return $handler->handle($request);
        }

        Session::put('message', 'Вы не авторизованы!');

        return response()->withHeader('Location', '/login')->withStatus(302);
    }
}