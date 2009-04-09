<?php

/**
 * osid_logging_LogSearch
 * 
 *     Specifies the OSID definition for osid_logging_LogSearch.
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
 * @package org.osid.logging
 */

require_once(dirname(__FILE__)."/../OsidSearch.php");

/**
 *  <p>The search interface for governing log searches. </p>
 * 
 * @package org.osid.logging
 */
interface osid_logging_LogSearch
    extends osid_OsidSearch
{


    /**
     *  Execute this search using a previous search result. 
     *
     *  @param object osid_logging_LogSearchResults $results results from a 
     *          query 
     *  @throws osid_InvalidArgumentException <code> results </code> is not 
     *          valid 
     *  @throws osid_NullArgumentException <code> results </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchWithinLogResults(osid_logging_LogSearchResults $results);


    /**
     *  Execute this search among the given list of logs. 
     *
     *  @param object osid_id_IdList $logIds list of logs 
     *  @throws osid_NullArgumentException <code> logIds </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchAmongLogs(osid_id_IdList $logIds);


    /**
     *  Specify an ordering to the search results. 
     *
     *  @param object osid_logging_LogSearchOrder $logSearchOrder log search 
     *          order 
     *  @throws osid_NullArgumentException <code> logSearchOrder </code> is 
     *          <code> null </code> 
     *  @throws osid_UnsupportedException <code> logSearchOrder </code> is not 
     *          of this service 
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderLogResults(osid_logging_LogSearchOrder $logSearchOrder);


    /**
     *  Gets the record corresponding to the given log search record <code> 
     *  Type. </code> This method must be used to retrieve an object 
     *  implementing the requested record interface along with all of its 
     *  ancestor interfaces. 
     *
     *  @param object osid_type_Type $logSearchRecordType a log search record 
     *          type 
     *  @return object osid_logging_LogSearchRecord the log search interface 
     *  @throws osid_NullArgumentException <code> logSearchRecordType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasSearchRecordType(logSearchRecordType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogSearchRecord(osid_type_Type $logSearchRecordType);

}
