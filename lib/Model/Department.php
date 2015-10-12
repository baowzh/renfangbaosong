<?php
class Model_Department extends Model_Base
{
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
    $gp = $_GET['page'];
    if (Zend_Validate::is($gp , 'Int'))
      $curPage = $gp;
    else
       $curPage = 1;

    $q = Doctrine_Query::create();
    $q->from('DBModel_Department d');
    $q->orderBy('d.id desc');
    
    $page = new Das_Page();
    $page->setPerPage(10);
    $page->setCurPage($curPage);
    $page->setModName("department");
    $page->setQuery($q);
    $page->setView($this->view);
    $page->process();

    $this->view->render("department.html");
    $q->free();
  }

  public function deleteAction(){
	if ($this->view->login->group_id != 1)
	{
		$this->error("权限不够" , "操作权限不够");
	}  
	$id = $_GET['id'];
	Doctrine_Core::getTable('DBModel_Department')->find($id)->delete();
	$this->redirect("department");
  }


  public function editAction(){
	if ($this->view->login->group_id != 1)
	{
		$this->error("权限不够" , "操作权限不够");
	}  
    $id = $_GET['id'];
    $q = Doctrine_Query::create()->from('DBModel_Department')->where('id = ? ', $id)->fetchOne();
    $this->view->data = $q;

    if ($this->isPost()){
      $p = $_POST;
      $q->free();
      $q = Doctrine_Query::create();
      $q->update('DBModel_Department');      
      $q->set('name' ,'?' , $p['name']);
      $q->where('id =?', $id);
      //echo $q->getSqlQuery();
      //die;
      $q->execute();
      $this->redirect("department");
    }

   
    $this->view->render("departmentedit.html");
    $q->free();

  }

  public function addAction(){
	if ($this->view->login->group_id != 1)
	{
		$this->error("权限不够" , "操作权限不够");
	}  
    if ($this->isPost()){
      $p = $_POST;
      $model = new DBModel_Department();
      $model->name = $p['name'];
      $model->save();
      $model->free();
      $this->redirect("department");
    }
    $this->view->render("departmentedit.html");
  }
}