<?php
//入口文件
define('DS', DIRECTORY_SEPARATOR);
define('__ROOT__', realpath(dirname(dirname(__FILE__))));
define('KYPHP_PATH',__ROOT__.DS.'kyphp');
define('APP_PATH',__ROOT__.DS.'safe');
require KYPHP_PATH.DS."kyphp.php";//kyphp路径
KYPHP::RUN();