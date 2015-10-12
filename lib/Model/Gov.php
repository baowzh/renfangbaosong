<?php
class Model_Gov extends Model_Base{
	public function indexAction(){
		
 	$gp = $_GET['page'];
    if (Zend_Validate::is($gp , 'Int'))
      $curPage = $gp;
    else
      $curPage = 1;
	  		
	$query = Doctrine_Query::create();
	$query->from('DBModel_GovPublic g');
	$query->leftJoin('g.Department d');
		
	$page = new Das_Page();
    $page->setPerPage(10);
    $page->setCurPage($curPage);
    $page->setModName("gov");
    $page->setQuery($query);
    $page->setView($this->view);
    $page->process();
    

    $this->view->render("govpublic.html");
    $query->free();		
		
	}
	
	public function addAction(){
		if ($this->isPost())
		{
			//echo "<pre>";
			//var_dump($_POST);
			//die;
			$model = new DBModel_GovPublic();
			$post = $_POST;
			$model->title = $post['title'];
			$model->content = $post['editor_content'];
			$model->author = $post['author'];
			$model->department_id = $this->view->login->department_id;
			$model->user_id = $this->view->login->id;
			$model->catid = $post['catid'];
			//$model->title = $post['title']; 
			$model->save();
			$model->free();
			$this->redirect("gov");
		}
		$editor = new Das_CKEditor();
        $this->view->editor = $editor->init();
		
		$cats = Doctrine_Core::getTable('DBModel_GovPublicCat')->findAll();
		$this->view->cats = $cats;
		$this->view->render("govpublicedit.html");
	}
	
	public function editAction(){
		$id = $_GET['id'];
		$model = Doctrine_Core::getTable("DBModel_GovPublic")->find($id);

		if ($this->isPost())
		{
			$model->title = $_POST['title'];
			$model->author = $_POST['author'];
			$model->content = $_POST['editor_content'];
			$model->save();
			$model->free();
			$this->redirect("gov");
		}

		$editor = new Das_CKEditor();
        $this->view->editor = $editor->init($model->content);

		$this->view->data = $model->toArray();

		$cats = Doctrine_Core::getTable('DBModel_GovPublicCat')->findAll();
		$this->view->cats = $cats;

		$this->view->render("govpublicedit.html");


	}

	public function showAction(){
		$id = $_GET['id'];
		$query = Doctrine_Query::create()->from("DBModel_GovPublic")->addWhere("id=?",$id)->fetchOne();
		$this->view->data = $query;
		$this->view->render("show.html");
	}
	
	public function catindexAction(){
		$query = Doctrine_Query::create();
		$query->from('DBModel_GovPublicCat d');	
 		$gp = $_GET['page'];
    	if (Zend_Validate::is($gp , 'Int')) $curPage = $gp;
    	else $curPage = 1;
		
		$page = new Das_Page();
    	$page->setPerPage(10);
    	$page->setCurPage($curPage);
    	$page->setModName("gov");
    	$page->setQuery($query);
    	$page->setView($this->view);
    	$page->process();					
		
		$this->view->render("govpubliccat.html");
	}
	
	public function cateditAction(){		
		$id = $_GET['id'];		
		$model = Doctrine_Core::getTable('DBModel_GovPublicCat')->find($id);
		
		if ($this->isPost()){
			$post = $_POST;
			$model = Doctrine_Core::getTable('DBModel_GovPublicCat')->find($_POST['id']);
			$model->name = $post['name'];
			$model->save();
			$model->free();
			$this->redirect("gov" , "catindex");
		}
		$this->view->data = $model->toArray();
		$this->view->render("govpubliccatedit.html");
	}
	
	public function cataddAction(){		
		if ($this->isPost()){
			$model = new DBModel_GovPublicCat();
			$model->name = $_POST['name'];
			$model->save();
			$model->free();
			$this->redirect("gov" , "catindex");
		}
		$this->view->render("govpubliccatedit.html");
	}	

	public function acceptAction(){
		$id = $_GET['id'];
		if (!Zend_Validate::is($id , 'Int'))
		{
			$this->error("参数非法" , "无效参数！");
		}
		$query = Doctrine_Query::create();
		$query->from("DBModel_GovPublic")->addWhere("id =?", $id);
		$ret = $query->fetchOne();

		if ($this->isPost())
		{			
			$title = $ret['title'];
			$content = $ret['content'];
			$catid = $_POST['cmscatid'];
			$this->addtocms($catid , $title, $content);
			echo "<script>alert('发布到网站后台成功，请查看网站后台相关栏目！');</script>";
			$this->redirect("gov");
		}
		
		
			$this->view->data = $ret;
			$query->free();

		  $cmsdb = Das_CmsDb::factory();
		  $cmsdb->query("set names utf8");
		  $ret2 = $cmsdb->query("select * from v9_category where siteid=1 and parentid=21");
		  $this->view->cmstypes = $ret2;

		  $this->view->render("govaccept.html");


	}

public function addtocms($cid , $title , $content)
  {
  	$db = Das_CmsDb::factory();
  	$db->query("set names utf8");

  	$dq = $db->fetchOne("select domain from v9_site where siteid = 1");
  	$domain = $dq;

  	$fdata = array(  		
  		'catid' => $cid,
  		'typeid' => 0,
  		'title' => $title,  		
  		'listorder'=>0,
  		'status' => 99,  		
  		'sysadd'=>1,
  		'islink' => 0,
  		'inputtime' => time()
  	);

  	$db->beginTransaction();
  	try{
  		$db->insert('v9_xzgk' , $fdata);
  		$lastid = $db->lastInsertId();
  		$mdata = array(
  			'id'=>$lastid,
  			'content'=>$content
  		);
  		$db->insert('v9_xzgk_data' , $mdata);

  		$up_data = array('url'=>$dq."index.php?m=content&c=index&a=show&catid=$cid&id=$lastid"); 
  		$where = array('id' => $lastid);
  		$db->update('v9_xzgk' , $up_data ,$where);


  		$db->commit();
  	}catch(Exception $e)
  	{
  		$db->rollBack();
  		echo "<pre>";
  		var_dump($e);
  		die;
  		return;
  	}
  }
}