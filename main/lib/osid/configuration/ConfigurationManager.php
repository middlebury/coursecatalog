<?php

/**
 * osid_configuration_ConfigurationManager
 * 
 *     Specifies the OSID definition for osid_configuration_ConfigurationManager.
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

require_once(dirname(__FILE__)."/../OsidManager.php");
require_once(dirname(__FILE__)."/ConfigurationProfile.php");

/**
 *  <p>The configuration manager provides access sessions to retrieve and 
 *  manage configurations. A manager may support federation in that values can 
 *  be accessed in a specified configuration and parameters may be defined in 
 *  a specified registry. The sessions included in this manager are: 
 *  <ul>
 *      <li> <code> ValueLookupSession: </code> a basic session for retrieving 
 *      configuration values </li> 
 *      <li> <code> ValueSearchSession: </code> a basic session for searching 
 *      configuration values </li> 
 *      <li> <code> ValueAdminSession: </code> a session for setting and 
 *      changing configuration values </li> 
 *      <li> <code> ValueNotificationSession: </code> a session for 
 *      subscribing to changes of configuration values </li> 
 *      <li> <code> ParameterLookupSession: </code> a session for retrieving 
 *      defined parameters </li> 
 *      <li> <code> ParameterSearchSession: </code> a session for searching 
 *      defined parameters </li> 
 *      <li> <code> ParameterAdminSession: </code> a session for creating, 
 *      updating and deleting parameter definitions </li> 
 *      <li> <code> ParameterNoitificationSession: </code> a session for 
 *      subscribing to adds and changes of parameters </li> 
 *      <li> <code> ParamaterRegistrySession: </code> a session for examining 
 *      mappings of parameters to registries </li> 
 *      <li> <code> ParamaterRegistryAssignmentSession: </code> a session for 
 *      making mappings of parameters to registries </li> 
 *      <li> <code> ParameterConfigurationSession: </code> a session for 
 *      examining mappings of parameters to configurations </li> 
 *      <li> <code> ParameterConfigurationAssignmentSession: </code> a session 
 *      for mapping parameters to configurations </li> 
 *  </ul>
 *  
 *  <ul>
 *      <li> <code> ConfigurationLookupSession: </code> a session for 
 *      retrieving configurations </li> 
 *      <li> <code> ConfigurationSearchSession: </code> a session for 
 *      searching configurations </li> 
 *      <li> <code> ConfigurationAdminSession: </code> a session for creating 
 *      and updating configurations </li> 
 *      <li> <code> ConfigurationNotificationSession: </code> a session for 
 *      subscribing to adds and changes to configurations </li> 
 *      <li> <code> ConfigurationHierarchySession: </code> a session for 
 *      traversing a hierarchy of configurations </li> 
 *      <li> <code> ConfigurationHierarchyDesignSession: </code> a session for 
 *      managing a hierarchy of configurations </li> 
 *      <li> <code> RegistryLookupSession: </code> a session for retrieving 
 *      configurations </li> 
 *      <li> <code> RegistrySearchSession: </code> a session for searching 
 *      configurations </li> 
 *      <li> <code> RegistryAdminSession: </code> a session for creating and 
 *      updating configurations </li> 
 *      <li> <code> RegistryNotificationSession: </code> a session for 
 *      subscribing to adds and changes to configurations </li> 
 *      <li> <code> RegistryHierarchySession: </code> a session for traversing 
 *      a hierarchy of configurations </li> 
 *      <li> <code> RegistryHierarchyDesignSession: </code> a session for 
 *      managing a hierarchy of configurations </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ConfigurationManager
    extends osid_OsidManager,
            osid_configuration_ConfigurationProfile
{


    /**
     *  
     *  <ul>
     *      <li> <code> ConfigurationLookupSession: </code> a session for 
     *      retrieving configurations </li> 
     *      <li> <code> ConfigurationLookupSession: </code> a session for 
     *      searching configurations </li> 
     *  </ul>
     *  
     *  <ul>
     *      <li> <code> ConfigurationAdminSession: </code> a session for 
     *      creating and updating configurations </li> 
     *      <li> <code> ConfigurationHierarchyTraversalSession: </code> a 
     *      session for traversing a hierarchy of configurations </li> 
     *      <li> <code> ConfigurationHierarchyDesignSession: </code> a session 
     *      for managing a hierarchy of configurations </li> 
     *      <li> <code> ConfigurationNotificationSession: </code> a session 
     *      for subscribing to adds and changes to configurations </li> 
     *      <li> <code> RegistryLookupSession: </code> a session for finding 
     *      and retrieving configurations </li> 
     *      <li> <code> RegistryAdminSession: </code> a session for creating 
     *      and updating configurations </li> 
     *      <li> <code> RegistryHierarchyTraversalSession: </code> a session 
     *      for traversing a hierarchy of configurations </li> 
     *      <li> <code> RegistryHierarchyDesignSession: </code> a session for 
     *      managing a hierarchy of configurations </li> 
     *      <li> <code> RegistryNotificationSession: </code> a session for 
     *      subscribing to adds and changes to configurations </li> 
     *  </ul>
     *  
     *
     *  @return object osid_configuration_ValueLookupSession a <code> 
     *          ValueLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsValueLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsValueLookup() </code> is <code> true. </code> 
     */
    public function getValueLookupSession();


    /**
     *  Gets a configuration value lookup session using the supplied 
     *  configuration. 
     *
     *  @param object osid_id_Id $configurationId the <code> Id </code> of the 
     *          <code> Configuration </code> to use 
     *  @return object osid_configuration_ValueLookupSession a <code> 
     *          ValueLookupSession </code> 
     *  @throws osid_NotFoundException <code> configurationId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> configurationId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsValueLookup() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsVisibleFederation() </code> and <code> 
     *              supportsValueLookup() </code> are <code> true. </code> 
     */
    public function getValueLookupSessionForConfiguration(osid_id_Id $configurationId);


    /**
     *  Gets a configuration value search session 
     *
     *  @return object osid_configuration_ValueSearchSession a <code> 
     *          ValueSearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsValueSearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsValueSearch() </code> is <code> true. </code> 
     */
    public function getValueSearchSession();


    /**
     *  Gets a configuration value search session using the supplied 
     *  configuration. 
     *
     *  @param object osid_id_Id $configurationId the <code> Id </code> of the 
     *          <code> Configuration </code> to use 
     *  @return object osid_configuration_ValueSearchSession a <code> 
     *          ValueSearchSession </code> 
     *  @throws osid_NotFoundException <code> configurationId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> configurationId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsValueSearch() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsVisibleFederation() </code> and <code> 
     *              supportsValueSearch() </code> are <code> true. </code> 
     */
    public function getValueSearchSessionForConfiguration(osid_id_Id $configurationId);


    /**
     *  Gets a configuration value administration session. 
     *
     *  @return object osid_configuration_ValueAdminSession a <code> 
     *          ValueAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsValueAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsValueAdmin() </code> is <code> true. </code> 
     */
    public function getValueAdminSession();


    /**
     *  Gets a value administration session using the supplied configuration. 
     *
     *  @param object osid_id_Id $configurationId the <code> Id </code> of the 
     *          <code> Configuration </code> to use 
     *  @return object osid_configuration_ValueAdminSession a <code> 
     *          ValueAdminSession </code> 
     *  @throws osid_NotFoundException <code> configurationId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> configurationId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException <code> supportsValueAdmin() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnimplementedException <code> supportsValueAdmin() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsVisibleFederation() </code> and <code> 
     *              supportsValueAdmin() </code> are <code> true. </code> 
     */
    public function getValueAdminSessionForConfiguration(osid_id_Id $configurationId);


    /**
     *  Gets a value notification session. 
     *
     *  @param object osid_configuration_ValueReceiver $receiver the 
     *          notification callback 
     *  @return object osid_configuration_ValueNotificationSession a <code> 
     *          ValueNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsValueNotification() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsValueNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getValueNotificationSession(osid_configuration_ValueReceiver $receiver);


    /**
     *  Gets a value notification session using the specified configuration 
     *
     *  @param object osid_configuration_ValueReceiver $receiver the 
     *          notification callback 
     *  @param object osid_id_Id $configurationId the <code> Id </code> of the 
     *          <code> Configuration </code> to use 
     *  @return object osid_configuration_ValueNotificationSession a <code> 
     *          ValueNotificationSession </code> 
     *  @throws osid_NotFoundException <code> configurationId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> receiver </code> or <code> 
     *          configurationId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsValueNotification() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsVisibleFederation() </code> and <code> 
     *              supportsValueNotification() </code> are <code> true. 
     *              </code> 
     */
    public function getValueNotificationSessionForConfiguration(osid_configuration_ValueReceiver $receiver, 
                                                                osid_id_Id $configurationId);


    /**
     *  Gets a parameter lookup session 
     *
     *  @return object osid_configuration_ParameterLookupSession a <code> 
     *          ParameterLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsParameterLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsParameterLookup() </code> is <code> true. </code> 
     */
    public function getParameterLookupSession();


    /**
     *  Gets a parameter lookup session using the supplied registry. 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          <code> Registry </code> to use 
     *  @return object osid_configuration_ParameterLookupSession a <code> 
     *          ParamaterLookupSession </code> 
     *  @throws osid_NotFoundException <code> registryId </code> is not found 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsParameterLookup() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsVisibleFederation() </code> and <code> 
     *              supportsParameterLookup() </code> are <code> true. </code> 
     */
    public function getParameterLookupSessionForRegistry(osid_id_Id $registryId);


    /**
     *  Gets a parameter search session 
     *
     *  @return object osid_configuration_ParameterSearchSession a <code> 
     *          ParameterSearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsParameterSearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsParameterSearch() </code> is <code> true. </code> 
     */
    public function getParameterSearchSession();


    /**
     *  Gets a parameter search session using the supplied registry. 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          <code> Registry </code> to use 
     *  @return object osid_configuration_ParameterSearchSession a <code> 
     *          ParamaterSearchSession </code> 
     *  @throws osid_NotFoundException <code> registryId </code> is not found 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsParameterSearch() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsVisibleFederation() </code> and <code> 
     *              supportsParameterSearch() </code> are <code> true. </code> 
     */
    public function getParameterSearchSessionForRegistry(osid_id_Id $registryId);


    /**
     *  Gets a parameter administration session. 
     *
     *  @return object osid_configuration_ParameterAdminSession a <code> 
     *          ParameterAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsParameterAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsParameterAdmin() </code> is <code> true. </code> 
     */
    public function getParameterAdminSession();


    /**
     *  Gets a parameter administration session using the supplied registry. 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          <code> Registry </code> to use 
     *  @return object osid_configuration_ParameterAdminSession a <code> 
     *          ParameterAdminSession </code> 
     *  @throws osid_NotFoundException <code> registryId </code> is not found 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsParameterSearch() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsVisibleFederation() </code> and <code> 
     *              supportsParameterAdmin() </code> are <code> true. </code> 
     */
    public function getParameterAdminSessionForRegistry(osid_id_Id $registryId);


    /**
     *  Gets a parameter notification session. 
     *
     *  @param object osid_configuration_ParameterReceiver $receiver the 
     *          notification callback 
     *  @return object osid_configuration_ParameterNotificationSession a 
     *          <code> ParameterNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsParameterNotification() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsParameterNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getParameterNotificationSession(osid_configuration_ParameterReceiver $receiver);


    /**
     *  Gets a parameter notification session using the specified registry. 
     *
     *  @param object osid_configuration_ParameterReceiver $receiver the 
     *          notification callback 
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          <code> Registry </code> to use 
     *  @return object osid_configuration_ParameterNotificationSession a 
     *          <code> ParameterNotificationSession </code> 
     *  @throws osid_NotFoundException <code> registryId </code> is not found 
     *  @throws osid_NullArgumentException <code> receiver </code> or <code> 
     *          registryId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsParameterNotification() </code> or <code> 
     *          supportsVisibleFederation() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsVisibleFederation() </code> and <code> 
     *              supportsParameterNotification() </code> are <code> true. 
     *              </code> 
     */
    public function getParameterNotificationSessionForRegistry(osid_configuration_ParameterReceiver $receiver, 
                                                               osid_id_Id $registryId);


    /**
     *  Gets a session for looking up mappings of parametres to 
     *  configurations. 
     *
     *  @return object osid_configuration_ParameterConfigurationSession a 
     *          <code> ParameterConfigurationSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsParameterConfiguration() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsParameterConfiguration() </code> is <code> true. 
     *              </code> 
     */
    public function getParameterConfigurationSession();


    /**
     *  Gets a session for managing mappings of parametres to configurations. 
     *
     *  @return object osid_configuration_ParameterConfigurationAssignmentSession 
     *          a <code> ParameterConfigurationAssignmentSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsParameterConfigurationAssignment() </code> is <code> 
     *          false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsParameterConfigurationAssignment() </code> is 
     *              <code> true. </code> 
     */
    public function getParameterConfigurationAssignmentSession();


    /**
     *  Gets a session for lookung up mappings of parametres to registries. 
     *
     *  @return object osid_configuration_ParameterRegistrySession a <code> 
     *          ParameterRegistrySession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsParameterRegistry() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsParameterRegistry() </code> is <code> true. 
     *              </code> 
     */
    public function getParameterRegistrySession();


    /**
     *  Gets a session for managing mappings of parametres to registries. 
     *
     *  @return object osid_configuration_ParameterRegistryAssignmentSession a 
     *          <code> ParameterRegistryAssignmentSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsParameterRegistryAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsParameterRegistryAssignment() </code> is <code> 
     *              true. </code> 
     */
    public function getParameterRegistryAssignmentSession();


    /**
     *  Gets a configuration lookup session. 
     *
     *  @return object osid_configuration_ConfigurationLookupSession a <code> 
     *          ConfigurationLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsConfigurationLookup() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsConfigurationLookup() </code> is <code> true. 
     *              </code> 
     */
    public function getConfigurationLookupSession();


    /**
     *  Gets a configuration search session. 
     *
     *  @return object osid_configuration_ConfigurationSearchSession a <code> 
     *          ConfigurationSearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsConfigurationSearch() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsConfigurationSearch() </code> is <code> true. 
     *              </code> 
     */
    public function getConfigurationSearchSession();


    /**
     *  Gets a configuration administration session. 
     *
     *  @return object osid_configuration_ConfigurationAdminSession a <code> 
     *          ConfigurationAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsConfigurationAdmin() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsConfigurationAdmin() </code> is <code> true. 
     *              </code> 
     */
    public function getConfigurationAdminSession();


    /**
     *  Gets the notification session for subscribing to changes to 
     *  configurations. 
     *
     *  @param object osid_configuration_ConfigurationReceiver $receiver the 
     *          notification callback 
     *  @return object osid_configuration_ConfigurationNotificationSession a 
     *          <code> ConfigurationNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsConfigurationNotification() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsConfigurationNotification() </code> is <code> 
     *              true. </code> 
     */
    public function getConfigurationNotificationSession(osid_configuration_ConfigurationReceiver $receiver);


    /**
     *  Gets a hierarchy traversal service for configurations. 
     *
     *  @return object osid_configuration_ConfigurationHierarchySession a 
     *          <code> ConfigurationHierarchySession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsConfigurationHierarchy() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsConfigurationHierarchy() </code> is <code> true. 
     *              </code> 
     */
    public function getConfigurationHierarchySession();


    /**
     *  Gets a hierarchy design service for configurations. 
     *
     *  @return object osid_configuration_ConfigurationHierarchyDesignSession 
     *          a <code> ConfigurationHierarchyDesignSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsConfigurationHierarchyDesign() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsConfigurationHierarchyDesign() </code> is <code> 
     *              true. </code> 
     */
    public function getConfigurationHierarchyDesignSession();


    /**
     *  Gets a registry lookup session. 
     *
     *  @return object osid_configuration_RegistryLookupSession a <code> 
     *          RegistryLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsRegistryLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRegistryLookup() </code> is <code> true. </code> 
     */
    public function getRegistryLookupSession();


    /**
     *  Gets a registry search session. 
     *
     *  @return object osid_configuration_RegistrySearchSession a <code> 
     *          RegistrySearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsRegistrySearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRegistrySearch() </code> is <code> true. </code> 
     */
    public function getRegistrySearchSession();


    /**
     *  Gets a registry administration session. 
     *
     *  @return object osid_configuration_RegistryAdminSession a <code> 
     *          RegistryAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsRegistryAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRegistryAdmin() </code> is <code> true. </code> 
     */
    public function getRegistryAdminSession();


    /**
     *  Gets a notification session for subscribing to changes to registries. 
     *
     *  @param object osid_configuration_RegistryReceiver $receiver the 
     *          notification callback 
     *  @return object osid_configuration_RegistryNotificationSession a <code> 
     *          RegistryNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsRegistryNotification() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRegistryNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getRegistryNotificationSession(osid_configuration_RegistryReceiver $receiver);


    /**
     *  Gets a hierarchy traversal service for registries. 
     *
     *  @return object osid_configuration_RegistryHierarchySession a <code> 
     *          RegistryHierarchySession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsRegistryHierarchy() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRegistryHierarchy() </code> is <code> true. 
     *              </code> 
     */
    public function getRegistryHierarchySession();


    /**
     *  Gets a hierarchy design service for registries. 
     *
     *  @return object osid_configuration_RegistryHierarchyDesignSession a 
     *          <code> RegistryHierarchyDesignSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsRegistryHierarchyDesign() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRegistryHierarchyDesign() </code> is <code> true. 
     *              </code> 
     */
    public function getRegistryHierarchyDesignSession();

}
