<?php

/**
 * osid_configuration_ConfigurationQuery
 * 
 *     Specifies the OSID definition for osid_configuration_ConfigurationQuery.
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

require_once(dirname(__FILE__)."/../OsidCatalogQuery.php");

/**
 *  <p>This is the query interface for searching configurations. Each method 
 *  match request produces an <code> AND </code> term while multiple 
 *  invocations of a method produces a nested <code> OR, </code> except for 
 *  accessing the <code> ConfigurationQuery </code> subinterface. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ConfigurationQuery
    extends osid_OsidCatalogQuery
{


    /**
     *  Tests if a <code> ValueQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a value query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsValueQuery();


    /**
     *  Gets the query interface for a value. 
     *
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for negative match 
     *  @return object osid_configuration_ValueQuery the value query 
     *  @throws osid_UnimplementedException <code> supportsValueQuery() 
     *          </code> is <code> false </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsValueQuery() </code> is <code> true. </code> 
     */
    public function getValueQuery($match);


    /**
     *  Gets the <code> ConfigurationQuery </code> interface corresponding to 
     *  the <code> Configuration </code> interface <code> Type </code> 
     *  provided in the search session. A <code> ConfigurationQuery </code> 
     *  returned from the search session is only required to implement the 
     *  root <code> ConfigurationQuery </code> interface. This method must be 
     *  used to retrieve a query object implementing the interface specified 
     *  when retrieving this object from the search session along with all of 
     *  its ancestor interfaces, including the core <code> ConfigurationQuery 
     *  </code> interface. 
     *
     *  @return object osid_configuration_ConfigurationQuery the configuration 
     *          query interface 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationQueryInterface();

}
