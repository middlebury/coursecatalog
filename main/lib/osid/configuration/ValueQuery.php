<?php

/**
 * osid_configuration_ValueQuery
 * 
 *     Specifies the OSID definition for osid_configuration_ValueQuery.
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

require_once(dirname(__FILE__)."/ParameterQuery.php");

/**
 *  <p>The interface to query a value. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ValueQuery
    extends osid_configuration_ParameterQuery
{


    /**
     *  Sets a configuration <code> Id </code> for this query. 
     *
     *  @param object osid_id_Id $configurationId a configuration <code> Id 
     *          </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> configurationId </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchConfigurationId(osid_id_Id $configurationId, $match);


    /**
     *  Tests if a configuration query is available. 
     *
     *  @return boolean <code> true </code> if a configuration query interface 
     *          is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsConfigurationQuery();


    /**
     *  Gets the query interface for a configuration. 
     *
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @return object osid_configuration_ConfigurationQuery the configuration 
     *          query 
     *  @throws osid_UnimplementedException <code> 
     *          supportsConfigurationQuery() </code> is <code> false </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsConfigurationQuery() </code> is <code> true. 
     *              </code> 
     */
    public function getConfigurationQuery($match);


    /**
     *  Gets the configuration query interface for the repository type. 
     *  Supported types are defined in the <code> ConfigurationManager. 
     *  </code> 
     *
     *  @param object osid_type_Type $configurationInterfaceType a 
     *          configuration interface type 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @return object osid_configuration_ConfigurationQuery the configuration 
     *          query 
     *  @throws osid_NullArgumentException <code> configurationInterfaceType 
     *          </code> is <code> null </code> 
     *  @throws osid_UnimplementedException <code> 
     *          supportsConfigurationQuery() </code> is <code> false </code> 
     *  @throws osid_UnsupportedException <code> 
     *          ConfigurationManager.supportsConfigurationInterfaceType(configurationInterfaceType) 
     *          is false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsConfigurationQuery() </code> is <code> true. 
     *              </code> 
     */
    public function getConfigurationQueryForInterfaceType(osid_type_Type $configurationInterfaceType, 
                                                          $match);


    /**
     *  Sets the value for this query. 
     *
     *  @param object $value a value 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> value </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchValue($value, $match);

}
