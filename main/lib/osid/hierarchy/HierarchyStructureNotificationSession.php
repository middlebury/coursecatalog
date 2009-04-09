<?php

/**
 * osid_hierarchy_HierarchyStructureNotificationSession
 * 
 *     Specifies the OSID definition for osid_hierarchy_HierarchyStructureNotificationSession.
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
 *  <p>This session defines methods to receive notifications on adds/changes 
 *  to a hierarchical structure. This session is intended for consumers 
 *  needing to synchronize their state with this service without the use of 
 *  polling. Notifications are cancelled when this session is closed. </p> 
 *  
 *  <p> Notifications are triggered with changes to the structure of a 
 *  hierarchy. For notifications of changes to the <code> Hierarchy </code> 
 *  object use <code> HierarchyNotificationSession. </code> </p>
 * 
 * @package org.osid.hierarchy
 */
interface osid_hierarchy_HierarchyStructureNotificationSession
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
     *  Gets the <code> Hierarchy </code> associated with this session. 
     *
     *  @return object osid_hierarchy_Hierarchy the <code> Hierarchy </code> 
     *          associated with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchy();


    /**
     *  Tests if this user can register for <code> Hierarchy </code> node 
     *  notifications. A return of true does not guarantee successful 
     *  authorization. A return of false indicates that it is known all 
     *  methods in this session will result in a <code> PERMISSION_DENIED. 
     *  </code> This is intended as a hint to an application that may opt not 
     *  to offer notification operations. 
     *
     *  @return boolean <code> false </code> if notification methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canRegisterForHierarchyStructureNotifications();


    /**
     *  Register for notifications of new hierarchy nodes. <code> 
     *  HierarchyStructureReceiver.newNode() </code> is invoked when a new 
     *  <code> Hierarchy </code> node is added. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewHierarchyNodes();


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  impacts the ancestors of the specified node. <code> 
     *  HierarchyStructureReceiver.newAncestor() </code> or <code> 
     *  HierarchyStructureReceiver.deletedAncestor() </code> is invoked when 
     *  the specified hierarchy node experiences a change in ancestry. 
     *
     *  @param object osid_id_Id $nodeId the <code> Id </code> of the <code> 
     *          hierarchy </code> node to monitor 
     *  @throws osid_NotFoundException a hierarchy node was not found 
     *          identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> nodeId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedAncestor(osid_id_Id $nodeId);


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  impacts the descendants of the specified node. <code> 
     *  HierarchyStructureReceiver.newDescendant() </code> or <code> 
     *  HierarchyStructureReceiver.deletedDescendant() </code> is invoked when 
     *  the specified hierarchy node experiences a change in offspring. 
     *
     *  @param object osid_id_Id $nodeId the <code> Id </code> of the <code> 
     *          hierarchy </code> node to monitor 
     *  @throws osid_NotFoundException a hierarchy node was not found 
     *          identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> nodeId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedDescendant(osid_id_Id $nodeId);


    /**
     *  Registers for notification of deleted hierarchy nodes. <code> 
     *  HierarchyStructureReceiver.deletedNode() </code> is invoked when a 
     *  hierarchy ndoe is deleted. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedHierarchyNodes();


    /**
     *  Registers for notification of a deleted hierarchy node. <code> 
     *  HierarchyStructureReceiver.deletedNode() </code> is invoked when the 
     *  specified hierarchy node is deleted. 
     *
     *  @param object osid_id_Id $nodeId the <code> Id </code> of the <code> 
     *          Hierarchy </code> node to monitor 
     *  @throws osid_NotFoundException a hierarchy node was not found 
     *          identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> nodeId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedHierarchyNode(osid_id_Id $nodeId);

}
