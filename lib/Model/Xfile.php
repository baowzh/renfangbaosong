<?php
class Model_Xfile extends Model_Base {
	public function __construct() {
		parent::__construct ();
	}
	public function indexAction() {
		$query = Doctrine_Query::create ();
		$query->from ( 'DBModel_XFile f' );
		$query->leftJoin ( 'f.Department d' );
		$query->leftJoin ( 'f.FileAccept ac' );
		if ($this->view->login->group_id == 3) {
			// 确保登录用户能看见自己的信息
			$query->addWhere ( "department_id = ? ", $this->view->login->department_id );
			// 确保信息被发送到登录用户所属部门
			if ($this->view->login->group_id == 3) {
				$added_sql = "FIND_IN_SET('%s',sendto) > 0 ";
				$added_sql = sprintf ( $added_sql, $this->view->login->department_id );
				$query->orWhere ( $added_sql );
			}
			$query->andWhere ( "is_xfwj = 0" );
			$query->orderBy ( 'f.id desc' );
		} else {
			$query->addWhere ( "is_xfwj = 0" );
			$query->orderBy ( 'f.id desc' );
		}
		$gp = $_GET ['page'];
		if (Zend_Validate::is ( $gp, 'Int' ))
			$curPage = $gp;
		else
			$curPage = 1;
		$page = new Das_Page ();
		$page->setPerPage ( 40 );
		$page->setCurPage ( $curPage );
		$page->setModName ( "xfile" );
		$page->setQuery ( $query );
		$page->setView ( $this->view );
		$page->process ();
		$this->view->render ( "xfile.html" );
		$query->free ();
	}
	public function showAction() {
		$id = $_GET ['id'];
		if (! Zend_Validate::is ( $id, 'Int' )) {
			$this->error ( "参数错误", "传递参数错误！" );
		}
		$query = Doctrine_Query::create ();
		$query->from ( "DBModel_XFile x" );
		$query->leftJoin ( "x.Department d" );
		$query->addWhere ( "id =?", $id );
		$data = $query->fetchOne ();
		$this->view->data = $data;
		
		$this->view->render ( "show.html" );
		$query->free ();
	}
	public function deleteAction() {
		$id = $_GET ['id'];
		if (! Zend_Validate::is ( $id, 'Int' )) {
			$this->error ( "参数错误", "传递参数错误！" );
		}
		
		$aq = Doctrine_Core::getTable ( "DBModel_FileAccept" )->findByxfile_id ( $id );
		foreach ( $aq as $a ) {
			$a->delete ();
		}
		
		$query = Doctrine_Core::getTable ( "DBModel_XFile" )->find ( $id )->delete ();
		$this->redirect ( "xfile" );
	}
	public function editAction() {
		$id = $_GET ['id'];
		if (! Zend_Validate::is ( $id, 'Int' )) {
			$this->error ( "参数错误", "传递参数错误！" );
		}
		$query = Doctrine_Query::create ()->from ( 'DBModel_XFile' )->where ( 'id = ? ', $id );
		$xfileModel = $query->fetchOne ();
		
		// 不是超级管理员则必须是文章发布人
		if (($this->view->login->group_id != 1) and ($xfileModel->user_id != $this->view->login->id)) {
			$this->error ( "权限不足", "您无法编辑此文章，您既不是超级管理员，也不是文章发布人" );
		}
		
		if ($this->isPost ()) {
			$posts = $_POST;
			$cond = Zend_Validate::is ( $posts ['title'], 'StringLength', array (
					1,
					999 
			) );
			if (! $cond) {
				$this->error ( "标题错误", "标题长度不够" );
			}
			$cond = Zend_Validate::is ( $posts ['editor_content'], 'StringLength', array (
					1,
					50000 
			) );
			if (! $cond) {
				$this->error ( "内容错误", "内容长度不够" );
			}
			$sendto = $posts ['send_to'];
			if (count ( $sendto ) != 0 or $sendto != null) {
				$xfileModel->send_to = $sendto;
			}
			$xfileModel->title = $posts ['title'];
			$xfileModel->content = $posts ['editor_content'];
			$xfileModel->author = $posts ['author'];
			$xfileModel->is_xfwj = $posts ['xfwj'];
			$xfileModel->update_time = new Doctrine_Expression ( "NOW()" );
			$xfileModel->save ();
			$xfileModel->free ();
			$this->redirect ( "XFile" );
		}
		
		$this->view->data = $xfileModel->toArray ();
		$editor = new Das_CKEditor ();
		$query = Doctrine_Query::create ()->from ( 'DBModel_Department' );
		$this->view->department = $query->execute ()->toArray ();
		$query->free ();
		$this->view->editor = $editor->init ( $this->view->data ['content'] );
		$this->view->render ( "xfileedit.html" );
	}
	public function addAction() {
		if ($this->isPost ()) {
			$post = $_POST;
			
			$cond = Zend_Validate::is ( $post ['title'], 'StringLength', array (
					1,
					999 
			) );
			if (! $cond) {
				$this->error ( "标题错误", "标题长度不够" );
			}
			$cond = Zend_Validate::is ( $post ['editor_content'], 'StringLength', array (
					1,
					50000 
			) );
			if (! $cond) {
				$this->error ( "内容错误", "内容长度不够" );
			}
			
			$sendto = $post ['sendto'];
			$sendto = implode ( ",", $sendto );
			$model = new DBModel_XFile ();
			$model->title = $post ['title'];
			$model->content = $post ['editor_content'];
			$model->author = $post ['author'];
			$model->is_xfwj = $post ['xfwj'];
			$model->sendto = $sendto;
			$model->Department = Doctrine_Core::getTable ( "DBModel_Department" )->find ( $this->view->login->department_id );
			$model->user_id = $this->view->login->id;
			$model->post_time = new Doctrine_Expression ( "NOW()" );
			$model->save ();
			$model->free ();
			$this->redirect ( "XFile", "index" );
		}
		
		$editor = new Das_CKEditor ();
		$this->view->editor = $editor->init ();
		
		$query = Doctrine_Query::create ()->from ( 'DBModel_Department' );
		$department = $query->execute ()->toArray ();
		$this->view->department = $department;
		$this->view->render ( "xfileedit.html" );
	}
	public function xiafawenjianAction() {
		$query = Doctrine_Query::create ();
		$query->from ( 'DBModel_XFile f' );
		$query->leftJoin ( 'f.Department d' );
		$query->leftJoin ( 'f.FileAccept ac' );
		$query->orderBy ( 'f.id desc' );
		if ($this->view->login->group_id == 3) {
			// 确保登录用户能看见自己的信息
			// $query->addWhere("department_id = ? ", $this->view->login->department_id);
			// 确保信息被发送到登录用户所属部门
			if ($this->view->login->group_id == 3) {
				$added_sql = "FIND_IN_SET('%s',sendto) > 0 ";
				$added_sql = sprintf ( $added_sql, $this->view->login->department_id );
				$query->orWhere ( $added_sql );
			}
			$query->andWhere ( "is_xfwj = 1" );
			$query->orderBy ( 'f.id desc' );
		} else {
			$query->addWhere ( "is_xfwj = 1" );
			$query->orderBy ( 'f.id desc' );
		}
		
		$gp = $_GET ['page'];
		if (Zend_Validate::is ( $gp, 'Int' ))
			$curPage = $gp;
		else
			$curPage = 1;
		
		$page = new Das_Page ();
		$page->setPerPage ( 10 );
		$page->setCurPage ( $curPage );
		$page->setModName ( "Xfile" );
		$page->setQuery ( $query );
		$page->setView ( $this->view );
		$page->process ();
		
		$this->view->render ( "xfile.html" );
		$query->free ();
	}
	public function acceptAction() {
		$id = $_GET ['id'];
		if (! Zend_Validate::is ( $id, 'Int' )) {
			$this->error ( "参数错误", "传递的参数有误!" );
		}
		if ($this->view->login->group_id == 1 or $this->view->login->group_id == 2) {
			
			$xfile = Doctrine_Core::getTable ( "DBModel_XFile" )->find ( $id );
			$this->view->xfile = $xfile;
			
			if ($this->isPost ()) {
				
				$model = new DBModel_FileAccept ();
				$model->xfile_id = $_POST ['xfile_id'];
				$model->cms_catid = $_POST ['cmscatid'];
				$model->accept_typeid = $_POST ['accepttype'];
				$model->accept_time = new Doctrine_Expression ( "NOW()" );
				$model->save ();
				if ($_POST ['cmscatid'] != 0) {
					$this->addart ( $_POST ['cmscatid'], $xfile->title, $xfile->content );
				}
				$this->redirect ( "xfile", "index" );
			}
			
			$atype_local = Doctrine_Query::create ()->from ( "DBModel_AcceptType" )->addWhere ( "atype = ?", 1 )->execute ()->toArray ();
			$atype_up = Doctrine_Query::create ()->from ( "DBModel_AcceptType" )->addWhere ( "atype = ?", 2 )->execute ()->toArray ();
			$this->view->types_local = $atype_local;
			$this->view->types_up = $atype_up;
			
			$cmsdb = Das_CmsDb::factory ();
			$cmsdb->query ( "set names utf8" );
			$ret = $cmsdb->query ( " select a.catid,a.catname,parentid from v9_category a where siteid=1 order by  parentid,catid" );
			$this->view->cmstypes = $ret;
			$arr = array
			(
					'Name'=>'111',
					'Age'=>20
			);
			$cats=$ret->fetchAll();
			$this->view->cats =$cats;
			$this->view->render ( "accept.html" );
		} else {
			$this->error ( "权限不足", "无法使用此功能！" );
		}
	}
	
