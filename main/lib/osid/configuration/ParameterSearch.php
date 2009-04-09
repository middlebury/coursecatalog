<?php

/**
 * osid_configuration_ParameterSearch
 * 
 *     Specifies the OSID definition for osid_configuration_ParameterSearch.
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
 *  <p><code> ParameterSearch </code> specifies the interface for specifying 
 *  parameter search options. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ParameterSearch
{


    /**
     *  By default, searches return all matching results. This method 
     *  restricts the number of results by setting the start and end of the 
     *  result set, starting from 1. The starting and ending results can be 
     *  used for paging results when a certain ordering is requested. The 
     *  ending position must be greater than the starting position. 
     *
     *  @param integer $start the start of the result set 
     *  @param integer $end the end of the result set 
     *  @throws osid_InvalidArgumentException <code> end </code> is less than 
     *          or equal to <code> start </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function limitResultSet($start, $end);


    /**
     *  Executes this search using a previous search result. 
     *
     *  @param object osid_configuration_ParameterSearchResults $results 
     *          results from a query 
     *  @throws osid_InvalidArgumentException <code> results </code> is not 
     *          valid 
     *  @throws osid_NullArgumentException <code> results </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchWithinParameterResults(osid_configuration_ParameterSearchResults $results);


    /**
     *  Executes this search among a given list of parameters. 
     *
     *  @param array $paraneterIds list of subjects 
     *  @throws osid_NullArgumentException <code> parameterIds </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchAmongParameters(array $paraneterIds);


    /**
     *  Specifies a preference for ordering the results by display name. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByDisplayName();


    /**
     *  Specifies a preference for ordering the results by genus type. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByGenusType();


    /**
     *  Specifies a preference for ordering the results by value type. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByValueType();


    /**
     *  Specifies a preference for ordering the results by registry. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByRegistry();

}
