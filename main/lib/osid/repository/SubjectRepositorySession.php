<?php

/**
 * osid_repository_SubjectRepositorySession
 * 
 *     Specifies the OSID definition for osid_repository_SubjectRepositorySession.
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
 *  <p>This session provides methods to retrieve <code> Subject </code> to 
 *  <code> Repository </code> mappings. A <code> Subject </code> may appear in 
 *  multiple <code> Repository </code> objects. Each Repository may have its 
 *  own authorizations governing who is allowed to look at it. </p> 
 *  
 *  <p> This lookup session defines several views: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete result set or is an error 
 *      condition </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_SubjectRepositorySession
    extends osid_OsidSession
{


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeRepositoryView();


    /**
     *  A complete view of the <code> Subject </code> and <code> Repository 
     *  </code> returns is desired. Methods will return what is requested or 
     *  result in an error. This view is used when greater precision is 
     *  desired at the expense of interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryCatalogView();


    /**
     *  Tests if this user can perform lookups of subject/repository mappings. 
     *  A return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known lookup methods in this session 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer lookup operations 
     *  to unauthorized users. 
     *
     *  @return boolean <code> false </code> if looking up mappings is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupSubjectRepositoryMappings();


    /**
     *  Gets the list of <code> Subject Ids </code> associated with a <code> 
     *  Repository. </code> 
     *
     *  @param object osid_id_Id $repositoryId <code> Id </code> of the <code> 
     *          Repository </code> 
     *  @return object osid_id_IdList list of related subject <code> Ids 
     *          </code> 
     *  @throws osid_NotFoundException <code> repositoryId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> repositoryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectIdsByRepository(osid_id_Id $repositoryId);


    /**
     *  Gets the list of <code> Subjects </code> associated with a <code> 
     *  Repository. </code> 
     *
     *  @param object osid_id_Id $repositoryId <code> Id </code> of the <code> 
     *          Repository </code> 
     *  @return object osid_repository_SubjectList list of related subjects 
     *  @throws osid_NotFoundException <code> repositoryId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> repositoryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectsByRepository(osid_id_Id $repositoryId);


    /**
     *  Gets the list of <code> Subject Ids </code> corresponding to a list of 
     *  <code> Repository </code> objects. 
     *
     *  @param object osid_id_IdList $repositoryIdList list of repository 
     *          <code> Ids </code> 
     *  @return object osid_id_IdList list of subject <code> Ids </code> 
     *  @throws osid_NullArgumentException <code> repositoryIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectIdsByRepositories(osid_id_IdList $repositoryIdList);


    /**
     *  Gets the list of <code> Subjects </code> corresponding to a list of 
     *  <code> Repository </code> objects. 
     *
     *  @param object osid_id_IdList $repositoryIdList list of repository 
     *          <code> Ids </code> 
     *  @return object osid_repository_SubjectList list of subjects 
     *  @throws osid_NullArgumentException <code> repositoryIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectsByRepositories(osid_id_IdList $repositoryIdList);


    /**
     *  Gets the <code> Repository </code> <code> Ids </code> mapped to a 
     *  <code> Subject. </code> 
     *
     *  @param object osid_id_Id $subjectId <code> Id </code> of a <code> 
     *          Subject </code> 
     *  @return object osid_id_IdList list of repository <code> Ids </code> 
     *  @throws osid_NotFoundException <code> subjectId </code> is not found 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepositoryIdsBySubject(osid_id_Id $subjectId);


    /**
     *  Gets the <code> Repository </code> objects mapped to a <code> Subject. 
     *  </code> 
     *
     *  @param object osid_id_Id $subjectId <code> Id </code> of a <code> 
     *          Subject </code> 
     *  @return object osid_repository_RepositoryList list of repositories 
     *  @throws osid_NotFoundException <code> subjectId </code> is not found 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepositoriesBySubject(osid_id_Id $subjectId);

}
