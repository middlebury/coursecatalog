<?php

/**
 * osid_repository_SubjectSearchSession
 * 
 *     Specifies the OSID definition for osid_repository_SubjectSearchSession.
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
 *  <p>This session provides methods for searching among <code> Asset </code> 
 *  objects. The search query is constructed using the <code> SubjectQuery 
 *  </code> interface. <code> getSubjectsByQuery() </code> is the basic search 
 *  method and returns a list of <code> Subjects. </code> A more advanced 
 *  search may be performed with <code> getSubjectsBySearch(). </code> It 
 *  accepts a <code> SubjectSearch </code> interface in addition to the query 
 *  interface for the purpose of specifying additional options affecting the 
 *  entire search, such as ordering. <code> getSubjectsBySearch() </code> 
 *  returns a <code> SubjectSearchResults </code> interface that can be used 
 *  to access the resulting <code> SubjectList </code> or be used to perform a 
 *  search within the result set through <code> SubjectSearch. </code> </p> 
 *  
 *  <p> This session defines views that offer differing behaviors for 
 *  searching. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> federated repository view: searches include subjects in 
 *      repositories of which this repository is an ancestor in the repository 
 *      hierarchy </li> 
 *      <li> isolated repository view: searches are restricted to subjects in 
 *      this repository </li> 
 *  </ul>
 *  Subjects may have a record query interface indicated by their respective 
 *  record interface types. The record query interface is accessed via the 
 *  <code> SubjectQuery. </code> </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_SubjectSearchSession
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
     *  Tests if this user can perform <code> Subject </code> searches. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer search operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if search methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canSearchSubjects();


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
     *  restricts lookups to this repository only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedRepositoryView();


    /**
     *  Gets a subject query interface. 
     *
     *  @return object osid_repository_SubjectQuery the subject query 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectQuery();


    /**
     *  Gets a list of <code> Subjects </code> matching the given search 
     *  interface. 
     *
     *  @param object osid_repository_SubjectQuery $subjectQuery the search 
     *          query 
     *  @return object osid_repository_SubjectList the returned <code> 
     *          SubjectList </code> 
     *  @throws osid_NullArgumentException <code> subjectQuery </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> subjectQuery </code> is not 
     *          of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectsByQuery(osid_repository_SubjectQuery $subjectQuery);


    /**
     *  Gets a subject search interface. 
     *
     *  @return object osid_repository_SubjectSearch the subject search 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectSearch();


    /**
     *  Gets a subject search order interface. The <code> SubjectSearchOrder 
     *  </code> is supplied to a <code> SubjectSearch </code> to specify the 
     *  ordering of results. 
     *
     *  @return object osid_repository_SubjectSearchOrder the subject search 
     *          order interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectSearchOrder();


    /**
     *  Gets the search results matching the given search query using the 
     *  given search. 
     *
     *  @param object osid_repository_SubjectQuery $subjectQuery the search 
     *          query 
     *  @param object osid_repository_SubjectSearch $subjectSearch the search 
     *          interface 
     *  @return object osid_repository_SubjectSearchResults the returned 
     *          search results 
     *  @throws osid_NullArgumentException <code> subjectQuery </code> or 
     *          <code> subjectSearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> subjectQuery </code> or 
     *          <code> subjectSearch </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectsBySearch(osid_repository_SubjectQuery $subjectQuery, 
                                        osid_repository_SubjectSearch $subjectSearch);

}
