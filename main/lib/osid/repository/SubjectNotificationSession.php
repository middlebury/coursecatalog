<?php

/**
 * osid_repository_SubjectNotificationSession
 * 
 *     Specifies the OSID definition for osid_repository_SubjectNotificationSession.
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
 *  to <code> Subject </code> objects in this <code> Repository. </code> This 
 *  session is intended for consumers needing to synchronize their state with 
 *  this service without the use of polling. Notifications are cancelled when 
 *  this session is closed. </p> 
 *  
 *  <p> The two views defined in this session correspond to the views in the 
 *  <code> SubjectLookupSession. </code> </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_SubjectNotificationSession
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
     *  Tests if this user can register for <code> Subject </code> 
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
    public function canRegisterForSubjectNotifications();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include subjects in repositories which are children of this repository 
     *  in the repository hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedRepositoryView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts notifications to this repository only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedRepositoryView();


    /**
     *  Register for notifications of new subjects. <code> 
     *  SubjectReceiver.newSubject() </code> is invoked when a new <code> 
     *  Subject </code> is appears in this repository. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewSubjects();


    /**
     *  Registers for notification if an ancestor is added to the specified 
     *  subject in the subject hierarchy. <code> 
     *  SubjectReceiver.newAncestorSubject() </code> is invoked when the 
     *  specified subject experiences an addition in ancestry. 
     *
     *  @param object osid_id_Id $subjectId the <code> Id </code> of the 
     *          subject to monitor 
     *  @throws osid_NotFoundException a subject was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewSubjectAncestors(osid_id_Id $subjectId);


    /**
     *  Registers for notification if a descendant is added to the specified 
     *  subject in the subject hierrachy. <code> 
     *  SubjectReceiver.newDescendantSubject() </code> is invoked when the 
     *  specified subject experiences an addition in descendants. 
     *
     *  @param object osid_id_Id $subjectId the <code> Id </code> of the 
     *          subject to monitor 
     *  @throws osid_NotFoundException a subject was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewSubjectDescendants(osid_id_Id $subjectId);


    /**
     *  Registers for notification of updated subjects. <code> 
     *  SubjectReceiver.changedSubject() </code> is invoked when a subject in 
     *  this repository is changed. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedSubjects();


    /**
     *  Registers for notification of an updated subject. <code> 
     *  SubjectReceiver.changedSubject() </code> is invoked when the specified 
     *  subject in this repository is changed. 
     *
     *  @param object osid_id_Id $subjectId the <code> Id </code> of the 
     *          <code> Subject </code> to monitor 
     *  @throws osid_NotFoundException a subject was not found in this 
     *          repository identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedSubject(osid_id_Id $subjectId);


    /**
     *  Registers for notification of deleted subjects. <code> 
     *  SubjectReceiver.deletedSubject() </code> is invoked when a subject is 
     *  deleted or removed from this repository. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedSubjects();


    /**
     *  Registers for notification of a deleted subject. <code> 
     *  SubjectReceiver.deletedSubject() </code> is invoked when the specified 
     *  subject is deleted or removed from this repository. 
     *
     *  @param object osid_id_Id $subjectId the <code> Id </code> of the 
     *          <code> Subject </code> to monitor 
     *  @throws osid_NotFoundException a subject was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedSubject(osid_id_Id $subjectId);


    /**
     *  Registers for notification if an ancestor is removed from the 
     *  specified subject. <code> SubjectReceiver.deletedAncestorSubject() 
     *  </code> is invoked when the specified subject experiences a removal in 
     *  ancestry. 
     *
     *  @param object osid_id_Id $subjectId the <code> Id </code> of the 
     *          subject to monitor 
     *  @throws osid_NotFoundException a subject was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedSubjectAncestors(osid_id_Id $subjectId);


    /**
     *  Registers for notification if a descendant is removed from the 
     *  specified subject. <code> SubjectReceiver.deletedAncestorSubject() 
     *  </code> is invoked when the specified subject experiences a removal in 
     *  one of its descendants. 
     *
     *  @param object osid_id_Id $subjectId the <code> Id </code> of the 
     *          subject to monitor 
     *  @throws osid_NotFoundException a subject was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedSubjectDescendants(osid_id_Id $subjectId);

}
