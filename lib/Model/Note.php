<?php
class Model_Note extends Model_Base{
  public function __construct(){
    parent::__construct();
  }


public function indexAction(){
  if ($this->view->login->group_id != 1)
  {
    $this->error("权限不够" , "操作权限不够");
  }    
    $query = Doctrine_Query::create();
    $query->from('DBModel_Note n');     
    $query->orderBy('n.id desc');
    $gp = $_GET['page'];
    if (Zend_Validate::is($gp , 'Int'))
      $curPage = $gp;
    else
      $curPage = 1;

    $page = new Das_Page();
    $page->setPerPage(10);
    $page->setCurPage($curPage);
    $page->setModName("note");
    $page->setQuery($query);
    $page->setView($this->view);
    $page->process();  
    $this->view->render("note.html");
    $query->free();      
}

public function showAction(){
    $query = Doctrine_Query::create();
    $query->from('DBModel_Note n');     
    $query->orderBy('n.id desc');
    $gp = $_GET['page'];
    if (Zend_Validate::is($gp , 'Int'))
      $curPage = $gp;
    else
      $curPage = 1;

    $page = new Das_Page();
    $page->setPerPage(10);
    $page->setCurPage($curPage);
    $page->setModName("note");
    $page->setQuery($query);
    $page->setView($this->view);
    $page->process();  
    $this->view->render("shownote.html");
    $query->free();    
}

  public function addAction(){
  if ($this->view->login->group_id != 1)
  {
    $this->error("权限不够" , "操作权限不够");
  }  
    if ($this->isPost()){
      $post = $_POST;   
      $model = new DBModel_Note();
      $model->title = $post['title'];
      $model->content = $post['editor_content'];
      $model->post_time = new Doctrine_Expression("NOW()");
      $model->save();
      $model->free();      
      $this->redirect("note");
    }
    $editor = new Das_CKEditor();
    $this->view->editor = $editor->init();    
    $this->view->render("noteedit.html");
  }

public function editAction(){
  if ($this->view->login->group_id != 1)
  {
    $this->error("权限不够" , "操作权限不够");
  }  
  $editor = new Das_CKEditor();
    $id = $_GET['id'];
    $q = Doctrine_Query::create()->from('DBModel_Note')->where('id = ? ', $id)->fetchOne();    
    $this->view->data = $q;
    $this->view->editor = $editor->init($this->view->data['content']);    

    if ($this->isPost()){
      $p = $_POST;
      $q->free();
      $q = Doctrine_Query::create();
      $q->update('DBModel_Note');      
      $q->set('title' ,'?' , $p['title']);
      $q->set('content' ,'?' , $p['editor_content']);
      $q->where('id =?', $id);
      $q->execute();
      $this->redirect("note");
    }

        $this->view->render("noteedit.html");
       $q->free();
}    


  public function deleteAction(){
  if ($this->view->login->group_id != 1)
  {
    $this->error("权限不够" , "操作权限不够");
  }  
  $id = $_GET['id'];
  Doctrine_Core::getTable('DBModel_Note')->find($id)->delete();
  $this->redirect("note");
  }


}