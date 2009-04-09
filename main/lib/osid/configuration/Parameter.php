<?php

/**
 * osid_configuration_Parameter
 * 
 *     Specifies the OSID definition for osid_configuration_Parameter.
 * 
 * Copyright (C) 2008 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.configuration
 */


/**
 *  <p>A parameter is used to map configuration values to an identifier and 
 *  syntax. The type of the value must be used across all values of the same 
 *  parameter. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_Parameter
{


    /**
     *  Gets the <code> Id </code> associated with this instance of this 
     *  parajmeter. Persisting any reference to this parameter is done by 
     *  persisting the <code> Id </code> returned from this method. The <code> 
     *  Id </code> returned may be different than the <code> Id </code> used 
     *  to query this object. In this case, the new <code> Id </code> should 
     *  be preferred over the old one for future queries. 
     *
     *  @return object osid_id_Id the parameter <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  The <code> Id </code> is intended to be constant and 
     *          persistent. A consumer may at any time persist the <code> Id 
     *          </code> for retrieval at any future time. Ideally, the <code> 
     *          Id </code> should consistently resolve into the designated 
     *          object and not be reused. 
     */
    public function getId();


    /**
     *  Gets the preferred display name associated with this instance of this 
     *  parameter appropriate for display to the user. 
     *
     *  @return string the display name 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  A display name is a string used for identifying an object in 
     *          human terms. A provider may wish to initialize the display 
     *          name based on one or more object attributes. In some cases, 
     *          the display name may not map to a specific or significant 
     *          object attribute but simply be used as a preferred display 
     *          name that can be modified. A provider may also wish to 
     *          translate the display name into a specific locale using the 
     *          Locale service. Some OSIDs define methods for more detailed 
     *          naming. 
     */
    public function getDisplayName();


    /**
     *  Gets the description associated with this instance of this parameter. 
     *
     *  @return string the description 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  A description is a string used for describing an object in 
     *          human terms and may not have significance in the underlying 
     *          system. A provider may wish to initialize the description 
     *          based on one or more object attributes and/or treat it as an 
     *          auxiliary piece of data that can be modified. A provider may 
     *          also wish to translate the description into a specific locale 
     *          using the Locale service. 
     */
    public function getDescription();


    /**
     *  Gets the type of this parameter values. 
     *
     *  @return object osid_type_Type the type of the values 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getValueType();


    /**
     *  Tests if this object supports the given interface <code> Type. </code> 
     *  The given interface type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the typed interface extension. 
     *
     *  @param object osid_type_Type $valueType a type 
     *  @return boolean <code> true </code> if the values associated with this 
     *          parameter implement the given <code> Type, </code> <code> 
     *          false </code> otherwise 
     *  @throws osid_NullArgumentException <code> valueType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function implementsValueType(osid_type_Type $valueType);

}
