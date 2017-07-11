<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Core\Errors;

class IndexController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        // авторизация, если не пройдена, то произойдет переход к форме аутентификации
        $this->checkAuth();

        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'index';
        $this->data['title'] = '';
        $this->data['menuTitle'] = '';
    }
}
