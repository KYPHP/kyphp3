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
class zlsafesession
{
	public static function sessionid($id){
		if(function_exists('zlsafe_session_id'))
		{
			if($id)
				return zlsafe_session_id($id);
			else
				return zlsafe_session_id();
		}else
		{			
			return session_id();
		}
	}

	/*
	只限定ip级别的访问,不同ip则返回false
	*/
	public static function checkip(){
		if(function_exists('zlsafe_session_id'))
		{
			return zlsafe_session_checkip();
		}
		else
		{
			return true;
		}
	}

	/*
	严格的，不是同个电脑返回false
	*/
	public static function check(){
		if(function_exists('zlsafe_session_id'))
		{
			return zlsafe_session_check();
		}
		else
		{
			return true;
		}
	
	}

}