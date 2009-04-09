<?php

/**
 * osid_course_CourseOffering
 * 
 *     Specifies the OSID definition for osid_course_CourseOffering.
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

require_once(dirname(__FILE__)."/../OsidObject.php");

/**
 *  <p>A <code> CourseOffering </code> represents a learning unit offered 
 *  duing a <code> Term. </code> A <code> Course </code> is instantiated at a 
 *  time and place through the creation of a <code> CourseOffering. </code> 
 *  </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseOffering
    extends osid_OsidObject
{


    /**
     *  Gets the formal title of this course. It may be the same as the 
     *  display name or it may be used to more formally label the course. A 
     *  display name might be Physics 102 where the title is Introduction to 
     *  Electromagentism. 
     *
     *  @return string the course title 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTitle();


    /**
     *  Gets the course number which is a label generally used to indedx the 
     *  course in a catalog, such as T101 or 16.004. 
     *
     *  @return string the course number 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNumber();


    /**
     *  Gets the number of credits in this course. 
     *
     *  @return float the number of credits 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCredits();


    /**
     *  Gets the an informational string for the course prerequisites. 
     *
     *  @return string the prerequisites 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPrereqInfo();


    /**
     *  Gets the canonical course <code> Id </code> associated with this 
     *  course offering. 
     *
     *  @return object osid_id_Id the course <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseId();


    /**
     *  Gets the canonical course associated with this course offering. 
     *
     *  @return object osid_course_Course the course 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourse();


    /**
     *  Gets the <code> Id </code> of the <code> Term </code> of this 
     *  offering. 
     *
     *  @return object osid_id_Id the <code> Term </code> <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermId();


    /**
     *  Gets the <code> Term </code> of this offering. 
     *
     *  @return object osid_course_Term the term 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTerm();


    /**
     *  Gets a string describing the location of this course offering. 
     *
     *  @return string location info 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLocationInfo();


    /**
     *  Tests if this course offering has an associated location resource. 
     *
     *  @return boolean <code> true </code> if this course offering has a 
     *          location resource, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasLocation();


    /**
     *  Gets the <code> Id </code> of the <code> Resource </code> representing 
     *  the location of this course offering. 
     *
     *  @return object osid_id_Id the location 
     *  @throws osid_IllegalStateException <code> hasLocation() </code> is 
     *          <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLocationId();


    /**
     *  Gets the <code> Resource </code> representing the location of this 
     *  offering. 
     *
     *  @return object osid_resource_Resource the location 
     *  @throws osid_IllegalStateException <code> hasLocation() </code> is 
     *          <code> false </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLocation();


    /**
     *  Gets a string describing the schedule of this course offering. 
     *
     *  @return string schedule info 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getScheduleInfo();


    /**
     *  Tests if this course offering has an associated calendar. 
     *
     *  @return boolean <code> true </code> if this course offering has a 
     *          calendar, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasCalendar();


    /**
     *  Gets the calendar for this course offering. Schedule items are 
     *  associated with this calendar through the available Scheduling 
     *  manager. 
     *
     *  @return object osid_id_Id <code> Id </code> of a <code> </code> 
     *          calendar 
     *  @throws osid_IllegalStateException <code> hasCalendar() </code> is 
     *          <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCalendarId();


    /**
     *  Gets the calendar for this course offering, which may be a root in a 
     *  calendar hierarchy. 
     *
     *  @return object osid_calendaring_Calendar a calendar 
     *  @throws osid_IllegalStateException <code> hasCalendar() </code> is 
     *          <code> false </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCalendar();


    /**
     *  Tests if this course offering has an associated learning objective. 
     *
     *  @return boolean <code> true </code> if this course offering has a 
     *          learning objective, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasLearningObjective();


    /**
     *  Gets the root node of a learning objective map for this course 
     *  offering. 
     *
     *  @return object osid_id_Id <code> Id </code> of a <code> l </code> 
     *          earning <code> Objective </code> 
     *  @throws osid_IllegalStateException <code> hasLearningObjective() 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getLearningObjectiveId();


    /**
     *  Gets the root node of a learning objective map for this course 
     *  offering. 
     *
     *  @return object osid_learning_Objective the returned learning <code> 
     *          Objective </code> 
     *  @throws osid_IllegalStateException <code> hasLearningObjective() 
     *          </code> is <code> false </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLearningObjective();


    /**
     *  Gets an external resource, such as a class web site, associated with 
     *  this offering. 
     *
     *  @return string a URL string 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getURL();


    /**
     *  Gets the record corresponding to the given <code> CourseOffering 
     *  </code> record <code> Type. </code> This method must be used to 
     *  retrieve an object implementing the requested record interface along 
     *  with all of its ancestor interfaces. The <code> 
     *  courseOfferingRecordType </code> may be the <code> Type </code> 
     *  returned in <code> getRecordTypes() </code> or any of its parents in a 
     *  <code> Type </code> hierarchy where <code> 
     *  hasRecordType(courseOfferingRecordType) </code> is <code> true </code> 
     *  . 
     *
     *  @param object osid_type_Type $courseOfferingRecordType the type of 
     *          course offering record to retrieve 
     *  @return object osid_course_CourseOfferingRecord the course offering 
     *          record 
     *  @throws osid_NullArgumentException <code> courseOfferingRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(courseOfferingRecordType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingRecord(osid_type_Type $courseOfferingRecordType);

}
