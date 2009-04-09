<?php

/**
 * osid_configuration_RegistryAdminSession
 * 
 *     Specifies the OSID definition for osid_configuration_RegistryAdminSession.
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
 *  Registry </code> objects. The data for create and update is provided by 
 *  the consumer via the form object. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_RegistryAdminSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can create <code> Registry </code> objects.A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating a <code> Registry </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may not wish to offer create operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> Registry </code> 
     *          creation is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateRegistries();


    /**
     *  Tests if this user can create a single <code> Registry </code> using 
     *  the desired interface types. A <code> Registry </code> interface 
     *  <code> Type </code> may specify the implementation of other more 
     *  well-known <code> Types. </code> A provider may or may not accept the 
     *  creation of a <code> Registry </code> using anything other than the 
     *  <code> RegistryForm </code> of the leaf interface <code> Type, </code> 
     *  or may accept creation using one or more higher level interface <code> 
     *  Types. </code> 
     *
     *  @param array $registryInterfaceTypes array of types 
     *  @return boolean <code> true </code> if <code> Registry </code> 
     *          creation using the specified <code> Types </code> is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> registryInterfaceTypes 
     *          </code> is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateRegistryWithInterfaceTypes(array $registryInterfaceTypes);


    /**
     *  Gets the list of minimum required interface <code> Types </code> for 
     *  creating a new <code> Registry. </code> A new <code> Registry </code> 
     *  can be created using any one of the interface types returned in this 
     *  list. This may be a series of leaf <code> Types, </code> or multiple 
     *  higher level <code> Types </code> if creation requirements are more 
     *  lenient. An empty list suggests no creates are supported as a root 
     *  <code> Registry </code> interface is always identified with its own 
     *  <code> Type. </code> 
     *
     *  @return object osid_type_TypeList one or more types 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRequiredRegistryInterfaceTypesForCreate();


    /**
     *  Gets the registry form for creating new registries. 
     *
     *  @param object osid_type_Type $registryInterfaceType a registry 
     *          interface type 
     *  @return object osid_configuration_RegistryForm the registry form 
     *  @throws osid_NullArgumentException <code> registryInterfaceType 
     *          </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          canCreateRegistryWithInterfaceTypes(registryInterfaceType) 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistryFormForCreate(osid_type_Type $registryInterfaceType);


    /**
     *  Creates a new <code> Registry. </code> 
     *
     *  @param array $registryForms the registry forms 
     *  @return object osid_configuration_Registry the new <code> Registry 
     *          </code> 
     *  @throws osid_AlreadyExistsException attempt to add a <code> Registry 
     *          </code> when one by that name or unique property already 
     *          exists 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NullArgumentException <code> registryForms </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException a <code> registryForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createRegistry(array $registryForms);


    /**
     *  Tests if this user can update <code> Registries. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a <code> Registry </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may not wish to offer update operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> Registry </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateRegistries();


    /**
     *  Tests if this user can update a specified <code> Registry. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known updating the <code> Registry 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may not wish to offer update 
     *  operations to unauthorized users. 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          registry 
     *  @return boolean <code> false </code> if registry modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> registryId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateRegistry(osid_id_Id $registryId);


    /**
     *  Gets the registry form for updating existing registries. A new 
     *  registry form should be requested for each update transaction. 
     *
     *  @param object osid_id_Id $registryId <code> Id </code> of a <code> 
     *          Configuration </code> 
     *  @return object osid_configuration_RegistryForm the registry form 
     *  @throws osid_NotFoundException <code> registryId </code> is not found 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistryFormForUpdate(osid_id_Id $registryId);


    /**
     *  Updates an existing <code> Registry. </code> 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          registry to update 
     *  @param object osid_configuration_RegistryForm $registryForm the 
     *          registry form 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> registryId </code> not found 
     *  @throws osid_NullArgumentException <code> registryId </code> or <code> 
     *          registryForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> registryForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateRegistry(osid_id_Id $registryId, 
                                   osid_configuration_RegistryForm $registryForm);


    /**
     *  Tests if this user can delete a specified <code> Registry. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> Registry 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may not wish to offer delete 
     *  operations to unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> Registry </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteRegistries();


    /**
     *  Tests if this user can delete a specified <code> Registry. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> Registry 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may not wish to offer delete 
     *  operations to unauthorized users. 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          registry 
     *  @return boolean <code> false </code> if <code> Registry </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> registryId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteRegistry(osid_id_Id $registryId);


    /**
     *  Deletes a <code> Registry. </code> 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          <code> Registry </code> to delete 
     *  @throws osid_NotFoundException <code> registryId </code> not found 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteRegistry(osid_id_Id $registryId);


    /**
     *  Adds an <code> Id </code> to a <code> Registry </code> for the purpose 
     *  of creating compatibility. The primary <code> Id </code> of the <code> 
     *  Registry </code> is determined by the provider. The new <code> Id 
     *  </code> performs as an alias to the primary <code> Id. </code> 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of a <code> 
     *          Registry </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> registryId </code> not found 
     *  @throws osid_NullArgumentException <code> registryId </code> or <code> 
     *          aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToRegistry(osid_id_Id $registryId, 
                                    osid_id_Id $aliasId);

}
