<?php
return [
    'Debug' => true,                //Debug模式，开发过程中开启，生产环境中请关闭
    'TimeZone' => 'PRC',            //系统时区
    'UrlSuffix' => '.html',         //URL后缀
    'MulitpleModule' => true,       //是否多模块，true-多模块，false-单模块
    'DefaultModule' => 'Home',      //默认模块，单模块可以不填
    'DefaultController' => 'Index', //默认控制器
    'DefaultAction' => 'index',     //默认方法
    'UrlRewrite' => true,           //是否Url重写，隐藏index.php,需要服务器支持和对应的规则
    'SessionPrefix' => '',          //Session前缀
    'CookiePrefix' => '',           //Cookie前缀
];