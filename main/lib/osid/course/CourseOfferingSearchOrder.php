<?php

/**
 * osid_course_CourseOfferingSearchOrder
 * 
 *     Specifies the OSID definition for osid_course_CourseOfferingSearchOrder.
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

require_once(dirname(__FILE__)."/../OsidSearchOrder.php");

/**
 *  <p>An interface for specifying the ordering of search results. </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseOfferingSearchOrder
    extends osid_OsidSearchOrder
{


    /**
     *  Specifies a preference for ordering the result set by course title. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByTitle();


    /**
     *  Specifies a preference for ordering the result set by course number. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByNumber();


    /**
     *  Specifies a preference for ordering the result set by course credits. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByCredits();


    /**
     *  Specifies a preference for ordering the result set by course 
     *  prerequisite information. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByPrereqInfo();


    /**
     *  Specifies a preference for ordering the result set by course. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByCourse();


    /**
     *  Tests if a course order interface is available. 
     *
     *  @return boolean <code> true </code> if a course order interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseSearchOrder();


    /**
     *  Gets the course order interface. 
     *
     *  @return object osid_course_CourseSearchOrder the course search order 
     *          interface 
     *  @throws osid_UnimplementedException <code> supportsCourseSearchOrder() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseSearchOrder() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseSearchOrder();


    /**
     *  Specifies a preference for ordering the result set by course. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByTerm();


    /**
     *  Tests if a term order interface is available. 
     *
     *  @return boolean <code> true </code> if a term order interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermSearchOrder();


    /**
     *  Gets the term order interface. 
     *
     *  @return object osid_course_TermSearchOrder the term search order 
     *          interface 
     *  @throws osid_UnimplementedException <code> supportsTermSearchOrder() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermSearchOrder() </code> is <code> true. </code> 
     */
    public function getTermSearchOrder();


    /**
     *  Specifies a preference for ordering the result set by location info. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByLocationInfo();


    /**
     *  Specifies a preference for ordering the result set by location. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByLocation();


    /**
     *  Tests if a resource order interface is available for the location. 
     *
     *  @return boolean <code> true </code> if a location order interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLocationSearchOrder();


    /**
     *  Gets the resource order interface for the location. 
     *
     *  @return object osid_resource_ResourceSearchOrder the location search 
     *          order interface 
     *  @throws osid_UnimplementedException <code> 
     *          supportsLocationSearchOrder() </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLocationSearchOrder() </code> is <code> true. 
     *              </code> 
     */
    public function getLocationSearchOrder();


    /**
     *  Specifies a preference for ordering the result set by schedule info. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByScheduleInfo();


    /**
     *  Specifies a preference for ordering the result set by calendar. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByCalendar();


    /**
     *  Tests if a resource order interface is available for the calendar. 
     *
     *  @return boolean <code> true </code> if a calendar order interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCalendarSearchOrder();


    /**
     *  Gets the calendar order interface. 
     *
     *  @return object osid_calendaring_CalendarSearchOrder the calendar 
     *          search order interface 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCalendarSearchOrder() </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCalendarSearchOrder() </code> is <code> true. 
     *              </code> 
     */
    public function getCalendarSearchOrder();


    /**
     *  Specifies a preference for ordering the result set by learning 
     *  objective. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByLearningObjective();


    /**
     *  Tests if a resource order interface is available for the learning 
     *  objective. 
     *
     *  @return boolean <code> true </code> if a learning objective order 
     *          interface is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLearningObjectiveSearchOrder();


    /**
     *  Gets the learning objective order interface. 
     *
     *  @return object osid_learning_ObjectiveSearchOrder the learning 
     *          objective search order interface 
     *  @throws osid_UnimplementedException <code> 
     *          supportsLearningObjectiveSearchOrder() </code> is <code> false 
     *          </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLearningObjectiveSearchOrder() </code> is <code> 
     *              true. </code> 
     */
    public function getLearningObjectiveSearchOrder();


    /**
     *  Specifies a preference for ordering the result set by url. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByURL();


    /**
     *  Gets the course search order record corresponding to the given course 
     *  record <code> Type. </code> Multiple retrievals return the same 
     *  underlying object. 
     *
     *  @param object osid_type_Type $courseRecordType a course record type 
     *  @return object osid_course_CourseSearchOrderRecord the course search 
     *          order record interface 
     *  @throws osid_NullArgumentException <code> courseRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(courseRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseSearchOrderRecord(osid_type_Type $courseRecordType);

}
