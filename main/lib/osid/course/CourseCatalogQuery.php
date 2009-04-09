<?php

/**
 * osid_course_CourseCatalogQuery
 * 
 *     Specifies the OSID definition for osid_course_CourseCatalogQuery.
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

require_once(dirname(__FILE__)."/../OsidCatalogQuery.php");

/**
 *  <p>This is the query interface for searching course catalogs. Each method 
 *  match specifies an <code> AND </code> term while multiple invocations of 
 *  the same method produce a nested <code> OR. </code> </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseCatalogQuery
    extends osid_OsidCatalogQuery
{


    /**
     *  Sets the course <code> Id </code> for this query to match courses that 
     *  have a related course. 
     *
     *  @param object osid_id_Id $courseId a course <code> Id </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> courseId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCourseId(osid_id_Id $courseId, $match);


    /**
     *  Tests if a <code> CourseQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a course query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseQuery();


    /**
     *  Gets the query interface for a course. Multiple retrievals produce a 
     *  nested <code> OR </code> term. 
     *
     *  @return object osid_course_CourseQuery the course query 
     *  @throws osid_UnimplementedException <code> supportsCourseQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseQuery() </code> is <code> true. </code> 
     */
    public function getCourseQuery();


    /**
     *  Matches courses that have any course. 
     *
     *  @param boolean $match <code> true </code> to match courses with any 
     *          course, <code> false </code> to match courses with no course 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyCourse($match);


    /**
     *  Sets the catalog <code> Id </code> for this query to match courses 
     *  that have a related course offering. 
     *
     *  @param object osid_id_Id $courseOfferingId a course offering <code> Id 
     *          </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCourseOfferingId(osid_id_Id $courseOfferingId, $match);


    /**
     *  Tests if a <code> CourseOfferingQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a course offering query 
     *          interface is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseOfferingQuery();


    /**
     *  Gets the query interface for a course offering. Multiple retrievals 
     *  produce a nested <code> OR </code> term. 
     *
     *  @return object osid_course_CourseOfferingQuery the course offering 
     *          query 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingQuery() </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingQuery() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseOfferingQuery();


    /**
     *  Matches course catalogs that have any course offering. 
     *
     *  @param boolean $match <code> true </code> to match courses with any 
     *          course offering, <code> false </code> to match courses with no 
     *          course offering 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyCourseOffering($match);


    /**
     *  Sets the term <code> Id </code> for this query to match catalogs 
     *  containing to terms. 
     *
     *  @param object osid_id_Id $termId the term <code> Id </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchTermId(osid_id_Id $termId, $match);


    /**
     *  Tests if a <code> TermQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a term query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermQuery();


    /**
     *  Gets the query interface for a term Multiple retrievals produce a 
     *  nested <code> OR </code> term. 
     *
     *  @return object osid_course_TermQuery the term query 
     *  @throws osid_UnimplementedException <code> supportsTermQuery() </code> 
     *          is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermQuery() </code> is <code> true. </code> 
     */
    public function getTermQuery();


    /**
     *  Matches course catalogs that have any term. 
     *
     *  @param boolean $match <code> true </code> to match course catalogs 
     *          with any term, <code> false </code> to match courses with no 
     *          term 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyTerm($match);


    /**
     *  Gets the record query interface corresponding to the given <code> 
     *  CourseCatalog </code> record <code> Type. </code> Multiple record 
     *  retrievals produce a nested <code> OR </code> term. 
     *
     *  @param object osid_type_Type $courseCatalogRecordType a course catalog 
     *          record type 
     *  @return object osid_course_CourseCatalogQueryRecord the course catalog 
     *          query record 
     *  @throws osid_NullArgumentException <code> courseCatalogRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(courseCatalogRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogQueryRecord(osid_type_Type $courseCatalogRecordType);

}