	/*
	 * define('PHPCMS_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR."/../" );
	 * var_dump($_SERVER);
	 *
	 *
	 * public function get_keywords($data, $number = 3) {
	 * include PHPCMS_PATH.'/phpcms/base.php';
	 * $data = trim(strip_tags($data));
	 * if(empty($data)) return '';
	 * $http = pc_base::load_sys_class('http');
	 * if(CHARSET != 'utf-8') $data = iconv('utf-8', CHARSET, $data);
	 * else $data = iconv('utf-8', 'gbk', $data);
	 * $http->post(API_URL_GET_KEYWORDS, array('siteurl'=>SITE_URL, 'charset'=>CHARSET, 'data'=>$data, 'number'=>$number));
	 * if($http->is_ok()) {
	 * if(CHARSET != 'utf-8') return $http->get_data();
	 * else return iconv('gbk', 'utf-8', $http->get_data());
	 * }
	 * return '';
	 * }
	 */
	public function addart($catid, $title, $content) { //
		include '../phpcms/base.php';
		$info = array ();
		$info ['catid'] = $catid;
		$info ['title'] = $title;
		$info ['content'] = $content;
		echo "<pre>";
		$db_config = pc_base::load_config ( 'database' );
		pc_base::load_sys_class ( 'mysql', '', 0 );
		pc_base::load_sys_class ( 'param', '', 0 );
		$db = pc_base::load_model ( 'content_model' );
		$categorys = getcache ( 'category_content_1', 'commons' );
		
		$category = $categorys [$catid];
		// var_dump($category);
		$modelid = intval ( $category ['modelid'] );
		// var_dump($modelid);
		// die;
		$db->set_model ( $modelid );
		
		$setting = string2array ( $category ['setting'] );
		// var_dump($setting);
		$workflowid = $setting ['workflowid'];
		$info ['status'] = 99;
		
		$info ['keywords'] = "";
		$info ['description'] = str_cut ( str_replace ( array (
				"\r\n",
				"\t",
				'[page]',
				'[/page]',
				'&ldquo;',
				'&rdquo;',
				'&nbsp;' 
		), '', strip_tags ( $info ['content'] ) ), 200 );
		$info ['username'] = "sumuya";
		$info ['inputtime'] = '';
		// var_dump($info);
		// echo "1";
		if ($db->add_content ( $info ))
			return true;
		
		/*
		 * //include PHPCMS_PATH.'/phpcms/base.php';
		 * include '../phpcms/base.php';
		 * $categorys = getcache('category_content_1','commons');
		 * $info=array();
		 * $info['catid'] = $catid;
		 * $info['title'] = $title;
		 * $info['content'] = $content;
		 * $db_config = pc_base::load_config('database');
		 * pc_base::load_sys_class('mysql', '', 0);
		 * pc_base::load_sys_class('param', '', 0);
		 * $db = pc_base::load_model('content_model');
		 *
		 * $category = $categorys[$catid];
		 * $modelid = $category['modelid'];
		 * $db->set_model($modelid);
		 *
		 * $setting = string2array($category['setting']);
		 * //var_dump($setting);
		 * //die;
		 * $workflowid = $setting['workflowid'];
		 * $info['status'] = 99;
		 *
		 * //$info['keywords'] = get_keywords($info['title'], 3);
		 * $info['keywords'] = '';
		 * $info['description'] = str_cut(str_replace(array("\r\n","\t",'[page]','[/page]','&ldquo;','&rdquo;','&nbsp;'), '', strip_tags($info['content'])),200);
		 * $info['username'] = "sumuya";
		 * $info['inputtime'] = "";
		 *
		 *
		 *
		 * if($db->add_content($info)) return true;
		 * return false;
		 */
	}
	public function addtocms($cid, $title, $content) {
		
		// require_once '../xpush.php';
		// $this->addart($cid , $title , $content);
		
		// 这是老版本，会导致地址错乱问题
		$db = Das_CmsDb::factory ();
		$db->query ( "set names utf8" );
		
		$dq = $db->fetchOne ( "select domain from v9_site where siteid = 1" );
		$domain = $dq;
		
		$fdata = array (
				'catid' => $cid,
				'typeid' => 0,
				'title' => $title,
				'listorder' => 0,
				'status' => 99,
				'sysadd' => 1,
				'islink' => 0,
				'description' => ' ',
				'url' => ' ',
				'username' => 'admin',
				'inputtime' => time () 
		);
		
		$db->beginTransaction ();
		try {
			$db->insert ( 'v9_news', $fdata );
			$lastid = $db->lastInsertId ();
			$mdata = array (
					'id' => $lastid,
					'content' => $content,
					'groupids_view' => 0,
					'paginationtype' => 0,
					'maxcharperpage' => 10000,
					'template' => 'show' 
			)
			;
			$db->insert ( 'v9_news_data', $mdata );
			
			$up_data = array (
					'url' => $dq . "index.php?m=content&c=index&a=show&catid=$cid&id=$lastid" 
			);
			$where = array (
					'id' => $lastid 
			);
			$db->update ( 'v9_news', $up_data, $where );
			
			$db->commit ();
		} catch ( Exception $e ) {
			$db->rollBack ();
			return;
		}
	}
	public function getchildcatAction()
	{   $cid= $_GET['cid'];
	   // $this->getHelper ('layout')->disableLayout ();
	    // print_r($this->getHelper ('layout'));
	    $cid='1440';
	    $cmsdb = Das_CmsDb::factory ();
	    $cmsdb->query ( "set names utf8" );
	    $ret = $cmsdb->query( "select * from v9_category where siteid=1 and parentid=".$cid." order by  parentid,catid" )->fetchAll();
	    header('Content-Type: application/json');
	    $this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
	    echo Zend_Json_Encoder::encode($ret);
		exit;
	}
}