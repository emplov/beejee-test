<?php

use League\Container\Container;

/** @var Container $container */

$container->add(\Twig\Environment::class, function () {
    $loader = new \Twig\Loader\FilesystemLoader(APP_FOLDER . '/resources/views');

    $config = [];

    if (env('APP_ENV') == 'prod') {
        $config['cache'] = APP_FOLDER . '/storage/views';
    }

    return new \Twig\Environment($loader, $config);
});

$container->add(\App\Controllers\FrontController::class)->addArgument(\Twig\Environment::class);
