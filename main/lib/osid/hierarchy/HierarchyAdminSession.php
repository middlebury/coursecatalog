<?php

/**
 * osid_hierarchy_HierarchyAdminSession
 * 
 *     Specifies the OSID definition for osid_hierarchy_HierarchyAdminSession.
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
 * @package org.osid.hierarchy
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session creates and removes hierarchies. The data for create and 
 *  update is provided by the consumer via the form object. </p>
 * 
 * @package org.osid.hierarchy
 */
interface osid_hierarchy_HierarchyAdminSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can create <code> Hierarchy </code> objects. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known creating a <code> Hierarchy 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may not wish to offer create 
     *  operations to unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> Hierarchy </code> 
     *          creation is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateHierarchies();


    /**
     *  Tests if this user can create a single <code> Hierarchy </code> using 
     *  the desired record types. While <code> 
     *  HierarchyManager.getHierarchyRecordTypes() </code> can be used to 
     *  examine which record interfaces are supported, this method tests which 
     *  record(s) are required for creating a specific <code> Hierarchy. 
     *  </code> Providing an empty array tests if a <code> Hierarchy </code> 
     *  can be created with no records. 
     *
     *  @param array $hierarchyRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> Hierarchy </code> 
     *          creation using the specified <code> Types </code> is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> hierarchyRecordTypes </code> 
     *          is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateHierarchyWithRecordTypes(array $hierarchyRecordTypes);


    /**
     *  Gets the hierarchy form for creating new hierarchies. A new form 
     *  should be requested for each create transaction. This method is used 
     *  for creating new hierarchies, where only the <code> Hierarchy </code> 
     *  <code> Type </code> is known. 
     *
     *  @return object osid_hierarchy_HierarchyForm the hierarchy form 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchyFormForCreate();


    /**
     *  Creates a new <code> Hierarchy. </code> 
     *
     *  @param object osid_hierarchy_HierarchyForm $hierarchyForm the form for 
     *          this <code> Hierarchy </code> 
     *  @return object osid_hierarchy_Hierarchy the new <code> Hierarchy 
     *          </code> 
     *  @throws osid_AlreadyExistsException attempt at duplicating a property 
     *          the underlying system is enforcing to be unique 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> hierarchyForm </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException a <code> hierarchyForm </code> is 
     *          not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createHierarchy(osid_hierarchy_HierarchyForm $hierarchyForm);


    /**
     *  Tests if this user can update <code> Hierarchy </code> objects. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known updating a <code> Hierarchy 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may not wish to offer update 
     *  operations to unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> Hierarchy </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateHierarchies();


    /**
     *  Tests if this user can update a specified <code> Hierarchy. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known updating the <code> Hierarchy 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may not wish to offer update 
     *  operations to unauthorized users. 
     *
     *  @param object osid_id_Id $hierarchyId the <code> Id </code> of the 
     *          <code> Hierarchy </code> 
     *  @return boolean <code> false </code> if hierarchy modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> hierarchyId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> hierarchyId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateHierarchy(osid_id_Id $hierarchyId);


    /**
     *  Gets the hierarchy form for updating an existing hierarchy. A new 
     *  hierarchy form should be requested for each update transaction. 
     *
     *  @param object osid_id_Id $hierarchyId the <code> Id </code> of the 
     *          <code> Hierarchy </code> 
     *  @return object osid_hierarchy_HierarchyForm the hierarchy form 
     *  @throws osid_NotFoundException <code> hierarchyId </code> is not found 
     *  @throws osid_NullArgumentException <code> hierarchyId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchyFormForUpdate(osid_id_Id $hierarchyId);


    /**
     *  Updates an existing hierarchy. 
     *
     *  @param object osid_id_Id $hierarchyId the <code> Id </code> of the 
     *          <code> Hierarchy </code> 
     *  @param object osid_hierarchy_HierarchyForm $hierarchyForm the form 
     *          containing the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> hierarchyId </code> is not found 
     *  @throws osid_NullArgumentException <code> hierarchyId </code> or 
     *          <code> hierarchyForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> hierarchyForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateHierarchy(osid_id_Id $hierarchyId, 
                                    osid_hierarchy_HierarchyForm $hierarchyForm);


    /**
     *  Tests if this user can delete <code> Hierarchy </code> objects. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting a <code> Hierarchy 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may not wish to offer delete 
     *  operations to unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> Hierarchy </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteHierarchies();


    /**
     *  Tests if this user can delete a specified <code> Hierarchy. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> Hierarchy 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may not wish to offer delete 
     *  operations to unauthorized users. 
     *
     *  @param object osid_id_Id $hierarchyId the <code> Id </code> of the 
     *          <code> Hierarchy </code> 
     *  @return boolean <code> false </code> if deletion of this <code> 
     *          Hierarchy </code> is not authorized, <code> true </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> hierarchyId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> hierarchyId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteHierarchy(osid_id_Id $hierarchyId);


    /**
     *  Deletes a <code> Hierarchy. </code> 
     *
     *  @param object osid_id_Id $hierarchyId the <code> Id </code> of the 
     *          <code> Hierarchy </code> to remove 
     *  @throws osid_NotFoundException <code> hierarchyId </code> not found 
     *  @throws osid_NullArgumentException <code> hierarchyId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteHierarchy(osid_id_Id $hierarchyId);


    /**
     *  Adds an <code> Id </code> to a <code> Hierarchy </code> for the 
     *  purpose of creating compatibility. The primary <code> Id </code> of 
     *  the <code> Hierarchy </code> is determined by the provider. The new 
     *  <code> Id </code> performs as an alias to the primary <code> Id. 
     *  </code> 
     *
     *  @param object osid_id_Id $hierarchyId the <code> Id </code> of an 
     *          <code> Hierarchy </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> hierarchyId </code> not found 
     *  @throws osid_NullArgumentException <code> hierarchyId </code> or 
     *          <code> aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToHierarchy(osid_id_Id $hierarchyId, 
                                     osid_id_Id $aliasId);

}
