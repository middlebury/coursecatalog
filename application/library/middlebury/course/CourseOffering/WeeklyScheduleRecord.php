<?php

/**
 * Copyright (c) 2009 Middlebury College.
 * 
 *     Permission is hereby granted, free of charge, to any person
 *     obtaining a copy of this software and associated documentation
 *     files (the "Software"), to deal in the Software without
 *     restriction, including without limitation the rights to use,
 *     copy, modify, merge, publish, distribute, sublicesne, and/or
 *     sell copies of the Software, and to permit the persons to whom the
 *     Software is furnished to do so, subject the following conditions:
 *     
 *     The above copyright notice and this permission notice shall be
 *     included in all copies or substantial portions of the Software.
 *     
 *     The Software is provided "AS IS", WITHOUT WARRANTY OF ANY KIND,
 *     EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 *     OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 *     NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 *     HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 *     WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *     OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 *     DEALINGS IN THE SOFTWARE.
 * 
 * @package middlebury.course
 */

/**
 * <p>A record for accessing the weekly schedule of a <code> CourseOffering. </code>
 * The methods specified by the record type are available through the 
 * underlying object. </p>
 * 
 *  The type for this record is:
 *		id namespace:	urn
 *		authority:		middlebury.edu
 *		identifier:		record:weekly_schedule
 *
 * @package middlebury.course
 */
interface middlebury_course_CourseOffering_WeeklyScheduleRecord
    extends osid_course_CourseOfferingRecord
{


    /**
     * Answer true if this CourseOffering meets on Sunday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnSunday ();
    
    /**
     * Answer time the meeting starts on Sunday
     * 
     * @return integer The start-time in seconds from midnight Sunday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnSunday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getSundayStartTime ();
    
    /**
     * Answer time the meeting ends on Sunday
     * 
     * @return integer The end-time in seconds from midnight Sunday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnSunday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getSundayEndTime ();
    
    /**
     * Answer true if this CourseOffering meets on Monday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnMonday ();
    
    /**
     * Answer time the meeting starts on Monday
     * 
     * @return integer The start-time in seconds from midnight Monday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnMonday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getMondayStartTime ();
    
    /**
     * Answer time the meeting ends on Monday
     * 
     * @return integer The end-time in seconds from midnight Monday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnMonday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getMondayEndTime ();
    
    /**
     * Answer true if this CourseOffering meets on Tuesday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnTuesday ();
    
    /**
     * Answer time the meeting starts on Tuesday
     * 
     * @return integer The start-time in seconds from midnight Tuesday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnTuesday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getTuesdayStartTime ();
    
    /**
     * Answer time the meeting ends on Tuesday
     * 
     * @return integer The end-time in seconds from midnight Tuesday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnTuesday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getTuesdayEndTime ();

	/**
     * Answer true if this CourseOffering meets on Wednesday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnWednesday ();
    
    /**
     * Answer time the meeting starts on Wednesday
     * 
     * @return integer The start-time in seconds from midnight Wednesday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnWednesday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getWednesdayStartTime ();
    
    /**
     * Answer time the meeting ends on Wednesday
     * 
     * @return integer The end-time in seconds from midnight Wednesday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnWednesday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getWednesdayEndTime ();
    
    /**
     * Answer true if this CourseOffering meets on Thursday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnThursday ();
    
    /**
     * Answer time the meeting starts on Thursday
     * 
     * @return integer The start-time in seconds from midnight Thursday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnThursday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getThursdayStartTime ();
    
    /**
     * Answer time the meeting ends on Thursday
     * 
     * @return integer The end-time in seconds from midnight Thursday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnThursday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getThursdayEndTime ();
    
    /**
     * Answer true if this CourseOffering meets on Friday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnFriday ();
    
    /**
     * Answer time the meeting starts on Friday
     * 
     * @return integer The start-time in seconds from midnight Friday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnFriday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getFridayStartTime ();
    
    /**
     * Answer time the meeting ends on Friday
     * 
     * @return integer The end-time in seconds from midnight Friday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnFriday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getFridayEndTime ();
    
    /**
     * Answer true if this CourseOffering meets on Saturday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnSaturday ();
    
    /**
     * Answer time the meeting starts on Saturday
     * 
     * @return integer The start-time in seconds from midnight Saturday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnSaturday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getSaturdayStartTime ();
    
    /**
     * Answer time the meeting ends on Saturday
     * 
     * @return integer The end-time in seconds from midnight Saturday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnSaturday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getSaturdayEndTime ();
}
