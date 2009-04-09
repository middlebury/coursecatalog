<?php

/**
 * osid_course_TermNotificationSession
 * 
 *     Specifies the OSID definition for osid_course_TermNotificationSession.
 * 
 * Copyright (C) 2009 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.course
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session defines methods to receive notifications on adds/changes 
 *  to <code> Term </code> objects in this <code> CourseCatalog. </code> This 
 *  also includes existing terms that may appear or disappear due to changes 
 *  in the <code> CourseCatalog </code> hierarchy, This session is intended 
 *  for consumers needing to synchronize their state with this service without 
 *  the use of polling. Notifications are cancelled when this session is 
 *  closed. </p> 
 *  
 *  <p> The two views defined in this session correspond to the views in the 
 *  <code> TermLookupSession. </code> </p>
 * 
 * @package org.osid.course
 */
interface osid_course_TermNotificationSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> CourseCatalog </code> <code> Id </code> associated 
     *  with this session. 
     *
     *  @return object osid_id_Id the <code> CourseCatalog Id </code> 
     *          associated with this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogId();


    /**
     *  Gets the <code> CourseCatalog </code> associated with this session. 
     *
     *  @return object osid_course_CourseCatalog the course catalog 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalog();


    /**
     *  Tests if this user can register for <code> Term </code> notifications. 
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
    public function canRegisterForTermNotifications();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include terms in catalogs which are children of this catalog in the 
     *  course catalog hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedCourseCatalogView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts notifications to this course catalog only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedCourseCatalogView();


    /**
     *  Register for notifications of new terms. <code> TermReceiver.newTerm() 
     *  </code> is invoked when a new <code> Term </code> appears in this 
     *  course catalog. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewTerms();


    /**
     *  Registers for notification of updated courses. <code> 
     *  TermReceiver.changedTerm() </code> is invoked when a term in this 
     *  course catalog is changed. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedTerms();


    /**
     *  Registers for notification of an updated term. <code> 
     *  TermReceiver.changedTerm() </code> is invoked when the specified term 
     *  in this course catalog is changed. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the <code> 
     *          Term </code> to monitor 
     *  @throws osid_NotFoundException a term was not found in this course 
     *          catalog identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedTerm(osid_id_Id $termId);


    /**
     *  Registers for notification of deleted terms. <code> 
     *  TermReceiver.deletedTerm() </code> is invoked when a term is deleted 
     *  or removed from this course catalog. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedTerms();


    /**
     *  Registers for notification of a deleted term. <code> 
     *  TermReceiver.deletedTerm() </code> is invoked when the specified term 
     *  is deleted or removed from this course catalog. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the <code> 
     *          Term </code> to monitor 
     *  @throws osid_NotFoundException a term was not found identified by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedTerm(osid_id_Id $termId);

}
