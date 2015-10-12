<?php
class Model_Search extends Model_Base{

  public function __construct()
  {
    parent::__construct();
  }

  public function searchAction(){
    $this->view->render("search.html");
  }

  public function indexAction()
  {
    //$dv = new Das_ViewUtil();
    //$params = array('a'=>'test','id'=>1);
    //$dv->url("xfile" , "edit" , $params);
    //
    //$this->view->render("index.html");
    //die("searching");

    $k = $_POST['q'];
    //die($k);



    $gp = $_GET['page'];
    if (Zend_Validate::is($gp , 'Int'))
      $curPage = $gp;
    else
      $curPage = 1;    

    $q = Doctrine_Query::create();    
    $q->from("DBModel_XFile f");
    $q->leftJoin('f.Department d');
    $q->leftJoin('f.FileAccept ac');        
    $q->addWhere("f.title LIKE '%".$k."%' ");    

if ($this->view->login->group_id == 3)
  {  
    //确保登录用户能看见自己的信息
    $q->addWhere("department_id = ? ", $this->view->login->department_id);
    //确保信息被发送到登录用户所属部门
      if ($this->view->login->group_id == 3)
    {
      $added_sql = "FIND_IN_SET('%s',sendto) > 0 ";
      $added_sql = sprintf($added_sql , $this->view->login->department_id);   
      $q->orWhere($added_sql);
    }
    $q->andWhere("is_xfwj = 0");
    $q->orderBy('f.id desc');
  }
  else{
    $q->addWhere("is_xfwj = 0");
    $q->orderBy('f.id desc');
  }    

    $page = new Das_Page();
    $page->setPerPage(10);
    $page->setCurPage($curPage);
    $page->setModName("xfile");
    $page->setQuery($q);
    $page->setView($this->view);
    $page->process();
    

    $this->view->render("xfile.html");
    $q->free();    

  }
  
  
  
}