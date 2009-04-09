<?php

/**
 * osid_repository_CompositionSearchSession
 * 
 *     Specifies the OSID definition for osid_repository_CompositionSearchSession.
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
 *  <p>This session provides methods for searching among <code> Composition 
 *  </code> objects. The search query is constructed using the <code> 
 *  CompositionQuery </code> interface. <code> getCompositionsByQuery() 
 *  </code> is the basic search method and returns a list of <code> 
 *  Compositions. </code> A more advanced search may be performed with <code> 
 *  getCompositionsBySearch(). </code> It accepts an <code> Composition 
 *  </code> interface in addition to the query interface for the purpose of 
 *  specifying additional options affecting the entire search, such as 
 *  ordering. <code> getCompositionsBySearch() </code> returns an <code> 
 *  CompositionSearchResults </code> interface that can be used to access the 
 *  resulting <code> Composition </code> or be used to perform a search within 
 *  the result set through <code> CompositionSearch. </code> </p> 
 *  
 *  <p> This session defines views that offer differing behaviors when 
 *  retrieving multiple objects. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> federated repository view: searches include compositions in 
 *      repositories of which this repository is an ancestor in the repository 
 *      hierarchy </li> 
 *      <li> isolated repository view: searches are restricted to subjects in 
 *      this repository </li> 
 *  </ul>
 *  Compositions may have a record query interface indicated by their 
 *  respective record interface types. The record query interface is accessed 
 *  via the <code> CompositionQuery. </code> </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_CompositionSearchSession
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
     *  Tests if this user can perform <code> Composition </code> searches. A 
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
    public function canSearchCompositions();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include compositions in repositories which are children of this 
     *  repository in the repository hierarchy. 
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
     *  Gets a composition query interface. 
     *
     *  @return object osid_repository_CompositionQuery the composition query 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionQuery();


    /**
     *  Gets a list of <code> Compositions </code> matching the given query 
     *  interface. 
     *
     *  @param object osid_repository_CompositionQuery $compositionQuery the 
     *          search query 
     *  @return object osid_repository_CompositionList the returned <code> 
     *          CompositionList </code> 
     *  @throws osid_NullArgumentException <code> compositionQuery </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> compositionQuery </code> is 
     *          not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionsByQuery(osid_repository_CompositionQuery $compositionQuery);


    /**
     *  Gets a composition search interface. 
     *
     *  @return object osid_repository_CompositionSearch the composition 
     *          search interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionSearch();


    /**
     *  Gets a composition search order interface. The <code> 
     *  CompositionSearchOrder </code> is supplied to an <code> 
     *  CompositionSearch </code> to specify the ordering of results. 
     *
     *  @return object osid_repository_CompositionSearchOrder the composition 
     *          search order interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionSearchOrder();


    /**
     *  Gets the search results matching the given search query using the 
     *  given search. 
     *
     *  @param object osid_repository_CompositionQuery $compositionQuery the 
     *          search query 
     *  @param object osid_repository_CompositionSearch $compositionSearch the 
     *          search interface 
     *  @return object osid_repository_CompositionSearchResults the search 
     *          results 
     *  @throws osid_NullArgumentException <code> compositionQuery </code> or 
     *          <code> compositionSearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> compositionQuery </code> or 
     *          <code> compositionSearch </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionsBySearch(osid_repository_CompositionQuery $compositionQuery, 
                                            osid_repository_CompositionSearch $compositionSearch);

}
