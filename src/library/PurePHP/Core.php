<?php
/*
PurePHP 超轻量级PHP快速开发框架
开发者：邹鹏
Author：Zou Peng
网址：http://purephp.zoupeng.com
PurePHP 依据MIT协议开源，只需保留此处开发者信息，你便可以使用PurePHP框架开发各种免费开源或商业收费WEB项目，也可以任意修改或复制PurePHP。
*/
namespace PurePHP;
use Exception;
class Core{
    public $Conf;
    function __construct(){
        $this->Conf = include '../config/common.php';
    }
    function Run(){
        if(phpversion() < 5.4)
            throw new Exception('程序要求PHP5.4+环境版本，当前环境为PHP' . phpversion() . ',请升级服务器环境');            
        session_start();
        date_default_timezone_set($this->Conf['TimeZone']);
        if($this->Conf['Debug'])
            error_reporting(E_ALL);
        else
            error_reporting(0);
        $this->LoadFunction();
        $this->GetRoute();
    }
    private function LoadFunction($dirPath = '../function/'){
        if($dir = opendir($dirPath)){
            while($file = readdir($dir)){
                if($file != '.' && $file != '..'){
                    $filePath = $dirPath . $file;
                    if(is_file($filePath)){
                        require_once($filePath);
                    }else{
                        $this->LoadFunction($filePath . '/');
                    }
                }
            }
        }
    }
    private function GetRoute(){
        $pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''));
        $pathInfo = str_replace('/index.php', '', mb_convert_encoding($pathInfo, 'UTF-8', 'GBK'));
        $pathInfo = str_replace($this->Conf['UrlSuffix'], '', substr($pathInfo, 1));
        $route = include('../config/route.php');
        foreach($route as $r)
            $pathInfo = preg_replace($r[0], $r[1], $pathInfo);
        $pathInfo = explode('/', $pathInfo);
        if($this->Conf['MulitpleModule']){
            $pathInfo[0] = isset($pathInfo[0]) && $pathInfo[0] != '' ? $pathInfo[0] : $this->Conf['DefaultModule'];
            $pathInfo[1] = isset($pathInfo[1]) && $pathInfo[1] != '' ? $pathInfo[1] : $this->Conf['DefaultController'];
            $pathInfo[2] = isset($pathInfo[2]) && $pathInfo[2] != '' ? $pathInfo[2] : $this->Conf['DefaultAction'];
            define('MODULE', $pathInfo[0]);
            define('CONTROLLER', $pathInfo[1]);
            define('ACTION', $pathInfo[2]);
            $controllerFile = '../application/' . MODULE . '/controller/' . CONTROLLER . 'Controller.php';
            if(!file_exists($controllerFile)){
                if($this->Conf['Debug'])
                    throw new Exception('控制器文件不存在：' . $controllerFile);
                else
                    $this->notFound();
            }
            require $controllerFile; 
            $argv = array_slice($pathInfo, 3);
        }else{ 
            $pathInfo[0] = isset($pathInfo[0]) && $pathInfo[0] != '' ? $pathInfo[0] : $this->Conf['DefaultController'];
            $pathInfo[1] = isset($pathInfo[1]) && $pathInfo[1] != '' ? $pathInfo[1] : $this->Conf['DefaultAction'];
            define('MODULE', '');
            define('CONTROLLER', $pathInfo[0]);
            define('ACTION', $pathInfo[1]);
            $controllerFile = '../application/controller/' . CONTROLLER . 'Controller.php';
            if(!file_exists($controllerFile)){
                if($this->Conf['Debug'])
                    throw new Exception('控制器文件不存在：' . $controllerFile);
                else
                    $this->notFound();
            }
            require $controllerFile; 
            $argv = array_slice($pathInfo, 2);
        }
        $this->StartApp(MODULE, CONTROLLER, ACTION, $argv);
    }
    private function StartApp($module, $controller, $action, $argv){
        $controller = 'Application\\' . ($module != '' ? $module . '\\' : '') . 'Controller\\' . $controller . 'Controller';
        if(!class_exists($controller)){
            if($this->Conf['Debug'])
                throw new Exception('控制器文件不存在：' . $controllerFile);
            else
                $this->notFound();
        }
        $action .= 'Action';
        $app = new $controller;
        $app->$action(...$argv);
    }
    private function NotFound(){
        header("HTTP/1.1 404 Not Found");  
        header("Status: 404 Not Found");
        die();
    }
    function Debug($e){
        echo '<meta charset="UTF-8">';
        echo '<p>异常信息：' . $e->getMessage() . '</p>';
        echo '<p>文件' . $e->getFile() . '</p>';
        echo '<p>行号' . $e->getLine() . '</p>';
        echo '<p>追踪异常：</p>';
        echo '<p>' . str_replace('#', '</p><p>#', $e->getTraceAsString()) . '</p>';
    }
}