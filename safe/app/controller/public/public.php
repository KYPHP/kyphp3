<?php 
class ControllerPublicPublic extends  blog{ 
	public function init() {
		$this->data['http']=__WEBURL__;
		$this->assign('sitename','blog example');//���ַ��������$this->data,$this->lable,$this->assign��Ч		
		$this->language('blog/data');
		$sitename=$this->language->get('sitename');
		$this->lable('title',$sitename);
		if(!zlsafesession::checkip())$this->redirect($this->url->link('public/login'));//��ͨ��safe session ipУ�鷵�ص�½
		if(!zlsafesession::check())$this->redirect($this->url->link('public/login'));//��ͨ��safe session�ϸ�У�鷵�ص�½,��checkip�ɸ���ʵ�������

		if(empty($_SESSION['username']))$this->redirect($this->url->link('public/login'));
  	}
}
?>
