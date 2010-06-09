<?php

/**
 * A helper to prepend url paths with the protocol and host name to make them
 * absolute URLs.
 * 
 * @since 6/9/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Helper_PathAsAbsoluteUrl
	extends Zend_Controller_Action_Helper_Abstract
{
	
	
	/**
	 * Strategy pattern: call helper as broker method
	 * 
	 * @param string $path
	 * @return string
	 * @access public
	 * @since 6/9/10
	 */
	public function direct ($path) {
		return $this->pathAsAbsoluteUrl($path);
	}
	
	/**
	 * Answer a URL path as an absolute URL.
	 * 
	 * @param string $path
	 * @return string
	 * @access public
	 * @since 6/9/10
	 */
	public function pathAsAbsoluteUrl ($path) {
		$parts = split('/', $_SERVER['SERVER_PROTOCOL']);
		return strtolower(trim(array_shift($parts)))
			. '://' . $_SERVER['HTTP_HOST'] . $path;
	}
	
}

?>