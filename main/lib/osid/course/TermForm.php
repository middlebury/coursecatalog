<?php

/**
 * osid_course_TermForm
 * 
 *     Specifies the OSID definition for osid_course_TermForm.
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
 *  <p>This is the form for creating and updating <code> Terms. </code> Like 
 *  all <code> OsidForm </code> objects, various data elements may be set here 
 *  for use in the create and update methods in the <code> TermAdminSession. 
 *  </code> For each data element that may be set, metadata may be examined to 
 *  provide display hints or data constraints. </p>
 * 
 * @package org.osid.course
 */
interface osid_course_TermForm
    extends osid_OsidForm
{


    /**
     *  Gets the metadata for a display label title. 
     *
     *  @return object osid_Metadata metadata for the display label 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDisplayLabelMetadata();


    /**
     *  Sets the display label. 
     *
     *  @param string $displayLabel the new display label 
     *  @throws osid_InvalidArgumentException <code> display label </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> display label </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setDisplayLabel($displayLabel);


    /**
     *  Removes the display label. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearDisplayLabel();


    /**
     *  Gets the metadata for a tem start date,. 
     *
     *  @return object osid_Metadata metadata for the term start 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getStartMetadata();


    /**
     *  Sets the term start date. 
     *
     *  @param DateTime $start the new term start date 
     *  @throws osid_InvalidArgumentException <code> start </code> is invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> start </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setStart($start);


    /**
     *  Removes the term start date. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearStart();


    /**
     *  Gets the metadata for a tem end date,. 
     *
     *  @return object osid_Metadata metadata for the term end 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEndMetadata();


    /**
     *  Sets the term end date. 
     *
     *  @param DateTime $end the new term end date 
     *  @throws osid_InvalidArgumentException <code> end </code> is invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> end </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setEnd($end);


    /**
     *  Removes the term end date. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearEnd();


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
    public function clearCalendarId();


    /**
     *  Gets the <code> TermFormRecord </code> interface corresponding to the 
     *  given term record interface <code> Type. </code> 
     *
     *  @param object osid_type_Type $termRecordType a term record type 
     *  @return object osid_course_TermFormRecord the record 
     *  @throws osid_NullArgumentException <code> termRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> hasRecordType(termRecordType) 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermFormRecord(osid_type_Type $termRecordType);

}
