<?php

namespace MVC\App\Engine\Router;

/**
 * Class Router
 * @package MVC\App\Engine\Router
 */
class Router
{
    /**
     * @var array
     */
    private $staticRoutes = [];
    /**
     * @var array
     */
    private $variableRoutes = [];

    /**
     * @var RouteParser
     */
    private $parser;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->parser = new RouteParser();
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return [$this->staticRoutes, $this->variableRoutes];
    }

    /**
     * Add new Route
     *
     * @param $httpMethod
     * @param $route
     * @param $action
     * @return bool
     */
    public function addRoute($httpMethod, $route, $action)
    {
        $routeArrays = $this->parser->parser($route);

        if (isset($routeArrays['variables'])) {
            $regex = $routeArrays['route'];
            foreach ($routeArrays['variables'] as $variable) {
                $regex .= '/(' . $variable[1] . ')';
            }
            $this->variableRoutes[$httpMethod][] = ['variables' => $routeArrays['variables'], 'action' => $action, 'regex' => $regex];

        } else {
            $this->staticRoutes[$httpMethod][$routeArrays['route']] = ['action' => $action];
        }
        return true;
    }

    /**
     * Add new Route with method GET
     *
     * @param $route
     * @param $action
     * @return bool
     */
    public function get($route, $action)
    {
        $this->addRoute('GET', $route, $action);
        return true;
    }

    /**
     * Add new Route with method POST
     *
     * @param $route
     * @param $action
     * @return bool
     */
    public function post($route, $action)
    {
        $this->addRoute('POST', $route, $action);
        return true;
    }
}