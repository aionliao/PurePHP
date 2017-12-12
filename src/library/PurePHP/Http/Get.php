<?php
namespace PurePHP\Http;
class Get extends Http{
    function Get($key, $options = []){
        $val = isset($_GET[$key]) ? $_GET[$key] : null;
        return $this->handling($val, $options);
    }
}
