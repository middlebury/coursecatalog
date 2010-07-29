<?php
/**
 * @since 6/14/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * A helper for accessing the currently active authentication helper.
 * 
 * @since 6/14/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Auth_Action_Helper_Auth 
	extends Zend_Controller_Action_Helper_Abstract
{

	private $authHelper = null;
	private $initialized = false;
	
	/**
	 * Initialize this helper.
	 * 
	 * @return void
	 * @access public
	 * @since 6/14/10
	 */
	public function init () {
		$config = Zend_Registry::getInstance()->config;
		if (!isset($config->authType) || !strlen(trim($config->authType))) {
			$authType = 'NullAuth';
		} else {
			$authType = $config->authType;
		}
		
		try {
			$this->authHelper = Zend_Controller_Action_HelperBroker::getStaticHelper($authType);
			if (!($this->authHelper instanceOf Auth_Action_Helper_AuthInterface)) {
				$class = get_class($this->authHelper);
				$this->authHelper = null;
				throw new Exception("Auth helper for auth-type '$authType' has class '$class' which does not implement Auth_Action_Helper_AuthInterface.");
			}
			$this->initialized = true;
		} catch (Zend_Controller_Action_Exception $e) {
			throw new Exception("Can not use authentication type '".$authType."'. ".$e->getMessage());
		}
	}
	
	
	/**
	 * Answer the configured Authentication Helper
	 * 
	 * @return Zend_Controller_Action_Helper_Interface
	 * @access public
	 * @since 6/14/10
	 */
	public function getHelper () {
		if (!$this->initialized)
			$this->init();
		
		if (is_null($this->authHelper))
			throw new Exception("No authentication helper is available. Maybe one wasn't configured.", 450);
		
		return $this->authHelper;
	}
	
	/**
	 * Answer the configured Authentication Helper
	 * 
	 * @return void
	 * @access public
	 * @since 6/14/10
	 */
	public function direct () {
		return $this->getHelper();
	}
}

?>