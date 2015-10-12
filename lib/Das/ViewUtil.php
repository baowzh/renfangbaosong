<?php
class Das_ViewUtil{
  
  public function __construct(){
  }

  public function url($mod , $act= null , $params = null)
  {
    $app_web_path = 'http://' .$_SERVER['SERVER_NAME'] . '' . $_SERVER['SCRIPT_NAME'];    
    $app_web_path = strtolower($app_web_path);
    $url = $app_web_path ."?cmd=".$mod;
    
    if ($act != null) $url .= "&act=" . $act;

    if (is_array($params))
      {
	foreach($params as $k=>$v)
	  {
	    $url .= "&" .  $k ."=". $v ."&";
	  }
      }
    return $url;
  }
}