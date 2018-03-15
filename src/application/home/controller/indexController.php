<?php
namespace application\home\controller;
use PurePHP\mvc\Controller;

class IndexController extends Controller{
    function indexAction(){
        $this->view();
    }
}