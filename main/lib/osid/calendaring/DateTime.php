<?php

/**
 * osid_calendaring_DateTime
 * 
 *     Specifies the OSID definition for osid_calendaring_DateTime.
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
 *  <p>The <code> DateTime </code> interface defines a date and/or time. This 
 *  interface provides a very broad range of dates, describes more or less 
 *  precision, and/or conveys an uncertainty. A number of convenience methods 
 *  for retrieving time elements are available but only those methods covered 
 *  by the specified granularity are valid. </p> 
 *  
 *  <p> A typical example is describing a day where the time isn't known, and 
 *  the event did not occur at midnight. </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       getMillenium() == 2
 *       
 *                   
 *       
 *  </pre>
 *  </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       getCentury() == 18
 *       getYear() == 1776
 *       getMonth() == 7
 *       getDay() == 4
 *       getHour() == 0
 *       getGranularity() == DateTimeResolution.DAY
 *       definesUncertainty() == false
 *       
 *                   
 *       
 *  </pre>
 *  </p> 
 *  
 *  <p> Another example showing that the time is probably 1pm but could have 
 *  been as late as 3pm or early asnoon.. 
 *  <pre>
 *       
 *       
 *       getMillenium() == 3
 *       getCentury() == 21
 *       getYear() == 2008
 *       getMonth() == 3
 *       getDay() == 17
 *       getHour() == 13
 *       getMinute() == 0
 *       getGranularity() == TimeResolution.MINUTE
 *       definesUncertainty() == true
 *       getUncertaintyGranularity() == DateTimeResolution.HOUR
 *       getUncertaintyMinus() == 1
 *       getUncertaintyPlus == 2
 *       
 *                   
 *       
 *  </pre>
 *  </p> 
 *  
 *  <p> An example marking the birth of the universe. 13.73 billion years +/- 
 *  120 million years. The granularity suggests that no more resolution than 
 *  one million years can be inferred from the "clock", making errors in the 
 *  exact number of millennia insignificant. </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       getEpoch() == -13,730
 *       getMillenium() == 0
 *       
 *                   
 *       
 *  </pre>
 *  </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       getCentury() == 0
 *       getYear() == 0
 *       getGranularity() == TimeResolution.EPOCH
 *       definesUncertainty() == true
 *       getUncertaintyGranularity() == DateTimeResolution.EPOCH
 *       getUncertaintyMinus() == 120
 *       getUncertaintyPlus == 120
 *       
 *                   
 *       
 *  </pre>
 *  </p> 
 *  
 *  <p> </p>
 * 
 * @package org.osid.calendaring
 */
interface osid_calendaring_DateTime
{


