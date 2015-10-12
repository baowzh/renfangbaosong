<?php
class Model_User extends Model_Base{
  public function __construct()
  {
    parent::__construct();
  }

  public function indexAction()
  {
	if ($this->view->login->group_id != 1)
	{
		$this->error("权限不够" , "操作权限不够");
	}
		  
    $query = Doctrine_Query::create();
    $query->from('DBModel_User u');
    $query->leftJoin('u.Department dp');
    $query->leftJoin('u.Group gp');    
    $query->orderBy('u.id desc');
    

    $gp = $_GET['page'];
    if (Zend_Validate::is($gp , 'Int'))
      $curPage = $gp;
    else
       $curPage = 1;
   
    $page = new Das_Page();
    $page->setPerPage(10);
    $page->setCurPage($curPage);
    $page->setQuery($query);
    $page->setView($this->view);
    $page->setModName("user");
    $page->process();
   
    $this->view->render("user.html");

    $query->free();
    
  }

  public function addAction(){
	if ($this->view->login->group_id != 1)
	{
		$this->error("权限不够" , "操作权限不够");
	}	  
    if ($this->isPost()){
      $post = $_POST;
      $model = new DBModel_User();
      $model->username = $post['username'];
      $model->password = $post['password'];
      $model->group_id = $post['group'];
      $model->department_id = $post['department'];
      $model->role = $post['role'];
      $model->save();
      $model->free();
      $this->redirect("user");
    }
    $group = Doctrine_Query::create()->from('DBModel_Group')->execute();
    $department = Doctrine_Query::create()->from('DBModel_Department')->execute();
    $this->view->group = $group;
    $this->view->department = $department;
    $this->view->render("useredit.html");
  }

  public function deleteAction()
	{
		if ($this->view->login->group_id != 1)
		{
			$this->error("权限不够" , "操作权限不够");
		}
		$id = $_GET['id'];
		Doctrine_Core::getTable('DBModel_User')->find($id)->delete();
		$this->redirect("user");
	}

  public function editAction(){
	if ($this->view->login->group_id != 1)
	{
		$this->error("权限不够" , "操作权限不够");
	}
	$id = $_GET['id'];
	if (!Zend_Validate::is($id , 'Int')){
		$this->error("参数不正确" , "所传递的参数不正确");
	}
	if ($this->isPost())
	{
		$post = $_POST;
		$userModel = Doctrine_Core::getTable("DBModel_User")->find($id);
		$userModel->username = $post['username'];
      	$userModel->password = $post['password'];
      	$userModel->group_id = $post['group'];
      	$userModel->department_id = $post['department'];
      	$userModel->role = $post['role'];
      	$userModel->save();
		$userModel->free();
		$this->redirect("user");
	}    
    $user = Doctrine_Query::create()->from('DBModel_User u')->where("id = ?", $id);
    $user->leftJoin('u.Group g');
    $user->leftJoin('u.Department d');
    $user = $user->fetchOne();
    $group = Doctrine_Query::create()->from('DBModel_Group')->execute();
    $department = Doctrine_Query::create()->from('DBModel_Department')->execute();
    $this->view->group = $group;
    $this->view->department = $department;
    $this->view->data = $user;
    $this->view->render("useredit.html");
    $user->free();
    $group->free();
    $department->free();
  }


}