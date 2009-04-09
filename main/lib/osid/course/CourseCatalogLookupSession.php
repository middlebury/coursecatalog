<?php

/**
 * osid_course_CourseCatalogLookupSession
 * 
 *     Specifies the OSID definition for osid_course_CourseCatalogLookupSession.
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
 *  <p>This session provides methods for retrieving <code> CourseCatalog 
 *  </code> objects. The <code> CourseCatalog </code> represents a collection 
 *  of courses, offerings and tems. </p> 
 *  
 *  <p> This session defines views that offer differing behaviors when 
 *  retrieving multiple objects. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete set or is an error condition 
 *      </li> 
 *  </ul>
 *  Generally, the comparative view should be used for most applications as it 
 *  permits operation even if there is data that cannot be accessed. For 
 *  example, a browsing application may only need to examine the <code> 
 *  CourseCatalogs </code> it can access, without breaking execution. However, 
 *  an assessment may only be useful if all <code> CourseCatalogs </code> 
 *  referenced by it are available, and a test-taking application may 
 *  sacrifice some interoperability for the sake of precision. </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseCatalogLookupSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> CourseCatalog </code> lookups. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupCourseCatalog();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeCourseCatalogView();


    /**
     *  A complete view of the <code> CourseCatalog </code> returns is 
     *  desired. Methods will return what is requested or result in an error. 
     *  This view is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryCourseCatalogView();


    /**
     *  Gets the <code> CourseCatalog </code> specified by its <code> Id. 
     *  </code> In plenary mode, the exact <code> Id </code> is found or a 
     *  <code> NOT_FOUND </code> results. Otherwise, the returned <code> 
     *  CourseCatalog </code> may have a different <code> Id </code> than 
     *  requested, such as the case where a duplicate <code> Id </code> was 
     *  assigned to a <code> CourseCatalog </code> and retained for 
     *  compatibility. 
     *
     *  @param object osid_id_Id $courseCatalogId <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_CourseCatalog the course catalog 
     *  @throws osid_NotFoundException <code> courseCatalogId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getCourseCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets a <code> CourseCatalogList </code> corresponding to the given 
     *  <code> IdList. </code> In plenary mode, the returned list contains all 
     *  of the repositories specified in the <code> Id </code> list, in the 
     *  order of the list, including duplicates, or an error results if an 
     *  <code> Id </code> in the supplied list is not found or inaccessible. 
     *  Otherwise, inaccessible <code> CourseCatalogs </code> may be omitted 
     *  from the list and may present the elements in any order including 
     *  returning a unique set. 
     *
     *  @param object osid_id_IdList $courseCatalogIdList the list of <code> 
     *          Ids </code> to rerieve 
     *  @return object osid_course_CourseCatalogList the returned <code> 
     *          CourseCatalog list </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> courseCatalogIdList </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogsByIds(osid_id_IdList $courseCatalogIdList);


    /**
     *  Gets a <code> CourseCatalogList </code> corresponding to the given 
     *  course catalog genus <code> Type </code> which does not include 
     *  repositories of types derived from the specified <code> Type. </code> 
     *  In plenary mode, the returned list contains all known course catalogs 
     *  or an error results. Otherwise, the returned list may contain only 
     *  those course catalogs that are accessible through this session. In 
     *  both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $courseCatalogGenusType a course catalog 
     *          genus type 
     *  @return object osid_course_CourseCatalogList the returned <code> 
     *          CourseCatalog list </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogGenusType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogsByGenusType(osid_type_Type $courseCatalogGenusType);


    /**
     *  Gets a <code> CourseCatalogList </code> corresponding to the given 
     *  course catalog genus <code> Type </code> and include any additional 
     *  course catalogs with genus types derived from the specified <code> 
     *  Type. </code> In plenary mode, the returned list contains all known 
     *  course catalogs or an error results. Otherwise, the returned list may 
     *  contain only those course catalogs that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $courseCatalogGenusType a course catalog 
     *          genus type 
     *  @return object osid_course_CourseCatalogList the returned <code> 
     *          Course Catalog list </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogGenusType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogsByParentGenusType(osid_type_Type $courseCatalogGenusType);


    /**
     *  Gets a <code> CourseCatalogList </code> containing the given course 
     *  catalog record <code> Type. </code> In plenary mode, the returned list 
     *  contains all known course catalogs or an error results. Otherwise, the 
     *  returned list may contain only those course catalogs that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @param object osid_type_Type $courseCatalogRecordType a course catalog 
     *          record type 
     *  @return object osid_course_CourseCatalogList the returned <code> 
     *          CourseCatalog list </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogsByRecordType(osid_type_Type $courseCatalogRecordType);


    /**
     *  Gets all <code> CourseCatalogs. </code> In plenary mode, the returned 
     *  list contains all known course catalogs or an error results. 
     *  Otherwise, the returned list may contain only those course catalogs 
     *  that are accessible through this session. In both cases, the order of 
     *  the set is not specified. 
     *
     *  @return object osid_course_CourseCatalogList a list of <code> 
     *          CourseCatalogs </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogs();

}
