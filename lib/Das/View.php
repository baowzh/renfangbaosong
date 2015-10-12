<?php

require_once 'Twig/Autoloader.php';

class Das_View{
  private $view;
  private $loader;
  private $data;
  private $fileName;

  public static function getInstance($fn)
  {
    return new Das_View($fn);
  }

  public function __construct($filename)
  {
    Twig_Autoloader::register();
    $this->fileName = $filename;
    $this->data = array();
    $this->init();
  }

  public function init()
  {
    $this->loader = new Twig_Loader_Filesystem(APPLICATION_PATH . '/template');
    $this->view = new Twig_Environment($this->loader); //todo add cache
  }

  public function render($filename = null)
  {
    if ($filename != null)  $this->fileName = $filename;
    echo $this->view->render($this->fileName , $this->data);
  }

  public function assign($key , $value)
  {
    $this->data[$key] = $value;
  }

  public function __set($key, $value)
  {
    $this->assign($key ,$value);
  }
  
  public function __get($key)
  {
	  return $this->data[$key];
  }
}