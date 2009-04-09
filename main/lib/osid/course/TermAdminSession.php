<?php

/**
 * osid_course_TermAdminSession
 * 
 *     Specifies the OSID definition for osid_course_TermAdminSession.
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
 *  <p>This session creates and removes terms. The data for create and update 
 *  is provided via the <code> TermForm. </code> </p> 
 *  
 *  <p> The view of the administrative methods defined in this session is 
 *  determined by the provider. For an instance of this session where no 
 *  course catalog has been specified, it may not be parallel to the <code> 
 *  TermLookupSession. </code> For example, a default <code> TermLookupSession 
 *  </code> may view the entire course catalog hierarchy while the default 
 *  <code> TermAdminSession </code> uses an isolated <code> CourseCatalog 
 *  </code> to create new <code> Terms </code> or <code> </code> a specific 
 *  course catalog to operate on a predetermined set of <code> Terms. </code> 
 *  Another scenario is a federated provider who does not wish to permit 
 *  administrative operations for the federation unaware. </p>
 * 
 * @package org.osid.course
 */
interface osid_course_TermAdminSession
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
     *  Tests if this user can create <code> Terms. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating a <code> Term </code> will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer create operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Term </code> ceration 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateTerms();


    /**
     *  Tests if this user can create a single <code> Term </code> using the 
     *  desired record types. While <code> CourseManager.getTermRecordTypes() 
     *  </code> can be used to examine which records are supported, this 
     *  method tests which record(s) are required for creating a specific 
     *  <code> Term. </code> Providing an empty array tests if a <code> Term 
     *  </code> can be created with no records. 
     *
     *  @param array $termRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> Term </code> creation 
     *          using the specified record <code> Types </code> is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> termRecordTypes </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateTermWithRecordTypes(array $termRecordTypes);


    /**
     *  Gets the term form for creating new terms. A new form should be 
     *  requested for each create transaction. 
     *
     *  @return object osid_course_TermForm the term form 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermFormForCreate();


    /**
     *  Creates a new <code> Term. </code> 
     *
     *  @param object osid_course_TermForm $termForm the form for this <code> 
     *          Term </code> 
     *  @return object osid_course_Term the new <code> Term </code> 
     *  @throws osid_AlreadyExistsException attempt at duplicating a property 
     *          the underlying system is enforcing to be unique 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> termForm </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> termForm </code> is not of 
     *          this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createTerm(osid_course_TermForm $termForm);


    /**
     *  Tests if this user can update <code> Terms. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a <code> Term </code> will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer update operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Term </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateTerms();


    /**
     *  Tests if this user can update a specified <code> Term. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known updating the <code> Term </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer an update operation 
     *  to an unauthorized user for this <code> term. </code> 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the <code> 
     *          Term </code> 
     *  @return boolean <code> false </code> if term modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> termId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateTerm(osid_id_Id $termId);


    /**
     *  Gets the term form for updating an existing term. A new term form 
     *  should be requested for each update transaction. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the <code> 
     *          Term </code> 
     *  @return object osid_course_TermForm the term form 
     *  @throws osid_NotFoundException <code> termId </code> is not found 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermFormForUpdate(osid_id_Id $termId);


    /**
     *  Updates an existing term. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the <code> 
     *          term </code> 
     *  @param object osid_course_TermForm $termForm the form containing the 
     *          elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> termId </code> is not found 
     *  @throws osid_NullArgumentException <code> termId </code> or <code> 
     *          termForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> termForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateTerm(osid_id_Id $termId, 
                               osid_course_TermForm $termForm);


    /**
     *  Tests if this user can delete <code> Terms. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting a <code> Term </code> will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer delete operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Term </code> deletion 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteTerms();


    /**
     *  Tests if this user can delete a specified <code> Term. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> Term </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer an delete operation 
     *  to an unauthorized user for this term. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the <code> 
     *          Term </code> 
     *  @return boolean <code> false </code> if deletion of this <code> Term 
     *          </code> is not authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> termId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteTerm(osid_id_Id $termId);


    /**
     *  Deletes a <code> Term. </code> 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the <code> 
     *          Term </code> to remove 
     *  @throws osid_NotFoundException <code> termId </code> not found 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteTerm(osid_id_Id $termId);


    /**
     *  Adds an <code> Id </code> to a <code> Term </code> for the purpose of 
     *  creating compatibility. The primary <code> Id </code> of the <code> 
     *  Term </code> is determined by the provider. The new <code> Id </code> 
     *  performs as an alias to the primary <code> Id. </code> 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of a <code> 
     *          Term </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> termId </code> not found 
     *  @throws osid_NullArgumentException <code> termId </code> or <code> 
     *          aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToTerm(osid_id_Id $termId, osid_id_Id $aliasId);

}
