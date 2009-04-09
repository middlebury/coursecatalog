<?php

/**
 * osid_course_CourseOfferingLookupSession
 * 
 *     Specifies the OSID definition for osid_course_CourseOfferingLookupSession.
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
 *  <p>This session defines methods for retrieving course offerings. A <code> 
 *  CourseOffering </code> is a scheduled course listed in a course catalog. A 
 *  <code> CourseOffering </code> is derived from a <code> Course </code> and 
 *  maps to an offering time and registered students. </p> 
 *  
 *  <p> This lookup session defines several views: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete result set or is an error 
 *      condition </li> 
 *      <li> isolated course catalog view: All course offering methods in this 
 *      session operate, retrieve and pertain to course offerings defined 
 *      explicitly in the current course catalog. Using an isolated view is 
 *      useful for managing <code> CourseOfferings </code> with the <code> 
 *      CourseOfferingAdminSession. </code> </li> 
 *      <li> federated course catalog view: All course offering lookup methods 
 *      in this session operate, retrieve and pertain to all course offerings 
 *      defined in this course catalog and any other courses implicitly 
 *      available in this course catalog through repository inheritence. </li> 
 *  </ul>
 *  The methods <code> useFederatedCourseCatalogView() </code> and <code> 
 *  useIsolatedCourseCatalogView() </code> behave as a radio group and one 
 *  should be selected before invoking any lookup methods. Courses may have an 
 *  additional records indicated by their respective record types. The record 
 *  may not be accessed through a cast of the <code> Course. </code> </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseOfferingLookupSession
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
     *  Tests if this user can perform <code> CourseOffering </code> lookups. 
     *  A return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may not offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupCourseOfferings();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeCourseOfferingView();


    /**
     *  A complete view of the <code> CourseOffering </code> returns is 
     *  desired. Methods will return what is requested or result in an error. 
     *  This view is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryCourseOfferingView();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include course offerings in catalogs which are children of this 
     *  catalog in the course catalog hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedCourseCatalogView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts retrievals to this course catalog only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedCourseCatalogView();


    /**
     *  Gets the <code> CourseOffering </code> specified by its <code> Id. 
     *  </code> In plenary mode, the exact <code> Id </code> is found or a 
     *  <code> NOT_FOUND </code> results. Otherwise, the returned <code> 
     *  CourseOffering </code> may have a different <code> Id </code> than 
     *  requested, such as the case where a duplicate <code> Id </code> was 
     *  assigned to a <code> Course </code> and retained for compatibility. 
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> of 
     *          the <code> CourseOffering </code> to rerieve 
     *  @return object osid_course_CourseOffering the returned <code> 
     *          CourseOffering </code> 
     *  @throws osid_NotFoundException no <code> CourseOffering </code> found 
     *          with the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOffering(osid_id_Id $courseOfferingId);


    /**
     *  Gets a <code> CourseOfferingList </code> corresponding to the given 
     *  <code> IdList. </code> In plenary mode, the returned list contains all 
     *  of the course offerings specified in the <code> Id </code> list, in 
     *  the order of the list, including duplicates, or an error results if an 
     *  <code> Id </code> in the supplied list is not found or inaccessible. 
     *  Otherwise, inaccessible <code> CourseOfferings </code> may be omitted 
     *  from the list and may present the elements in any order including 
     *  returning a unique set. 
     *
     *  @param object osid_id_IdList $courseOfferingIdList the list of <code> 
     *          Ids </code> to rerieve 
     *  @return object osid_course_CourseOfferingList the returned <code> 
     *          CourseOffering list </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> courseOfferingIdList </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingsByIds(osid_id_IdList $courseOfferingIdList);


    /**
     *  Gets a <code> CourseOfferingList </code> corresponding to the given 
     *  course offering genus <code> Type </code> which does not include 
     *  course offerings of types derived from the specified <code> Type. 
     *  </code> In plenary mode, the returned list contains all known course 
     *  offerings or an error results. Otherwise, the returned list may 
     *  contain only those course offerings that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $courseOfferingGenusType a course 
     *          offering genus type 
     *  @return object osid_course_CourseOfferingList the returned <code> 
     *          CourseOffering list </code> 
     *  @throws osid_NullArgumentException <code> courseOfferingGenusType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingsByGenusType(osid_type_Type $courseOfferingGenusType);


    /**
     *  Gets a <code> CourseOfferingList </code> corresponding to the given 
     *  course genus <code> Type </code> and include any additional course 
     *  offerings with genus types derived from the specified <code> Type. 
     *  </code> In plenary mode, the returned list contains all known course 
     *  offerings or an error results. Otherwise, the returned list may 
     *  contain only those course offerings that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $courseOfferingGenusType a course 
     *          offering genus type 
     *  @return object osid_course_CourseOfferingList the returned <code> 
     *          CourseOffeing list </code> 
     *  @throws osid_NullArgumentException <code> courseOfferingGenusType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingsByParentGenusType(osid_type_Type $courseOfferingGenusType);


    /**
     *  Gets a <code> CourseOfferingList </code> containing the given course 
     *  offering record <code> Type. </code> In plenary mode, the returned 
     *  list contains all known courses or an error results. Otherwise, the 
     *  returned list may contain only those course offerings that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @param object osid_type_Type $courseOfferingRecordType a course 
     *          offering record type 
     *  @return object osid_course_CourseOfferingList the returned <code> 
     *          CourseOfferingList list </code> 
     *  @throws osid_NullArgumentException <code> courseOfferingRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingsByRecordType(osid_type_Type $courseOfferingRecordType);


    /**
     *  Gets all <code> CourseOfferings </code> associated with a given <code> 
     *  Course. </code> In plenary mode, the returned list contains all known 
     *  course offerings or an error results. Otherwise, the returned list may 
     *  contain only those course offerings that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_id_Id $courseId a course <code> Id </code> 
     *  @return object osid_course_CourseOfferingList a list of <code> 
     *          CoursesOfferings </code> 
     *  @throws osid_NullArgumentException <code> courseId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingsForCourse(osid_id_Id $courseId);


    /**
     *  Gets all <code> CourseOfferings </code> associated with a given <code> 
     *  Term. </code> In plenary mode, the returned list contains all known 
     *  course offerings or an error results. Otherwise, the returned list may 
     *  contain only those course offerings that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_id_Id $termId a term <code> Id </code> 
     *  @return object osid_course_CourseOfferingList a list of <code> 
     *          CoursesOfferings </code> 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingsByTerm(osid_id_Id $termId);


    /**
     *  Gets all <code> CourseOfferings </code> associated with a given <code> 
     *  Term </code> and <code> Course. </code> In plenary mode, the returned 
     *  list contains all known course offerings or an error results. 
     *  Otherwise, the returned list may contain only those course offerings 
     *  that are accessible through this session. In both cases, the order of 
     *  the set is not specified. 
     *
     *  @param object osid_id_Id $termId a term <code> Id </code> 
     *  @param object osid_id_Id $courseId a course <code> Id </code> 
     *  @return object osid_course_CourseOfferingList a list of <code> 
     *          CoursesOfferings </code> 
     *  @throws osid_NullArgumentException <code> termId </code> or <code> 
     *          courseId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingsByTermForCourse(osid_id_Id $termId, 
                                                      osid_id_Id $courseId);


    /**
     *  Gets all <code> CourseOfferings. </code> In plenary mode, the returned 
     *  list contains all known course offerings or an error results. 
     *  Otherwise, the returned list may contain only those courses that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specifed. 
     *
     *  @return object osid_course_CourseOfferingList a list of <code> 
     *          CourseOfferings </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferings();

}
