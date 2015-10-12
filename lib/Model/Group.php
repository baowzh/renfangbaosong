<?php
class Model_Group extends Model_Base
{
  public function __construct()
  {
    parent::__construct();
  }

  public function indexAction(){
    $q = Doctrine_Query::create();
    $q->from('DBModel_Group');
    $ret = $q->execute();
    $this->view->data = $ret;
    $this->view->render("group.html");
  }

  public function editAction(){
	  if ($this->isPost()){
		  $post = $_POST;
		  
	  }
	  $id = $_GET['id'];
	  if (!Zend_Validate::is($id , 'Int')) exit();
	  $q = Doctrine_Query::create()->from("DBModel_Group")->where('id=?',$id);
	  $data = $q->fetchOne()->toArray();
	  $this->view->data = $data;
	  $q->free();
      $this->view->render("groupedit.html");	 
  }
}