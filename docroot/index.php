<?php

define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));
define('APPLICATION_PATH', BASE_PATH . '/application');

set_include_path(
	BASE_PATH . '/library/incubator'
    . PATH_SEPARATOR .BASE_PATH . '/library'
    . PATH_SEPARATOR .APPLICATION_PATH . '/library'
    . PATH_SEPARATOR . get_include_path()
);

// require_once('Zend/Loader.php');
// $autoloader = Zend_Loader_Autoloader::getInstance();
function __autoload($className) {
	require_once(implode('/', explode('_', $className)).'.php');
}

Zend_Controller_Front::run(APPLICATION_PATH.'/controllers');
