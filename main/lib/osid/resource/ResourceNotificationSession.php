<?php

/**
 * osid_resource_ResourceNotificationSession
 * 
 *     Specifies the OSID definition for osid_resource_ResourceNotificationSession.
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
 *  <p>This session defines methods to receive notifications on adds/changes 
 *  to <code> Resource </code> objects in this <code> Bin. </code> This also 
 *  includes existing resources that may appear or disappear due to changes in 
 *  the <code> Bin </code> hierarchy, This session is intended for consumers 
 *  needing to synchronize their state with this service without the use of 
 *  polling. Notifications are cancelled when this session is closed. </p> 
 *  
 *  <p> The two views defined in this session correspond to the views in the 
 *  <code> ResourceLookupSession. </code> </p>
 * 
 * @package org.osid.resource
 */
interface osid_resource_ResourceNotificationSession
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
     *  Tests if this user can register for <code> Resource </code> 
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
    public function canRegisterForResourceNotifications();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include resources in bins which are children of this bin in the bin 
     *  hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedBinView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts notifications to this bin only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedBinView();


    /**
     *  Register for notifications of new resources. <code> 
     *  ResourceReceiver.newResource() </code> is invoked when a new <code> 
     *  Resource </code> is appears in this bin. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewResources();


    /**
     *  Registers for notification of updated resources. <code> 
     *  ResourceReceiver.changedResource() </code> is invoked when a resource 
     *  in this bin is changed. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedResources();


    /**
     *  Registers for notification of an updated resource. <code> 
     *  ResourceReceiver.changedResource() </code> is invoked when the 
     *  specified resource in this bin is changed. 
     *
     *  @param object osid_id_Id $resourceId the <code> Id </code> of the 
     *          <code> Resource </code> to monitor 
     *  @throws osid_NotFoundException a resource was not found in this bin 
     *          identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> resourceId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedResource(osid_id_Id $resourceId);


    /**
     *  Registers for notification of deleted resources. <code> 
     *  ResourceReceiver.deletedResource() </code> is invoked when a resource 
     *  is deleted or removed from this bin. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedResources();


    /**
     *  Registers for notification of a deleted resource. <code> 
     *  ResourceReceiver.deletedResource() </code> is invoked when the 
     *  specified resource is deleted or removed from this bin. 
     *
     *  @param object osid_id_Id $resourceId the <code> Id </code> of the 
     *          <code> Resource </code> to monitor 
     *  @throws osid_NotFoundException a resource was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> resourceId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedResource(osid_id_Id $resourceId);

}
