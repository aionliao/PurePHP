<?php

namespace Application\Home\Controller;

use PurePHP\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view();
    }
}
