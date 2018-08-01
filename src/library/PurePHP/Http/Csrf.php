<?php

namespace PurePHP\Http;

class Csrf
{
    public static function token(){
        $token = base64_encode(md5(time() . rand(1000,9999)));
        Session::set('csrf_token', $token);
        return $token;
    }
    public static function check(){
        if (HttpPost::get('__csrf_token__') == '' || HttpPost::get('__csrf_token__') != Session::get('csrf_token')) {
            return false;
        } else {
            return true;
        }
    }
    public static function unsetToken(){
        Session::delete('csrf_token');
    }
}