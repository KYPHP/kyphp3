<?php 
class ControllerPublicLogin extends Controller { 
	public function index() {
		$this->data['http']=__WEBURL__;
		$this->assign('sitename','blog example');//在字符串情况下$this->data,$this->lable,$this->assign等效
		$this->safe->setkey(md5('thisisakey'));//在这里设置key,如在config设置safe_key，此处将覆盖配置
		$this->data['Login']=$this->safe->encrypt('Login');
		$this->data['Password']=$this->safe->encrypt('Password');
		$this->data['login_key']=$this->safe->encrypt('login_key');
		$this->data['login_value']=$this->safe->md5('key');//可以用来检测是否本地提交
		$this->data['error']='';
		$this->data['js']=zlrsa::js();
		$rsa_config=$this->config->get('rsa_config');
		$this->data['b']=$rsa_config['b'];
		$this->data['c']=$rsa_config['c'];
		
		if($this->request->post){			
			$this->request->post=$this->safe->array_decrypt($this->request->post);
			
			if($this->safe->md5check($this->request->post['login_key'])==false)die('error');//如果限ip使用，则用$this->safe->checkip(),否则识别该机器
			$this->service('public/login');
			if($this->S['public/login']->login($this->request->post))
			{
				$this->redirect($this->url->link('home/index'));
			}
			else
			{
				$this->data['error']="登陆失败";
			}		
		}
		$this->display('public/login.html');	//可指定模板，模认为当前controll的名字+方法	
  	}
	
	
}
?>
