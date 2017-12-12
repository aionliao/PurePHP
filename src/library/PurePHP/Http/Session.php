<?php
namespace PurePHP\Http;
class Session extends Http{
    function Set($key, $val){
        $_SESSION[$this->Conf['SessionPrefix'] . $key] = $val;
    }
    function Get($key, $options = []){
        $val = isset($_SESSION[$this->Conf['SessionPrefix'] . $key]) ? $_SESSION[$this->Conf['SessionPrefix'] . $key] : null;
        return $this->handling($val, $options);
    }
    function Delete($key){
        unset($_SESSION[$this->Conf['SessionPrefix'] . $key]);
    }
}
