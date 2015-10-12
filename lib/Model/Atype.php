<?php
class Model_Atype extends Model_Base{

    public function __construct(){
        parent::__construct();
    }

    public function indexAction(){
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
        $q->from('DBModel_AcceptType');
        
        $page = new Das_Page();
        $page->setPerPage(10);
        $page->setCurPage($curPage);
        $page->setModName("Atype");
        $page->setQuery($q);
        $page->setView($this->view);
        $page->process();

        $this->view->render("accepttype.html");
        $q->free();
    }

	public function deleteAction()
	{
		if ($this->view->login->group_id != 1)
        {
            $this->error("权限不够" , "操作权限不够");
            exit();
        }
		$id = $_GET['id'];
        if (!Zend_Validate::is($id , 'Int'))
        {
            $this->error("参数不正确" , "参数非法");
        }

		Doctrine_Core::getTable("DBModel_AcceptType")->find($id)->delete();
		$this->redirect("Atype");

	}

    public function editAction(){
        if ($this->view->login->group_id != 1)
        {
            $this->error("权限不够" , "操作权限不够");
            exit();
        }

        $id = $_GET['id'];
        if (!Zend_Validate::is($id , 'Int'))
        {
            $this->error("参数不正确" , "参数非法");
        }

        $model = Doctrine_Core::getTable("DBModel_AcceptType")->find($id);
        if ($this->isPost()){
            $model->atype = $_POST['atype'];
            $model->name = $_POST['name'];
            $model->save();
            $this->redirect("Atype");
        }

        $this->view->data = $model->toArray();
        $this->view->render("accepttypeedit.html");
        $model->free();        
    }

    public function addAction(){
        if ($this->view->login->group_id != 1)
        {
            $this->error("权限不够" , "操作权限不够");
            exit();
        }
        $model = new DBModel_AcceptType();
        if ($this->isPost())
        {
            $model->atype = $_POST['atype'];
            $model->name = $_POST['name'];
            $model->save();
            $this->redirect("Atype");
        }
        $this->view->render("accepttypeedit.html");
        $model->free();
    }
}