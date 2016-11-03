<?php
/**
 * @since 6/14/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A placeholder helper for authentication.
 *
 * @since 6/14/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Auth_Action_Helper_NullAuth
	extends Zend_Controller_Action_Helper_Abstract
	implements Auth_Action_Helper_AuthInterface
{

	/**
	 * Initialize this helper.
	 *
	 * @return void
	 * @access public
	 * @since 6/14/10
	 */
	public function init () {

	}

	/**
	 * Answer true if this authentication method allows login.
	 *
	 * @return boolean
	 * @access public
	 */
	public function isAuthenticationEnabled () {
		return false;
	}

	/**
	 * Log in. Throw an exception if isAuthenticationEnabled is false.
	 *
	 * @return boolean TRUE on successful login.
	 * @access public
	 */
	public function login()
	{
		throw new Exception('The NullAuth authentication method does not support login.');
	}

	/**
	 * Log out. Throw an exception if isAuthenticationEnabled is false.
	 *
	 * @param optional string $returnUrl A url to return to after successful logout.
	 * @return void
	 * @access public
	 */
	public function logout($returnUrl = null)
	{
		throw new Exception('The NullAuth authentication method does not support logout.');
	}

	/**
	 * Answer true if a user is currently authenticated.
	 *
	 * @return boolean
	 * @access public
	 */
	public function isAuthenticated() {
		return false;
	}

	/**
	 * Answer the user id if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 *
	 * @return string
	 * @access public
	 */
	public function getUserId() {
		throw new Exception('No user authenticated.');
	}

	/**
	 * Answer a name for the user if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 *
	 * @return string
	 * @access public
	 */
	public function getUserDisplayName() {
		throw new Exception('No user authenticated.');
	}

	/**
	 * Answer an email address for the user if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 *
	 * @return string
	 * @access public
	 */
	public function getUserEmail() {
		throw new Exception('No user authenticated.');
	}

	/**
	 * Answer an array of groups for the user if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 *
	 * @return array
	 * @access public
	 */
	public function getUserGroups() {
		throw new Exception('No user authenticated.');
	}
}
