<?php

/**
 * osid_OsidSearch
 * 
 *     Specifies the OSID definition for osid_OsidSearch.
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
 *  <p><code> OsidSearch </code> specifies search options used to perform OSID 
 *  searches. An <code> OsidSearch </code> is available from an <code> 
 *  OsidSession </code> and defines methods to govern the overall search of 
 *  terms supplied in one or more <code> OsidQuery </code> interfaces. <code> 
 *  </code> </p> 
 *  
 *  <p> <code> </code> This interface is available from a search 
 *  session.Example us using the search interface to retrieve the first 25 
 *  results: 
 *  <pre>
 *       
 *       
 *       OsidSearch os = session.getObjectSearch();
 *       os.limitResultSet(1, 25);
 *       
 *       OsidQuery query;
 *       query = session.getObjectQuery();
 *       query.addDescriptionMatch("*food*", wildcardStringMatchType, true);
 *       
 *       ObjectSearchResults results = session.getObjectsBySearch(query, os);
 *       ObjectList list = results.getObjectList();
 *       
 *                   
 *       
 *  </pre>
 *  </p>
 * 
 * @package org.osid
 */
interface osid_OsidSearch
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
     *  Tests if this search supports the given record <code> Type. </code> 
     *  The given record type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the record interface. 
     *
     *  @param object osid_type_Type $searchRecordType a type 
     *  @return boolean <code> true </code> if a search record the given 
     *          record <code> Type </code> is available, <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> searchRecordType </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasSearchRecordType(osid_type_Type $searchRecordType);

}
