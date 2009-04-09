<?php

/**
 * osid_logging_LogSearchSession
 * 
 *     Specifies the OSID definition for osid_logging_LogSearchSession.
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

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session provides methods for searching <code> Log </code> objects. 
 *  The search query is constructed using the <code> LogQuery </code> 
 *  interface. The log record <code> Type </code> also specifies the interface 
 *  for the log query. </p> 
 *  
 *  <p> <code> getLogsByQuery() </code> is the basic search method and returns 
 *  a list of <code> Log </code> elements. A more advanced search may be 
 *  performed with <code> getLogsBySearch(). </code> It accepts a <code> 
 *  LogSearch </code> interface in addition to the query interface for the 
 *  purpose of specifying additional options affecting the entire search, such 
 *  as ordering. <code> getLogsBySearch() </code> returns a <code> 
 *  LogSearchResults </code> interface that can be used to access the 
 *  resulting <code> LogList </code> or be used to perform a search within the 
 *  result set through <code> LogSearch. </code> Logs may have a query record 
 *  indicated by their respective record types. The query record is accessed 
 *  via the <code> LogQuery. </code> The returns in this session may not be 
 *  cast directly to these interfaces. </p>
 * 
 * @package org.osid.logging
 */
interface osid_logging_LogSearchSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> Log </code> searches. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer search operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if search methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canSearchLogs();


    /**
     *  Gets a log query interface. The returned query will not have an 
     *  extension query. 
     *
     *  @return object osid_logging_LogQuery the log query 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogQuery();


    /**
     *  Gets a list of <code> Logs </code> matching the given search 
     *  interface. 
     *
     *  @param object osid_logging_LogQuery $logQuery the search query array 
     *  @return object osid_logging_LogList the returned <code> LogList 
     *          </code> 
     *  @throws osid_NullArgumentException <code> logQuery </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> logQuery </code> is not of 
     *          this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogsByQuery(osid_logging_LogQuery $logQuery);


    /**
     *  Gets a log search interface. 
     *
     *  @return object osid_logging_LogSearch the log search interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogSearch();


    /**
     *  Gets a log search order interface. The <code> LogSearchOrder </code> 
     *  is supplied to a <code> LogSearch </code> to specify the ordering of 
     *  results. 
     *
     *  @return object osid_logging_LogSearchOrder the log search order 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogSearchOrder();


    /**
     *  Gets the search results matching the given search interface. 
     *
     *  @param object osid_logging_LogQuery $logQuery the search query array 
     *  @param object osid_logging_LogSearch $logSearch the search interface 
     *  @return object osid_logging_LogSearchResults the search results 
     *  @throws osid_NullArgumentException <code> logQuery </code> or <code> 
     *          logSearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> logQuery </code> or <code> 
     *          logSearch </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogsBySearch(osid_logging_LogQuery $logQuery, 
                                    osid_logging_LogSearch $logSearch);

}
