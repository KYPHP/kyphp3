<?php 
class ServicePublicLogin extends Service { 
	public function login($data) {
		$this->model('blog/data');
		$test=$this->M['blog/data']->test();
		$this->session->data['test']=$test;
		$data['Password'] =$this->rsa-> decrypt($data['Password'], $this->config->get('rsa_config'));
			$password=md5('admin');
			if($data['Login']=='admin'&&$data['Password']==$password)
			{
				$this->session->data['username']='admin';
				
				return true;
			}else{
			
				return false;
			}
  	}
	
	
}
?>
