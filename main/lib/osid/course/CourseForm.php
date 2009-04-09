<?php

/**
 * osid_course_CourseForm
 * 
 *     Specifies the OSID definition for osid_course_CourseForm.
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
 *  <p>This is the form for creating and updating <code> Courses. </code> Like 
 *  all <code> OsidForm </code> objects, various data elements may be set here 
 *  for use in the create and update methods in the <code> CourseAdminSession. 
 *  </code> For each data element that may be set, metadata may be examined to 
 *  provide display hints or data constraints. </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseForm
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
     *  Gets the <code> CourseFormRecord </code> interface corresponding to 
     *  the given course record interface <code> Type. </code> 
     *
     *  @param object osid_type_Type $courseRecordType a course record type 
     *  @return object osid_course_CourseFormRecord the record 
     *  @throws osid_NullArgumentException <code> courseRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(courseRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseFormRecord(osid_type_Type $courseRecordType);

}
