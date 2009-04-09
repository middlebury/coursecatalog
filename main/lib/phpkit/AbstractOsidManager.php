<?php
/**
 * @since 10/28/08
 * @package org.osid
 * 
 * @copyright Copyright &copy; 2007, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id$
 */ 

/**
 * Supplies basic information in common throughout the managers and
 * profiles.
 * 
 * @since 10/28/08
 * @package org.osid
 * 
 * @copyright Copyright &copy; 2007, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id$
 */
abstract class phpkit_AbstractOsidManager {
		
	private $osidVersions = array("3.0.0");

    private $id;
    private $displayName = "Secretary Not Sure";
    private $description = "This is an abstract class for a manager template. I'm sure I will do something great someday.";

    private $implVersion  = "0.0.0";
    private $releaseDate;

    private $license = "";
    private $provider;
    private $branding;

    private $serviceMessage = "";
    private $serviceMessageReceiver;

    private $runtime;
    private $typeManager;
    private $typeImpl;
	
	/**
	 * Constructor
	 * 
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function __construct () {
		$this->id = new phpkit_id_URNInetId("urn:inet:osid.org:id:implementation/SampleChangeMe");
 		$this->releaseDate = new phpkit_calendaring_DateTime('1919-01-15T12:30');
		$this->provider = new phpkit_provider_UnknownProvider;
		$this->branding = array();
	}
	
	/**
	 * Answer the runtime manager for accessing other managers needed by the implementation
	 * 
	 * @return osid_OsidRuntimeManager
	 * @access protected
	 * @since 10/30/08
	 */
	protected function impl_getRuntimeManager () {
		return $this->runtime;
	}
	
/*********************************************************
 * Methods from osid_OsidManager
 *********************************************************/
	
	/**
	 *	Initializes this manager. A manager is initialized once at the time of 
	 *	creation. 
	 *
	 *	@param object osid_OsidRuntimeManager $runtime the runtime environment 
	 *	@throws osid_ConfigurationErrorException an error with implementation 
	 *			configuration 
	 *	@throws osid_IllegalStateException this manager has already been 
	 *			initialized by the <code> OsidLoader </code> or this manager 
	 *			has been shut down 
	 *	@throws osid_NullArgumentException <code> runtime </code> is <code> 
	 *			null </code> 
	 *	@throws osid_OperationFailedException unable to complete request 
	 *	@compliance mandatory This method must be implemented. 
	 *	@notes	In addition to loading its runtime configuration an 
	 *			implementation may create shared resources such as connection 
	 *			pools to be shared among all sessions of this service and 
	 *			released when this manager is closed. Providers must 
	 *			thread-protect any data stored in the manager. 
	 *			<br/><br/>
	 *			To maximize interoperability, providers should not honor a 
	 *			second call to <code> initialize() </code> and must set an 
	 *			<code> ILLEGAL_STATE </code> error. 
	 */
	public function initialize(osid_OsidRuntimeManager $runtime) {
		if (isset($this->runtime))
			throw new osid_IllegalStateException("Already initialized");
		
		$this->runtime = $runtime;
		
		if (!is_null($this->typeImpl)) {
			try {
				$this->typeManager = $runtime->getManager(osid_OSID::TYPE(), 
											  $this->typeImpl, 
											  $this->osidVersions[0]);
			} catch (Exception $e) {
			}
		}
	}


	/**
	 *	Gets the Journal session for this service. 
	 *
	 *	@return a journal session 
	 *	@throws osid_OperationFailedException unable to complete request 
	 *	@throws osid_PermissionDeniedException authorization failure occurred 
	 *	@throws osid_UnimplementedException <code> supportsJournaling() 
	 *			</code> is <code> false </code> 
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function getJournalSession() {
		throw new osid_UnimplementedException();
	}


	/**
	 *	Rolls back this service to a point in time. 
	 *
	 *	@param java.util.Date $rollbackTime the requested time 
	 *	@return the journal entry corresponding to the actual state of this 
	 *			service 
	 *	@throws osid_OperationFailedException unable to complete request 
	 *	@throws osid_PermissionDeniedException authorization failure occurred 
	 *	@throws osid_UnimplementedException <code> supportsJournaling() 
	 *			</code> is <code> false </code> 
	 *	@throws osid_NullArgumentException null argument provided 
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function rollbackService($rollbackTime) {
		throw new osid_UnimplementedException();
	}


	/**
	 *	Gets a service message which can be used for service announcements. 
	 *
	 *	@return service message 
	 *	@throws osid_OperationFailedException unable to complete request 
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function getServiceMessage() {
		return $this->serviceMessage;
	}


	/**
	 *	Register for service messages. <code> ServiceMessage.newMessage() 
	 *	</code> is invoked for each new message. There is a single service 
	 *	message receiver per manager. 
	 *
	 *	@param object osid_ServiceReceiver $receiver supplied interface for 
	 *			service messages 
	 *	@throws osid_NullArgumentException <code> receiver </code> is <code> 
	 *			null </code> 
	 *	@throws osid_OperationFailedException unable to complete request 
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function registerForServiceMessages(osid_ServiceReceiver $receiver) {
		$this->serviceMessageReceiver = $reciever;
	}


	/**
	 *	Unregister for service messages. 
	 *
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function unregisterForServiceMessages() {
		unset($this->serviceMessageReceiver);
	}
	
	/**
     *  Sets a service message.
     *
     * @param string $message
     * @return void
     * @throws osid_IllegalStateException This manager has been closed.
     * @throws osid_NullArgumentException <code> message </code> is 
     *          <code> null </code> 
     */
    protected function setServiceMessage($message) {

		if (is_null($message))
			throw new osid_NullArgumentException("Message must not be null");
	
		$this->serviceMessage = $message;
		if (isset($this->serviceMessageReceiver)) {
			$this->serviceMessageReceiver->newMessage($this->id, $this->serviceMessage);
		}
    }


