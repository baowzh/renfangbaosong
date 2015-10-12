<?php
class Model_Login extends Model_Base{

  public function __construct()
  {        
    parent::__construct();
  }

  public function indexAction()
  {
  	
    if ($this->isPost())
    {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$login = new Das_Login();
		$login->login($username , $password);
		if ($login->checkLogin()){
			//loginSuccess
			$info = $login->getUserInfo();
			//echo "<pre>";
			//var_dump($info);
			//die;			
			$this->redirect("index");
		}
		else{
			$this->view->isError = true;
			$this->view->error = "登录不成功";
		}
    }
    $this->view->render("login.html");
  }
  





}