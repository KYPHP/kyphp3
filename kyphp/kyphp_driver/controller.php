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
 class Controller extends Action{
	 public function _init_time(){
		
		$this->runtimes[__CLASS__]=new runtime();
		$this->runtimes[__CLASS__]->start();
	 }
	 public function _destruct_time(){
		
		 $this->runtimes[__CLASS__]->stop();
		 $time= $this->runtimes[__CLASS__]->spent();
		 $this->runtimes[__CLASS__]->time=$time;
		 $classname= get_class($this);
		 $GLOBALS['debug_class_time'][$classname]=$time;
	
	}
	/*
	public function __call($name, $arguments){}
	public function __get($name){}	
	public function __set($name,$value){}	
	public function __toString($name){}
	public function __clone(){}
	public function __sleep(){}
	public function __wakeup(){}
	public function __isset($name){}
	public function __unset($name){}
	public function __set_state(){}
	*/
	
	
	
		
}
?>