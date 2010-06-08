<?php

/** Zend_Controller_Action */
class AuthController extends Zend_Controller_Action
{
	public function init () {
		self::initializePhpCas();
	}
	
    public function indexAction()
    {
    	phpCAS::forceAuthentication();
    	$this->_redirect('/', array('prependBase' => true, 'exit' => true));
    }
    
    public function logoutAction()
    {
    	session_destroy();
    	phpCAS::logoutWithUrl($this->_getParam('return'));
    	exit;
    }
    
    public static function isAuthenticated() {
    	self::initializePhpCas();
    	return phpCAS::isAuthenticated();
    }
    
    public static function getUserId() {
    	self::initializePhpCas();
    	return phpCAS::getUser();
    }
    
    public static function getUserDisplayName() {
    	self::initializePhpCas();
    	$displayName = '';
    	$displayName .= phpCAS::getAttribute('FirstName');
    	$displayName .= ' '.phpCAS::getAttribute('LastName');
    	$displayName .= ' ('.phpCAS::getAttribute('EMail').')';
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
    
    /**
	 * Answer an absolute URL from a relative string.
	 * 
	 * @param string $url
	 * @return string
	 * @access private
	 * @since 6/15/09
	 */
	public static function getAsAbsolute ($url) {
		$parts = split('/', $_SERVER['SERVER_PROTOCOL']);
		return strtolower(trim(array_shift($parts)))
			. '://' . $_SERVER['HTTP_HOST'] . $url;
	}
}
