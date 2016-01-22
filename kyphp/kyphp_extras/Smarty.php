<?php
// +----------------------------------------------------------------------
// ×î±ã½ÝµÄphp¿ò¼Ü
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.ky53.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.ky53.net )
// +----------------------------------------------------------------------

/**
 
 * @author   qq:974005652(zhx626@126.com) byÀÏÍçÍ¯
 * @version  2.0
 +------------------------------------------------------------------------------
 */
class KYSmarty extends Smarty
{    
	public function __construct($registry)
	{	
		parent::__construct();

        
        if (is_callable('mb_internal_encoding')) 
        {
            mb_internal_encoding(Smarty::$_CHARSET);
        }
		
		if(!$registry->config->get('smarty_right_delimiter'))$registry->config->set('smarty_right_delimiter',';}');
        $this->start_time = microtime(true);
		if($registry->config->get('smarty_path'))$this->path= $registry->config->get('smarty_path');
		$this->right_delimiter=$registry->config->get('smarty_right_delimiter');
		if($registry->config->get('smarty_left_delimiter'))$this->left_delimiter=$registry->config->get('smarty_left_delimiter');
		$action=isset($_GET['k'])?$_GET['k']:'';
		
		$path=DIR_CACHE.'smarty/template/templates_c/'.$action;
		checkDir($path);		
		$this->muteExpectedErrors();
		$this->setTemplateDir(DEFAULT_TPL_PATH)->setCompileDir($path)->setPluginsDir(SMARTY_PLUGINS_DIR);
       
            	  
	}
}
?>