<?php

/**
 * osid_resource_ResourceAdminSession
 * 
 *     Specifies the OSID definition for osid_resource_ResourceAdminSession.
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
 * @package org.osid.resource
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session creates and removes resources. The data for create and 
 *  update is provided via the <code> ResourceForm. </code> </p> 
 *  
 *  <p> The view of the administrative methods defined in this session is 
 *  determined by the provider. For an instance of this session where no bin 
 *  has been specified, it may not be parallel to the <code> 
 *  ResourceLookupSession. </code> For example, a default <code> 
 *  ResourceLookupSession </code> may view the entire bin hierarchy while the 
 *  default <code> ResourceAdminSession </code> uses an isolated <code> Bin 
 *  </code> to create new <code> Resources </code> or <code> </code> a 
 *  specific bin to operate on a predetermined set of <code> Resources. 
 *  </code> Another scenario is a federated provider who does not wish to 
 *  permit administrative operations for the federation unaware. </p>
 * 
 * @package org.osid.resource
 */
interface osid_resource_ResourceAdminSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> Bin </code> <code> Id </code> associated with this 
     *  session. 
     *
     *  @return object osid_id_Id the <code> Bin Id </code> associated with 
     *          this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getBinId();


    /**
     *  Gets the <code> Bin </code> associated with this session. 
     *
     *  @return object osid_resource_Bin the <code> Bin </code> associated 
     *          with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getBin();


    /**
     *  Tests if this user can create <code> Resources. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating a <code> Resource </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer create operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Resource </code> 
     *          ceration is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateResources();


    /**
     *  Tests if this user can create a single <code> Resource </code> using 
     *  the desired record types. While <code> 
     *  ResourceManager.getResourceRecordTypes() </code> can be used to 
     *  examine which record interfaces are supported, this method tests which 
     *  record(s) are required for creating a specific <code> Resource. 
     *  </code> Providing an empty array tests if a <code> Resource </code> 
     *  can be created with no records. 
     *
     *  @param array $resourceRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> Resource </code> 
     *          creation using the specified <code> Types </code> is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> resourceRecordTypes </code> 
     *          is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateResourceWithRecordTypes(array $resourceRecordTypes);


    /**
     *  Gets the resource form for creating new resources. A new form should 
     *  be requested for each create transaction. 
     *
     *  @return object osid_resource_ResourceForm the resource form 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceFormForCreate();


    /**
     *  Creates a new <code> Resource. </code> 
     *
     *  @param object osid_resource_ResourceForm $resourceForm the form for 
     *          this <code> Resource </code> 
     *  @return object osid_resource_Resource the new <code> Resource </code> 
     *  @throws osid_AlreadyExistsException attempt at duplicating a property 
     *          the underlying system is enforcing to be unique 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> resourceForm </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> resourceForm </code> is not 
     *          of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createResource(osid_resource_ResourceForm $resourceForm);


    /**
     *  Tests if this user can update <code> Resources. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a <code> Resource </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer update operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Resource </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateResources();


    /**
     *  Tests if this user can update a specified <code> Resource. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known updating the <code> Resource 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may opt not to offer an 
     *  update operation to an unauthorized user for this <code> Resource. 
     *  </code> 
     *
     *  @param object osid_id_Id $resourceId the <code> Id </code> of the 
     *          <code> Resource </code> 
     *  @return boolean <code> false </code> if resource modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> resourceId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> resourceId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateResource(osid_id_Id $resourceId);


    /**
     *  Gets the resource form for updating an existing resource. A new 
     *  resource form should be requested for each update transaction. 
     *
     *  @param object osid_id_Id $resourceId the <code> Id </code> of the 
     *          <code> Resource </code> 
     *  @return object osid_resource_ResourceForm the resource form 
     *  @throws osid_NotFoundException <code> resourceId </code> is not found 
     *  @throws osid_NullArgumentException <code> resourceId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceFormForUpdate(osid_id_Id $resourceId);


    /**
     *  Updates an existing resource. 
     *
     *  @param object osid_id_Id $resourceId the <code> Id </code> of the 
     *          <code> Resource </code> 
     *  @param object osid_resource_ResourceForm $resourceForm the form 
     *          containing the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> resourceId </code> is not found 
     *  @throws osid_NullArgumentException <code> resourceId </code> or <code> 
     *          resourceForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> resourceForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateResource(osid_id_Id $resourceId, 
                                   osid_resource_ResourceForm $resourceForm);


    /**
     *  Tests if this user can delete <code> Resources. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting a <code> Resource </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer delete operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Resource </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteResources();


    /**
     *  Tests if this user can delete a specified <code> Resource. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> Resource 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may opt not to offer an 
     *  delete operation to an unauthorized user for this resource. 
     *
     *  @param object osid_id_Id $resourceId the <code> Id </code> of the 
     *          <code> Resource </code> 
     *  @return boolean <code> false </code> if deletion of this <code> 
     *          Resource </code> is not authorized, <code> true </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> resourceId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> resourceId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteResource(osid_id_Id $resourceId);


    /**
     *  Deletes a <code> Resource. </code> 
     *
     *  @param object osid_id_Id $resourceId the <code> Id </code> of the 
     *          <code> Resource </code> to remove 
     *  @throws osid_NotFoundException <code> resourceId </code> not found 
     *  @throws osid_NullArgumentException <code> resourceId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteResource(osid_id_Id $resourceId);


    /**
     *  Adds an <code> Id </code> to a <code> Resource </code> for the purpose 
     *  of creating compatibility. The primary <code> Id </code> of the <code> 
     *  Resource </code> is determined by the provider. The new <code> Id 
     *  </code> performs as an alias to the primary <code> Id. </code> 
     *
     *  @param object osid_id_Id $resourceId the <code> Id </code> of a <code> 
     *          Resource </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> resourceId </code> not found 
     *  @throws osid_NullArgumentException <code> aliasId </code> or <code> 
     *          resourceId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToResource(osid_id_Id $resourceId, 
                                    osid_id_Id $aliasId);

}
