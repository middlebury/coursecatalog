<?php
/**
 * @since 4/14/09
 * @package catalog
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

if (!defined('OSID_BASE')) {
	define('OSID_BASE', dirname(__FILE__));
	function __autoload($className) {
		require_once(OSID_BASE.'/'.implode('/', explode('_', $className)).'.php');
	}
}