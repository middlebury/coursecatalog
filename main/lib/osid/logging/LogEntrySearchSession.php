<?php

/**
 * osid_logging_LogEntrySearchSession
 * 
 *     Specifies the OSID definition for osid_logging_LogEntrySearchSession.
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
 *  <p>This session provides methods for searching among log entries. The 
 *  search query is constructed using the <code> LogEntryQuery </code> 
 *  interface. <code> getLogEntriesByQuery() </code> is the basic search 
 *  method and returns a list of log entries. A more advanced search may be 
 *  performed with <code> getLogEntriesBySearch(). </code> It accepts a <code> 
 *  LogEntrySearch </code> interface in addition to the query interface for 
 *  the purpose of specifying additional options affecting the entire search, 
 *  such as ordering. <code> getLogEntriesBySearch() </code> returns a <code> 
 *  LogEntrySearchResults </code> interface that can be used to access the 
 *  resulting <code> LogEntryList </code> or be used to perform a search 
 *  within the result set through <code> LogEntrySearch. </code> </p> 
 *  
 *  <p> This session defines views that offer differing behaviors for 
 *  searching. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> federated log view: searches include entries in repositories of 
 *      which this log is an ancestor in the log hierarchy </li> 
 *      <li> isolated log view: searches are restricted to entries in this log 
 *      only </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.logging
 */
interface osid_logging_LogEntrySearchSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> Log </code> <code> Id </code> associated with this 
     *  session. 
     *
     *  @return object osid_id_Id the <code> Log Id </code> associated with 
     *          this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogId();


    /**
     *  Gets the <code> Log </code> associated with this session. 
     *
     *  @return object osid_logging_Log the <code> Log </code> associated with 
     *          this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLog();


    /**
     *  Tests if this user can perform <code> LogEntry </code> searches. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer search operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if search methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canSearchLogEntries();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include entries in logs which are children of this log in the log 
     *  hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedLogView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to this log only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedLogView();


    /**
     *  Gets a log entry query interface. 
     *
     *  @return object osid_logging_LogEntryQuery the log entry query 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogEntryQuery();


    /**
     *  Gets a list of log entries matching the given search interface. 
     *
     *  @param object osid_logging_LogEntryQuery $logEntryQuery the search 
     *          query array 
     *  @return object osid_logging_LogEntryList the returned <code> 
     *          LogEntryList </code> 
     *  @throws osid_NullArgumentException <code> logEntryQuery </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> logEntryQuery </code> is not 
     *          of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogEntriesByQuery(osid_logging_LogEntryQuery $logEntryQuery);


    /**
     *  Gets a log entry search interface. 
     *
     *  @return object osid_logging_LogEntrySearch the log entry search 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogEntrySearch();


    /**
     *  Gets a log entry search order interface. The <code> 
     *  LogEntrySearchOrder </code> is supplied to an <code> LogEntrySearch 
     *  </code> to specify the ordering of results. 
     *
     *  @return object osid_logging_LogEntrySearchOrder the log entry search 
     *          order interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogEntrySearchOrder();


    /**
     *  Gets the search results matching the given search query using the 
     *  given search. 
     *
     *  @param object osid_logging_LogEntryQuery $logEntryQuery the search 
     *          query array 
     *  @param object osid_logging_LogEntrySearch $logEntrySearch the search 
     *          interface 
     *  @return object osid_logging_LogEntrySearchResults the returned search 
     *          results 
     *  @throws osid_NullArgumentException <code> logEntryQuery </code> or 
     *          <code> logEntrySearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> logEntryQuery </code> or 
     *          <code> logEntrySearch </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogEntriesBySearch(osid_logging_LogEntryQuery $logEntryQuery, 
                                          osid_logging_LogEntrySearch $logEntrySearch);

}
