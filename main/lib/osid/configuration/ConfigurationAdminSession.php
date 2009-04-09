<?php

/**
 * osid_configuration_ConfigurationAdminSession
 * 
 *     Specifies the OSID definition for osid_configuration_ConfigurationAdminSession.
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

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session defines an interface to create, update and delete <code> 
 *  Configuration </code> objects. The data for create and update is provided 
 *  by the consumer via the form object. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ConfigurationAdminSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can create <code> Configurations. </code> A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating a C <code> onfiguration </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer create operations 
     *  to an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Configuration </code> 
     *          creation is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateConfigurations();


    /**
     *  Tests if this user can create a single <code> Configuration </code> 
     *  using the desired interface types. A <code> Configuration </code> 
     *  interface <code> Type </code> may specify the implementation of other 
     *  more well-known <code> Types. </code> A provider may or may not accept 
     *  the creation of a <code> Configuration </code> using anything other 
     *  than the <code> ConfigurationForm </code> of the leaf interface <code> 
     *  Type, </code> or may accept creation using one or more higher level 
     *  interface <code> Types. </code> 
     *
     *  @param array $configurationInterfaceTypes array of types 
     *  @return boolean <code> true </code> if <code> Configuration </code> 
     *          creation using the specified <code> Types </code> is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> configurationInterfaceTypes 
     *          </code> is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateConfigurationWithInterfaceTypes(array $configurationInterfaceTypes);


    /**
     *  Gets the list of minimum required interface <code> Types </code> for 
     *  creating a new <code> Configuration. </code> A new <code> 
     *  Configuration </code> can be created using any one of the interface 
     *  types returned in this list. This may be a series of leaf <code> 
     *  Types, </code> or multiple higher level <code> Types </code> if 
     *  creation requirements are more lenient. An empty list suggests no 
     *  creates are supported as a root <code> Configuration </code> interface 
     *  is always identified with its own <code> Type. </code> 
     *
     *  @return object osid_type_TypeList one or more types 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRequiredConfigurationInterfaceTypesForCreate();


    /**
     *  Gets the configuration form for creating new configurations. 
     *
     *  @param object osid_type_Type $configurationInterfaceType a 
     *          configuration interface type 
     *  @return object osid_configuration_ConfigurationForm the configuration 
     *          form 
     *  @throws osid_NullArgumentException <code> configurationInterfaceType 
     *          </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          canCreateConfigurationWithInterfaceTypes(configurationInterfaceType) 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationFormForCreate(osid_type_Type $configurationInterfaceType);


    /**
     *  Creates a new <code> Configuration. </code> 
     *
     *  @param array $configurationForms the configuration forms 
     *  @return object osid_configuration_Configuration the new <code> 
     *          Configuration </code> 
     *  @throws osid_AlreadyExistsException attempt to add a <code> 
     *          Configuration </code> when one by that name or unique property 
     *          already exists 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NullArgumentException <code> configurationForms </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException a <code> configurationForm </code> 
     *          is not supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createConfiguration(array $configurationForms);


    /**
     *  Tests if this user can update <code> Configurations. </code> A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a C <code> onfiguration </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer update operations 
     *  to an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Configuration </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateConfigurations();


    /**
     *  Tests if this user can update a specified <code> Configuration. 
     *  </code> A return of true does not guarantee successful authorization. 
     *  A return of false indicates that it is known updating the <code> 
     *  Configuration </code> will result in a <code> PERMISSION_DENIED. 
     *  </code> This is intended as a hint to an application that may opt not 
     *  to offer update operations to an unauthoirzed user. 
     *
     *  @param object osid_id_Id $configurationId the <code> Id </code> of the 
     *          configuration 
     *  @return boolean <code> false </code> if configuration modification is 
     *          not authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> configurationId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> configurationId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateConfiguration(osid_id_Id $configurationId);


    /**
     *  Gets the configuration form for updating existing configurations. A 
     *  new configuration form should be requested for each update 
     *  transaction. 
     *
     *  @param object osid_id_Id $configurationId <code> Id </code> of a 
     *          <code> Configuration </code> 
     *  @return object osid_configuration_ConfigurationForm the configuration 
     *          form 
     *  @throws osid_NotFoundException <code> configurationId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> configurationId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationFormForUpdate(osid_id_Id $configurationId);


    /**
     *  Updates an existing <code> Configuration. </code> 
     *
     *  @param object osid_id_Id $configurationId the <code> Id </code> of the 
     *          <code> Configuration </code> to update 
     *  @param object osid_configuration_ConfigurationForm $configurationForm 
     *          the configuration form 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> configurationId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> configurationId </code> or 
     *          <code> configurationForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> configurationForm </code> is 
     *          not supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateConfiguration(osid_id_Id $configurationId, 
                                        osid_configuration_ConfigurationForm $configurationForm);


    /**
     *  Tests if this user can delete <code> Configurations. </code> A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting a <code> Configuration </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer delete operations 
     *  to an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Configuration </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteConfigurations();


    /**
     *  Tests if this user can delete a specified <code> Configuration. 
     *  </code> A return of true does not guarantee successful authorization. 
     *  A return of false indicates that it is known deleting the <code> 
     *  Configuration </code> will result in a <code> PERMISSION_DENIED. 
     *  </code> This is intended as a hint to an application that may opt not 
     *  to offer delete operations to an unauthorized user. 
     *
     *  @param object osid_id_Id $configurationId the <code> Id </code> of the 
     *          configuration 
     *  @return boolean <code> false </code> if <code> Configuration </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> configurationId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> configurationId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteConfiguration(osid_id_Id $configurationId);


    /**
     *  Deletes a <code> Configuration. </code> 
     *
     *  @param object osid_id_Id $configurationId the <code> Id </code> of the 
     *          <code> Configuration </code> to delete 
     *  @throws osid_NotFoundException <code> configurationId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> configurationId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteConfiguration(osid_id_Id $configurationId);


    /**
     *  Adds an <code> Id </code> to a <code> Configuration </code> for the 
     *  purpose of creating compatibility. The primary <code> Id </code> of 
     *  the <code> Configuration </code> is determined by the provider. The 
     *  new <code> Id </code> performs as an alias to the primary <code> Id. 
     *  </code> 
     *
     *  @param object osid_id_Id $configurationId the <code> Id </code> of a 
     *          <code> Configuration </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> configurationId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> configurationId </code> or 
     *          <code> aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToConfiguration(osid_id_Id $configurationId, 
                                         osid_id_Id $aliasId);

}
