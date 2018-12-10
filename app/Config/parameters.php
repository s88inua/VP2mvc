<?php
use MVC\MVC\ConfigStore;
/**
 * Блок настроек подлючения к БД
 */
ConfigStore::addItem('driver'   , 'mysql');
ConfigStore::addItem('host'     , 'localhost');
ConfigStore::addItem('database' , 'mvc');
ConfigStore::addItem('username' , 'mysql');
ConfigStore::addItem('password' , 'mysql');
ConfigStore::addItem('charset'  , 'UTF8');
ConfigStore::addItem('collation', 'utf8_unicode_ci');
ConfigStore::addItem('prefix'   , '');
/**
 * Базовый namespace к контроллерам
 */
ConfigStore::addItem('controllerBasePath', '\\MVC\\MVC\\Controller\\');