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
abstract class Model {
	protected $registry;	
	public function __construct($KYPHP) {
		$this->runtimes[__CLASS__]=new runtime();
		$this->runtimes[__CLASS__]->start();
		$this->kyphp=$KYPHP;
		foreach($KYPHP as $key=>$value){
		
			$this->$key=$value;
		}
		
	}
	public function model($file){
		
	 if(file_exists(DEFAULT_M_PATH."/{$file}.php"))require_once DEFAULT_M_PATH."/{$file}.php";
		$classname = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $file);
		
	 if(!class_exists($classname))error(2,$classname);
	 $filemodel='model_'.str_replace('/','_',$file);
	 $this->$filemodel=new $classname($this->kyphp);
	}
	public function language($language){
		
		$this->language->load($language);
	}
	public function custom($class){
		return new $class;
	}
	public function __destruct(){
		
		 $this->runtimes[__CLASS__]->stop();
		 $time= $this->runtimes[__CLASS__]->spent();
		 $this->runtimes[__CLASS__]->time=$time;
		 $classname= get_class($this);		 
		 $GLOBALS['debug_class_time'][$classname]=$time;
	
	}
}
?>