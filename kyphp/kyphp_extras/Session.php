<?php
// +----------------------------------------------------------------------
// ���ݵ�php���
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.ky53.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.ky53.net )
// +----------------------------------------------------------------------

/**
 
 * @author   qq:974005652(zhx626@126.com) by����ͯ
 * @version  2.0
 +------------------------------------------------------------------------------
 */
final class Session {
	public $data = array();
			
  	public function __construct() {
		global $KYPHP;
		
		if (!session_id()) {
			ini_set('session.use_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
		
			session_set_cookie_params(0, '/');
			if($KYPHP->config->get('sessionid'))
			zlsafesession::sessionid($KYPHP->config->get('sessionid'));
			if($KYPHP->config->get('session_check_on')==true){
				if(!zlsafesession::check())die('The client connect error');//��ͨ��safe session�ϸ�У�鷵�ص�½,��checkip�ɸ���ʵ�������
			}
			session_start();
			
			
		}
		
		$this->data =& $_SESSION;
	}
}
?>