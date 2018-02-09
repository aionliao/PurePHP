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
        $DI = include ROOT_PATH . '/config/dependencyInjection.php';
        foreach($DI as $k => $v){
            if(is_callable($v)){
                $this->$k = $v();
            }else{
                $this->$k = $v;
            }
        }
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
