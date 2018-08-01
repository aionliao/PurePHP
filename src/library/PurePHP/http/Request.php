<?php

namespace PurePHP\Http;

class Request extends Http
{
    public static function get($key, $options = [])
    {
        $val = isset($_GET[$key]) ? $_GET[$key] : null;
        return self::handling($val, $options);
    }
    public static function post($key, $options = [])
    {
        $val = isset($_POST[$key]) ? $_POST[$key] : null;
        return self::handling($val, $options);
    }
    public static function postInput()
    {
        $val = file_get_contents('php://input');
        return $val;
    }
    public static function headers()
    {
        $headers = []; 
        foreach ($_SERVER as $key => $value) { 
            if ('HTTP_' == substr($key, 0, 5)) { 
                $headers[ucfirst(strtolower(str_replace('_', '-', substr($key, 5))))] = $value; 
            } 
        }
        return $headers;
    }
    public static function header($key)
    {
        return self::headers()[ucfirst(strtolower($key))];
    }
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    public static function isGet()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) == 'GET';
    }
    public static function isPost()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) == 'POST';
    }
    public static function isAjax()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }    
}