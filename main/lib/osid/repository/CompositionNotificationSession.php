<?php

/**
 * osid_repository_CompositionNotificationSession
 * 
 *     Specifies the OSID definition for osid_repository_CompositionNotificationSession.
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
 * @package org.osid.repository
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session defines methods to receive notifications on adds/changes 
 *  to <code> Composition </code> objects in this <code> Repository. </code> 
 *  This session is intended for consumers needing to synchronize their state 
 *  with this service without the use of polling. Notifications are cancelled 
 *  when this session is closed. </p> 
 *  
 *  <p> Two view are defined in this session: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> <code> federateRepositoryView: </code> includes notifications of 
 *      compositions in repositories of which this repository is an ancestor 
 *      in the repository hierarchy </li> 
 *      <li> <code> isolateRepositoryView: </code> restricts notifications to 
 *      this <code> Repository </code> only </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_CompositionNotificationSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> Repository </code> <code> Id </code> associated with 
     *  this session. 
     *
     *  @return object osid_id_Id the <code> Repository Id </code> associated 
     *          with this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepositoryId();


    /**
     *  Gets the <code> Repository </code> associated with this session. 
     *
     *  @return object osid_repository_Repository the <code> Repository 
     *          </code> associated with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepository();


    /**
     *  Tests if this user can register for <code> Composition </code> 
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
    public function canRegisterForCompositionNotifications();


    /**
     *  Federates the view for composition methods in this session. A 
     *  federated view will include compositions in repositories which are 
     *  children of this repository in the repository hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedRepositoryView();


    /**
     *  Isolates the view for composition methods in this session. An isolated 
     *  view restricts notifications to this repository only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedRepositoryView();


    /**
     *  Federates the view for asset methods in this session. A federated view 
     *  will include assets in compositions of which are children of the 
     *  specified composition in the composition hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedCompositionView();


    /**
     *  Isolates the view for asset methods in this session. An isolated view 
     *  restricts notifications to the specified composition only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedCompositionView();


    /**
     *  Register for notifications of new composition. <code> 
     *  CompositionReceiver.newComposition() </code> is invoked when a new 
     *  <code> Composition </code> is created. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewCompositions();


    /**
     *  Registers for notification if an asset is added to a specified 
     *  composition. <code> CompositionReceiver.newCompositionAsset() </code> 
     *  is invoked when an asset is added to the specified composition. In the 
     *  federated composition view, notifications include additions to child 
     *  compositions. 
     *
     *  @param object osid_id_Id $compositionId the <code> Id </code> of the 
     *          composition to monitor 
     *  @throws osid_NotFoundException a composition was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForAssignedAssetsInComposition(osid_id_Id $compositionId);


    /**
     *  Registers for notification if an ancestor is added to the specified 
     *  composition in the composition hierarchy. <code> 
     *  CompositionReceiver.newCompositionAncestor() </code> is invoked when 
     *  the specified composition experiences an addition in ancestry. 
     *
     *  @param object osid_id_Id $compositionId the <code> Id </code> of the 
     *          composition to monitor 
     *  @throws osid_NotFoundException a composition was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewCompositionAncestors(osid_id_Id $compositionId);


    /**
     *  Registers for notification if a descendant is added to the specified 
     *  composition in the composition hierarchy. <code> 
     *  CompositionReceiver.newCompositionDescendant() </code> is invoked when 
     *  the specified composition experiences an addition in descedants. 
     *
     *  @param object osid_id_Id $compositionId the <code> Id </code> of the 
     *          composition to monitor 
     *  @throws osid_NotFoundException a composition was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewCompositionDescendants(osid_id_Id $compositionId);


    /**
     *  Register for notifications of new compositions. <code> 
     *  CompositionReceiver.changedComposition() </code> is invoked when a 
     *  <code> Composition </code> is changed. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedCompositions();


    /**
     *  Registers for notification of an updated composition. <code> 
     *  CompositionReceiver.changedComposition() </code> is invoked when the 
     *  specified composition is changed. 
     *
     *  @param object osid_id_Id $compositionId the <code> Id </code> of the 
     *          <code> Composition </code> to monitor 
     *  @throws osid_NotFoundException a composition was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedComposition(osid_id_Id $compositionId);


    /**
     *  Register for notifications of new compositions. <code> 
     *  CompositionReceiver.deletedComposition() </code> is invoked when a 
     *  <code> Composition </code> is deleted. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedCompositions();


    /**
     *  Registers for notification of a deleted composition. <code> 
     *  CompositionReceiver.deletedComposition() </code> is invoked when the 
     *  specified composition is deleted. 
     *
     *  @param object osid_id_Id $compositionId the <code> Id </code> of the 
     *          <code> Composition </code> to monitor 
     *  @throws osid_NotFoundException a composition was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedComposition(osid_id_Id $compositionId);


    /**
     *  Registers for notification if an asset is removed from a specified 
     *  composition. <code> CompositionReceiver.deletedCompositionAsset() 
     *  </code> is invoked when an asset is removed from the specified 
     *  composition. In the federated composition view, notifications include 
     *  removals from child compositions. 
     *
     *  @param object osid_id_Id $compositionId the <code> Id </code> of the 
     *          composition to monitor 
     *  @throws osid_NotFoundException a composition was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForUnassignedAssetsInComposition(osid_id_Id $compositionId);


    /**
     *  Registers for notification if an ancestor is removed from the 
     *  specified composition in the composition hierarchy. <code> 
     *  CompositionReceiver.deletedCompositionAncestor() </code> is invoked 
     *  when the specified composition experiences a removal of an ancestor.. 
     *
     *  @param object osid_id_Id $compositionId the <code> Id </code> of the 
     *          composition to monitor 
     *  @throws osid_NotFoundException a composition was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedCompositionAncestors(osid_id_Id $compositionId);


    /**
     *  Registers for notification if a descendant removed from to the 
     *  specified composition in the composition hierarchy. <code> 
     *  CompositionReceiver.deletedCompositionDescendant() </code> is invoked 
     *  when the specified composition experiences a removal of a descendant. 
     *
     *  @param object osid_id_Id $compositionId the <code> Id </code> of the 
     *          composition to monitor 
     *  @throws osid_NotFoundException a composition was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedCompositionDescendants(osid_id_Id $compositionId);

}
