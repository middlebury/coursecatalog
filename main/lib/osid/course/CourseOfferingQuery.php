<?php

/**
 * osid_course_CourseOfferingQuery
 * 
 *     Specifies the OSID definition for osid_course_CourseOfferingQuery.
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
 *  <p>This is the query interface for searching course offerings. Each method 
 *  match specifies an <code> AND </code> term while multiple invocations of 
 *  the same method produce a nested <code> OR. </code> </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseOfferingQuery
    extends osid_OsidQuery
{


    /**
     *  Adds a title for this query. 
     *
     *  @param string $title title string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> title </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> title </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchTitle($title, osid_type_Type $stringMatchType, $match);


    /**
     *  Matches a title that has any value. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any title, <code> false </code> to match course offerings 
     *          with no title 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyTitle($match);


    /**
     *  Adds a course number for this query. 
     *
     *  @param string $number course number string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> number </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> number </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchNumber($number, osid_type_Type $stringMatchType, 
                                $match);


    /**
     *  Matches a course number that has any value. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any number, <code> false </code> to match course 
     *          offerings with no number 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyNumber($match);


    /**
     *  Matches courses with credits between the given numbers inclusive. 
     *
     *  @param float $min low number 
     *  @param float $max high number 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> max </code> is less than 
     *          <code> min </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCredits($min, $max, $match);


    /**
     *  Matches a course that has any credits assigned. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any credits, <code> false </code> to match course 
     *          offerings with no credits 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyCredits($match);


    /**
     *  Matches courses with the prerequisites informational string. 
     *
     *  @param string $prereqInfo prerequisite informational string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> prereqInfo </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> prereqInfo </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchPrereqInfo($prereqInfo, 
                                    osid_type_Type $stringMatchType, $match);


    /**
     *  Matches a course that has any prerequisite information assigned. 
     *
     *  @param boolean $match <code> true </code> to match courses with any 
     *          prerequisite information, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyPrereqInfo($match);


    /**
     *  Sets the course <code> Id </code> for this query to match courses 
     *  offerings that have a related course. 
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
     *  Sets the term <code> Id </code> for this query to match courses 
     *  offerings that have a related term. 
     *
     *  @param object osid_id_Id $termId a term <code> Id </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
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
     *  Gets the query interface for a term. Multiple retrievals produce a 
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
     *  Adds a location informational string for this query. 
     *
     *  @param string $locationInfo location string string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> locationInfo </code> not 
     *          of <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> locationInfo </code> or 
     *          <code> stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchLocationInfo($locationInfo, 
                                      osid_type_Type $stringMatchType, $match);


    /**
     *  Matches a location informational string that has any value. 
     *
     *  @param boolean $match <code> true </code> to match courses offerings 
     *          with any location string, <code> false </code> to match course 
     *          offerings with no location string 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyLocationInfo($match);


    /**
     *  Sets the location resource <code> Id </code> for this query to match 
     *  courses offerings that have a related location resource. 
     *
     *  @param object osid_id_Id $resourceId a location resource <code> Id 
     *          </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> locationId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchLocationId(osid_id_Id $resourceId, $match);


    /**
     *  Tests if a <code> ResourceQuery </code> is available for the location. 
     *
     *  @return boolean <code> true </code> if a resource query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLocationQuery();


    /**
     *  Gets the query interface for a location resource. Multiple retrievals 
     *  produce a nested <code> OR </code> term. 
     *
     *  @return object osid_resource_ResourceQuery the resource query 
     *  @throws osid_UnimplementedException <code> supportsLocationQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLocationQuery() </code> is <code> true. </code> 
     */
    public function getLocationQuery();


    /**
     *  Matches any location resource. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any location, <code> false </code> to match course 
     *          offerings with no location 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyLocation($match);


    /**
     *  Adds a schedule informational string for this query. 
     *
     *  @param string $scheduleInfo schedule string string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> scheduleInfo </code> not 
     *          of <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> scheduleInfo </code> or 
     *          <code> stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchScheduleInfo($scheduleInfo, 
                                      osid_type_Type $stringMatchType, $match);


    /**
     *  Matches a schedule informational string that has any value. 
     *
     *  @param boolean $match <code> true </code> to match courses offerings 
     *          with any schedule string, <code> false </code> to match course 
     *          offerings with no schedule string 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyScheduleInfo($match);


    /**
     *  Sets the calendar <code> Id </code> for this query to match courses 
     *  offerings that have a related calendar. 
     *
     *  @param object osid_id_Id $calendarId a calendar <code> Id </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> calendarId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCalendarId(osid_id_Id $calendarId, $match);


    /**
     *  Tests if a <code> CalendarQuery </code> is available for the location. 
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
     *  Matches any calendar resource. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any calendar, <code> false </code> to match course 
     *          offerings with no calendar 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyCalendar($match);


    /**
     *  Sets the course catalog <code> Id </code> for this query to match 
     *  courses offerings assigned to a learning objecive. 
     *
     *  @param object osid_id_Id $learningObjectiveId a learning objective 
     *          <code> Id </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> learningObjectiveId </code> 
     *          is <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchLearningObjectiveId(osid_id_Id $learningObjectiveId, 
                                             $match);


    /**
     *  Tests if a <code> LearningObjective </code> is available for the 
     *  location. 
     *
     *  @return boolean <code> true </code> if a learning objective query 
     *          interface is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLearningObjectiveQuery();


    /**
     *  Gets the query interface for a learning objective. Multiple retrievals 
     *  produce a nested <code> OR </code> term. 
     *
     *  @return object osid_learning_ObjectiveQuery the learning objective 
     *          query 
     *  @throws osid_UnimplementedException <code> 
     *          supportsLearningObjectiveQuery() </code> is <code> false 
     *          </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLearningObjectiveQuery() </code> is <code> true. 
     *              </code> 
     */
    public function getLearningObjectiveQuery();


    /**
     *  Matches any learning objective. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any learning objective, <code> false </code> to match 
     *          course offerings with no learning objective 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyLearningObjective($match);


    /**
     *  Sets the course catalog <code> Id </code> for this query to match 
     *  course offerings assigned to course catalogs. 
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
     *  Adds a class url for this query. 
     *
     *  @param string $url url string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> url </code> not of <code> 
     *          stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> url </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchURL($url, osid_type_Type $stringMatchType, $match);


    /**
     *  Matches a url that has any value. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any url, <code> false </code> to match course offerings 
     *          with no url 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyURL($match);


    /**
     *  Gets the record query interface corresponding to the given <code> 
     *  CourseOffering </code> record <code> Type. </code> Multiple record 
     *  retrievals produce a nested <code> OR </code> term. 
     *
     *  @param object osid_type_Type $courseOfferingRecordType a course 
     *          offering record type 
     *  @return object osid_course_CourseOfferingQueryRecord the course 
     *          offering query record 
     *  @throws osid_NullArgumentException <code> courseOfferingRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(courseOfferingRecordType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingQueryRecord(osid_type_Type $courseOfferingRecordType);

}
