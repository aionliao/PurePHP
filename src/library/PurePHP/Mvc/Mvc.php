<?php

namespace PurePHP\Mvc;

use Medoo\Medoo;
use PurePHP\Ioc;

abstract class Mvc
{
    public $conf;
    public function __construct()
    {
        $this->conf = include '../config/common.php';
        $databaseConf = include ROOT_PATH . '/config/database.php';
        if ($databaseConf['database_type'] != '') {
            $this->data = new Medoo($databaseConf);            
        }
    }
    protected function model($model, $module = MODULE)
    {       
        $model = 'Application\\' . ($module != '' ? $module . '\\' : '') . 'Model\\' . $model . 'Model';
        return Ioc::getInstance($model);
    }
    protected function url($url)
    {
        $url = $url . $this->conf['UrlSuffix'];
        if ($this->conf['UrlRewrite']) {
            $url = '/' . $url;
        } else {
            $url = '/index.php/' . $url;
        }
        return str_replace('%2F', '/', urlencode($url));
    }    
}