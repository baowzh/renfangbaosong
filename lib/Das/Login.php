<?php
class Das_Login {
	protected $auth;
	public $name = 'userlogin';
	public function __construct() {
		$this->auth = Zend_Auth::getInstance ();
		$storage_session = new Zend_Auth_Storage_Session ( $this->name );
		$storage_session->setExpirationSeconds ( 300000 );
		$this->auth->setStorage ( $storage_session );
	}
	public function checkLogin() {
		return $this->auth->hasIdentity ();
	}
	public function login($username, $password) {
		$ret = false;
		$filter = new Zend_Filter_StripTags ();
		$username = $filter->filter ( $username );
		$password = $filter->filter ( $password );
		if (isset ( $username ) && isset ( $password )) {
			$db = Das_Db::factory ();
			$authAdapter = new Zend_Auth_Adapter_DbTable ( $db );
			$authAdapter->setTableName ( 'v9_user' );
			$authAdapter->setIdentityColumn ( 'username' );
			$authAdapter->setCredentialColumn ( 'password' );
			$authAdapter->setIdentity ( $username );
			$authAdapter->setCredential ( $password );
			$result = $this->auth->authenticate ( $authAdapter );
			if ($result->isValid ()) {
				$storage = $this->auth->getStorage ();
				// $retObj = $authAdapter->getResultRowObject();
				// $storage->write($retObj->group_id);
				$storage->write ( $authAdapter->getResultRowObject () );
				$ret = true;
			}
		}
		return $ret;
	}
	public function getUserInfo() {
		return $this->auth->getIdentity ();
	}
	public function logout() {
		$this->auth->clearIdentity ();
	}
}