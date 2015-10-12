<?php
class Das_Acl extends Zend_Acl{
  
  public function __construct()
  {
    $roleGuest = new Zend_Acl_Role('guest');
    $guest = $roleGuest->getRoleid();
    

    /*
      设置用户组， 为 游客 普通 采编 超管
     */
    $this->addRole(new Zend_Acl_Role($guest));
    $this->addRole(new Zend_Acl_Role('normal') , $guest);
    $this->addRole(new Zend_Acl_Role('publisher') , 'normal');
    $this->addRole(new Zend_Acl_Role('admin') , 'publisher');
 
    //可增加报送的信息，可编辑自己的报送的信息
    $this->add(new Zend_Acl_Resource('xfile_normal'));
	
	//下发文件
	$this->add(new Zend_Acl_Resource('xfile_all'));
	
	
	
  }
}