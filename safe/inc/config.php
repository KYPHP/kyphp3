<?php
if (!defined('KYPHP_PATH'))	exit('未定义KY_PATH');
$array = array(
	'WEBURL'=>'http://localhost/php7/safe',
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
	'templete_mode'=>'smarty',//smarty,kysmarty 默认为kysmarty
	'smarty_library'=>'../smarty-3.1.29/libs',
	'smarty_left_delimiter'=>'{',//smarty left_delimiter
	'smarty_right_delimiter'=>';}',//smarty right_delimiter
	//'smarty_path'=>'', //smarty path
    'smarty_phpexe'=>true,//默认为开，如不支持eval则关掉
	'safe_key'=>md5('safe_key'),//用于safe表单加解密，必须16/24/32位
	'sessionid'=>'test',//设置安全的session_id种子值
	'session_check_on'=>false,//如这里设置为fase,可以项目中使用zlsafesession::checkip(),zlsafesession::check()来自行判断，默认为false
	'rsa_config'=>array(
		'b'=>'10001',
		'c'=>'00b03024badeeb24eb5e65561b072262bb64a5b18aaef78a69de298d10c6d88b5b743daae26a5a834e057cfaa92376877a9ee44afa3d113d45a60e43bf40a8a3f17ce763c397bab86d65e5c07f182d1d929cda62e88bdd5738bea24c0a7497793dcde1e9ab2b1a1e7c7e50e9a755b6b2925acc8faa49da49e1c55699b9ddd6eec5',
		'private_key'=>'39625791540835417436891128397506764935563892928272101685687427273849982469642170696312487939968812477191318704917825155723251320458514199764535999334083230860106839841495908331706522589041717721827490756823946649673221114342026160341602411902334346606763836729497506308985085939846669625996582542454949461953',
		'modulus'=>'123723463564160588497452781409595085925776600802294841742491516019357136784799377842983874326905005398651141208394450082450439342014752030012786840798371280137327645637900428030154284284250927914133045752162297788052305616113844253100460757516268140566009335869326752606399810195953029456105798847888302206661',
	),
	'waf_report'=>true,//默认是开
);
return $array;

?>