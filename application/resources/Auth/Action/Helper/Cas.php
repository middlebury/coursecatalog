<?php
/**
 * @since 6/14/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * A helper for authenticating via CAS.
 * 
 * @since 6/14/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Auth_Action_Helper_Cas
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
		self::initializePhpCas();
	}
	
	/**
	 * Answer true if this authentication method allows login.
	 * 
	 * @return boolean
	 * @access public
	 */
	public function isAuthenticationEnabled () {
		return true;
	}
	
	/**
	 * Log in. Throw an exception if isAuthenticationEnabled is false.
	 * 
	 * @return boolean TRUE on successful login.
	 * @access public
	 */
	public function login()
    {
    	self::initializePhpCas();
    	
    	phpCAS::forceAuthentication();
    	return true;
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
    	self::initializePhpCas();
    	
    	session_destroy();
    	if ($return) {
	    	phpCAS::logoutWithUrl($returnUrl);
	    } else {
	    	phpCAS::logout();
	    }
    	exit;
    }
	
	/**
	 * Answer true if a user is currently authenticated.
	 * 
	 * @return boolean
	 * @access public
	 */
	public function isAuthenticated() {
		self::initializePhpCas();
		
    	return phpCAS::isAuthenticated();
    }
    
    /**
	 * Answer the user id if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 * 
	 * @return string
	 * @access public
	 */
    public function getUserId() {
    	self::initializePhpCas();
    	
    	return phpCAS::getUser();
    }
    
    /**
	 * Answer a name for the user if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 * 
	 * @return string
	 * @access public
	 */
    public function getUserDisplayName() {
    	self::initializePhpCas();
    	
    	$displayName = '';
    	$displayName .= phpCAS::getAttribute('FirstName');
    	$displayName .= ' '.phpCAS::getAttribute('LastName');
//     	$displayName .= ' ('.phpCAS::getAttribute('EMail').')';
    	return trim($displayName);
    }
    
    static $phpcasInitialized = false;
    /**
     * Initialize phpCAS
     * 
     * @return void
     * @access protected
     * @since 6/7/10
     */
    protected static function initializePhpCas () {
    	if (!self::$phpcasInitialized) {
			require_once('CAS.php');
			
			$config = Zend_Registry::getInstance()->config;
			
			if ($config->cas->debug_file) {
				phpCAS::setDebug($config->cas->debug_file);
			}
			
			phpCAS::client(
				CAS_VERSION_2_0, 
				$config->cas->host, 
				(int)$config->cas->port, 
				$config->cas->path, 
				false);
			
			if ($config->cas->server_cert) {
				phpCAS::setCasServerCACert($config->cas->server_cert);
			} else {
				phpCAS::setNoCasServerValidation();
			}
			
			self::$phpcasInitialized = true;
		}
    }
}

?>