<?php

/**
 * osid_course_TermSearchSession
 * 
 *     Specifies the OSID definition for osid_course_TermSearchSession.
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
 *  <p>This session provides methods for searching among <code> Term </code> 
 *  objects. The search query is constructed using the <code> TermQuery 
 *  </code> interface. <code> getTermsByQuery() </code> is the basic search 
 *  method and returns a list of <code> Terms. </code> A more advanced search 
 *  may be performed with <code> getTermsBySearch(). </code> It accepts a 
 *  <code> TermSearch </code> interface in addition to the query interface for 
 *  the purpose of specifying additional options affecting the entire search, 
 *  such as ordering. <code> getTermsBySearch() </code> returns a <code> 
 *  TermsSearchResults </code> interface that can be used to access the 
 *  resulting <code> TermList </code> or be used to perform a search within 
 *  the result set through <code> TermSearch. </code> </p> 
 *  
 *  <p> This session defines views that offer differing behaviors for 
 *  searching. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> federated course catalog view: searches include terms in course 
 *      catalogs of which this course catalog is an ancestor in the course 
 *      catalog hierarchy </li> 
 *      <li> isolated course catalog view: searches are restricted to terms in 
 *      this course catalog </li> 
 *  </ul>
 *  Terms may have a record query interface indicated by their respective 
 *  record interface types. The record query interface is accessed via the 
 *  <code> TermQuery. </code> </p>
 * 
 * @package org.osid.course
 */
interface osid_course_TermSearchSession
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
     *  Tests if this user can perform <code> Term </code> lookups. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may not offer lookup operations to unauthorized 
     *  users. 
     *
     *  @return boolean <code> false </code> if search methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canSearchTerms();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include terms in course catalog which are children of this course 
     *  catalog in the course catalog hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedCourseCatalogView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to this course catalog only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedCourseCatalogView();


    /**
     *  Gets a term query interface. 
     *
     *  @return object osid_course_TermQuery the term query interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermQuery();


    /**
     *  Gets a list of <code> Terms </code> matching the given search 
     *  interface. 
     *
     *  @param object osid_course_TermQuery $termQuery the search query 
     *  @return object osid_course_TermList the returned <code> TermList 
     *          </code> 
     *  @throws osid_NullArgumentException <code> termQuery </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> termQuery </code> is not of 
     *          this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermsByQuery(osid_course_TermQuery $termQuery);


    /**
     *  Gets a term search interface. 
     *
     *  @return object osid_course_TermSearch the term search interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermSearch();


    /**
     *  Gets a term search order interface. The <code> TermSearchOrder </code> 
     *  is supplied to a <code> TermSearch </code> to specify the ordering of 
     *  results. 
     *
     *  @return object osid_course_TermSearchOrder the term search order 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermSearchOrder();


    /**
     *  Gets the search results matching the given search query using the 
     *  given search. 
     *
     *  @param object osid_course_TermQuery $termQuery the term query 
     *  @param object osid_course_TermSearch $termSearch the term search 
     *          interface 
     *  @return object osid_course_TermSearchResults the returned search 
     *          results 
     *  @throws osid_NullArgumentException <code> termQuery </code> or <code> 
     *          termSearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> termQuery </code> or <code> 
     *          termSearch </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermsBySearch(osid_course_TermQuery $termQuery, 
                                     osid_course_TermSearch $termSearch);

}
