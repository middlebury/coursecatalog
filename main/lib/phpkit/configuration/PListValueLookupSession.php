<?php

/**
 * osid_configuration_ValueLookupSession
 * 
 *     Specifies the OSID definition for osid_configuration_ValueLookupSession.
 * 
 * Copyright (C) 2008 Massachusetts Institute of Technology. All Rights 
 * Reserved. 
 * 
 *     This Work is being provided by the copyright holder(s) subject to the 
 *     following license. By obtaining, using and/or copying this Work, you 
 *     agree that you have read, understand, and will comply with the 
 *     following terms and conditions. 
 *     
 *     This Work and the information contained herein is provided on an ""AS 
 *     IS"" basis. The Massachusetts Institute of Technology, the Open 
 *     Knowledge Initiative, and THE AUTHORS DISCLAIM ALL WARRANTIES, EXPRESS 
 *     OR IMPLIED, INCLUDING BUT NOT LIMITED TO WARRANTIES OF MERCHANTABILITY, 
 *     FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
 *     THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR 
 *     OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, 
 *     ARISING FROM, OUT OF OR IN CONNECTION WITH THE WORK OR THE USE OR OTHER 
 *     DEALINGS IN THE WORK. 
 *     
 *     Permission to use, copy and distribute unmodified versions of this 
 *     Work, for any purpose, without fee or royalty is hereby granted, 
 *     provided that you include the above copyright notice and the terms of 
 *     this license on ALL copies of the Work or portions thereof. 
 *     
 *     You may nodify or create Derivatives of this Work only for your 
 *     internal purposes. You shall not distribute or transfer any such 
 *     Derivative of this Work to any location or to any third party. For the 
 *     purposes of this license, Derivative shall mean any derivative of the 
 *     Work as defined in the United States Copyright Act of 1976, such as a 
 *     translation or modification. 
 *     
 *     The export of software employing encryption technology may require a 
 *     specific license from the United States Government. It is the 
 *     responsibility of any person or organization comtemplating export to 
 *     obtain such a license before exporting this Work. 
 * 
 * @package org.osid.configuration
 */

/**
 *  <p>This session is used to retrieve configuration values. Two views of the 
 *  configuration data are defined; </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> federated: parameters defined in configurations that are a parent 
 *      of this configuration in the configuration hierarchy are included 
 *      </li> 
 *      <li> isolated: parameters are contained to within this configuration 
 *      </li> 
 *  </ul>
 *  Values are not OSID objects and are obtained using a reference to a 
 *  Parameter. </p>
 * 
 * @package org.osid.configuration
 */
