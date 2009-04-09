<?php

/**
 * osid_OsidRuntimeManager
 * 
 *     Specifies the OSID definition for osid_OsidRuntimeManager.
 * 
 * Copyright (C) 2008 Massachusetts Institute of Technology. All Rights 
 * Reserved. 
 * 
 *     This Work is being provided by the copyright holder(s) subject to the 
 *     following license. By obtaining, using and/or copying this Work, you 
 *     agree that you have read, understand, and will comply with the 
 *     following terms and conditions. 
 *     
 *     This Work and the information contained herein is provided on an "AS 
 *     IS" basis. The Massachusetts Institute of Technology, the Open 
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
 * @package org.osid
 */

require_once(dirname(__FILE__)."/OsidManager.php");
require_once(dirname(__FILE__)."/OsidRuntimeProfile.php");

/**
 *  <p>The <code> OsidRuntimeManager </code> represents and OSID platform and 
 *  contains the information required for running OSID implementations such as 
 *  search paths and configurations. </p> 
 *  
 *  <p> The <code> OsidRuntimeManager </code> is defined as an interface to 
 *  provide flexibility for managing an OSID environment. The instantiation of 
 *  a <code> OsidRuntimeManager </code> implementation is defined by the OSID 
 *  platform. </p> 
 *  
 *  <p> The <code> OsidRuntimeManager </code> should be instantiated with a 
 *  string that identifies the application or environment current at the time 
 *  of instantiation. This key is used soley for the purpose of seeding the 
 *  configuration service as a means to enable lower level OSIDs to tune their 
 *  configuration in response to this key, or, it can be used by the 
 *  application to retrieve configuration data for itself. </p>
 * 
 * @package org.osid
 */
interface osid_OsidRuntimeManager
    extends osid_OsidManager,
            osid_OsidRuntimeProfile
{


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
    public function getManager(osid_OSID $osid, $implClassName, $version);


    /**
     *  Finds, loads and instantiates providers of OSID managers. Providers 
     *  must conform to an <code> OsidManager </code> interface. The 
     *  interfaces are defined in the OSID enumeration. For all OSID requests, 
     *  an instance of <code> OsidManager </code> that implements the <code> 
     *  OsidManager </code> interface is returned. In bindings where 
     *  permitted, this can be safely cast into the requested manager. 
     *
     *  @param object osid_OSID $osid represents the OSID 
     *  @param string $implementation the name of the implementation 
     *  @param string $version the minimum required interface version 
     *  @return object osid_OsidProxyManager the manager of the service 
     *  @throws osid_NotFoundException the implementation package was not 
     *          found 
     *  @throws osid_NullArgumentException <code> implementation </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnsupportedException <code> implementation </code> does 
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
    public function getProxyManager(osid_OSID $osid, $implementation, $version);


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
    public function getConfiguration();


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
    public function getConfigurationManager();


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
    public function getInstallationManager();

}
