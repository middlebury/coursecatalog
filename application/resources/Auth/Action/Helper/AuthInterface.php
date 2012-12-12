<?php
/**
 * @since 6/14/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * An interface for authentication helpers
 * 
 * @since 6/14/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface Auth_Action_Helper_AuthInterface {
		
	/**
	 * Answer true if this authentication method allows login.
	 * 
	 * @return boolean
	 * @access public
	 */
	public function isAuthenticationEnabled ();
	
	/**
	 * Log in. Throw an exception if isAuthenticationEnabled is false.
	 * 
	 * @return boolean TRUE on successful login.
	 * @access public
	 */
	public function login();
    
    /**
	 * Log out. Throw an exception if isAuthenticationEnabled is false.
	 * 
	 * @param optional string $returnUrl A url to return to after successful logout.
	 * @return void
	 * @access public
	 */
    public function logout($returnUrl = null);
	
	/**
	 * Answer true if a user is currently authenticated.
	 * 
	 * @return boolean
	 * @access public
	 */
	public function isAuthenticated();
    
    /**
	 * Answer the user id if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 * 
	 * @return string
	 * @access public
	 */
    public function getUserId();
    
    /**
	 * Answer a name for the user if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 * 
	 * @return string
	 * @access public
	 */
    public function getUserDisplayName();
    
    /**
	 * Answer an email address for the user if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 * 
	 * @return string
	 * @access public
	 */
    public function getUserEmail();
    
    /**
	 * Answer an array of groups for the user if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 * 
	 * @return array
	 * @access public
	 */
    public function getUserGroups();
	
}

?>