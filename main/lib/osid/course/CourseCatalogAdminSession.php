<?php

/**
 * osid_course_CourseCatalogAdminSession
 * 
 *     Specifies the OSID definition for osid_course_CourseCatalogAdminSession.
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
 *  <p>This session creates and removes course catalogs. The data for create 
 *  and update is provided via the <code> CourseCatalogForm. </code> </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseCatalogAdminSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can create <code> CourseCatalogs. </code> A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating a <code> CourseCatalog. </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may not wish to offer create operations 
     *  to unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> CourseCatalog </code> 
     *          ceration is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateCourseCatalogs();


    /**
     *  Tests if this user can create a single <code> CourseCatalog </code> 
     *  using the desired record types. While <code> 
     *  CourseManager.getCourseCatalogRecordTypes() </code> can be used to 
     *  examine which records are supported, this method tests which record(s) 
     *  are required for creating a specific <code> CourseCatalog. </code> 
     *  Providing an empty array tests if a <code> CourseCatalog </code> can 
     *  be created with no records. 
     *
     *  @param array $courseCatalogRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> CourseCatalog </code> 
     *          creation using the specified record <code> Types </code> is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> courseCatalogRecordTypes 
     *          </code> is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateCourseCatalogWithRecordTypes(array $courseCatalogRecordTypes);


    /**
     *  Gets the course catalog form for creating new course catalogs. A new 
     *  form should be requested for each create transaction. 
     *
     *  @return object osid_course_CourseCatalogForm the course catalog form 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogFormForCreate();


    /**
     *  Creates a new <code> CourseCatalog. </code> 
     *
     *  @param object osid_course_CourseCatalogForm $courseCatalogForm the 
     *          form for this <code> CourseCatalog </code> 
     *  @return object osid_course_CourseCatalog the new <code> CourseCatalog 
     *          </code> 
     *  @throws osid_AlreadyExistsException attempt at duplicating a property 
     *          the underlying system is enforcing to be unique 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> courseCatalogForm </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> courseCatalogForm </code> is 
     *          not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createCourseCatalog(osid_course_CourseCatalogForm $courseCatalogForm);


    /**
     *  Tests if this user can update <code> CourseCatalogs. </code> A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a <code> CourseCatalog </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may not wish to offer update operations 
     *  to unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> CourseCatalog </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateCourseCatalogs();


    /**
     *  Tests if this user can update a specified <code> CourseCatalog. 
     *  </code> A return of true does not guarantee successful authorization. 
     *  A return of false indicates that it is known updating the <code> 
     *  CourseCatalog </code> will result in a <code> PERMISSION_DENIED. 
     *  </code> This is intended as a hint to an application that may not wish 
     *  to offer update operations to unauthorized users. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return boolean <code> false </code> if course catalog modification is 
     *          not authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> courseCatalogId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateCourseCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the course caalog form for updating an existing course catalog. A 
     *  new course catalog form should be requested for each update 
     *  transaction. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_CourseCatalogForm the course catalog form 
     *  @throws osid_NotFoundException <code> courseCatalogId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogFormForUpdate(osid_id_Id $courseCatalogId);


    /**
     *  Updates an existing course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @param object osid_course_CourseCatalogForm $courseCatalogForm the 
     *          form containing the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> courseCatalogId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> or 
     *          <code> courseCatalogForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> courseCatalogForm </code> is 
     *          not supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateCourseCatalog(osid_id_Id $courseCatalogId, 
                                        osid_course_CourseCatalogForm $courseCatalogForm);


    /**
     *  Tests if this user can delete <code> CourseCatalogs. </code> A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting a <code> CourseCatalog </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may not wish to offer delete operations 
     *  to unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> CourseCatalog </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteCourseCatalogs();


    /**
     *  Tests if this user can delete a specified <code> CourseCatalog. 
     *  </code> A return of true does not guarantee successful authorization. 
     *  A return of false indicates that it is known deleting the <code> 
     *  CourseCatalog </code> will result in a <code> PERMISSION_DENIED. 
     *  </code> This is intended as a hint to an application that may not wish 
     *  to offer delete operations to unauthorized users. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return boolean <code> false </code> if deletion of this <code> 
     *          CourseCatalog </code> is not authorized, <code> true </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> courseCatalogId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteCourseCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Deletes a <code> CourseCatalog. </code> 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> to remove 
     *  @throws osid_NotFoundException <code> courseCatalogId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteCourseCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Adds an <code> Id </code> to a <code> CourseCtaalog </code> for the 
     *  purpose of creating compatibility. The primary <code> Id </code> of 
     *  the <code> COurseCatalog </code> is determined by the provider. The 
     *  new <code> Id </code> performs as an alias to the primary <code> Id. 
     *  </code> 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of a 
     *          <code> CourseCatalog </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> courseCatalogId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> or 
     *          <code> aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToCourseCatalog(osid_id_Id $courseCatalogId, 
                                         osid_id_Id $aliasId);

}
