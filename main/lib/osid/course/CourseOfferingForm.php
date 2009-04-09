<?php

/**
 * osid_course_CourseOfferingForm
 * 
 *     Specifies the OSID definition for osid_course_CourseOfferingForm.
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

require_once(dirname(__FILE__)."/../OsidForm.php");

/**
 *  <p>This is the form for creating and updating <code> CourseOfferings. 
 *  </code> Like all <code> OsidForm </code> objects, various data elements 
 *  may be set here for use in the create and update methods in the <code> 
 *  CourseOfferingAdminSession. </code> For each data element that may be set, 
 *  metadata may be examined to provide display hints or data constraints. 
 *  </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseOfferingForm
    extends osid_OsidForm
{


    /**
     *  Gets the metadata for a course title. 
     *
     *  @return object osid_Metadata metadata for the title 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTitleMetadata();


    /**
     *  Sets the title. 
     *
     *  @param string $title the new title 
     *  @throws osid_InvalidArgumentException <code> title </code> is invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> title </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setTitle($title);


    /**
     *  Removes the title. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearTitle();


    /**
     *  Gets the metadata for a course number. 
     *
     *  @return object osid_Metadata metadata for the course number 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNumberMetadata();


    /**
     *  Sets the course number. 
     *
     *  @param string $number the new course number 
     *  @throws osid_InvalidArgumentException <code> number </code> is invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> number </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setNumber($number);


    /**
     *  Removes the course number. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearNumber();


    /**
     *  Gets the metadata for course credits. 
     *
     *  @return object osid_Metadata metadata for the course credits 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCreditsMetadata();


    /**
     *  Sets the course credits. 
     *
     *  @param float $credits the new course credits 
     *  @throws osid_InvalidArgumentException <code> credits </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setCredits($credits);


    /**
     *  Removes the course credits. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearCredits();


    /**
     *  Gets the metadata for a course prerequisite informational string. 
     *
     *  @return object osid_Metadata metadata for the prerequisite information 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPrereqInfoMetadata();


    /**
     *  Sets the prerequisitie information. 
     *
     *  @param string $prereqInfo the new prerequsite information 
     *  @throws osid_InvalidArgumentException <code> prereqInfo </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> prereqInfo </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setPrereqInfo($prereqInfo);


    /**
     *  Removes the prerequisite information. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearPrereqInfo();


    /**
     *  Gets the metadata for a location informatoin string. 
     *
     *  @return object osid_Metadata metadata for the location information 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLocationInfoMetadata();


    /**
     *  Sets the location information. This information may be derived from a 
     *  separate location resource and not settable here. 
     *
     *  @param string $locationInfo the new location info 
     *  @throws osid_InvalidArgumentException <code> locationInfo </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> locationInfo </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setLocationInfo($locationInfo);


    /**
     *  Removes the location info. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearLocationInfo();


    /**
     *  Gets the metadata for the location resource. 
     *
     *  @return object osid_Metadata metadata for the location resource 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLocationMetadata();


    /**
     *  Sets a location resource. 
     *
     *  @param object osid_id_Id $resourceId the new location 
     *  @throws osid_InvalidArgumentException <code> resourceId </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> resourceId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setLocation(osid_id_Id $resourceId);


    /**
     *  Removes the location. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearLocation();


    /**
     *  Gets the metadata for a schedule informatoin string. 
     *
     *  @return object osid_Metadata metadata for the schedule information 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getScheduleInfoMetadata();


    /**
     *  Sets the schedule information. This information may be derived from 
     *  the calendar and not settable here. 
     *
     *  @param string $scheduleInfo the new schedule info 
     *  @throws osid_InvalidArgumentException <code> scheduleInfo </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> scheduleInfo </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function seScheduleInfo($scheduleInfo);


    /**
     *  Removes the schedule info. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearScheduleInfo();


    /**
     *  Gets the metadata for the calendar. 
     *
     *  @return object osid_Metadata metadata for the calendar 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCalendarMetadata();


    /**
     *  Sets a calendar. 
     *
     *  @param object osid_id_Id $calendarId the new calendar 
     *  @throws osid_InvalidArgumentException <code> calendarId </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> calendarId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setCalendar(osid_id_Id $calendarId);


    /**
     *  Removes the calendar. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearCalendar();


    /**
     *  Gets the metadata for the learning objective. 
     *
     *  @return object osid_Metadata metadata for the learning objective 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLearningObjectiveMetadata();


    /**
     *  Sets a learning objective. 
     *
     *  @param object osid_id_Id $objectiveId the new learning objective 
     *  @throws osid_InvalidArgumentException <code> objectiveId </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> objectiveId </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setLearningObjective(osid_id_Id $objectiveId);


    /**
     *  Removes the learning objective. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearLearningObjective();


    /**
     *  Gets the metadata for a class url. 
     *
     *  @return object osid_Metadata metadata for the url 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getURLMetadata();


    /**
     *  Sets the url for a class web site. 
     *
     *  @param string $url the new url 
     *  @throws osid_InvalidArgumentException <code> url </code> is invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> url </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setURL($url);


    /**
     *  Removes the url. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearURL();


    /**
     *  Gets the <code> CourseOfferingFormRecord </code> interface 
     *  corresponding to the given course offering record interface <code> 
     *  Type. </code> 
     *
     *  @param object osid_type_Type $courseOfferingRecordType a course 
     *          offering record type 
     *  @return object osid_course_CourseOfferingFormRecord the record 
     *  @throws osid_NullArgumentException <code> courseOfferingRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(courseOfferingRecordType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingFormRecord(osid_type_Type $courseOfferingRecordType);

}
