<?php
/**
 * @since 4/15/08
 * @package edu.middlebury
 * 
 * @copyright Copyright &copy; 2007, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id$
 */ 
 
/**
 * <##>
 * 
 * @since 4/15/08
 * @package edu.middlebury
 * 
 * @copyright Copyright &copy; 2007, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id$
 */
class phpkit_OsidRuntimeManager
	extends phpkit_AbstractOsidManager
	implements osid_OsidRuntimeManager
{

/*********************************************************
 * Custom bootstrapping methods
 *********************************************************/
	/**
	 * Set the configuration and class paths
	 * 
	 * @param string $configPath
	 * @param string $classPath
	 * @return void
	 * @access public
	 * @since 4/9/09
	 */
	public function __construct ($configPath, $classPath) {
		if (!file_exists($configPath))
			throw new osid_ConfigurationErrorException("Config Path '$configPath' does not exist.");
		if (!is_readable($configPath))
			throw new osid_ConfigurationErrorException("Config Path '$configPath' is not readable.");
		if (!file_exists($classPath))
			throw new osid_ConfigurationErrorException("Class Path '$classPath' does not exist.");
		if (!is_readable($classPath))
			throw new osid_ConfigurationErrorException("Class Path '$classPath' is not readable.");
		
		parent::__construct();
    	$this->setId(new phpkit_id_URNInetId('urn:inet:middlebury.edu:id:implementations/phpkit_runtime'));
    	$this->setDisplayName('Runtime Manager');
    	$this->setDescription('An OSID runtime manager in a demo application.');
		
		$this->configPath = $configPath;
		$this->classPath = $classPath;
	}
	
/*********************************************************
 * From OsidManager
 *********************************************************/

    /**
     *  Initializes this manager. A manager is initialized once at the time of 
     *  creation. 
     *
     *  @param object osid_OsidRuntimeManager $runtime the runtime environment 
     *  @throws osid_ConfigurationErrorException an error with implementation 
     *          configuration 
     *  @throws osid_IllegalStateException this manager has already been 
     *          initialized by the <code> OsidLoader </code> or this manager 
     *          has been shut down 
     *  @throws osid_NullArgumentException <code> runtime </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  In addition to loading its runtime configuration an 
     *          implementation may create shared resources such as connection 
     *          pools to be shared among all sessions of this service and 
     *          released when this manager is closed. Providers must 
     *          thread-protect any data stored in the manager. 
     *          <br/><br/>
     *          To maximize interoperability, providers should not honor a 
     *          second call to <code> initialize() </code> and must set an 
     *          <code> ILLEGAL_STATE </code> error. 
     */
    public function initialize(osid_OsidRuntimeManager $runtime) {
    	throw new osid_IllegalStateException('Cannot intialize this runtime manager again. This runtime manager is initialized at creation only.');
    }


/*********************************************************
 * From OsidRuntimeManager
 *********************************************************/

    /**
     *  Finds, loads and instantiates providers of OSID managers. Providers 
     *  must conform to an OsidManager interface. The interfaces are defined 
     *  in the OSID enumeration. For all OSID requests, an instance of <code> 
     *  OsidManager </code> that implements the <code> OsidManager </code> 
     *  interface is returned. In bindings where permitted, this can be safely 
     *  cast into the requested manager. 
     *
     *  @param object osid_OSID $osid represents the OSID 
     *  @param string $implClassName the name of the implementation 
     *  @param string $version the minimum required interface version 
     *  @return object osid_OsidManager the manager of the service 
     *  @throws osid_NotFoundException the implementation class name was not 
     *          found 
     *  @throws osid_NullArgumentException <code> implClassName </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnsupportedException <code> implClassName </code> does 
     *          not support the requested OSID 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  After finding and instantiating the requested <code> 
     *          OsidManager, </code> providers must invoke <code> 
     *          OsidManager.initialize(OsidRuntimeManager) </code> where the 
     *          environment is an instance of the current environment that 
     *          includes the configuration for the service being initialized. 
     *          The <code> OsidRuntimeManager </code> passed may include 
     *          information useful for the configuration such as the identity 
     *          of the service being instantiated. 
     */
    public function getManager(osid_OSID $osid, $implClassName, $version) {
    	$parts = explode('_', $implClassName);
    	require_once(OSID_BASE.'/'.implode('/', $parts).'.php');
    	
    	$className = implode('_', $parts);
    	
    	$manager = new $className;
    	
    	// Validate manager is an instance of the requested OSID.
    	$interface = $osid->getManager();
    	if (!($manager instanceof $interface))
    		throw new osid_UnsupportedException(get_class($manager).' is not an instance of '.$osid->manager());
    	
    	$manager->initialize($this);
    	
    	return $manager;
    }


    /**
     *  Finds, loads and instantiates providers of OSID managers. Providers 
     *  must conform to an <code> OsidManager </code> interface. The 
     *  interfaces are defined in the OSID enumeration. For all OSID requests, 
     *  an instance of <code> OsidManager </code> that implements the <code> 
     *  OsidManager </code> interface is returned. In bindings where 
     *  permitted, this can be safely cast into the requested manager (the 
     *  only place in the specification where this is permitted). Other 
     *  bindings may require a different expression of this interface to cope 
     *  with constraints in the programming language. 
     *
     *  @param object osid_OSID $osid represents the OSID 
     *  @param string $implementation the name of the implementation 
     *  @return object OsidProxyManager the manager of the service 
     *  @throws osid_NotFoundException the implementation package was not 
     *          found 
     *  @throws osid_NullArgumentException <code> implementation </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnsupportedException <code> implementation </code> does 
     *          not support the requested OSID 
     *  @compliance mandatory This method must be implemented. 
     *  @notes After finding and instantiating the requested <code> 
     *      OsidManager, </code> providers must invoke <code> 
     *      OsidManager.initialize(OsidRuntimeManager) </code> where the 
     *      environment is an instance of the current environment that 
     *      includes the configuration for the service being initialized. The 
     *      <code> OsidRuntimeManager </code> passed may include information 
     *      useful for the configuration such as the identity of the service 
     *      being instantiated. 
     */
    public function getProxyManager(osid_OSID $osid, $implementation, $version) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the current configuration in the runtime environment. 
     *
     *  @return object osid_configuration_ValueLookupSession a configuration 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException an authorization failure 
     *          occured 
     *  @throws osid_UnimplementedException a configuration service is not 
     *          supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsConfiguration() </code> is <code> true. </code> 
     */
    public function getConfiguration() {
    	return new phpkit_configuration_PListValueLookupSession($this->configPath);
    }


    /**
     *  Gets the current configuration for updating in the runtime 
     *  environment. 
     *
     *  @return object osid_configuration_ConfigurationManager a configuration 
     *          manager 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException a configuration service is not 
     *          supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsConfiguration() </code> is <code> true. </code> 
     *  @notes  A configuration service may provide user-specific 
     *          configurations by making use of an authentication service. 
     */
    public function getConfigurationManager() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the installation manager used in the runtime environment. 
     *
     *  @return object osid_installation_InstallationManager a configuration 
     *          manager 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException a configuration service is not 
     *          supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsInstallation() </code> is <code> true. </code> 
     */
    public function getInstallationManager() {
    	throw new osid_UnimplementedException();
    }
    
    
/*********************************************************
 * From OsidRuntimeProfile
 *********************************************************/
 
    /**
     *  Tests if a configuration service is provided within this runtime 
     *  environment. 
     *
     *  @return boolean <code> true </code> if a configuration service is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsConfiguration() {
    	return false;
    }


    /**
     *  Tests if an installation service is provided within this runtime 
     *  environment. 
     *
     *  @return boolean <code> true </code> if a installation service is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsInstallation() {
    	return false;
    }
}
