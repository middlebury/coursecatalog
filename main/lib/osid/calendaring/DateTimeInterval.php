<?php

/**
 * osid_calendaring_DateTimeInterval
 * 
 *     Specifies the OSID definition for osid_calendaring_DateTimeInterval.
 * 
 * Copyright (C) 2002-2008 Massachusetts Institute of Technology. All Rights 
 * Reserved. 
 * 
 *     This Work is being provided by the copyright holder(s) subject to the 
 *     following license. By obtaining, using and/or copying this Work, you 
 *     agree that you have read, understand, and will comply with the 
 *     following terms and conditions. 
 *     
 *     This Work and the information contained herein is provided on an "AS 
 *     IS" basis. The Massachusetts Institute of Technology, the Open 
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
 * @package org.osid.calendaring
 */


/**
 *  <p>The <code> DateTimeInterval </code> interface defines an i_terval 
 *  betwee_ two date times. </p>
 * 
 * @package org.osid.calendaring
 */
interface osid_calendaring_DateTimeInterval
{


    /**
     *  Gets the starting time for this interval. 
     *
     *  @return object osid_calendaring_DateTime the starting time 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getStart();


    /**
     *  Gets the ending time for this interval. The ending time is greater 
     *  than or equal to the starting time. 
     *
     *  @return object osid_calendaring_DateTime the ending time 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEnd();


    /**
     *  Tests if the given time interval is included in this one. A time 
     *  interval is inclusive of this time interval if the start end end times 
     *  of the given interval are completely contained in this one. 
     *
     *  @param object osid_calendaring_DateTimeInterval $interval the interval 
     *          to compare 
     *  @return boolean <code> true </code> if the given time interval is 
     *          included in this one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> interval </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isInclusive(osid_calendaring_DateTimeInterval $interval);


    /**
     *  Tests if the given time interval is exclusive in this one. A time is 
     *  exclsuive of this time interval if there is no overlap between the 
     *  start end end times. 
     *
     *  @param object osid_calendaring_DateTimeInterval $interval the interval 
     *          to compare 
     *  @return boolean <code> true </code> if the given time is exclsuive of 
     *          this one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> interval </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isExclusive(osid_calendaring_DateTimeInterval $interval);


    /**
     *  Tests if the given time interval is equal to this one. A time 
     *  interfaval is equal if the start and end times are also equal. 
     *
     *  @param object osid_calendaring_DateTimeInterval $interval the interval 
     *          to compare 
     *  @return boolean <code> true </code> if the given time interval is 
     *          equal to this one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> interval </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isEqual(osid_calendaring_DateTimeInterval $interval);


    /**
     *  Tests if the given time is included in this time interval. A time is 
     *  inclusive of this time interval if the start time and its granularity 
     *  and uncertainty are completely contained in this interval. 
     *
     *  @param object osid_calendaring_DateTime $time the date time to compare 
     *  @return boolean <code> true </code> if the given time is included in 
     *          this interval, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> time </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isTimeInclusive(osid_calendaring_DateTime $time);


    /**
     *  Tests if the given time is exclusive in this time interval. A time is 
     *  exclusive of this time interval if the start time and its granularity 
     *  and uncertainty are completely outside this interval. 
     *
     *  @param object osid_calendaring_DateTime $time the date time to compare 
     *  @return boolean <code> true </code> if the given time is exclsuive of 
     *          this one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> time </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isTimeExclusive(osid_calendaring_DateTime $time);

}
