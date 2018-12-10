<?php

namespace MVC\App\Engine\Router;


/**
 * Class RouteParser
 * @package MVC\App\Engine\Router
 */
class RouteParser
{

    const REGEX = '\{\s*([a-zA-Z][a-zA-Z0-9]*)\s*(?::\s*([a-zA-Z][a-zA-Z0-9]*))?\}';
    const DEFAULT_DISPATCH_REGEX = '[0-9a-zA-Z_ ]+';
    const REGEX_TYPES = [
            'int' => '[1-9][0-9]*'
        ];

    /**
     * Parse route
     *
     * @param $route
     * @return array
     */
    public function parser($route)
    {
        if (!preg_match_all(
            '|' . self::REGEX . '|x',
            $route, $matches,
            PREG_OFFSET_CAPTURE | PREG_SET_ORDER
        )) {
            return ['route' => $route];
        }

        $offset = 0;
        $routeData = [];
       /* $variableData = [];*/

        foreach ($matches as $set) {
            if (!isset($routeData['route']) && $set[0][1] > $offset) {
                $routeData['route'] = rtrim(substr($route, $offset, $set[0][1] - $offset), '/');
            }
            $variableData = [
                $set[1][0],
                isset($set[2]) ?
                    (array_key_exists(trim($set[2][0]), self::REGEX_TYPES))
                        ? self::REGEX_TYPES[trim($set[2][0])]
                        : trim($set[2][0])
                    : self::DEFAULT_DISPATCH_REGEX
            ];
            $offset = $set[0][1] + strlen($set[0][0]);
            $routeData['variables'][] = $variableData;
        }

        if ($offset != strlen($route)) {
            $routeData[] = substr($route, $offset);
        }

        return $routeData;
    }
}