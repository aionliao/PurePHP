<?php
define('ROOT_PATH', dirname(__FILE__) . '/..');
require(ROOT_PATH . '/vendor/autoload.php');

$core = new PurePHP\Core;
$core->run();