	/**
	 *	Shuts down this <code>osid.OsidManager</code>
	 */

	public function shutdown() {
		if (!isset($this->runtime))
			throw new osid_IllegalStateException("this manager has been shut down");
		
		unset($this->runtime);
		$this->branding = array();
	}

/*********************************************************
 * Methods from osid_OsidProfile
 *********************************************************/

	/**
	 *	Gets an identifier for this service implementation. The identifier is 
	 *	unique among services but multiple instantiations of the same service 
	 *	use the same <code> Id. </code> This identifier is the same identifier 
	 *	used in managing OSID installations. 
	 *
	 *	@return the <code> Id </code> 
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set the Id
	 * 
	 * @param object osid_id_Id $id
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function setId (osid_id_Id $id) {
		if (isset($this->runtime))
			throw new osid_IllegalStateException("Already initialized");
		
		$this->id = $id;
	}

	/**
	 *	Gets a display name for this service implementation. 
	 *
	 *	@return a display name 
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function getDisplayName() {
		return $this->displayName;
	}

	/**
	 * Set the DisplayName
	 * 
	 * @param string $displayName
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function setDisplayName ($displayName) {
		if (isset($this->runtime))
			throw new osid_IllegalStateException("Already initialized");
		
		$this->displayName = $displayName;
	}

	/**
	 *	Gets a description of this service implementation. 
	 *
	 *	@return a description 
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * Set the description
	 * 
	 * @param string $description
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function setDescription ($description) {
		if (isset($this->runtime))
			throw new osid_IllegalStateException("Already initialized");
		
		$this->description = $description;
	}


	/**
	 *	Gets the version of this service implementation. 
	 *
	 *	@return the version 
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function getVersion() {
		return $this->implVersion;
	}
	
	/**
	 * Set the version
	 * 
	 * @param string $version
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function setVersion ($version) {
		if (isset($this->runtime))
			throw new osid_IllegalStateException("Already initialized");
		
		$this->version = $version;
	}


	/**
	 *	Gets the date this service implementation was released. 
	 *
	 *	@return the release date 
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function getReleaseDate() {
		return $this->releaseDate;
	}
	
	/**
	 * Set the release date
	 * 
	 * @param object osid_calendaring_DateTime $releaseDate
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function setReleaseDate (osid_calendaring_DateTime $releaseDate) {
		if (isset($this->runtime))
			throw new osid_IllegalStateException("Already initialized");
		
		$this->releaseDate = $releaseDate;
	}


	/**
	 *	Gets the terms of usage with respect to this service implementation. 
	 *
	 *	@return the license 
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function getLicense() {
		return $this->license;
	}

	/**
	 * Set the license
	 * 
	 * @param string $license
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function setLicense ($license) {
		if (isset($this->runtime))
			throw new osid_IllegalStateException("Already initialized");
		
		$this->license = $license;
	}

	/**
	 *	Gets the <code> Resource Id </code> representing the provider of this 
	 *	service. 
	 *
	 *	@return the provider <code> Id </code> 
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function getProviderId() {
		$provider = $this->getProvider();
		return $provider->getId();
	}


	/**
	 *	Gets the provider of this service, expressed using the <code> Resource 
	 *	</code> interface. 
	 *
	 *	@return the service provider resource 
	 *	@throws osid_OperationFailedException unable to complete request 
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 *	@notes	The <code> Resource </code> at minimum may only contain some 
	 *			identifier along with a name and description, or a typed 
	 *			interface extension can be used to reveal more information 
	 *			such as contact information about the provider. 
	 */
	public function getProvider() {
		if (!isset($this->runtime))
			throw new osid_IllegalStateException("Service shut down.");
			
		if (!isset($this->provider))
			throw new osid_OperationFailedException('Provider not available');
		
		return $this->provider;
	}
	
	/**
	 * Set the Provider
	 * 
	 * @param object osid_resource_Resource $provider
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function setProvider (osid_resource_Resource $provider) {
		if (isset($this->runtime))
			throw new osid_IllegalStateException("Already initialized");
		
		$this->provider = $provider;
	}


	/**
	 *	Gets a branding, such as an image or logo, expressed using the <code> 
	 *	Asset </code> interface. 
	 *
	 *	@return a list of assets 
	 *	@throws osid_OperationFailedException unable to complete request 
	 *	@throws osid_IllegalStateException this manager has been shut down 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function getBranding() {
		return $this->branding;
	}


	/**
	 *	Test for support of an OSID version. 
	 *
	 *	@param String $version the version string to test 
	 *	@return <code> true </code> if this manager supports the given 
	 *			version, <code> false </code> otherwise 
	 *	@throws osid_NullArgumentException null argument provided 
	 *	@compliance mandatory This method must be implemented. 
	 *	@notes	An implementation may support multiple versions of an OSID. 
	 */
	public function supportsOSIDVersion($version) {
		if (is_null($version))
			throw new osid_NullArgumentException('version must not be null');
		
		return in_array($version, $this->osidVersions);
	}


	/**
	 *	Test for support of a journaling service. 
	 *
	 *	@return <code> true </code> if this manager supports the journaling, 
	 *			<code> false </code> otherwise 
	 *	@compliance mandatory This method must be implemented. 
	 */
	public function supportsJournaling() {
		return false;
	}
}

?>