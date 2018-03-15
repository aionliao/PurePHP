<?php
namespace PurePHP\Http;

class Cookie extends Http{
    static function set($key, $val, $options = []){
        $expire = isset($options['expire']) ? $options['expire'] : 0;
        $path = isset($options['path']) ? $options['path'] : '/';
        $domain = isset($options['domain']) ? $options['domain'] : null;
        $secure = isset($options['secure']) ? $options['secure'] : false;
        $httponly = isset($options['httponly']) ? $options['httponly'] : true;
        $conf = self::getConfig();
        setcookie($conf['CookiePrefix'] . $key, $val, $expire, $path, $domain, $secure, $httponly);
    }
    static function get($key, $options = []){
        $conf = self::getConfig();
        $val = isset($_COOKIE[$conf['CookiePrefix'] . $key]) ? $_COOKIE[$conf['CookiePrefix'] . $key] : null;
        return self::handling($val, $options);
    }
    static function delete($key, $options = []){
        $path = isset($options['path']) ? $options['path'] : '/';
        $domain = isset($options['domain']) ? $options['domain'] : null;
        $secure = isset($options['secure']) ? $options['secure'] : false;
        $httponly = isset($options['httponly']) ? $options['httponly'] : true;
        $conf = self::getConfig();
        setcookie($conf['CookiePrefix'] . $key, '', time()-1, $path, $domain, $secure, $httponly);
    }
}
