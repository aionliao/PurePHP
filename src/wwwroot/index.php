<?php
define('ROOT_PATH', dirname(__FILE__) . '/..');
require(ROOT_PATH . '/function/autoload.php');
$Core = new PurePHP\Core;
try{
    $Core->Run();
}catch(Exception $e){
    if($Core->Conf['Debug'])
        $Core->Debug($e);
}
