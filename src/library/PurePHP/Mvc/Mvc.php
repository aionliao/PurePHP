<?php
namespace PurePHP\Mvc;
use Exception;
use Medoo\Medoo;
use PurePHP\Http;
abstract class Mvc{
    protected $Data, $Conf, $Cookie, $Session;
    function __construct(){
        $this->Conf = include ROOT_PATH . '/config/common.php';
        $DatabaseConf = include ROOT_PATH . '/config/database.php';
        if($DatabaseConf['database_type'] != ''){
            $this->Data = new Medoo($DatabaseConf);            
        }
        $this->Cookie = new Http\Cookie;
        $this->Session = new Http\Session;
        $this->Csrf = new Http\Csrf;
    }
    protected function Get($key, $options = []){
        $get = new Http\Get;
        return $get->Get($key, $options);
    }
    protected function Post($key, $options = []){
        $post = new Http\Post;
        return $post->Get($key, $options);
    }
    protected function Model($model, $module = MODULE){
        $modelFile = ROOT_PATH . '/application/' . ($module != '' ? $module . '/' : '') . 'model/' . $model . 'Model.php';
        if(!file_exists($modelFile))
            throw new Exception('模型文件不存在：' . $modelFile);
        include_once($modelFile);
        $mod = 'Application\\' . ($module != '' ? $module . '\\' : '') . 'Model\\' . $model . 'Model';
        if(!class_exists($mod))
            throw new Exception('模型不存在：' . $mod);
        return new $mod;
    }
    protected function Url($url){
        $url = $url . $this->Conf['UrlSuffix'];
        if($this->Conf['UrlRewrite']){
            $url = '/' . $url;
        }else{
            $url = '/index.php/' . $url;
        }
        return str_replace('%2F', '/', urlencode($url));
    }
    protected function IsGet(){
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }
    protected function IsPost(){
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }
    protected function IsAjax(){
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }    
}
