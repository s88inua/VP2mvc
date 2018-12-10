<?php

namespace MVC\App\Engine;

use Twig_Environment;
use Twig_Loader_Filesystem;

class MainView
{

    private $loader;
    private $twig;
    public function __construct()
    {
        $this->loader = new Twig_Loader_Filesystem(__DIR__ . '/../MVC/View');
        $this->twig = new Twig_Environment($this->loader, [__DIR__ . '/../cache']);
    }

    public function render($filename, $data = [])
    {
        echo $this->twig->render($filename, $data);
    }
}