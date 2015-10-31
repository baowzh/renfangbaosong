<?php
class Model_Summery extends Model_Base {
	public function __construct() {
		parent::__construct ();
	}
	public function indexAction() {
		// $dv = new Das_ViewUtil();
		// $params = array('a'=>'test','id'=>1);
		// $dv->url("xfile" , "edit" , $params);
		//
		// $this->view->render("index.html");
		// echo "test";
		$q = Doctrine_Manager::getInstance ()->getCurrentConnection ();
		// var_dump($q);
		$result = $q->execute ( "
select 
v9_department.name,
count(v9_xfile.id) as sendnum,
count(v9_fileaccept.xfile_id) as anum,
(select count(accept_typeid) from v9_fileaccept where accept_typeid=1435 and v9_fileaccept.xfile_id=v9_xfile.id) as cy1,
(select count(accept_typeid) from v9_fileaccept where accept_typeid=1440 and v9_fileaccept.xfile_id=v9_xfile.id) as cy2,
(select count(accept_typeid) from v9_fileaccept where accept_typeid=1453 and v9_fileaccept.xfile_id=v9_xfile.id) as cy3,
(select count(accept_typeid) from v9_fileaccept where accept_typeid=1454 and v9_fileaccept.xfile_id=v9_xfile.id) as cy4,
(select count(accept_typeid) from v9_fileaccept where accept_typeid=1455 and v9_fileaccept.xfile_id=v9_xfile.id) as cy5,
(select count(accept_typeid) from v9_fileaccept where accept_typeid=1457 and v9_fileaccept.xfile_id=v9_xfile.id) as cy6,
(select count(accept_typeid) from v9_fileaccept where accept_typeid=1458 and v9_fileaccept.xfile_id=v9_xfile.id) as cy7,
(select count(accept_typeid) from v9_fileaccept where accept_typeid=1459 and v9_fileaccept.xfile_id=v9_xfile.id) as cy8,
(select count(accept_typeid) from v9_fileaccept where accept_typeid=1476 and v9_fileaccept.xfile_id=v9_xfile.id) as cy9
from v9_department
left join v9_xfile on v9_xfile.department_id = v9_department.id
left join v9_fileaccept on v9_xfile.id=v9_fileaccept.xfile_id
group by name
order by sendnum desc

    " );
		$result->setFetchMode ( Doctrine_Core::FETCH_ASSOC );
		$ret = $result->fetchAll ();
		// var_dump($ret);
		$this->view->data = $ret;
		$this->view->render ( "bszs.html" );
	}
	public function caiyongAction() {
		$q = Doctrine_Manager::getInstance ()->getCurrentConnection ();
		// var_dump($q);
		$result = $q->execute ( "SELECT v9_department.name AS name, COUNT( v9_fileaccept.xfile_id ) AS num
FROM v9_department
LEFT JOIN v9_xfile ON v9_xfile.department_id = v9_department.id
LEFT JOIN v9_fileaccept ON v9_xfile.id = v9_fileaccept.xfile_id
GROUP BY name
ORDER BY COUNT( v9_fileaccept.xfile_id ) DESC " );
		$result->setFetchMode ( Doctrine_Core::FETCH_ASSOC );
		$ret = $result->fetchAll ();
		// var_dump($ret);
		$this->view->data = $ret;
		$this->view->render ( "cyzs.html" );
	}
	public function logoutAction() {
		$login = new Das_login ();
		$login->logout ();
		$this->redirect ( "login" );
	}
}