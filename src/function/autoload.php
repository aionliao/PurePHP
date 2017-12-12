<?php
spl_autoload_register(function($class){
    $file = ROOT_PATH . '/library/' . $class . '.php';
    $file = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file);
    if(file_exists($file))
        include $file;
    else{
        $load = include ROOT_PATH . '/config/autoload.php';
        $file_exists = false;
        foreach($load as $k => $v){
            if(in_array($class, $v)){
                $file = ROOT_PATH . '/library/' . $k . '.php';
                $file = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file);
                if(file_exists($file)){
                    include $file;
                    $file_exists = true;
                }else{
                    throw new Exception('引用类的文件不存在:' . $file);
                }
            }
        }
        if(!$file_exists)
            throw new Exception('引用类的文件不存在:' . $file);
    }
    if(!class_exists($class))
        throw new Exception('引用类不存在:' . $class);
});