    /**
     *  Gets the aeon starting from 1. 1B years. 
     *
     *  @return integer the aeon 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAeon();


    /**
     *  Gets the epoch starting from 1.An epoch is 1M years. 
     *
     *  @return integer the millenium 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEpoch();


    /**
     *  Gets the millenium starting from 1. A millenium is 1,000 years. 
     *
     *  @return integer the millenium 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMillenium();


    /**
     *  Gets the century starting from 1. 
     *
     *  @return integer the century 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCentury();


    /**
     *  Gets the year starting from 1. 
     *
     *  @return integer the year 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getYear();


    /**
     *  Gets the month number starting from 1. 
     *
     *  @return integer the month 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMonth();


    /**
     *  Gets the day of the month starting from 1. 
     *
     *  @return integer the day of the month 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDay();


    /**
     *  Gets the hour of the day 0-23. 
     *
     *  @return integer the hour of the day 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHour();


    /**
     *  Gets the minute of the hour 0-59. 
     *
     *  @return integer the minute of the hour 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMinute();


    /**
     *  Gets the second of the minute 0-59. 
     *
     *  @return integer the second of the minute 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSecond();


    /**
     *  Gets the number of milliseconds in this second 0-999. A millisecond is 
     *  one thousandth of a second. 
     *
     *  @return integer the milliseconds of the second 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMilliseconds();


    /**
     *  Gets the number of microseconds of the second 0-999. A microsecond is 
     *  one millionth of a second. 
     *
     *  @return integer the micrseconds of the millisecond 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMicroseconds();


    /**
     *  Gets the number of nanoseconds of the microsecond 0-999. A nanosecond 
     *  is one billionth of a second. 
     *
     *  @return integer the nanoseconds of the microsecond 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNanoseconds();


    /**
     *  Gets the number of picoseconds of the nanosecond 0-999. A picosecond 
     *  is one trillionth of a second. 
     *
     *  @return integer the picoseconds of the nanosecond 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPicoseconds();


    /**
     *  Gets the number of femtoseconds of the picosecond 0-999. A femtosecond 
     *  is one quadrillionth of a second. 
     *
     *  @return integer the femtoseconds of the picosecond 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFemtoseconds();


    /**
     *  Gets the number of attoseconds of the femtoseconds 0-999. An 
     *  attosecond is one quintillionth of a second. 
     *
     *  @return integer the attoseconds of the femtosecond 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAttoseconds();


    /**
     *  Gets the number of zeptoseconds of the attosecond 0-999. A zeptosecond 
     *  is one sextillionth of a second. 
     *
     *  @return integer the zeptoseconds of the attosecond 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getZeptoseconds();


    /**
     *  Gets the number of yoctoseconds of the picosecond 0-999. A yoctosecond 
     *  is one septillionth of a second. This is getting quite small. 
     *
     *  @return integer the yoctoseconds of the yoctosecond 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getYoctoseconds();


    /**
     *  Gets the number of xoxxoseconds of the yoctosecond 0-999. A 
     *  xoxxosecond is one octillionth of a second. We're going with Rudy 
     *  Rucker here. 
     *
     *  @return integer the xoxxoseconds of the yoctosecond 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getXoxxoseconds();


    /**
     *  Gets the number of weebleseconds of the xoxxosecond 0-999. A 
     *  weeblesecond is one nonillionth of a second. 
     *
     *  @return integer the weebleseconds of the xoxxoseconds 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getWeebleseconds();


    /**
     *  Gets the number of vatoseconds of the xoxxosecond 0-999. A vatosecond 
     *  is one decillionth of a second. 
     *
     *  @return integer the vatoseconds of the weeblesecond 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getVatoseconds();


    /**
     *  Gets the number of undaseconds of the vatosecond 0-999. An undasecond 
     *  is one unadecillionth of a second. 
     *
     *  @return integer the undaseconds of the vatosecond 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getUndaseconds();


    /**
     *  Gets the number of Plancks of the vatoseconds. A Planck is 10 
     *  quattuordecillionths of a second. 
     *
     *  @return float the plancks of the undasecond 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPlanckSeconds();


    /**
     *  Gets the granularity of this time. The granularity indicates the 
     *  resolution of the clock. More precision that what is specified in this 
     *  method cannot be inferred from the available data. 
     *
     *  @return object osid_calendaring_DateTimeResolution granularity 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getGranularity();


    /**
     *  Tests if uncertainty is defined for this time. 
     *
     *  @return boolean <code> true </code> if uncertainty is defined, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function definesUncertainty();


    /**
     *  Gets the units of the uncertainty. 
     *
     *  @return object osid_calendaring_DateTimeResolution units of the 
     *          uncertainty 
     *  @throws osid_IllegalStateException <code> definesUncertainty() </code> 
     *          is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getUncertaintyUnits();


    /**
     *  Gets the uncertainty of this time in the negative direction. 
     *
     *  @return integer the uncertainty under this value 
     *  @throws osid_IllegalStateException <code> definesUncertainty() </code> 
     *          is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getUncertaintyMinus();


    /**
     *  Gets the uncertainty of this time in the positive direction. 
     *
     *  @return integer the uncertainty over this value 
     *  @throws osid_IllegalStateException <code> definesUncertainty() </code> 
     *          is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getUncertaintyPlus();


    /**
     *  Tests if the given time is greater than this one. A time is greater if 
     *  its time inclusive of granularity minus uncertainty is greater than 
     *  the other inclusive of its granularity plus its uncertainty. 
     *
     *  @param object osid_calendaring_DateTime $time the time to compare 
     *  @return boolean <code> true </code> if the given time is greater than 
     *          this one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> time </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isGreater(osid_calendaring_DateTime $time);


    /**
     *  Tests if the given time is less than this one. A time is greater if 
     *  its time inclusive of granularity plus uncertainty is less than the 
     *  other inclusive of its granularity minus its uncertainty. 
     *
     *  @param object osid_calendaring_DateTime $time the time to compare 
     *  @return boolean <code> true </code> if the given time is less than 
     *          this one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> time </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isLess(osid_calendaring_DateTime $time);


    /**
     *  Tests if the given time is included in this one. A time is inclusive 
     *  of this time and granularity plus and minus its uncertainty is 
     *  completely contained within this one inclusive of its granulariity and 
     *  uncertainty. 
     *
     *  @param object osid_calendaring_DateTime $time the time to compare 
     *  @return boolean <code> true </code> if the given time is included in 
     *          this one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> time </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isInclusive(osid_calendaring_DateTime $time);


    /**
     *  Tests if the given time is exclusive in this one. A time is exclsuive 
     *  of this time if there is no overlap taking into account granularity 
     *  and uncertainty. 
     *
     *  @param object osid_calendaring_DateTime $time the time to compare 
     *  @return boolean <code> true </code> if the given time is exclsuive of 
     *          this one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> time </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isExclusive(osid_calendaring_DateTime $time);


    /**
     *  Tests if the given time is equal to this one. A time is equal if the 
     *  data, granularity and uncertainty are equal. 
     *
     *  @param object osid_calendaring_DateTime $time the time to compare 
     *  @return boolean <code> true </code> if the given time is equal to this 
     *          one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> time </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isEqual(osid_calendaring_DateTime $time);

}
