<?php

/**
 * osid_course_CourseCatalogSearchSession
 * 
 *     Specifies the OSID definition for osid_course_CourseCatalogSearchSession.
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
 *  <p>This session provides methods for searching among <code> CourseCatalog 
 *  </code> objects. The search query is constructed using the <code> 
 *  CourseCatalogQuery </code> interface. <code> getCourseCatalogsByQuery() 
 *  </code> is the basic search method and returns a list of <code> 
 *  CourseCatalogs. </code> A more advanced search may be performed with 
 *  <code> getCourseCatalogsBySearch(). </code> It accepts a <code> 
 *  CourseCatalogSearch </code> interface in addition to the query interface 
 *  for the purpose of specifying additional options affecting the entire 
 *  search, such as ordering. <code> getCourseCatalogsBySearch() </code> 
 *  returns a <code> CourseCatalogSearchResults </code> interface that can be 
 *  used to access the resulting <code> CourseCatalogList </code> or be used 
 *  to perform a search within the result set through <code> 
 *  CourseCatalogSearch. </code> </p> 
 *  
 *  <p> This session defines views that offer differing behaviors for 
 *  searching. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> federated course catalog view: searches include course catalogs 
 *      in course catalogs of which this course catalog is an ancestor in the 
 *      course catalog hierarchy </li> 
 *      <li> isolated course catalog view: searches are restricted to course 
 *      catalogs in this course catalog </li> 
 *  </ul>
 *  Course catalogs may have a record query interface indicated by their 
 *  respective record interface types. The record query interface is accessed 
 *  via the <code> CourseCatalogQuery. </code> </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseCatalogSearchSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> CourseCatalog </code> lookups. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may not offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if search methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canSearchCourseCatalogs();


    /**
     *  Gets a course catalog query interface. 
     *
     *  @return object osid_course_CourseCatalogQuery the course catalog query 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogQuery();


    /**
     *  Gets a list of <code> CourseCatalogs </code> matching the given search 
     *  interface. 
     *
     *  @param object osid_course_CourseCatalogQuery $courseCatalogQuery the 
     *          search query 
     *  @return object osid_course_CourseCatalogList the returned <code> 
     *          CourseCatalogList </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogQuery </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> courseCatalogQuery </code> is 
     *          not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogsByQuery(osid_course_CourseCatalogQuery $courseCatalogQuery);


    /**
     *  Gets a course catalog search interface. 
     *
     *  @return object osid_course_CourseCatalogSearch the course catalog 
     *          search interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogSearch();


    /**
     *  Gets a course catalog search order interface. The <code> 
     *  CourseCatalogSearchOrder </code> is supplied to a <code> 
     *  CourseCatalogSearch </code> to specify the ordering of results. 
     *
     *  @return object osid_course_CourseCatalogSearchOrder the course catalog 
     *          search order interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogSearchOrder();


    /**
     *  Gets the search results matching the given search query using the 
     *  given search. 
     *
     *  @param object osid_course_CourseCatalogQuery $courseCatalogQuery the 
     *          course catalog query 
     *  @param object osid_course_CourseCatalogSearch $courseCatalogSearch the 
     *          course catalog search interface 
     *  @return object osid_course_CourseCatalogSearchResults the returned 
     *          search results 
     *  @throws osid_NullArgumentException <code> courseCatalogQuery </code> 
     *          or <code> courseCatalogSearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> courseCatalogQuery </code> or 
     *          <code> courseCatalogSearch </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogsBySearch(osid_course_CourseCatalogQuery $courseCatalogQuery, 
                                              osid_course_CourseCatalogSearch $courseCatalogSearch);

}
