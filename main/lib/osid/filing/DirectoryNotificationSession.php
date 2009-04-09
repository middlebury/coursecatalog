<?php

/**
 * osid_filing_DirectoryNotificationSession
 * 
 *     Specifies the OSID definition for osid_filing_DirectoryNotificationSession.
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
 * @package org.osid.filing
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session defines methods to receive notifications on adds/changes 
 *  to <code> Directories. </code> This session is intended for consumers 
 *  needing to synchronize their state with this service without the use of 
 *  polling. Notifications are cancelled when this session is closed. </p> 
 *  
 *  <p> Two view are defined in this session: </p> 
 *  
 *  <p> federated view: includes notifications of directories located in 
 *  descendants of this directory </p> 
 *  
 *  <p> isolated view: includes notifcations of directories in this directory 
 *  only </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_DirectoryNotificationSession
    extends osid_OsidSession
{


    /**
     *  Gets the absolute path of this directory. 
     *
     *  @return string the absolute path of this directory 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDirectoryPath();


    /**
     *  Gets the <code> Directory </code> associated with this session. 
     *
     *  @return object osid_filing_Directory the <code> Directory </code> 
     *          associated with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDirectory();


    /**
     *  Tests if this user can register for <code> Directory </code> 
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
    public function canRegisterForDirectoryNotifications();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include directories in directories which are children of this 
     *  directory. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedDirectoryView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts notifications to this diretory only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedDirectoryView();


    /**
     *  Register for notifications of new directories. <code> 
     *  DirectoryReceiver.newDirectory() </code> is invoked when a new <code> 
     *  Directory </code> is created. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewDirectories();


    /**
     *  Registers for notification of updated directories. <code> 
     *  DirectoryReceiver.changedDirectory() </code> is invoked when a file is 
     *  changed. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedDirectories();


    /**
     *  Registers for notification of an updated file. <code> 
     *  DirectoryReceiver.changedDirectory() </code> is invoked when the 
     *  specified directory is changed. 
     *
     *  @param string $name the name of the directory to monitor 
     *  @throws osid_NotFoundException a directory was not found identified by 
     *          the given <code> name </code> 
     *  @throws osid_NullArgumentException <code> name </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedDirectory($name);


    /**
     *  Registers for notification of deleted directories. <code> 
     *  DirectoryReceiver.deletedDirectory() </code> is invoked when a 
     *  directory is deleted. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedDirectories();


    /**
     *  Registers for notification of a deleted directory. <code> 
     *  DirectoryReceiver.changedDirectory() </code> is invoked when the 
     *  specified directory is changed. 
     *
     *  @param string $name the name of the directory 
     *  @throws osid_NotFoundException a directory was not found identified by 
     *          the given <code> path </code> 
     *  @throws osid_NullArgumentException <code> path </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedDirectory($name);

}
