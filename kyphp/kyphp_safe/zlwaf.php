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
 * @version  3.0
 +------------------------------------------------------------------------------
 *���ṩ���װ汾,������ȫ�����չ������ϵ����
 */
class zlwaf{
	public function __construct() {
		$this->start();
	}
	//���������������м�ǧ������
	private function rule(){
		return 'and\s*\d+\s*=\s*\d+|or\s*\d+\s*=\s*\d+|union\s|select\s|insert\s|update\s';
	}
	public function start(){
		$rule=$this->rule();
		$uri=isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';		
		if(preg_match('/'.$rule.'/si',urldecode($uri))){
			$this->report($uri);
		}
	}
	public function report($uri){
		
		$log=new Log('waf');
		$log->write($_SERVER['REMOTE_ADDR']."\t$uri");
	}

}
?>