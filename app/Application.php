<?php

namespace App;

use Dotenv\Dotenv;

use League\Route\Router;
use League\Container\Container;
use League\Route\Strategy\ApplicationStrategy;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

use Symfony\Component\ErrorHandler\Debug;

use Illuminate\Database\Capsule\Manager as Capsule;

class Application
{
    public Router $router;

    public Container $container;

    public ServerRequest $request;

    public array $env;

    public function __construct()
    {
        $this->loadEnv();

        $this->loadRouter();
        $this->loadContainer();

        $this->loadDebugger();
        $this->loadCapsule();
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $response = $this->router->dispatch($this->request);

        (new SapiEmitter)->emit($response);
    }

    /**
     * @void
     */
    private function loadEnv()
    {
        $dotenv = Dotenv::createImmutable(APP_FOLDER);

        $data = $dotenv->load();

        $dotenv->required([
            'APP_DEBUG',
        ])->notEmpty();

        $this->env = $data;
    }

    /**
     * @void
     */
    private function loadRouter()
    {
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );

        $router = new Router;

        $this->request = $request;
        $this->router = $router;
    }

    /**
     * @void
     */
    private function loadDebugger()
    {
        $isAppDebuggable = env('APP_DEBUG');

        if ($isAppDebuggable) {
            Debug::enable();
        }
    }

    /**
     * @void
     */
    private function loadContainer()
    {
        $container = new Container();

        require APP_FOLDER . '/config/di.php';

        $this->container = $container;

        $strategy = (new ApplicationStrategy)->setContainer($this->container);

        $this->router->setStrategy($strategy);
    }

    private function loadCapsule()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver' => env("DB_CONNECTION"),
            'host' => env("DB_HOST"),
            'database' => env("DB_DATABASE"),
            'username' => env("DB_USERNAME"),
            'password' => env("DB_PASSWORD"),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}