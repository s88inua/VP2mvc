<?php
/**
 * Список всех возможных роутов
 */
$this->router->get('/', 'HomeController:index');
$this->router->get('/users/{sort}', 'HomeController:userSort');
$this->router->get('/user/register/form', 'HomeController:formRegister');
$this->router->get('/user/logout', 'HomeController:logout');
$this->router->post('/user/register/confirm', 'HomeController:registerConfirm');
$this->router->post('/user/login', 'HomeController:login');
$this->router->post('/user/info/update', 'HomeController:infoUpdate');
$this->router->post('/photo/upload', 'HomeController:upload');
$this->router->get('/photo/select/form', 'HomeController:selectForm');
$this->router->post('/photo/select/save', 'HomeController:avatar');
$this->router->get('/admin/password/request', 'HomeController:passwordForm');
$this->router->post('/admin/password/change', 'HomeController:passwordChange');
$this->router->get('/admin/user/add/request', 'HomeController:formAddUser');
$this->router->post('/admin/user/add/confirm', 'HomeController:addUser');
$this->router->get('/admin/user/view/{id:int}', 'HomeController:viewUser');




