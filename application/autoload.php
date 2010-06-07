<?php
/**
 * @since 4/14/09
 * @package catalog
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

if (!defined('BASE_PATH')) {
	define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));
	define('APPLICATION_PATH', BASE_PATH.'/application');
	set_include_path(
		APPLICATION_PATH . '/library'
		. PATH_SEPARATOR . BASE_PATH . '/library/osid-phpkit'
		. PATH_SEPARATOR . BASE_PATH . '/library/ZendFramework/library'
		. PATH_SEPARATOR . BASE_PATH . '/library/fsmparser'
		. PATH_SEPARATOR . BASE_PATH . '/library/phpcas/source'
		. PATH_SEPARATOR . get_include_path()
	);
}

if (!function_exists('__autoload')) {
	function __autoload($className) {
		require_once(implode('/', explode('_', $className)).'.php');
	}
}