<?php

/**
 * osid_hierarchy_HierarchyManager
 * 
 *     Specifies the OSID definition for osid_hierarchy_HierarchyManager.
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

require_once(dirname(__FILE__)."/../OsidManager.php");
require_once(dirname(__FILE__)."/HierarchyProfile.php");

/**
 *  <p>The hierarchy manager provides access sessions to traverse and manage 
 *  hierrachies of <code> Ids. </code> The sessions included in this manager 
 *  are: 
 *  <ul>
 *      <li> <code> HierarchyTraversalSession: </code> a basic session 
 *      traversing a hierarchy </li> 
 *      <li> <code> HierarchyDesignSession: </code> a session to design a 
 *      hiererachy </li> 
 *      <li> <code> HierarchyStructureNotificationSession: </code> a session 
 *      for notififxations within a hierarchy structure </li> 
 *      <li> <code> HiererachyLookupSession: </code> a session looking up 
 *      hiererachies </li> 
 *      <li> <code> HiererachySearchSession: </code> a session for searching 
 *      for hierarchies </li> 
 *      <li> <code> HierarchyAdminSession: </code> a session for creating and 
 *      deleting hierarchies </li> 
 *      <li> <code> HierarchyNotificationSession: </code> a session for 
 *      subscribing to changes in hierarchies </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.hierarchy
 */
interface osid_hierarchy_HierarchyManager
    extends osid_OsidManager,
            osid_hierarchy_HierarchyProfile
{


    /**
     *  Gets the <code> OsidSession </code> associated with the hierarchy 
     *  traversal service. 
     *
     *  @return object osid_hierarchy_HierarchyTraversalSession a <code> 
     *          HierarchyTraversalSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsHierarchyTraversal() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsHierarchyTraversal() </code> is <code> true. 
     *              </code> 
     */
    public function getHierarachyTraversalSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the hierarchy 
     *  traversal service for the given hierarchy. 
     *
     *  @param object osid_id_Id $hierarchyId the <code> Id </code> of the 
     *          hierarchy 
     *  @return object osid_hierarchy_HierarchyTraversalSession the new <code> 
     *          HierarchyTraversalSession </code> 
     *  @throws osid_NotFoundException <code> hierarchyId </code> not found 
     *  @throws osid_NullArgumentException <code> hierarchyid </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_UnimplementedException <code> 
     *          supportsHierarchyTraversal() </code> or <code> 
     *          supportsVisibleFedaration() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsHierarchyTraversal() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getHierachyTraversalSessionForHierarchy(osid_id_Id $hierarchyId);


    /**
     *  Gets the <code> OsidSession </code> associated with the hierarchy 
     *  design service. 
     *
     *  @return object osid_hierarchy_HierarchyDesignSession a <code> 
     *          HierarchyDesignSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsHierarchyDesign() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsHierarchyDesign() </code> is <code> true. </code> 
     */
    public function getHierarchyDesignSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the topology 
     *  design service using for the given hierarchy. 
     *
     *  @param object osid_id_Id $hierarchyId the <code> Id </code> of the 
     *          graph 
     *  @return object osid_hierarchy_HierarchyDesignSession a <code> 
     *          HierarchyDesignSession </code> 
     *  @throws osid_NotFoundException <code> hierarchyId </code> is not found 
     *  @throws osid_NullArgumentException <code> hierarchyId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsHierarchyDesign() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsHierarchyDesign() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getHierarchyDesignSessionForHierarchy(osid_id_Id $hierarchyId);


    /**
     *  Gets the session for subscribing to notifications of changes within a 
     *  hierarchy structure. 
     *
     *  @return object osid_hierarchy_HierarchyStructureNotificationSession a 
     *          <code> HierarchyStructureNotificationSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsHierarchyStructureNotification() </code> is <code> 
     *          false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsHierarchyStructureNotification() </code> is <code> 
     *              true. </code> 
     */
    public function getHierarchyStructureNotificationSession();


    /**
     *  Gets the session for subscribing to notifications of changes within a 
     *  hierarchy structure for the given hierarchy. 
     *
     *  @param object osid_id_Id $hierarchyId the <code> Id </code> of the 
     *          graph 
     *  @return object osid_hierarchy_HierarchyStructureNotificationSession a 
     *          <code> HierarchyStructureNotificationSession </code> 
     *  @throws osid_NotFoundException <code> hierarchyId </code> is not found 
     *  @throws osid_NullArgumentException <code> hierarchyId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsHierarchyStructureNotification() </code> or <code> 
     *          supportsVisibleFederation() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsHierarchyStructureNotification() </code> and 
     *              <code> supportsVisibleFederation() </code> are <code> 
     *              true. </code> 
     */
    public function getHierarchyStructureNotificationSessionForHierarchy(osid_id_Id $hierarchyId);


    /**
     *  Gets the <code> OsidSession </code> associated with the hierarchy 
     *  lookup service. 
     *
     *  @return object osid_hierarchy_HierarchyLookupSession a <code> 
     *          HierarchyLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsHierarchyLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsHierarchyLookup() </code> is <code> true. </code> 
     */
    public function getHierarchyLookupSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the hierarchy 
     *  search service. 
     *
     *  @return object osid_hierarchy_HierarchySearchSession a <code> 
     *          HierarchySearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsHierarchySearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsHierarchySearch() </code> is <code> true. </code> 
     */
    public function getHierarchySearchSession();


    /**
     *  Gets the hierarchy administrative session. 
     *
     *  @return object osid_hierarchy_HierarchyAdminSession a <code> 
     *          HierarchyAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsHierarchyAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsHierarchyAdmin() </code> is <code> true. </code> 
     */
    public function getHierarchyAdminSession();


    /**
     *  Gets a hierarchy notification session. 
     *
     *  @param object osid_hierarchy_HierarchyReceiver $receiver notification 
     *          callback 
     *  @return object osid_hierarchy_HierarchyNotificationSession a <code> 
     *          HierarchyNotificationSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsHierarchyNotification() </code> is <code> false 
     *          </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsHierarchyNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getHierarchyNotificationSession(osid_hierarchy_HierarchyReceiver $receiver);

}
