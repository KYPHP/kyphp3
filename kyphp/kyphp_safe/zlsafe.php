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
class zlsafe{
	public function __construct($key='') {
		$this->key=$key;		
	}
	public function setkey($key)
	{
		$this->key=$key;
	}
	/*
	*方法名:array_encrypt
	*参数:$data 数组
	*将返回该数组键值加密后的数组
	*/
	public function array_encrypt($data){
		if(function_exists('zlsafe_array_encrypt'))
		{
			return zlsafe_array_encrypt($data,$this->key);
		}
		else
		{
			foreach($data as $key=>$value)
			{
				$key=base64_encode(key);
				$data[$key]=$value;
			
			}
			return $data;
		}
	}
	/*
	*方法名:array_decrypt
	*参数:$data 数组
	*将加密过的数组解密
	*/
	public function array_decrypt($data){
		if(function_exists('zlsafe_array_decrypt'))
		{
			return zlsafe_array_decrypt($data,$this->key);
		}
		else
		{
			foreach($data as $key=>$value)
			{
				$key=base64_decode(key);
				$data[$key]=$value;
			
			}
			return $data;
		}
	}
	/*
	*方法名:encrypt
	*参数:$string 
	*返回用于array_encrypt的加密
	*/
	public function encrypt($string){
		if(function_exists('zlsafe_encrypt'))
		{
			return zlsafe_encrypt($string,$this->key);
		}
		else
		{
			return base64_encode($string);
		}
	
	}
	/*
	*方法名:decrypt
	*参数:$string
	*返回用于array_decrypt的解密
	*/
	public function decrypt($string){
		if(function_exists('zlsafe_decrypt'))
		{
			return zlsafe_decrypt($string,$this->key);
		}
		else
		{
			return base64_decode($string);
		}
	
	}
	/*
	*方法名:md5
	*参数:$string 需加密的串
	*将返回带有客户端特征的md5值
	*/
	public function md5($string)
	{
		if(function_exists('zlsafe_md5_encrypt'))
		{
			return zlsafe_md5_encrypt($string);
		}
		else
		{
			return md5($string);
		}
	
	}

	/*
	*方法名:md5check
	*参数:$hash 被zlsafe_md5_encrypt加密的串
	*严格的，不是同个电脑返回false,也可以传入sessionid作校验
	*/
	public function md5check($hash)
	{
		if(function_exists('zlsafe_session_check'))
		{
			return zlsafe_session_check($hash);
		}
		else
		{
			return true;
		}
	
	}

	/*
	*方法名:md5checkip
	*参数:$hash 被zlsafe_md5_encrypt加密的串
	*只限定ip级别的访问,不同ip则返回false,也可以传入sessionid作校验
	*/
	public function md5checkip($hash)
	{
		if(function_exists('zlsafe_session_checkip'))
		{
			return zlsafe_session_checkip($hash);
		}
		else
		{
			return true;
		}
	
	}


}
