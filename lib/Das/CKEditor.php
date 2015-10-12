<?php
class Das_CKEditor {
  
  protected $ckeditor_path ; 
  protected $ckfinder_path ;
  protected $ckeditor_webpath;
  protected $ckfinder_webpath;
  protected $_content;

  public function __construct(){
    $this->ckeditor_path = APPLICATION_PATH . "/resources/scripts/ckeditor/";
    $this->ckfinder_path = APPLICATION_PATH . "/resources/scripts/ckfinder/";
    //echo $this->ckeditor_path;
    //d:\xampp\htdocs\etc.....

    $app_web_path = 'http://' .$_SERVER['SERVER_NAME'] . '' . $_SERVER['SCRIPT_NAME'];   
    $app_web_path = strtolower($app_web_path);   
    $app_web_path = str_replace(  "index.php" , "" , $app_web_path);

    $this->ckeditor_webpath = $app_web_path . "resources/scripts/ckeditor/";
    $this->ckfinder_webpath = $app_web_path . "resources/scripts/ckfinder/";    		
  }

  public function init($content = null)
  {
    require_once $this->ckeditor_path . "ckeditor.php";
    require_once $this->ckfinder_path . "ckfinder.php";
    $ckeditor = new CKEditor();
    $ckeditor->returnOutput = true;
    $ckeditor->basePath = $this->ckeditor_webpath;
    CKFinder::SetupCKEditor($ckeditor , $this->ckfinder_webpath);
	//die($this->_content);
    $contentarea = $ckeditor->editor("editor_content" ,$content);
    return $contentarea;
  }

  
   
}