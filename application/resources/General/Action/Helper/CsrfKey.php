<?php
/**
 * @since 7/30/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * A helper for generating a per-session key used for preventing Cross-Site 
 * Request Forgery attacks.
 * 
 * @since 7/30/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class General_Action_Helper_CsrfKey
	extends Zend_Controller_Action_Helper_Abstract
{
		
	/**
	 * Answer the CSRF-key for the session, creating it if needed.
	 * 
	 * @return string
	 * @access public
	 * @since 6/14/10
	 */
	public function direct () {
		if (!isset($_SESSION['CSRF_KEY']))
			$_SESSION['CSRF_KEY'] = uniqid();
		return $_SESSION['CSRF_KEY'];
	}
	
}

?>