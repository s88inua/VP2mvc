<?php

namespace MVC\App\Engine\Router;

/**
 * Class URIParser
 * @package MVC\App\Engine\Router
 */
class URIParser
{
    /**
     * Prepare URI before dispatch
     *
     * @return string
     */
    public static function getURI()
    {
        if ($pos = strpos($_SERVER['REQUEST_URI'], '?')) {
            $uri = substr($_SERVER['REQUEST_URI'], 0, $pos);
        } else {
            $uri = $_SERVER['REQUEST_URI'];
        }
        return self::trim($uri);
    }

    /**
     * Get method from server
     *
     * @return mixed
     */
    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Helper
     *
     * @param $uri
     * @return string
     */
    private static function trim($uri)
    {
        return strlen($uri) !== 1 ? rtrim($uri, '/') : $uri;
    }
}