<?php
class Das_Page{
  protected $query;
  protected $view;
  protected $layout;
  protected $pageNum;
  protected $curPage;
  protected $perPage;
  protected $modName;

  public function __construct(){
  }

  public function setQuery($q){
    $this->query = $q;
  }

  public function setView($v){
    $this->view = $v;
  }

  public function setCurPage($p){
    $this->pageNum = $p;
    $this->curPage = $p;
  }

  public function setPerPage($p){
    $this->perPage = $p;
  }

  public function setModName($name){
    $this->modName = $name;
  }

  public function process(){
    $this->layout = new Doctrine_Pager_Layout(
					      new Doctrine_Pager($this->query,
								 $this->curPage,
								 $this->perPage),
					      new Doctrine_Pager_Range_Sliding(array('chunk' => 5)), '');
    $this->layout->setTemplate('<a href="./index.php?cmd='.$this->modName . '&page={%page_number}">[{%page}]</a>' );
    $this->view->min = $this->pageNum / 10;
    $this->view->max = $this->pageNum * 10;
    $this->view->rcc = $this->query->execute()->count();
    $this->view->page = $this->layout;
    $this->view->data = $this->layout->execute();
  }
}