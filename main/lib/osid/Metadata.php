<?php

/**
 * osid_Metadata
 * 
 *     Specifies the OSID definition for osid_Metadata.
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
 *  <p>The <code> Metadata </code> interface defines a set of methods 
 *  describing a the syntax and rules for updating a data element or property 
 *  inside an OSID object. This interface provides a means to retrieve special 
 *  restrictions placed upon data elements such as sizes and ranges that may 
 *  vary from provider to provider or from object to object. </p>
 * 
 * @package org.osid
 */
interface osid_Metadata
{


    /**
     *  Gets instructions for updating this data. This is a human readable 
     *  description of the data element or property that may include special 
     *  instructions or caveats to the end-user above and beyond what this 
     *  interface provides. 
     *
     *  @return string instructions 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getInstructions();


    /**
     *  Tests if this data element is required for creating new objects. 
     *
     *  @return boolean <code> true </code> if this data is required, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isRequired();


    /**
     *  Tests if this data element is has a value. 
     *
     *  @return boolean <code> true </code> if this data has been set, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasValue();


    /**
     *  Tests if this data can be updated. This may indicate the result of a 
     *  pre-authorization but is not a guarantee that an authorization failure 
     *  will not occur when the create or update transaction is issued. 
     *
     *  @return boolean <code> true </code> if this data is not updatable, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isReadOnly();


    /**
     *  Gets the syntax of this data. 
     *
     *  @return object osid_MetadataSyntax an enumeration indicating the 
     *          <code> </code> type of value 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSyntax();


    /**
     *  Gets the units of this data for display purposes ('lbs', 'gills', 
     *  'furlongs'). 
     *
     *  @return string the display units of this data or an empty string if 
     *          not applicable 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getUnits();


    /**
     *  Gets the minimum cardinal value. 
     *
     *  @return integer the minimum value 
     *  @throws osid_IllegalStateException syntax is not a <code> CARDINAL 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMinCardinal();


    /**
     *  Gets the maximum cardinal value. 
     *
     *  @return integer the maximum value 
     *  @throws osid_IllegalStateException syntax is not a <code> CARDINAL 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMaxCardinal();


    /**
     *  Gets the set of acceptable cardinal values. 
     *
     *  @return array of integers the set of values 
     *  @throws osid_IllegalStateException syntax is not a <code> CARDINAL 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCardinalSet();


    /**
     *  Gets the minimum date value. 
     *
     *  @return object osid_calendaring_DateTime the minimum value 
     *  @throws osid_IllegalStateException syntax is not a <code> DATETIME 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMinDateTime();


    /**
     *  Gets the maximum date value. 
     *
     *  @return object osid_calendaring_DateTime the maximum value 
     *  @throws osid_IllegalStateException syntax is not a <code> DATETIME 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMaxDateTime();


    /**
     *  Gets the set of acceptable date time values. 
     *
     *  @return array of osid_calendaring_DateTime objects  the set of values 
     *  @throws osid_IllegalStateException syntax is not a <code> DATETIME 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDateTimeSet();


    /**
     *  Gets the resolution of the date time value. 
     *
     *  @return object osid_calendaring_DateTimeResolution the resolution 
     *  @throws osid_IllegalStateException syntax is not a <code> DATETIME 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDateTimeResolution();


    /**
     *  Gets the minimum float value. 
     *
     *  @return float the minimum value 
     *  @throws osid_IllegalStateException syntax is not a <code> FLOAT 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMinFloat();


    /**
     *  Gets the maximum float value. 
     *
     *  @return float the maximum float 
     *  @throws osid_IllegalStateException syntax is not a <code> FLOAT 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMaxFloat();


    /**
     *  Gets the set of acceptable float values. 
     *
     *  @return array of floats the set of values 
     *  @throws osid_IllegalStateException syntax is not a <code> FLOAT 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFloatSet();


    /**
     *  Gets the minimum integer value. 
     *
     *  @return integer the minimum value 
     *  @throws osid_IllegalStateException syntax is not an <code> INTEGER 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMinInteger();


    /**
     *  Gets the maximum integer value. 
     *
     *  @return integer the maximum value 
     *  @throws osid_IllegalStateException syntax is not an <code> INTEGER 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMaxInteger();


    /**
     *  Gets the set of acceptable integer values. 
     *
     *  @return array of integers the set of values 
     *  @throws osid_IllegalStateException syntax is not an <code> INTEGER 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getIntegerSet();


    /**
     *  Gets the minimum string size. 
     *
     *  @return integer the minimum string length 
     *  @throws osid_IllegalStateException syntax is not a <code> STRING 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMinStringSize();


    /**
     *  Gets the maximum string length. 
     *
     *  @return integer the maximum string length 
     *  @throws osid_IllegalStateException syntax is not a <code> STRING 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getMaxStringLength();


    /**
     *  Gets the set of acceptable string values. 
     *
     *  @return array of strings the set of values 
     *  @throws osid_IllegalStateException syntax is not a <code> STRING 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getStringSet();


    /**
     *  Gets the set of acceptable <code> Ids. </code> 
     *
     *  @return array of osid_id_Id objects  the set of <code> Ids </code> 
     *  @throws osid_IllegalStateException syntax is not an <code> ID </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getIdSet();


    /**
     *  Gets the set of acceptable <code> Types. </code> 
     *
     *  @return array of osid_type_Type objects  the set of <code> Types 
     *          </code> 
     *  @throws osid_IllegalStateException syntax is not an <code> TYPE 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTypeSet();

}
