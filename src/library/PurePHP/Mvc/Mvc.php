<?php
namespace PurePHP\mvc;
use Medoo\Medoo;
use PurePHP\Ioc;

abstract class Mvc{
    public $conf;
    function __construct(){
        $this->conf = include '../config/common.php';
        $DatabaseConf = include ROOT_PATH . '/config/database.php';
        if($DatabaseConf['database_type'] != ''){
            $this->Data = new Medoo($DatabaseConf);            
        }
    }
    protected function model($model, $module = MODULE){       
        $model = 'application\\' . ($module != '' ? $module . '\\' : '') . 'model\\' . $model . 'Model';
        return Ioc::getInstance($model);
    }
    protected function url($url){
        $url = $url . $this->conf['UrlSuffix'];
        if($this->conf['UrlRewrite']){
            $url = '/' . $url;
        }else{
            $url = '/index.php/' . $url;
        }
        return str_replace('%2F', '/', urlencode($url));
    }
    protected function isGet(){
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }
    protected function isPost(){
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }
    protected function isAjax(){
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }    
}