<?php
// +----------------------------------------------------------------------
// 最便捷的php框架
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.ky53.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.ky53.net )
// +----------------------------------------------------------------------

/**
 
 * @author   qq:974005652(zhx626@126.com) by老顽童
 * @version  3.0
 +------------------------------------------------------------------------------
 */
if(!defined('APP_PATH'))define('APP_PATH',__ROOT__);
if(!defined('__CONFIG__'))define('__CONFIG__',APP_PATH.'/inc/config.php');
if(!defined('__CHARSET__')){
		define('__CHARSET__','utf-8');
} 
if(!defined('KYPHP_PATH'))die('const KYPHP_PATH not defined');
define('KY_PATH',KYPHP_PATH);
if(phpversion()<'5.3.0')
{
	require KYPHP_PATH.'/function/cmd5.2.php';
	require_once(KYPHP_PATH.'/kyphp_extras/Html5.2.php');
}
else
{
	require KYPHP_PATH.'/function/cmd.php';
	require_once(KYPHP_PATH.'/kyphp_extras/Html.php');
}
require KYPHP_PATH.'/function/message.php';
require KYPHP_PATH.'/kyphp_driver/front.php';
require KYPHP_PATH.'/kyphp_driver/Action.php';
require KYPHP_PATH.'/kyphp_driver/controller.php';
require KYPHP_PATH.'/kyphp_driver/model.php';
require KYPHP_PATH.'/kyphp_driver/view.php';
require KYPHP_PATH.'/kyphp_driver/service.php';
require_once(KYPHP_PATH.'/kyphp_extras/Cache.php');
require_once(KYPHP_PATH.'/kyphp_extras/Config.php');
require_once(KYPHP_PATH.'/kyphp_extras/Cookie.php');
require_once(KYPHP_PATH.'/kyphp_extras/DB.php');
require_once(KYPHP_PATH.'/kyphp_extras/Image.php');
require_once(KYPHP_PATH.'/kyphp_extras/Json.php');
require_once(KYPHP_PATH.'/kyphp_extras/Language.php');
require_once(KYPHP_PATH.'/kyphp_extras/Language.php');
require_once(KYPHP_PATH.'/kyphp_extras/Log.php');
require_once(KYPHP_PATH.'/kyphp_extras/Pagination.php');
require_once(KYPHP_PATH.'/kyphp_extras/Request.php');
require_once(KYPHP_PATH.'/kyphp_extras/Response.php');
require_once(KYPHP_PATH.'/kyphp_extras/runtime.php');
require_once(KYPHP_PATH.'/kyphp_extras/Session.php');
require_once(KYPHP_PATH.'/kyphp_extras/templete.php');
require_once(KYPHP_PATH.'/kyphp_extras/Url.php');

spl_autoload_register("__autoload");

?>