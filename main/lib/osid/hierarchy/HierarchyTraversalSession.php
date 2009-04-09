<?php

/**
 * osid_hierarchy_HierarchyTraversalSession
 * 
 *     Specifies the OSID definition for osid_hierarchy_HierarchyTraversalSession.
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
 *  <p>This session defines methods for traversing a hiercrhy. Each node in 
 *  the hierarchy is a unique OSID <code> Id. </code> The hierarchy may be 
 *  traversed recursively to establish the tree structure through <code> 
 *  getParents() </code> and <code> getChildren(). </code> To relate these 
 *  <code> Ids </code> to another OSID, <code> getAncestors() </code> and 
 *  <code> getDescendants() </code> can be used for retrievals that can be 
 *  used for bulk lookups in other OSIDs. </p> 
 *  
 *  <p> Any Id available in an associated OSID is known to this hierarchy. A 
 *  lookup up a particular <code> Id </code> in this hierarchy for the 
 *  purposes of establishing a starting point for traversal or determining 
 *  relationships should use the <code> Id </code> returned from the 
 *  corresponding OSID object, not an Id that has been stored, to avoid 
 *  problems with <code> Id </code> translation or aliasing. </p> 
 *  
 *  <p> A user may not be authorized to traverse the entire hierarchy. Parts 
 *  of the hierarchy may be made invisible through omission from the returns 
 *  of <code> getParents() </code> or <code> getChildren() </code> in lieu of 
 *  a <code> PERMISSION_DENIED </code> error that may disrupt the traversal 
 *  through authorized pathways. </p>
 * 
 * @package org.osid.hierarchy
 */
