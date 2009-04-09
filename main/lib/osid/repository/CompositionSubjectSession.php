<?php

/**
 * osid_repository_CompositionSubjectSession
 * 
 *     Specifies the OSID definition for osid_repository_CompositionSubjectSession.
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
 *  <p>This session defines methods for accessing the subjects of a 
 *  composition. </p> 
 *  
 *  <p> This lookup session defines several views: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete result set or is an error 
 *      condition results </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_CompositionSubjectSession
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
    public function useComparativeCompositionView();


    /**
     *  A complete view of the <code> Composition </code> returns is desired. 
     *  Methods will return what is requested or result in an error. This view 
     *  is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryCompositionView();


    /**
     *  Tests if this user can perform lookups on subjects of compositions. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer lookup operations. 
     *
     *  @return boolean <code> false </code> if lookups are not authorized, 
     *          <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupCompositionSubjects();


    /**
     *  Gets the list of <code> Composition Ids </code> associated with a 
     *  <code> Subject. </code> 
     *
     *  @param object osid_id_Id $subjectId <code> Id </code> of the <code> 
     *          Subject </code> 
     *  @return object osid_id_IdList list of matching asset <code> Ids 
     *          </code> 
     *  @throws osid_NotFoundException <code> subjectId </code> is not found 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionIdsBySubject(osid_id_Id $subjectId);


    /**
     *  Gets the list of <code> Compositions </code> associated with a <code> 
     *  Subject. </code> 
     *
     *  @param object osid_id_Id $subjectId <code> Id </code> of the <code> 
     *          Subject </code> 
     *  @return object osid_repository_CompositionList list of matching 
     *          compositions 
     *  @throws osid_NotFoundException <code> subjectId </code> is not found 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionsBySubject(osid_id_Id $subjectId);


    /**
     *  Gets the list of <code> Composition Ids </code> corresponding to a 
     *  list of <code> Subjects. </code> 
     *
     *  @param object osid_id_IdList $subjectIdList list of subject <code> Ids 
     *          </code> 
     *  @return object osid_id_IdList list of composition <code> Ids </code> 
     *  @throws osid_NullArgumentException <code> subjectIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionIdsBySubjects(osid_id_IdList $subjectIdList);


    /**
     *  Gets the list of <code> Compositions </code> corresponding to a list 
     *  of <code> Subjects. </code> 
     *
     *  @param object osid_id_IdList $subjectIdList list of subject <code> Ids 
     *          </code> 
     *  @return object osid_repository_CompositionList list of compositions 
     *  @throws osid_NullArgumentException <code> subjectIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionsBySubjects(osid_id_IdList $subjectIdList);


    /**
     *  Gets the <code> Subject Ids </code> mapped to a <code> Composition. 
     *  </code> 
     *
     *  @param object osid_id_Id $compositionId <code> Id </code> of a <code> 
     *          Composition </code> 
     *  @return object osid_id_IdList list of subject <code> Ids </code> 
     *  @throws osid_NotFoundException <code> compositionId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectIdsByComposition(osid_id_Id $compositionId);


    /**
     *  Gets the <code> Subjects </code> mapped to a <code> Composition. 
     *  </code> 
     *
     *  @param object osid_id_Id $compositionId <code> Id </code> of a <code> 
     *          Composition </code> 
     *  @return object osid_repository_SubjectList list of subjects 
     *  @throws osid_NotFoundException <code> compositionId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectsByComposition(osid_id_Id $compositionId);

}
