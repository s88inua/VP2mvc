<?php

namespace MVC\App\Engine;

use Illuminate\Database\Capsule\Manager as Capsule;
use MVC\MVC\ConfigStore;

class MainEloquent
{
    public static function run()
    {
        $capsule = new Capsule();
        $parametersInString = 'driver,host,database,username,password,charset,collation,prefix';
        $capsule->addConnection(ConfigStore::getArrayByKeys($parametersInString));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    }
}