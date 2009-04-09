<?php

/**
 * osid_configuration_ConfigurationProfile
 * 
 *     Specifies the OSID definition for osid_configuration_ConfigurationProfile.
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

require_once(dirname(__FILE__)."/../OsidProfile.php");

/**
 *  <p>The <code> ConfigurationProfile </code> describes the profile of the 
 *  configuration service. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ConfigurationProfile
    extends osid_OsidProfile
{


    /**
     *  Tests if federation is visible for this service. 
     *
     *  @return boolean <code> true </code> if visible federation is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsVisibleFederation();


    /**
     *  Tests for the availability of a configuration value lookup service. 
     *
     *  @return boolean <code> true </code> if value lookup is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsValueLookup();


    /**
     *  Tests for the availability of a configuration value search service. 
     *
     *  @return boolean <code> true </code> if value search is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsValueSearch();


    /**
     *  Tests for the availability of a configuration value administration 
     *  service. 
     *
     *  @return boolean <code> true </code> if value administration is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsValueAdmin();


    /**
     *  Tests for the availability of a configuration value notification 
     *  service. 
     *
     *  @return boolean <code> true </code> if value notification is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsValueNotification();


    /**
     *  Tests for the availability of a parameter lookup service. 
     *
     *  @return boolean <code> true </code> if parameter lookup is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsParameterLookup();


    /**
     *  Tests for the availability of a parameter search service. 
     *
     *  @return boolean <code> true </code> if parameter search is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsParameterSearch();


    /**
     *  Tests for the availability of a parameter update service. 
     *
     *  @return boolean <code> true </code> if parameter update is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented in all 
     *              providers. 
     */
    public function supportsParameterAdmin();


    /**
     *  Tests for the availability of a parameter notification service. 
     *
     *  @return boolean <code> true </code> if parameter notification is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsParameterNotification();


    /**
     *  Tests for the availability of a service to lookup mappings of 
     *  parameters to registries. 
     *
     *  @return boolean <code> true </code> if parameter registry is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsParameterRegistry();


    /**
     *  Tests for the availability of a service to map parameters to 
     *  registries. 
     *
     *  @return boolean <code> true </code> if parameter registry mapping is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsParameterRegistryAssignment();


    /**
     *  Tests for the availability of a service to lookup mappings of 
     *  parameters to configurations. 
     *
     *  @return boolean <code> true </code> if parameter configuration is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented in all 
     *              providers. 
     */
    public function supportsParameterConfiguration();


    /**
     *  Tests for the availability of a service to map parameters to 
     *  configurations. 
     *
     *  @return boolean <code> true </code> if parameter configuration 
     *          assignment is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented in all 
     *              providers. 
     */
    public function supportsParameterConfigurationAssignment();


    /**
     *  Tests for the availability of a configuration lookup service. 
     *
     *  @return boolean <code> true </code> if configuration lookup is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsConfigurationLookup();


    /**
     *  Tests for the availability of a configuration search service. 
     *
     *  @return boolean <code> true </code> if configuration search is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsConfigurationSearch();


    /**
     *  Tests for the availability of a configuration admin service. 
     *
     *  @return boolean <code> true </code> if configuration admin is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsConfigurationAdmin();


    /**
     *  Tests for the availability of a notification service for subscribing 
     *  to changes to configurations. 
     *
     *  @return boolean <code> true </code> if a configuration notification 
     *          service is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsConfigurationNotification();


    /**
     *  Tests for the availability of a configuration hierarchy traversal 
     *  service. 
     *
     *  @return boolean <code> true </code> if a configuration hierarchy 
     *          traversal is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsConfigurationHierarchy();


    /**
     *  Tests for the availability of a configuration hierarchy design 
     *  service. 
     *
     *  @return boolean <code> true </code> if a configuration hierarchy 
     *          design is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsConfigurationHierarchyDesign();


    /**
     *  Tests for the availability of a registry lookup service. 
     *
     *  @return boolean <code> true </code> if registry lookup is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRegistryLookup();


    /**
     *  Tests for the availability of a registry search service. 
     *
     *  @return boolean <code> true </code> if registry search is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRegistrySearch();


    /**
     *  Tests for the availability of a registry admin service. 
     *
     *  @return boolean <code> true </code> if registry admin is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRegistryAdmin();


    /**
     *  Tests for the availability of a notification service for subscribing 
     *  to changes to registries. 
     *
     *  @return boolean <code> true </code> if a registry notification service 
     *          is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRegistryNotification();


    /**
     *  Tests for the availability of a registry hierarchy traversal service. 
     *
     *  @return boolean <code> true </code> if a registry hierarchy traversal 
     *          is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRegistryHierarchy();


    /**
     *  Tests for the availability of a registry hierarchy design service. 
     *
     *  @return boolean <code> true </code> if a registry hierarchy design is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRegistryHierarchyDesign();


    /**
     *  Gets all the value search interface types supported. 
     *
     *  @return object osid_type_TypeList the list of supported value search 
     *          types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getValueSearchInterfaceTypes();


    /**
     *  Tests if a given value search interface type is supported. 
     *
     *  @param object osid_type_Type $valueSearchInterfaceType the value 
     *          search interface type 
     *  @return boolean <code> true </code> if the value search interface type 
     *          is supported <code> , </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsValueSearchInterfaceType(osid_type_Type $valueSearchInterfaceType);


    /**
     *  Gets all the parameter search interface types supported. 
     *
     *  @return object osid_type_TypeList the list of supported parameter 
     *          search interface types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParameterSearchTypes();


    /**
     *  Tests if a given parameter search interface type is supported. 
     *
     *  @param object osid_type_Type $parameterSearchInterfaceType the value 
     *          search type 
     *  @return boolean <code> true </code> if the parameter search interface 
     *          type is supported <code> , </code> <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsParameterSearchInterfaceType(osid_type_Type $parameterSearchInterfaceType);


    /**
     *  Gets all the configuration interface types supported. 
     *
     *  @return object osid_type_TypeList the list of supported configuration 
     *          interface types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationInterfaceTypes();


    /**
     *  Tests if a given configuration interface type is supported. 
     *
     *  @param object osid_type_Type $configurationInterfaceType a 
     *          configuration interface type 
     *  @return boolean <code> true </code> if the configuration interface 
     *          type is supported <code> , </code> <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsConfigurationInterfaceType(osid_type_Type $configurationInterfaceType);


    /**
     *  Gets all the configuration search interface types supported. 
     *
     *  @return object osid_type_TypeList the list of supported configuration 
     *          search interface types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationSearchInterfaceTypes();


    /**
     *  Tests if a given configuration search interface type is supported. 
     *
     *  @param object osid_type_Type $configurationSearchInterfaceType the 
     *          configuration search interface type 
     *  @return boolean <code> true </code> if the configuration search 
     *          interface type is supported <code> , </code> <code> false 
     *          </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsConfigurationSearchInterfaceType(osid_type_Type $configurationSearchInterfaceType);


    /**
     *  Gets all the registry interface types supported. 
     *
     *  @return object osid_type_TypeList the list of supported registry 
     *          interface types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistryInterfaceTypes();


    /**
     *  Tests if a given regustry interface type is supported. 
     *
     *  @param object osid_type_Type $registryInterfaceType a registry 
     *          interface type 
     *  @return boolean <code> true </code> if the registry interface type is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRegistryInterfaceType(osid_type_Type $registryInterfaceType);


    /**
     *  Gets all the registry search interface types supported. 
     *
     *  @return object osid_type_TypeList the list of supported registry 
     *          search interface types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistrySearchInterfaceTypes();


    /**
     *  Tests if a given registry search interface type is supported. 
     *
     *  @param object osid_type_Type $registrySearchInterfaceType the registry 
     *          search interface type 
     *  @return boolean <code> true </code> if the registry search interface 
     *          type is supported <code> , </code> <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRegistrySearchInterfaceType(osid_type_Type $registrySearchInterfaceType);

}
