<?php
class Model_Base{
  public $view;

  protected $res_path;
  protected $app_web_path;
  protected $allow_redirect;

  public function __construct()
  {
    $this->allow_redirect = true;
    $this->view = Das_View::getInstance($this->getDefaultFile());    
    $app_web_path = 'http://' .$_SERVER['SERVER_NAME'] . '' . $_SERVER['SCRIPT_NAME'];
    $app_web_path = strtolower($app_web_path);
    $app_web_path = str_replace(  "index.php" , "" , $app_web_path);
    $this->app_web_path = $app_web_path;
    $this->view->RES_PATH = $app_web_path;

    $dv = new Das_ViewUtil();
    $this->view->dv = $dv;
	
	$login = new Das_Login();
	$this->view->login = $login->getUserInfo();
    
    $act = $this->getAction();

    if (!isset($act) && ($act == null))
    {       
		$act = "index";
    }   
    $act = strtolower($act);	       
	$method = $act . "Action";	
	
	if (method_exists($this , $method))
	{
		$this->$method();
	}
	else
	{
		$this->indexAction();
	}
  }

  public function indexAction(){
    echo "base index";
  }

  public function getDefaultFile()
  {
    return "index.html";
  }

  public function getAction()
  {
      if (isset($_GET['act'])){
        return $_GET['act'];
      };
  }

  public function redirect($cmd , $act=null)
  {
    $url = $this->app_web_path . "index.php?cmd=" . strtolower($cmd);
    if (!is_null($act))
      {
	$url .= "&act=".$act;
      }
    echo "<script>location.href=\"$url\";</script>";
  }

  public function isPost()
  {
    return isset($_POST) && ($_POST != NULL);
  }
  
  public function error($title , $text)
  {
		$this->view->title = $title;
		$this->view->text = $text;
		$this->view->render("error.html");
		exit;	  
  }
}