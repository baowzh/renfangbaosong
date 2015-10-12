<?php
class Model_Faq extends Model_Base{
  public function __construct(){
    parent::__construct();
  }


public function indexAction(){

    $query = Doctrine_Query::create();
    $query->from('DBModel_Faq n');     
    $query->orderBy('n.id desc');
    $gp = $_GET['page'];
    if (Zend_Validate::is($gp , 'Int'))
      $curPage = $gp;
    else
      $curPage = 1;

    $page = new Das_Page();
    $page->setPerPage(10);
    $page->setCurPage($curPage);
    $page->setModName("faq");
    $page->setQuery($query);
    $page->setView($this->view);
    $page->process();  
    $this->view->render("faq.html");
    $query->free();      
}

  public function addAction(){
  }

  public function addqAction(){
    if ($this->isPost()){
      $post = $_POST;   
      $model = new DBModel_Faq();
      $model->q = $post['q'];      
      $model->save();
      $model->free();      
      $this->redirect("faq");
    }
    $editor = new Das_CKEditor();
    $this->view->editor = $editor->init();    
    $this->view->render("addq.html");    
  }

  public function addaAction(){
  if ($this->view->login->group_id != 1)
  {
    $this->error("权限不够" , "操作权限不够");
  }      
    $id = $_GET['id'];
    if ($this->isPost()){
      $post = $_POST;   
      $q = Doctrine::getTable("DBModel_Faq")->findOneById($id);
      $q->a = $post['q'];
      $q->save();
      $q->free();
      $this->redirect("faq");      
    }
    $editor = new Das_CKEditor();
    $this->view->editor = $editor->init();    
    $this->view->render("addq.html");    
  }  

public function editAction(){
  
  $editor = new Das_CKEditor();
    $id = $_GET['id'];
    $q = Doctrine_Query::create()->from('DBModel_Faq')->where('id = ? ', $id)->fetchOne();    
    $this->view->data = $q;
    $this->view->editor = $editor->init($this->view->data['content']);    

    if ($this->isPost()){
      $p = $_POST;
      $q->free();
      $q = Doctrine_Query::create();
      $q->update('DBModel_Faq');      
      $q->set('title' ,'?' , $p['title']);
      $q->set('content' ,'?' , $p['editor_content']);
      $q->where('id =?', $id);
      $q->execute();
      $this->redirect("faq");
    }

        $this->view->render("noteedit.html");
       $q->free();
} 

  public function showansAction()
  {
        $id = $_GET['id'];
        $q = Doctrine_Query::create()->from('DBModel_Faq')->where('id = ? ', $id)->fetchOne();    
        $this->view->data = $q;
        $this->view->render("showans.html");
  }   


  public function deleteAction(){
  if ($this->view->login->group_id != 1)
  {
    $this->error("权限不够" , "操作权限不够");
  }      
  $id = $_GET['id'];
  Doctrine_Core::getTable('DBModel_Faq')->find($id)->delete();
  $this->redirect("faq");
  }


}