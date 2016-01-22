<?php
define('APP_PATH',dirname(__FILE__));//当前项目路径
define('KYPHP_PATH',APP_PATH.'/../../kyphp/');//KYPHP框架路径
define('__CHARSET__','gbk');
define('__CONFIG__',APP_PATH.'/config.php');//定义必须在kyphp加载前面
require KYPHP_PATH."kyphp.php";
//如果要自定义config路径，需要定义__CONFIG__

$config=require(__CONFIG__);
KYPHP::Run($config);


?>