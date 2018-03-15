<?php
namespace PurePHP\Http;

class Session extends Http{
    static function set($key, $val){
        $conf = self::getConfig();
        $_SESSION[$conf['SessionPrefix'] . $key] = $val;
    }
    static function get($key, $options = []){
        $conf = self::getConfig();
        $val = isset($_SESSION[$conf['SessionPrefix'] . $key]) ? $_SESSION[$conf['SessionPrefix'] . $key] : null;
        return self::handling($val, $options);
    }
    static function delete($key){
        $conf = self::getConfig();
        unset($_SESSION[$conf['SessionPrefix'] . $key]);
    }
}
