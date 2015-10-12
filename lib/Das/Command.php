<?php
class Das_Command{

  private $get;
  private $post;
  private $mod;
  private $act;
  private $view;
  private $isPost;

  public function __construct()
  {
    $this->post = $_POST;
    $this->get = $_GET;
    $this->init();
  }

  protected function init()
  {
    /*
    if (isset($this->post) && $this->post != null)
      {		
	$this->isPost = true;
	$this->doPost($this->post);
	exit;
      }
    if (isset($this->get))
      {       
	$this->doGet($this->get);
      }   
    */
    $cmd = $this->get['cmd'];
    $prefix = "Model_";
    if (isset($cmd))
      {
	//echo "eval command";
	$cmd = strtolower($cmd);
	$modname = $prefix . ucfirst($cmd);
	//echo $modname;
	if (class_exists($modname))
	  {
	    $mod = new $modname();
	  }
      }
    else
      {
	//echo "fuck up";
	$modname = "index";
	$modname = ucfirst($modname);
	//echo $modname;
	$modname = $prefix .$modname;
	if (class_exists($modname))
	  {
	    $mod = new $modname();	   
	  }
      }
  }

  public function getInstance()
  {
    return new Das_Command();
  }
    
}