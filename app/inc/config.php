<?php
if (!defined('KYPHP_PATH'))	exit('未定义KY_PATH');
$array = array(
	'WEBURL'=>'http://localhost/kyphp_framework/blog_smarty',
	'SSLURL'=>'http://localhost/kyphp_framework/blog_smarty',
    'URL_ROUTER_ON' => true,
    'DEFAULT_MODULE' =>'home/index',//默认路由
    'PATH_KEY'=>5, // URL类型,兼容模式请设置为3	//2是伪静态 1是pathinfo 4为静态
	'URL_MODULE'=>'public/url',//PATH_KEY为5时必须指定,url类的路由
	'DB_CHARSET'=>'utf8',
	'DB_HOST'=>'127.0.0.1',//php7 mysqli用localhost可能会报错
	'DB_NAME'=>'kyfrm_utf8',
	'DB_USER'=>'test',
	'DB_PWD'=>'123456',
	'DB_PREFIX'=>'ky_',
	'DB_TYPE'=>'mysqli',
	'_db'=>array(
		'mysqli'=>array('DB_CHARSET'=>'utf8','DB_HOST'=>'127.0.0.1',	'DB_NAME'=>'kyfrm_utf8',	'DB_USER'=>'test',	'DB_PWD'=>'123456','DB_PREFIX'=>'ky_',	'DB_TYPE'=>'mysqli'),//$this->_db['db1']
		'pdo'=>array('DB_HOST'=>'127.0.0.1',	'DB_NAME'=>'kyfrm_utf8',	'DB_USER'=>'test',	'DB_PWD'=>'123456','DB_PREFIX'=>'ky_',	'DB_TYPE'=>'pdo_mysql'),//多数据库设置，$this->_db[$key]键值$this->_db[1]
	
	),
	'cache'=>array('driver'=>'file',//支持redis,file,memcache
					'host'=>'127.0.0.1',
					'port'=>'11211',
					
	),
	'_cache'=>array(	
		//'memcache'=>array('driver'=>'memcache',	'host'=>'127.0.0.1','port'=>'11211'),//多缓存使用
		'file'=>array('driver'=>'file'),
	
	),
	'app_dir'=>array('app','app2'),//'app_dir'=>'app'

	//'DEFAULT_M_PATH' =>'m',//指定生成M文件路径 空为'model'
	//'DEFAULT_C_PATH' =>'c',//指定生成LIB文件路径，空为'controller'	
	//'DEFAULT_V_PATH' =>'v',//指定生成TPL文件路径，空为'tpl'
	//'DEFAULT_L_PATH' =>'l',//指定生成TPL文件路径，空为'language'
	//'DEFAULT_CMD_PATH'=>'common',//指定生成TPL文件路径，空为'common'
	'error_filename'=>'log.txt',//日志文件名
	'error_display'=>'on',//输出错误
	'error_log'=>'on',//记录错误
	'debug'=>'on',//开启debug trace
	'DIR_LOGS'=>'log',//错误日志目录
	'templete_ext'=>'.html',//模板扩展名
	'code'=>'zh-cn',//默认zh-cn 当前语言
	//'templete_mode'=>'smarty',//smarty,kysmarty 默认为kysmarty
	'smarty_library'=>'../smarty-3.1.29/libs',
	'smarty_left_delimiter'=>'{',//smarty left_delimiter
	'smarty_right_delimiter'=>';}',//smarty right_delimiter
	//'smarty_path'=>'', //smarty path
    'smarty_phpexe'=>true,//默认为开，如不支持eval则关掉
);
return $array;

?>