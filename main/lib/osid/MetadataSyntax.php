<?php

/**
 * osid_MetadataSyntax
 * 
 *     Specifies the OSID definition for osid_MetadataSyntax.
 * 
 * Copyright (C) 2008 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid
 */

/**
 *  This enumeration contains the possible value types. 
 */

class osid_MetadataSyntax {

    /** No value available. */
    public static function NONE() {
        return "none";
    }

    /** A truth value of <code> true </code> or <code> false. </code> */
    public static function BOOLEAN() {
        return "boolean";
    }

    /** A non-negative number supporting a 64-bit value ( <code> 
        0..9,223,372,036,854,775,808 </code> ). <code> Cardinal </code> 
        numbers should be used to represent numbers such as sizes and counters 
        where negative numbers have no meaning. */
    public static function CARDINAL() {
        return "cardinal";
    }

    /** An OSID <code> DateTime. </code> */
    public static function DATETIME() {
        return "datetime";
    }

    /** A signed floating point number supporting a signed significand of 
        range <code> -281,474,976,710,656.. 281,474,976,710,656 </code> and an 
        8-bit exponent ( <code> 1..255 </code> ). */
    public static function FLOAT() {
        return "float";
    }

    /** An OSID <code> Id </code> . */
    public static function ID() {
        return "id";
    }

    /** A number supporting a 64-bit value ( <code> 
        -9,223,372,036,854,775,808.. 9,223,372,036,854,775,808 </code> ). */
    public static function INTEGER() {
        return "integer";
    }

    /** An arbitrary object. */
    public static function OBJECT() {
        return "object";
    }

    /** A string of characters. */
    public static function STRING() {
        return "string";
    }

    /** An OSID <code> Type. </code> */
    public static function TYPE() {
        return "type";
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

