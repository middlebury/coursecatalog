<?php

/**
 * osid_logging_LogNotificationSession
 * 
 *     Specifies the OSID definition for osid_logging_LogNotificationSession.
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
 * @package org.osid.logging
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session defines methods to receive notifications on adds/changes 
 *  to <code> Log </code> objects. This session is intended for consumers 
 *  needing to synchronize their state with this service without the use of 
 *  polling. Notifications are cancelled when this session is closed. </p> 
 *  
 *  <p> Notifications are triggered with changes to the <code> Log </code> 
 *  object itself. Adding and removing entries result in notifications 
 *  available from the notification session for log entries. </p>
 * 
 * @package org.osid.logging
 */
interface osid_logging_LogNotificationSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can register for <code> Log </code> notifications. 
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
    public function canRegisterForLogNotifications();


    /**
     *  Register for notifications of new logs. <code> LogReceiver.newLog() 
     *  </code> is invoked when a new <code> Log </code> is created. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewLogs();


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  introduces a new ancestor of the specified log. <code> 
     *  LogReceiver.newAncestorLog() </code> is invoked when the specified log 
     *  node gets a new ancestor. 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> node to monitor 
     *  @throws osid_NotFoundException a log node was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewLogAncestors(osid_id_Id $logId);


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  introduces a new descendant of the specified log. <code> 
     *  LogReceiver.newDescendantLog() </code> is invoked when the specified 
     *  log node gets a new descendant. 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> node to monitor 
     *  @throws osid_NotFoundException a log node was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewLogDescendants(osid_id_Id $logId);


    /**
     *  Registers for notification of updated logs. <code> 
     *  LogReceiver.changedLog() </code> is invoked when a log is changed. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedLogs();


    /**
     *  Registers for notification of an updated log. <code> 
     *  LogReceiver.changedLog() </code> is invoked when the specified log is 
     *  changed. 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> to monitor 
     *  @throws osid_NotFoundException a log was not found identified by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedLog(osid_id_Id $logId);


    /**
     *  Registers for notification of deleted logs. <code> 
     *  LogReceiver.deletedLog() </code> is invoked when a log is deleted. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedLogs();


    /**
     *  Registers for notification of a deleted log. <code> 
     *  LogReceiver.deletedLog() </code> is invoked when the specified log is 
     *  deleted. 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> to monitor 
     *  @throws osid_NotFoundException a log was not found identified by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedLog(osid_id_Id $logId);


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  removes an ancestor of the specified log. <code> 
     *  LogReceiver.deletedAncestor() </code> is invoked when the specified 
     *  log node loses an ancestor. 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> node to monitor 
     *  @throws osid_NotFoundException a log node was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedLogAncestors(osid_id_Id $logId);


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  removes a descendant of the specified log. <code> 
     *  LogReceiver.deletedDescendant() </code> is invoked when the specified 
     *  log node loses a descendant. 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> node to monitor 
     *  @throws osid_NotFoundException a log node was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedLogDescendants(osid_id_Id $logId);

}
