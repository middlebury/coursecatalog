<?php

/**
 * osid_calendaring_DateTimeResolution
 * 
 *     Specifies the OSID definition for osid_calendaring_DateTimeResolution.
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
 *  This enumeration contains the possible date/time resolutions. 
 */

class osid_calendaring_DateTimeResolution {

    /** ten quattuordecillionth second resolution */
    public static function PLANCKSECOND() {
        return "plancksecond";
    }

    /** one unadecillionth second resolution */
    public static function UNDASECOND() {
        return "undasecond";
    }

    /** one decillionth second resolution */
    public static function VATOSECOND() {
        return "vatosecond";
    }

    /** one nonillionth second resolution */
    public static function WEEBLESECOND() {
        return "weeblesecond";
    }

    /** one octillionth second resolution */
    public static function XOXXOSECOND() {
        return "xoxxosecond";
    }

    /** one septllionth second resolution */
    public static function YOCTOSECOND() {
        return "yoctosecond";
    }

    /** one sextillionth second second resolution */
    public static function ZEPTOSECOND() {
        return "zeptosecond";
    }

    /** one quintillionth second resolution */
    public static function ATTOSECOND() {
        return "attosecond";
    }

    /** one quadrillionth second resolution */
    public static function FEMTOSECOND() {
        return "femtosecond";
    }

    /** one trillionth second resolution */
    public static function PICOSECOND() {
        return "picosecond";
    }

    /** one billionth second resolution */
    public static function NANOSECOND() {
        return "nanosecond";
    }

    /** one millionth second resolution */
    public static function MICROSECOND() {
        return "microsecond";
    }

    /** one thousandth second resolution */
    public static function MILLISECOND() {
        return "millisecond";
    }

    /** second resolution */
    public static function SECOND() {
        return "second";
    }

    /** minute resolution */
    public static function MINUTE() {
        return "minute";
    }

    /** 15 minute resolution */
    public static function QUARTER_HOUR() {
        return "quarter_hour";
    }

    /** 30 minute resolution */
    public static function HALF_HOUR() {
        return "half_hour";
    }

    /** hour resolution */
    public static function HOUR() {
        return "hour";
    }

    /** day resolution */
    public static function DAY() {
        return "day";
    }

    /** week resolution */
    public static function WEEK() {
        return "week";
    }

    /** month resolution */
    public static function MONTH() {
        return "month";
    }

    /** quarter resolution (jan-mar, apr-jun, jul-sep, oct-dec) */
    public static function QUARTER() {
        return "quarter";
    }

    /** season resolution (spring, winter, summer, fall) */
    public static function SEASON() {
        return "season";
    }

    /** yearly resolution */
    public static function YEAR() {
        return "year";
    }

    /** once in a blue moon */
    public static function BLUEMOON() {
        return "bluemoon";
    }

    /** decade resolution */
    public static function DECADE() {
        return "decade";
    }

    /** century resolution */
    public static function CENTURY() {
        return "century";
    }

    /** millenium resolution */
    public static function MILLENNIA() {
        return "millennia";
    }

    /** 100K years resolution */
    public static function GLACIAL() {
        return "glacial";
    }

    /** 1M years resolution */
    public static function EPOCH() {
        return "epoch";
    }

    /** 1B years resolution */
    public static function AEON() {
        return "aeon";
    }

    /** clock is invalid */
    public static function INFINITY() {
        return "infinity";
    }


    public static function values() {
        $ret = array();
        $ref = new ReflectionClass(__CLASS__);
        $properties = $ref->getProperties();
        foreach ($properties as $property)
            $ret[$property->getName()] = $property->getValue();
        return $ret;
    }
}