class phpkit_configuration_PListValueLookupSession
    implements osid_configuration_ValueLookupSession
{

	/**
	 * Constructor
	 * 
	 * @param optional string $configPath If not specified, will default to the directory of the original script.
	 * @return void
	 * @access public
	 * @since 10/30/08
	 */
	public function __construct ($configPath = null) {
		if (is_null($configPath))
			$configPath = dirname(realpath($_SERVER['SCRIPT_NAME'])).'/configuration.plist';
		
		if (!file_exists($configPath))
			throw new osid_OperationFailedException("Configuration plist file does not exist at '$configPath'.");
		
		$this->configPath = $configPath;
		$this->doc = new DOMDocument;
		$this->doc->preserveWhiteSpace = false;
		$res = @$this->doc->load($configPath);
		if (!$res)
			throw new osid_OperationFailedException("Configuration plist file at '$configPath' is not a valid XML document.");
		
		// @todo Add schema validation of the plist file.
	}

    /**
     *  Gets the <code> Configuration </code> <code> Id </code> associated 
     *  with this session. 
     *
     *  @return object osid_configuration_Configuration the <code> 
     *          Configuration </code> <code> Id </code> associated with this 
     *          session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationId() {
    	return new phpkit_id_Id('localhost', 'urn', 'configuration:'.$configPath);
    }


    /**
     *  Gets the <code> Configuration </code> associated with this session. 
     *
     *  @return object osid_configuration_Configuration the <code> 
     *          Configuration </code> associated with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfiguration() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if this user can perform <code> Value </code> lookups. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupValues() {
    	return true;
    }


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include values from parent configurations in the configuration 
     *  hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedConfigurationView() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to this configuration only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedConifgurationView() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the <code> Values </code> with the given <code> Id. </code> 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> to retrieve 
     *  @return object osid_configuration_ValueList the value list 
     *  @throws osid_NotFoundException the <code> parameterId </code> not 
     *          found 
     *  @throws osid_NullArgumentException the <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParameterValues(osid_id_Id $parameterId) {
    	
    }


    /**
     *  Gets the <code> Parameter </code> values with the given <code> Id. 
     *  </code> The returned array is sorted by the value index. 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> to retrieve 
     *  @return array of objects the value list 
     *  @throws osid_NotFoundException the <code> parameterId </code> not 
     *          found 
     *  @throws osid_NullArgumentException the <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getValues(osid_id_Id $parameterId) {
    	if (!isset($this->doc))
    		throw new osid_IllegalStateException('this session has been closed');
    	
    	$xpath = new DOMXpath($this->doc);
    	$idString = phpkit_id_URNInetId::getInetURNString($parameterId);
    	$values = $xpath->query('//key[. = "'.$idString.'"]/following-sibling::*[1]');
    	
    	$valueArray = array();
    	$indices = array();
    	foreach ($values as $value) {
    		$valueObj = new phpkit_configuration_PlistValue($value);
    		$valueArray[] = $valueObj;
    		$indices[] = $valueObj->getIndex();
    	}
    	
    	array_multisort($indices, array_keys($valueArray), $valueArray);
    	return $valueArray;
    }
    
/*********************************************************
 * Methods from osid_OsidSession
 *********************************************************/
/**
 *  <p>The <code> OsidSession </code> is the top level interface for all OSID 
 *  sessions. An <code> OsidSession </code> is created through its 
 *  corresponding <code> OsidManager. </code> A new <code> OsidSession </code> 
 *  should be created for each user of a service and for each processing 
 *  thread. A session maintains a single authenticated user and is not 
 *  required to ensure thread-protection. A typical OSID session defines a set 
 *  of service methods corresponding to some compliance level as defined by 
 *  the service and is generally responsible for the management and retrieval 
 *  of <code> OsidObjects. </code> </p> 
 *  
 *  <p> <code> OsidSession </code> defines a set of common methods used 
 *  throughout all OSID sessions. An OSID session may optionally support 
 *  transactions through the transaction interface. </p>
 * 
 * @package org.osid
 */

    /**
     *  Tests if there are valid authentication credentials used by this 
     *  service. 
     *
     *  @return <code> true </code> if valid authentication credentials exist, 
     *          <code> false </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  Providers must also query <code> OsidSessions </code> 
     *          instantiated by this session. 
     */
    public function isAuthenticated() {
		return false;
	}


    /**
     *  Gets the authenticated identities used by this service to give the 
     *  user feedback as to which of the Agent identitites are actively being 
     *  used on the user's behalf. 
     *
     *  @return the list of authenticated Agents 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  Providers must also include any authenticated <code> Agents 
     *          </code> from all <code> OsidSessions </code> instantiated by 
     *          this service. 
     */
    public function getAuthenticatedAgents() {
		return new phpkit_agent_ArrayAgentList(array());
	}


    /**
     *  Tests for the availability of transactions. 
     *
     *  @return <code> true </code> if transaction methods are available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTransactions() {
		return false;
	}


    /**
     *  Starts a new transaction for this sesson. Transactions are a means for 
     *  an OSID to provide an all-or-nothing set of operations within a 
     *  session and may be used to coordinate this service from an external 
     *  transaction manager. A session supports one transaction at a time. 
     *  Starting a second transaction before the previous has been committed 
     *  or aborted results in an <code> ILLEGAL_STATE </code> error. 
     *
     *  @return a new transaction 
     *  @throws osid_IllegalStateException a transaction is already open or 
     *          this session has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnsupportedException transactions not supported 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTransactions() </code> is true. 
     *  @notes  Ideally, a provider that supports transactions should 
     *          guarantee atomicity, consistency, isolation and durability in 
     *          a 2 phase commit process. This is not always possible in 
     *          distributed systems and a transaction provider may simply 
     *          allow for a means of processing bulk updates. 
     *          <br/><br/>
     *          To maximize interoperability, providers should honor the 
     *          one-transaction-at-a-time rule. 
     */
    public function startTransaction() {
		throw new osid_UnsupportedException();
	}


    /**
     *  Closes this <code>osid.OsidSession</code>
     */

    public function close() {
		unset($this->doc);
	}
}
