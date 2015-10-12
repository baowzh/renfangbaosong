<?php
class Das_Util {

  public static function getPageNum(){
    $p = $_GET['page'];
    if (Zend_Validate::is($p , 'Int')){
      return $p;
    }
    else return 1;
  }

  public static function gurl($mod , $act = 'index' ){
    
  }

  
}