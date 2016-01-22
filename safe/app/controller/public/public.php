<?php 
class ControllerPublicPublic extends  blog{ 
	public function init() {
		$this->data['http']=__WEBURL__;
		$this->assign('sitename','blog example');//在字符串情况下$this->data,$this->lable,$this->assign等效		
		$this->language('blog/data');
		$sitename=$this->language->get('sitename');
		$this->lable('title',$sitename);
		if(!zlsafesession::checkip())$this->redirect($this->url->link('public/login'));//不通过safe session ip校验返回登陆
		if(!zlsafesession::check())$this->redirect($this->url->link('public/login'));//不通过safe session严格校验返回登陆,与checkip可根据实际情况用

		if(empty($_SESSION['username']))$this->redirect($this->url->link('public/login'));
  	}
}
?>
