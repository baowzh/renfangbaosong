<?php
defined('APPLICATION_PATH')
||define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/'));

set_include_path(implode(PATH_SEPARATOR, array(
					       realpath(APPLICATION_PATH. '/lib'),
					       get_include_path()
					       )));
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Zend');
$loader->registerNamespace('Das');
$loader->registerNamespace('Model');
$loader->registerNamespace('Doctrine');

//require_once 'Twig/Autoloader.php';
//Twig_Autoloader::register();

//loading doctrine
$loader->pushAutoloader(array('Doctrine','autoload'));
spl_autoload_register(array('Doctrine', 'modelsAutoload'));
$manager = Doctrine_Manager::getInstance();
$manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE,true);
$manager->setAttribute( 
    Doctrine_Core::ATTR_MODEL_LOADING, 
    1 
); 
$model_path = APPLICATION_PATH . "/lib/DBModel";
Doctrine_Core::loadModels($model_path);
$dsn = "mysql://root@localhost/fulian";
$conn = Doctrine_Manager::connection($dsn, 'doctrine');
$conn->setAttribute(Doctrine::ATTR_USE_NATIVE_ENUM, true);

$config = new Zend_Config_Ini('c.ini','ccc');
var_dump($config->toArray());
die;
$cli = new Doctrine_Cli($config->toArray());
$cli->run($_SERVER['argv']);