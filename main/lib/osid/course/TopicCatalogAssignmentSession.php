<?php

/**
 * osid_course_TopicCatalogAssignmentSession
 * 
 *     Specifies the OSID definition for osid_course_TopicCatalogAssignmentSession.
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
 *  <p>This session provides methods to re-assign <code> Topics </code> to 
 *  <code> CourseCatalog </code> objects A <code> Topic </code> may appear in 
 *  multiple <code> CourseCatalog </code> objects and removing the last 
 *  reference to a <code> Topic </code> is the equivalent of deleting it. Each 
 *  <code> CourseCatalog </code> may have its own authorizations governing who 
 *  is allowed to operate on it. </p> 
 *  
 *  <p> Moving or adding a reference of a <code> Topic </code> to another 
 *  <code> CourseCatalog </code> is not a copy operation (eg: does not change 
 *  its <code> Id </code> ). </p>
 * 
 * @package org.osid.course
 */
interface osid_course_TopicCatalogAssignmentSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can alter topic/course catalog mappings. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known mapping methods in this session will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if mapping is not authorized, 
     *          <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canAssignTopics();


    /**
     *  Adds an existing <code> Topic </code> to a <code> CourseCatalog. 
     *  </code> 
     *
     *  @param object osid_id_Id $topicId the <code> Id </code> of the <code> 
     *          Topic </code> 
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @throws osid_NotFoundException <code> topicId </code> or <code> 
     *          courseCatalogId </code> not found 
     *  @throws osid_NullArgumentException <code> topicId </code> or <code> 
     *          courseCatalogId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function assignTopicToCatalog(osid_id_Id $topicId, 
                                         osid_id_Id $courseCatalogId);


    /**
     *  Removes a <code> Topic </code> from a <code> CourseCatalog. </code> 
     *
     *  @param object osid_id_Id $topicId the <code> Id </code> of the <code> 
     *          Topic </code> 
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @throws osid_NotFoundException <code> topicId </code> or <code> 
     *          courseCatalogId </code> not found 
     *  @throws osid_NullArgumentException <code> topicId </code> or <code> 
     *          courseCatalogId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function unassignTopicFromCatalog(osid_id_Id $topicId, 
                                             osid_id_Id $courseCatalogId);

}
