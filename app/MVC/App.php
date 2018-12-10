<?php

namespace MVC\MVC;

use MVC\App\Engine\Router\RouterDispatcher;

class App
{

    private $routerDispatcher;
    private $router;
    public function __construct()
    {
        require_once __DIR__ . '/../Config/parameters.php';
        $this->routerDispatcher = new RouterDispatcher();
        $this->router = $this->routerDispatcher->getRouter();
    }

    public function run()
    {
        session_start();
        require_once __DIR__ . '/../Config/routes.php';
        $this->routerDispatcher->dispatch();
    }
}