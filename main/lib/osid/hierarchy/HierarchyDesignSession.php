<?php

/**
 * osid_hierarchy_HierarchyDesignSession
 * 
 *     Specifies the OSID definition for osid_hierarchy_HierarchyDesignSession.
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
 *  <p>This session provides methods to manage a hierarchy. Each node is 
 *  expressed as an OSID <code> Id </code> that represents an external object. 
 *  The hierarchy only expresses relationships among these Ids. However, 
 *  changing the hierarchy may have implications, such as inherited data, in 
 *  the associated OSID. </p>
 * 
 * @package org.osid.hierarchy
 */
interface osid_hierarchy_HierarchyDesignSession
    extends osid_OsidSession
{


    /**
     *  Gets the hierarchy <code> Id </code> associated with this session. 
     *
     *  @return object osid_id_Id the hierarchy <code> Id </code> associated 
     *          with this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchyId();


    /**
     *  Gets the hierarchy associated with this session. 
     *
     *  @return object osid_hierarchy_Hierarchy the hierarchy associated with 
     *          this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchy();


    /**
     *  Tests if this user can change the hierarchy. A return of true does not 
     *  guarantee successful authorization. A return of false indicates that 
     *  it is known performing any update will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer these operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if changing this hierarchy is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canModifyHierarchy();


    /**
     *  Adds a root node. 
     *
     *  @param object osid_id_Id $id the <code> Id </code> of the node 
     *  @throws osid_AlreadyExistsException <code> id </code> is already in 
     *          hierarchy 
     *  @throws osid_NotFoundException <code> id </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addRoot(osid_id_Id $id);


    /**
     *  Adds a child to a <code> Id. </code> 
     *
     *  @param object osid_id_Id $id the <code> Id </code> of the node 
     *  @param object osid_id_Id $childId the <code> Id </code> of the new 
     *          child 
     *  @throws osid_NotFoundException <code> id </code> or <code> childId 
     *          </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> or <code> childId 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addChild(osid_id_Id $id, osid_id_Id $childId);


    /**
     *  Reorders nodes at the same hierarchy level for sequencing by moving a 
     *  node ahead of a reference node. 
     *
     *  @param object osid_id_Id $id the <code> Id </code> of the node to move 
     *  @param object osid_id_Id $referenceId id is ordered in front of this 
     *          reference node 
     *  @throws osid_InvalidArgumentException <code> id </code> or <code> 
     *          referenceId </code> not in a sequence 
     *  @throws osid_NotFoundException <code> id </code> or <code> referenceId 
     *          </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> or <code> 
     *          referenceId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance optional This method must be implemented if <code> 
     *              HierarchyManager.supportsNodeSequencing() </code> is 
     *              <code> true. </code> 
     */
    public function moveAhead(osid_id_Id $id, osid_id_Id $referenceId);


    /**
     *  Reorders nodes at the same hierarchy level for sequencing by moving a 
     *  node behind a reference node. 
     *
     *  @param object osid_id_Id $id the <code> Id </code> of the node to move 
     *  @param object osid_id_Id $referenceId id is ordered behind this 
     *          reference node 
     *  @throws osid_InvalidArgumentException <code> id </code> or <code> 
     *          referenceId </code> not in a sequence 
     *  @throws osid_NotFoundException <code> id </code> or <code> referenceId 
     *          </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> or <code> 
     *          referenceId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance optional This method must be implemented if <code> 
     *              HierarchyManager.supportsNodeSequencing() </code> is 
     *              <code> true. </code> 
     */
    public function moveBehind(osid_id_Id $id, osid_id_Id $referenceId);


    /**
     *  Removes a root node. 
     *
     *  @param object osid_id_Id $id the <code> Id </code> of the node 
     *  @throws osid_NotFoundException <code> id </code> was not found or not 
     *          in hierarchy 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function removeRoot(osid_id_Id $id);


    /**
     *  Removes a child <code> </code> from an <code> Id. </code> 
     *
     *  @param object osid_id_Id $id the <code> Id </code> of the node 
     *  @param object osid_id_Id $childId the <code> Id </code> of the child 
     *          to remove 
     *  @throws osid_NotFoundException <code> id </code> or <code> childId 
     *          </code> was not found 
     *  @throws osid_NullArgumentException <code> id </code> or <code> childId 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function removeChild(osid_id_Id $id, osid_id_Id $childId);


    /**
     *  Removes all children <code> </code> from an <code> Id. </code> 
     *
     *  @param object osid_id_Id $id the <code> Id </code> of the node 
     *  @throws osid_NotFoundException an node identified by the given <code> 
     *          Id </code> was not found 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function removeChildren(osid_id_Id $id);

}
