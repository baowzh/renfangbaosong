<?php
class Model_Index extends Model_Base{

  public function __construct()
  {
    parent::__construct();
  }

  public function indexAction()
  {
    //$dv = new Das_ViewUtil();
    //$params = array('a'=>'test','id'=>1);
    //$dv->url("xfile" , "edit" , $params);
    //
    $this->view->render("index.html");
  }
  
  
  public function logoutAction(){
	  $login = new Das_login();
	  $login->logout();
	  $this->redirect("login");
  }
  
}