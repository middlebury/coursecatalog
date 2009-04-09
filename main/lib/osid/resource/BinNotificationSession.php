<?php

/**
 * osid_resource_BinNotificationSession
 * 
 *     Specifies the OSID definition for osid_resource_BinNotificationSession.
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
 *  to <code> Bin </code> objects. This session is intended for consumers 
 *  needing to synchronize their state with this service without the use of 
 *  polling. Notifications are cancelled when this session is closed. </p>
 * 
 * @package org.osid.resource
 */
interface osid_resource_BinNotificationSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can register for <code> Bin </code> notifications. 
     *  A return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer notification 
     *  operations. 
     *
     *  @return boolean <code> false </code> if notification methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canRegisterForBinNotifications();


    /**
     *  Register for notifications of new bins. <code> BinReceiver.newBin() 
     *  </code> is invoked when a new <code> Bin </code> is created. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewBins();


    /**
     *  Registers for notification if an ancestor is added to the specified 
     *  bin in the bin hierarchy. <code> BinReceiver.newBinAncestor() </code> 
     *  is invoked when the specified bin experiences an addition in ancestry. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the bin to 
     *          monitor 
     *  @throws osid_NotFoundException a bin was not found identified by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> binId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewBinAncestors(osid_id_Id $binId);


    /**
     *  Registers for notification if a descendant is added to the specified 
     *  bin in the bin hierarchy. <code> BinReceiver.newBinDescendant() 
     *  </code> is invoked when the specified bin experiences an addition in 
     *  descendants. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the bin to 
     *          monitor 
     *  @throws osid_NotFoundException a bin was not found identified by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> binId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewBinDescendants(osid_id_Id $binId);


    /**
     *  Registers for notification of updated bins. <code> 
     *  BinReceiver.changedBin() </code> is invoked when a bin is changed. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedBins();


    /**
     *  Registers for notification of an updated bin. <code> 
     *  BinReceiver.changedBin() </code> is invoked when the specified bin is 
     *  changed. 
     *
     *  @param object osid_id_Id $binId the Id of the Bin to monitor 
     *  @throws osid_NotFoundException a bin was not found identified by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> binId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedBin(osid_id_Id $binId);


    /**
     *  Registers for notification of deleted bins. <code> 
     *  BinReceiver.deletedBin() </code> is invoked when a bin is deleted. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedBins();


    /**
     *  Registers for notification of a deleted bin. <code> 
     *  BinReceiver.deletedBin() </code> is invoked when the specified bin is 
     *  deleted. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the <code> 
     *          Bin </code> to monitor 
     *  @throws osid_NotFoundException a bin was not found identified by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> binId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedBin(osid_id_Id $binId);


    /**
     *  Registers for notification if an ancestor is removed from the 
     *  specified bin in the bin hierarchy. <code> 
     *  BinReceiver.deletedBinAncestor() </code> is invoked when the specified 
     *  bin experiences a removal of an ancestor. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the bin to 
     *          monitor 
     *  @throws osid_NotFoundException a bin was not found identified by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> binId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedBinAncestors(osid_id_Id $binId);


    /**
     *  Registers for notification if a descendant is removed from fthe 
     *  specified bin in the bin hierarchy. <code> 
     *  BinReceiver.deletedBinDescednant() </code> is invoked when the 
     *  specified bin experiences a removal of one of its descdendents. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the bin to 
     *          monitor 
     *  @throws osid_NotFoundException a bin was not found identified by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> binId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedBinDescendants(osid_id_Id $binId);

}
