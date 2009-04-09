<?php

/**
 * osid_repository_SubjectAdminSession
 * 
 *     Specifies the OSID definition for osid_repository_SubjectAdminSession.
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
 *  <p>This session creates and removes subjects. The data for create and 
 *  update is provided via the <code> SubjectForm. </code> </p> 
 *  
 *  <p> The view of the administrative methods defined in this session is 
 *  determined by the provider. For an instance of this session where no 
 *  repository has been specified, it may not be parallel to the <code> 
 *  SubjectLookupSession. </code> For example, a default <code> 
 *  SubjectLookupSession </code> may view the entire repository hierarchy 
 *  while the default <code> SubjectAdminSession </code> uses an isolated 
 *  <code> Repository </code> to create new <code> Subjects </code> or <code> 
 *  </code> a specific repository to operate on a predetermined set of <code> 
 *  Subjects. </code> Another scenario is a federated provider who does not 
 *  wish to permit administrative operations for the federation unaware. 
 *  Example update: </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       String newDescription = "hello, world!";
 *       if (!session.canUpdateSubject(subjectId)) {
 *           return "cannot update subject";
 *       }
 *       
 *       SubjectForm subjectForm = session.getSubjectFormForUpdate(subjectId);
 *       Metadata metadata = subjectForm.getDescriptionMetadata();
 *       if (metadata.isReadOnly()) {
 *           return "cannot change subject description";
 *       }
 *       
 *       if (metadata.getSyntax() != MetadtaSyntax.STRING) {
 *           return "subject description is the wrong type";
 *       }
 *       
 *       if ((metadata.getMinStringSize() > newDescription.length()) or
 *           (metadata.getMaxStringSize() < newDescription.length())) {
 *           return "subject description too long or too short";
 *       }
 *       
 *       subjectForm.setDescription(newDescription);
 *       if (!form.isValid()) {
 *           return form.getValidationMessage();
 *       }
 *       
 *       session.updateSubject(subjectId, subjectForm);
 *       return "subject updated";
 *       
 *                   
 *       
 *  </pre>
 *  </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_SubjectAdminSession
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
     *  Tests if this user can create <code> Subjects. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating a <code> Subject </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer create operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Subject </code> 
     *          ceration is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateSubjects();


    /**
     *  Tests if this user can create a single <code> Subject </code> using 
     *  the desired record types. While <code> 
     *  RepositoryManager.getSubjectRecordTypes() </code> can be used to 
     *  examine which records are supported, this method tests which record(s) 
     *  are required for creating a specific <code> Subject. </code> Providing 
     *  an empty array tests if a <code> Subject </code> can be created with 
     *  no records. 
     *
     *  @param array $subjectRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> Subject </code> creation 
     *          using the specified record <code> Types </code> is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> subjectrecordTypes </code> 
     *          is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateSubjectWithRecordTypes(array $subjectRecordTypes);


    /**
     *  Gets the subject form for creating new subjects. A new form should be 
     *  requested for each create transaction. 
     *
     *  @return object osid_repository_SubjectForm the subject form 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectFormForCreate();


    /**
     *  Creates a new <code> Subject. </code> 
     *
     *  @param object osid_repository_SubjectForm $subjectForm the form for 
     *          this <code> Subject </code> 
     *  @return object osid_repository_Subject the new <code> Subject </code> 
     *  @throws osid_AlreadyExistsException attempt at duplicating a property 
     *          the underlying system is enforcing to be unique 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> subjectForm </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> subjectForm </code> is not of 
     *          this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createSubject(osid_repository_SubjectForm $subjectForm);


    /**
     *  Tests if this user can update <code> Subjects. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a <code> Subject </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer update operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Subject </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateSubjects();


    /**
     *  Tests if this user can update a specified <code> Subject. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known updating the <code> Subject 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may opt not to offer an 
     *  update operation to an unauthorized user for this <code> Subject. 
     *  </code> 
     *
     *  @param object osid_id_Id $subjectId the <code> Id </code> of the 
     *          <code> Subject </code> 
     *  @return boolean <code> false </code> if subject modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> subjectId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateSubject(osid_id_Id $subjectId);


    /**
     *  Gets the subject form for updating an existing subject. A new subject 
     *  form should be requested for each update transaction. 
     *
     *  @param object osid_id_Id $subjectId the <code> Id </code> of the 
     *          <code> Subject </code> 
     *  @return object osid_repository_SubjectForm the subject form 
     *  @throws osid_NotFoundException <code> subjectId </code> is not found 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectFormForUpdate(osid_id_Id $subjectId);


    /**
     *  Updates an existing subject. 
     *
     *  @param object osid_id_Id $subjectId the <code> Id </code> of the 
     *          <code> Subject </code> 
     *  @param object osid_repository_SubjectForm $subjectForm the form 
     *          containing the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> subjectId </code> is not found 
     *  @throws osid_NullArgumentException <code> subjectId </code> or <code> 
     *          subjectForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> subjectForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateSubject(osid_id_Id $subjectId, 
                                  osid_repository_SubjectForm $subjectForm);


    /**
     *  Tests if this user can delete <code> Subjects. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting a <code> Subject </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer delete operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Subject </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteSubjects();


    /**
     *  Tests if this user can delete a specified <code> Subject. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> Subject 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may opt not to offer an 
     *  delete operation to an unauthorized user for this agent. 
     *
     *  @param object osid_id_Id $subjectId the <code> Id </code> of the 
     *          <code> Subject </code> 
     *  @return boolean <code> false </code> if deletion of this <code> 
     *          Subject </code> is not authorized, <code> true </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> subjectId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteSubject(osid_id_Id $subjectId);


    /**
     *  Deletes a <code> Subject. </code> 
     *
     *  @param object osid_id_Id $subjectId the <code> Id </code> of the 
     *          <code> Subject </code> to remove 
     *  @throws osid_NotFoundException <code> subjectId </code> not found 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteSubject(osid_id_Id $subjectId);


    /**
     *  Adds an <code> Id </code> to a <code> Subject </code> for the purpose 
     *  of creating compatibility. The primary <code> Id </code> of the <code> 
     *  Subject </code> is determined by the provider. The new <code> Id 
     *  </code> performs as an alias to the primary <code> Id. </code> 
     *
     *  @param object osid_id_Id $subjectId the <code> Id </code> of a <code> 
     *          Subject </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> subjectId </code> not found 
     *  @throws osid_NullArgumentException <code> subjectId </code> or <code> 
     *          aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToSubject(osid_id_Id $subjectId, osid_id_Id $aliasId);

}
