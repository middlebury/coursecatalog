<?php

/**
 * osid_course_CourseOfferingAdminSession
 * 
 *     Specifies the OSID definition for osid_course_CourseOfferingAdminSession.
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
 *  <p>This session creates and removes course offerings. The data for create 
 *  and update is provided via the <code> CourseOfferingForm. </code> </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseOfferingAdminSession
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
     *  Tests if this user can create <code> CourseOfferings. </code> A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating a <code> CourseOffering </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer create operations 
     *  to an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> CourseOffering </code> 
     *          ceration is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateCourseOfferings();


    /**
     *  Tests if this user can create a single <code> CourseOffering </code> 
     *  using the desired record types. While <code> 
     *  CourseManager.getCourseOfferingRecordTypes() </code> can be used to 
     *  examine which records are supported, this method tests which record(s) 
     *  are required for creating a specific <code> CourseOffering. </code> 
     *  Providing an empty array tests if a <code> CourseOffering </code> can 
     *  be created with no records. 
     *
     *  @param array $courseOfferingRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> CourseOffering </code> 
     *          creation using the specified record <code> Types </code> is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> courseOfferingRecordTypes 
     *          </code> is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateCourseOfferingWithRecordTypes(array $courseOfferingRecordTypes);


    /**
     *  Gets the course form for creating new course offerings. A new form 
     *  should be requested for each create transaction. 
     *
     *  @return object osid_course_CourseOfferingForm the course offering form 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingFormForCreate();


    /**
     *  Creates a new <code> CourseOffering. </code> 
     *
     *  @param object osid_id_Id $courseId the <code> Id </code> of the 
     *          associated <code> Course </code> 
     *  @param object osid_id_Id $termId the <code> Id </code> of the 
     *          associated <code> Term </code> 
     *  @param object osid_course_CourseForm $courseOfferingForm the form for 
     *          this <code> CourseOffering </code> 
     *  @return object osid_course_CourseOffering the new <code> 
     *          CourseOffering </code> 
     *  @throws osid_AlreadyExistsException attempt at duplicating a property 
     *          the underlying system is enforcing to be unique 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NotFoundException <code> courseId </code> or <code> 
     *          termId </code> not found 
     *  @throws osid_NullArgumentException <code> courseId, termId </code> , 
     *          or <code> courseOfferingForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> courseOfferingForm </code> is 
     *          not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createCourseOffering(osid_id_Id $courseId, 
                                         osid_id_Id $termId, 
                                         osid_course_CourseForm $courseOfferingForm);


    /**
     *  Creates a new <code> CourseOffering </code> under an existing <code> 
     *  CourseOffering. </code> The new <code> CourseOffering </code> will 
     *  appear as a child in the <code> CourseOffering </code> hierarchy and 
     *  inherit the <code> Course </code> and <code> Term </code> mappings. 
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> of 
     *          the parent <code> CourseOffering </code> 
     *  @param object osid_course_CourseForm $courseOfferingForm the form for 
     *          this <code> CourseOffering </code> 
     *  @return object osid_course_CourseOffering the new <code> 
     *          CourseOffering </code> 
     *  @throws osid_AlreadyExistsException attempt at duplicating a property 
     *          the underlying system is enforcing to be unique 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NotFoundException <code> courseOfferingId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> or 
     *          <code> courseOfferingForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> courseOfferingForm </code> is 
     *          not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createCourseOfferingSection(osid_id_Id $courseOfferingId, 
                                                osid_course_CourseForm $courseOfferingForm);


    /**
     *  Tests if this user can update <code> CourseOfferings. </code> A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a <code> CourseOffering </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer update operations 
     *  to an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> CourseOffering </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateCourseOfferings();


    /**
     *  Tests if this user can update <code> CourseOfferings. </code> A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a <code> CourseOffering </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer update operations 
     *  to an unauthorized user. 
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> of 
     *          the <code> CourseOffering </code> 
     *  @return boolean <code> false </code> if course modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> courseOfferingId </code> is not found, then it 
     *          is acceptable to return false to indicate the lack of an 
     *          update available. 
     */
    public function canUpdateCourseOffering(osid_id_Id $courseOfferingId);


    /**
     *  Gets the course offering form for updating an existing course 
     *  offering. A new course offering form should be requested for each 
     *  update transaction. 
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> of 
     *          the <code> CourseOffering </code> 
     *  @return object osid_course_CourseOfferingForm the course offering form 
     *  @throws osid_NotFoundException <code> courseOfferingId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingFormForUpdate(osid_id_Id $courseOfferingId);


    /**
     *  Updates an existing course offering. 
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> of 
     *          the <code> CourseOffering </code> 
     *  @param object osid_course_CourseOfferingForm $courseOfferingForm the 
     *          form containing the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> courseOfferingId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> or 
     *          <code> courseForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> courseOfferingForm </code> is 
     *          not supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateCourseOffering(osid_id_Id $courseOfferingId, 
                                         osid_course_CourseOfferingForm $courseOfferingForm);


    /**
     *  Remaps a course offering to another course and term. 
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> of 
     *          the existing <code> CourseOffering </code> 
     *  @param object osid_id_Id $courseId the <code> Id </code> of the new 
     *          associated <code> Course </code> 
     *  @param object osid_id_Id $termId the <code> Id </code> of the new 
     *          associated <code> Term </code> 
     *  @throws osid_NotFoundException <code> courseOfferingId, courseId 
     *          </code> or <code> termId </code> is not found 
     *  @throws osid_NullArgumentException <code> courseOfferingId, courseId 
     *          </code> or <code> termId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function moveCourseOffering(osid_id_Id $courseOfferingId, 
                                       osid_id_Id $courseId, 
                                       osid_id_Id $termId);


    /**
     *  Tests if this user can delete <code> CourseOfferings. </code> A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting a <code> CourseOffering </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer delete operations 
     *  to an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> CourseOffering </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteCourseOfferings();


    /**
     *  Tests if this user can delete a specified <code> CourseOffering. 
     *  </code> A return of true does not guarantee successful authorization. 
     *  A return of false indicates that it is known deleting the <code> 
     *  CourseOffering </code> will result in a <code> PERMISSION_DENIED. 
     *  </code> This is intended as a hint to an application that may opt not 
     *  to offer an delete operation to an unauthorized user for this course. 
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> of 
     *          the <code> CourseOffering </code> 
     *  @return boolean <code> false </code> if deletion of this <code> 
     *          CourseOffering </code> is not authorized, <code> true </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> courseOfferingId </code> is not found, then it 
     *          is acceptable to return false to indicate the lack of an 
     *          delete available. 
     */
    public function canDeleteCourseOffering(osid_id_Id $courseOfferingId);


    /**
     *  Deletes a <code> CourseOffering. </code> 
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> of 
     *          the <code> CourseOffering </code> to remove 
     *  @throws osid_NotFoundException <code> courseOfferingId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteCourseOffering(osid_id_Id $courseOfferingId);


    /**
     *  Adds an <code> Id </code> to a <code> CourseOffering </code> for the 
     *  purpose of creating compatibility. The primary <code> Id </code> of 
     *  the <code> Course </code> is determined by the provider. The new 
     *  <code> Id </code> performs as an alias to the primary <code> Id. 
     *  </code> 
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> of a 
     *          <code> CourseOffering </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> courseOfferingId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> or 
     *          <code> aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToCourseOffering(osid_id_Id $courseOfferingId, 
                                          osid_id_Id $aliasId);

}
