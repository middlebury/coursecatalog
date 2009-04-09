<?php

/**
 * osid_course_Term
 * 
 *     Specifies the OSID definition for osid_course_Term.
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
 *  <p>A <code> Term </code> represents a period of time in which a course is 
 *  offered. </p>
 * 
 * @package org.osid.course
 */
interface osid_course_Term
    extends osid_OsidObject
{


    /**
     *  Gets a display label for this term which may be less formal than the 
     *  display name. 
     *
     *  @return string the term label 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDisplayLabel();


    /**
     *  Gets the start time for this term. 
     *
     *  @return DateTime the start time 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getStartTime();


    /**
     *  Gets the end time for this term. 
     *
     *  @return DateTime the end time 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEndTime();


    /**
     *  Tests if this term has an associated calendar. 
     *
     *  @return boolean <code> true </code> if there is a calendar associated 
     *          with this term, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasCalendar();


    /**
     *  Gets the <code> Calendar </code> <code> Id </code> associated with 
     *  this term. 
     *
     *  @return object osid_id_Id the calendar <code> Id </code> 
     *  @throws osid_IllegalStateException <code> hasCalendar() </code> is 
     *          <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCalendarId();


    /**
     *  Gets the <code> Calendar </code> associated with this term. 
     *
     *  @return object osid_calendaring_Calendar the calendar 
     *  @throws osid_IllegalStateException <code> hasCalendar() </code> is 
     *          <code> false </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCalendar();


    /**
     *  Gets the record corresponding to the given <code> Term </code> record 
     *  <code> Type. </code> This method must be used to retrieve an object 
     *  implementing the requested record interface along with all of its 
     *  ancestor interfaces. The <code> termRecordType </code> may be the 
     *  <code> Type </code> returned in <code> getRecordTypes() </code> or any 
     *  of its parents in a <code> Type </code> hierarchy where <code> 
     *  hasRecordType(termRecordType) </code> is <code> true </code> . 
     *
     *  @param object osid_type_Type $termRecordType the type of term record 
     *          to retrieve 
     *  @return object osid_course_TermRecord the term record 
     *  @throws osid_NullArgumentException <code> termRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> hasRecordType(termRecordType) 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermRecord(osid_type_Type $termRecordType);

}
