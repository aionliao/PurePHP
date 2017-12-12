<?php
namespace PurePHP\Http;
class Cookie extends Http{
    function Set($key, $val, $options = []){
        $expire = isset($options['expire']) ? $options['expire'] : 0;
        $path = isset($options['path']) ? $options['path'] : '/';
        $domain = isset($options['domain']) ? $options['domain'] : null;
        $secure = isset($options['secure']) ? $options['secure'] : false;
        $httponly = isset($options['httponly']) ? $options['httponly'] : true;
        setcookie($this->Conf['CookiePrefix'] . $key, $val, $expire, $path, $domain, $secure, $httponly);
    }
    function Get($key, $options = []){
        $val = isset($_COOKIE[$this->Conf['CookiePrefix'] . $key]) ? $_COOKIE[$this->Conf['CookiePrefix'] . $key] : null;
        return $this->handling($val, $options);
    }
    function Delete($key, $options = []){
        $path = isset($options['path']) ? $options['path'] : '/';
        $domain = isset($options['domain']) ? $options['domain'] : null;
        $secure = isset($options['secure']) ? $options['secure'] : false;
        $httponly = isset($options['httponly']) ? $options['httponly'] : true;
        setcookie($this->Conf['CookiePrefix'] . $key, '', time()-1, $path, $domain, $secure, $httponly);
    }
}
