<?php

/**
 * osid_configuration_ConfigurationSearch
 * 
 *     Specifies the OSID definition for osid_configuration_ConfigurationSearch.
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

require_once(dirname(__FILE__)."/../OsidSearch.php");

/**
 *  <p>The search interface to query a configuration. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ConfigurationSearch
    extends osid_OsidSearch
{


    /**
     *  Execute this search using a previous search result. 
     *
     *  @param object osid_configuration_ConfigurationSearchResults $results 
     *          results from a query 
     *  @throws osid_InvalidArgumentException <code> results </code> is not 
     *          valid 
     *  @throws osid_NullArgumentException <code> results </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchWithinConfigurationResults(osid_configuration_ConfigurationSearchResults $results);


    /**
     *  Execute this search among the given list of configurations. 
     *
     *  @param array $configurationIds list of configurations 
     *  @throws osid_NullArgumentException <code> configurationIds </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchAmongConfigurations(array $configurationIds);


    /**
     *  Gets the <code> ConfigurationSearch </code> interface corresponding to 
     *  the configuration search interface <code> Type </code> provided in the 
     *  search session <code> . </code> A <code> ConfigurationSearch </code> 
     *  returned from the search session is only required to implement the 
     *  root <code> ConfigurationSearch </code> interface. This method must be 
     *  used to retrieve a query object implementing the interface specified 
     *  when retrieving this object from the search session along with all of 
     *  its ancestor interfaces, including the core <code> ConfigurationSearch 
     *  </code> interface. 
     *
     *  @return object osid_configuration_ConfigurationSearch the 
     *          configuration search interface 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationSearchInterface();

}
