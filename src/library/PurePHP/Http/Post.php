<?php
namespace PurePHP\Http;
class Post extends Http{
    function Set($key, $val){
        $_POST[$key] = $val;
    }
    function Get($key, $options = []){
        $val = isset($_POST[$key]) ? $_POST[$key] : null;
        return $this->handling($val, $options);
    }
    function Delete($key){
        unset($_POST[$key]);
    }
}
