<?php
namespace MVC\MVC;

/**
 * Класс-контейнер с конфигурацией
 */

class ConfigStore
{

    private static $items = [];


    public static function addItem($key, $value)
    {
        self::$items[$key] = $value;
    }


    public static function getItem($key)
    {
        return self::$items[$key];
    }

    private static function isSetItem($key)
    {
        return !empty(self::$items[$key]);
    }

    public static function getArrayByKeys($string)
    {
        $arr = [];

        if (!empty($string)) {
            $arrKeys = array_map('trim', explode(',', $string));
            foreach ($arrKeys as $key) {
                $arr[$key] = self::getItem($key);
            }
        }

        return $arr;

    }
}