interface osid_hierarchy_HierarchyTraversalSession
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
     *  Tests if this user can perform hierarchy queries. A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canAccessHierarchy();


    /**
     *  Gets the root nodes of this hierarchy. 
     *
     *  @return object osid_id_IdList the root nodes 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRoots();


    /**
     *  Tests if this <code> Id </code> contains any parents. 
     *
     *  @param object osid_id_Id $id the <code> Id </code> to query 
     *  @return boolean <code> true </code> if this <code> Id </code> contains 
     *          parents, <code> false </code> otherwise 
     *  @throws osid_NotFoundException <code> id </code> is not found 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasParents(osid_id_Id $id);


    /**
     *  Tests if an <code> Id </code> is a direct parent of another. 
     *
     *  @param object osid_id_Id $id the <code> Id </code> to query 
     *  @param object osid_id_Id $parentId the <code> Id </code> of a parent 
     *  @return boolean <code> true </code> if this <code> parentId </code> is 
     *          a parent of <code> id, </code> <code> false </code> otherwise 
     *  @throws osid_NotFoundException <code> id </code> is not found 
     *  @throws osid_NullArgumentException <code> id </code> or <code> 
     *          parentId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If <code> parentId </code> not found return <code> false. 
     *          </code> 
     */
    public function isParent(osid_id_Id $id, osid_id_Id $parentId);


    /**
     *  Gets the parents of the given <code> id. </code> 
     *
     *  @param object osid_id_Id $id the <code> Id </code> to query 
     *  @return object osid_id_IdList the parents of the <code> id </code> 
     *  @throws osid_NotFoundException <code> id </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParents(osid_id_Id $id);


    /**
     *  Tests if an <code> Id </code> is an ancestor of another. 
     *
     *  @param object osid_id_Id $id the <code> Id </code> to query 
     *  @param object osid_id_Id $ancestorId the <code> Id </code> of an 
     *          ancestor 
     *  @return boolean <code> true </code> if this <code> ancestorId </code> 
     *          is a parent of <code> id, </code> <code> false </code> 
     *          otherwise 
     *  @throws osid_NotFoundException <code> id </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> or <code> 
     *          ancestorId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If <code> ancestorId </code> not found return <code> false. 
     *          </code> 
     */
    public function isAncestor(osid_id_Id $id, osid_id_Id $ancestorId);


    /**
     *  Gets the ancestors of the given <code> id. </code> 
     *
     *  @param object osid_id_Id $id the <code> Id </code> to query 
     *  @param integer $levels the maximum number of levels to include. A 
     *          value of 1 returns the same set as <code> getParents(). 
     *          </code> 
     *  @return object osid_id_IdList the ancestors of the <code> id </code> 
     *  @throws osid_NotFoundException <code> id </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAncestors(osid_id_Id $id, $levels);


    /**
     *  Tests if this <code> Id </code> has any children. 
     *
     *  @param object osid_id_Id $id the <code> Id </code> to query 
     *  @return boolean <code> true </code> if this <code> Id </code> has 
     *          children, <code> false </code> otherwise 
     *  @throws osid_NotFoundException <code> id </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasChildren(osid_id_Id $id);


    /**
     *  Tests if a node is a direct child of another. 
     *
     *  @param object osid_id_Id $id the <code> Id </code> to query 
     *  @param object osid_id_Id $childId the <code> Id </code> of a child 
     *  @return boolean <code> true </code> if this <code> childId </code> is 
     *          a child of the <code> Id, </code> <code> false </code> 
     *          otherwise 
     *  @throws osid_NotFoundException <code> id </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> or <code> childId 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If <code> childId </code> not found return <code> false. 
     *          </code> 
     */
    public function isChild(osid_id_Id $id, osid_id_Id $childId);


    /**
     *  Gets the children of the given <code> Id. </code> 
     *
     *  @param object osid_id_Id $id the <code> Id </code> to query 
     *  @return object osid_id_IdList the children of the <code> id </code> 
     *  @throws osid_NotFoundException <code> id </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getChildren(osid_id_Id $id);


    /**
     *  Tests if a node is a descendant of another. 
     *
     *  @param object osid_id_Id $id the <code> Id </code> to query 
     *  @param object osid_id_Id $descendantId the <code> Id </code> of a 
     *          descendant 
     *  @return boolean <code> true </code> if this <code> descendantId 
     *          </code> is a child of the <code> Id, </code> <code> false 
     *          </code> otherwise 
     *  @throws osid_NotFoundException <code> id </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> or <code> 
     *          descendant </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If not found return <code> false. </code> 
     */
    public function isDescendant(osid_id_Id $id, osid_id_Id $descendantId);


    /**
     *  Gets the descendants of the given <code> id. </code> 
     *
     *  @param object osid_id_Id $id the <code> Id </code> to query 
     *  @param integer $levels the maximum number of levels to include. A 
     *          value of 1 returns the same set as <code> getChildren(). 
     *          </code> 
     *  @return object osid_id_IdList the descendants of the <code> id </code> 
     *  @throws osid_NotFoundException <code> id </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDescendants(osid_id_Id $id, $levels);


    /**
     *  Traverses the hierarchy from the start node upward. A hierarchy with 
     *  multiple parents may return multiple positions for the same <code> Id 
     *  </code> at different levels of the hierarchy. 
     *
     *  @param object osid_id_Id $startNodeId the <code> Id </code> of the 
     *          start node 
     *  @param integer $levels the maximum number of levels to include 
     *  @return object osid_hierarchy_PositionList the ancestors of the <code> 
     *          id </code> 
     *  @throws osid_NotFoundException <code> id </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function traverseUpBreadthFirst(osid_id_Id $startNodeId, $levels);


    /**
     *  Traverses the hierarchy from the start node upward. A hierarchy with 
     *  multiple parents may return multiple positions for the same Id at 
     *  different levels of the hierarchy. 
     *
     *  @param object osid_id_Id $startNodeId the <code> Id </code> of the 
     *          start node 
     *  @param integer $levels the maximum number of levels to include 
     *  @return object osid_hierarchy_PositionList the ancestors of the <code> 
     *          id </code> 
     *  @throws osid_NotFoundException <code> id </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function traverseUpDepthFirst(osid_id_Id $startNodeId, $levels);


    /**
     *  Traverses the hierarchy from the start node downward. A hierarchy with 
     *  multiple parents may return multiple positions for the same Id at 
     *  different levels of the hierarchy. 
     *
     *  @param object osid_id_Id $startNodeId the <code> Id </code> of the 
     *          start node 
     *  @param integer $levels the maximum number of levels to include 
     *  @return object osid_hierarchy_PositionList the descendants of the 
     *          <code> id </code> 
     *  @throws osid_NotFoundException <code> id </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function traverseDownBreadthFirst(osid_id_Id $startNodeId, $levels);


    /**
     *  Traverses the hierarchy from the start node downward. A hierarchy with 
     *  multiple parents may return multiple positions for the same Id at 
     *  different levels of the hierarchy. 
     *
     *  @param object osid_id_Id $startNodeId the <code> Id </code> of the 
     *          start node 
     *  @param integer $levels the maximum number of levels to include 
     *  @return object osid_hierarchy_PositionList the descendants of the 
     *          <code> id </code> 
     *  @throws osid_NotFoundException <code> id </code> not found 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function traverseDownDepthFirst(osid_id_Id $startNodeId, $levels);

}
