<?php
defined('APPLICATION_PATH')
||define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/'));

set_include_path(implode(PATH_SEPARATOR, array(
					       realpath(APPLICATION_PATH. '/lib'),
					       get_include_path()
					       )));
						   
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Doctrine');

$loader->pushAutoloader(array('Doctrine','autoload'));
spl_autoload_register(array('Doctrine', 'modelsAutoload'));

$config = new Zend_Config_Ini(APPLICATION_PATH ."/config.ini" , "doctrine");
$dsn = $config->dsn;

$conn = Doctrine_Manager::connection($dsn, 'doctrine');
$conn->execute("set names utf8");
$query = $conn->prepare("SELECT department.name, COUNT( xfile.id ) AS num
FROM department
LEFT JOIN xfile ON xfile.department_id = department.id
GROUP BY name
ORDER BY COUNT( xfile.id ) DESC 
LIMIT 0 , 6");
$query->execute();
$data = $query->fetchAll();

return $data;