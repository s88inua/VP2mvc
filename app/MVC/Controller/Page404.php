<?php

namespace MVC\MVC\Controller;

use MVC\App\Engine\MainController;


class Page404 extends MainController
{

    private function parseError($errorCode, $text = null)
    {
        $error = [];
        switch ($errorCode) {
            case 0:
                $error = [404, 'Страница не найдена'];
                break;
            case 1:
                $error = [405, 'Метод не доступен. Доступен как ' . $text . ' метод'];
                break;
            case 2:
                $error = [400, 'Пользователь под ID: ' . $text . ' не найден'];
                break;
        }

        return $error;
    }

    public function index($errorCode, $method = null)
    {
            list($httpCode, $errorText) = $this->parseError($errorCode, $method);
            http_response_code($httpCode);
            echo $this->view->render('page404.html.twig',
                ['error' => $errorText, 'code' => http_response_code()]);
    }
}