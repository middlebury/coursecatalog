<?php

/**
 * osid_course_TermQuery
 * 
 *     Specifies the OSID definition for osid_course_TermQuery.
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

require_once(dirname(__FILE__)."/../OsidQuery.php");

/**
 *  <p>This is the query interface for searching terms. Each method match 
 *  specifies an <code> AND </code> term while multiple invocations of the 
 *  same method produce a nested <code> OR. </code> </p>
 * 
 * @package org.osid.course
 */
interface osid_course_TermQuery
    extends osid_OsidQuery
{


    /**
     *  Adds a display label for this query. 
     *
     *  @param string $label label string to match 
     *  @param object osid_type_Type $stringMatchType the label match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> label </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> label </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchDisplayLabel($label, osid_type_Type $stringMatchType, 
                                      $match);


    /**
     *  Matches a display label that has any value. 
     *
     *  @param boolean $match <code> true </code> to match courses with any 
     *          label, <code> false </code> to match assets with no title 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyDisplayLabel($match);


    /**
     *  Matches terms that include the given time. 
     *
     *  @param DateTime $time date 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchTime($time, $match);


    /**
     *  Matches terms with start and end times between the given range 
     *  inclusive. 
     *
     *  @param DateTime $start start date 
     *  @param DateTime $end end date 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> end </code> is less than 
     *          <code> start </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchTimeInlcuisve($start, $end, $match);


    /**
     *  Matches a term that has any time assigned. 
     *
     *  @param boolean $match <code> true </code> to match terms with any 
     *          time, <code> false </code> to match assets with no time 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyTime($match);


    /**
     *  Sets the calendar <code> Id </code> for this query. 
     *
     *  @param object osid_id_Id $calendarId the calendar <code> Id </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> calendarId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCalendarId(osid_id_Id $calendarId, $match);


    /**
     *  Tests if a <code> CalendarQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a calendar query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCalendarQuery();


    /**
     *  Gets the query interface for a calendar. Multiple retrievals produce a 
     *  nested <code> OR </code> term. 
     *
     *  @return object osid_calendaring_CalendarQuery the calendar query 
     *  @throws osid_UnimplementedException <code> supportsCalendarQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCalendarQuery() </code> is <code> true. </code> 
     */
    public function getCalendarQuery();


    /**
     *  Matches courses that have any course offering. 
     *
     *  @param boolean $match <code> true </code> to match terms with any 
     *          calendar, <code> false </code> to match terms with no calendar 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyCalendar($match);


    /**
     *  Sets the course offering <code> Id </code> for this query to match 
     *  terms assigned to course offerings. 
     *
     *  @param object osid_id_Id $courseOfferingId the course offering <code> 
     *          Id </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
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
     *  Matches courses that have any course offering. 
     *
     *  @param boolean $match <code> true </code> to match terms with any 
     *          course offering, <code> false </code> to match subjects with 
     *          no composition 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyCourseOffering($match);


    /**
     *  Sets the course catalog <code> Id </code> for this query to match 
     *  terms assigned to course catalogs. 
     *
     *  @param object osid_id_Id $courseCatalogId the course catalog <code> Id 
     *          </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCourseCatalogId(osid_id_Id $courseCatalogId, $match);


    /**
     *  Tests if a <code> CourseCatalogQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a course catalog query 
     *          interface is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseCatalogQuery();


    /**
     *  Gets the query interface for a course catalog. Multiple retrievals 
     *  produce a nested <code> OR </code> term. 
     *
     *  @return object osid_course_CourseCatalogQuery the course catalog query 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseCatalogQuery() </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseCatalogQuery() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseCatalogQuery();


    /**
     *  Gets the record query interface corresponding to the given <code> Term 
     *  </code> record <code> Type. </code> Multiple record retrievals produce 
     *  a nested <code> OR </code> term. 
     *
     *  @param object osid_type_Type $termRecordType a term record type 
     *  @return object osid_course_TermQueryRecord the term record 
     *  @throws osid_NullArgumentException <code> termRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> hasRecordType(termRecordType) 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermQueryRecord(osid_type_Type $termRecordType);

}
