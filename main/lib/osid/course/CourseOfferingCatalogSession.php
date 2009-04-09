<?php

/**
 * osid_course_CourseOfferingCatalogSession
 * 
 *     Specifies the OSID definition for osid_course_CourseOfferingCatalogSession.
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
 *  <p>This session provides methods to retrieve <code> CourseOffering </code> 
 *  to <code> CourseCatalog </code> mappings. A <code> CourseOffering </code> 
 *  may appear in multiple <code> CourseCatalog </code> objects. Each catalog 
 *  may have its own authorizations governing who is allowed to look at it. 
 *  </p> 
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
 * @package org.osid.course
 */
interface osid_course_CourseOfferingCatalogSession
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
    public function useComparativeCourseOfferingCatalogView();


    /**
     *  A complete view of the <code> CourseOffering </code> and <code> 
     *  CourseCatalog </code> returns is desired. Methods will return what is 
     *  requested or result in an error. This view is used when greater 
     *  precision is desired at the expense of interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryCourseOfferingCatalogView();


    /**
     *  Tests if this user can perform lookups of course offering/course 
     *  catalog mappings. A return of true does not guarantee successful 
     *  authorization. A return of false indicates that it is known lookup 
     *  methods in this session will result in a <code> PERMISSION_DENIED. 
     *  </code> This is intended as a hint to an application that may opt not 
     *  to offer lookup operations to unauthorized users. 
     *
     *  @return boolean <code> false </code> if looking up mappings is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupCourseOfferingCatalogMappings();


    /**
     *  Gets the list of <code> CourseOffering Ids </code> associated with a 
     *  <code> CourseCatalog. </code> 
     *
     *  @param object osid_id_Id $courseCatalogId <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_id_IdList list of related course offering <code> 
     *          Ids </code> 
     *  @throws osid_NotFoundException <code> courseCatalogId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingIdsByCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the list of <code> CourseOfferings </code> associated with a 
     *  <code> CourseCatalog. </code> 
     *
     *  @param object osid_id_Id $courseCatalogId <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_CourseOfferingList list of related course 
     *          offerings 
     *  @throws osid_NotFoundException <code> courseCatalogId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingsByCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the list of <code> CourseOffering Ids </code> corresponding to a 
     *  list of <code> CourseCatalog </code> objects. 
     *
     *  @param object osid_id_IdList $courseCatalogIdList list of course 
     *          catalog <code> Ids </code> 
     *  @return object osid_id_IdList list of course offering <code> Ids 
     *          </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogIdList </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingIdsByCatalogs(osid_id_IdList $courseCatalogIdList);


    /**
     *  Gets the list of <code> CourseOfferings </code> corresponding to a 
     *  list of <code> CourseCatalog </code> objects. 
     *
     *  @param object osid_id_IdList $courseCatalogIdList list of course 
     *          catalog <code> Ids </code> 
     *  @return object osid_course_CourseOfferingList list of course offerings 
     *  @throws osid_NullArgumentException <code> courseCatalogIdList </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingsByCatalogs(osid_id_IdList $courseCatalogIdList);


    /**
     *  Gets the <code> CourseCatalog </code> <code> Ids </code> mapped to a 
     *  <code> CourseOffering. </code> 
     *
     *  @param object osid_id_Id $courseOfferingId <code> Id </code> of a 
     *          <code> CourseOffering </code> 
     *  @return object osid_id_IdList list of course catalogs 
     *  @throws osid_NotFoundException <code> courseOfferingId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogIdsByCourseOffering(osid_id_Id $courseOfferingId);


    /**
     *  Gets the <code> CourseCatalog </code> objects mapped to a <code> 
     *  CourseOffering. </code> 
     *
     *  @param object osid_id_Id $courseOfferingId <code> Id </code> of a 
     *          <code> CourseOffering </code> 
     *  @return object osid_course_CourseCatalogList list of course catalogs 
     *  @throws osid_NotFoundException <code> courseOfferingId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogsByCourseOffering(osid_id_Id $courseOfferingId);

}
