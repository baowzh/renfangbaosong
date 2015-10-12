<?php
class Das_CmsDb extends Zend_Db{
 

  public static function factory()
  {
	$config = new Zend_Config_Ini(APPLICATION_PATH . "/config.ini" , "cms");
	$cfg = array();
	$cfg['host'] = $config->host;
	$cfg['username'] = $config->username;
	$cfg['password'] = $config->password;
	$cfg['dbname'] = $config->dbname;
    return Zend_Db::factory('pdo_mysql' ,$cfg);
  }

}