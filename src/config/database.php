<?php
// Mysql 数据库
return [
    'database_type' => 'mysql',     //数据库类型，如果不需要使用数据库，这里留空
    'server' => '',                 //数据库服务器地址
    'database_name' => '',          //数据库名称
    'username' => '',               //数据库用户名
    'password' => '',               //数据库密码
    'charset' => 'utf8',            //数据库字符集
    'port' => 3306,                 //数据库端口
    'prefix' => 'pure_'                  //数据表前缀
];
/*

// Sqlite 数据库

return [
    'database_type' => 'sqlite',                                //数据库类型
    'database_file' => ROOT_PATH . '/database/database.db',     //数据库文件路径
    'prefix' => 'pure_'                                         //数据表前缀
];

