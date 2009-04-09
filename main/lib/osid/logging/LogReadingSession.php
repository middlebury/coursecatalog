<?php

/**
 * osid_logging_LogReadingSession
 * 
 *     Specifies the OSID definition for osid_logging_LogReadingSession.
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
 *  <p></p>
 * 
 * @package org.osid.logging
 */
interface osid_logging_LogReadingSession
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
     *  @return object osid_logging_Log the log 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLog();


    /**
     *  Tests if this user can read the log. A return of true does not 
     *  guarantee successful authorization. A return of false indicates that 
     *  it is known all methods in this session will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer reading operations. 
     *
     *  @return boolean <code> false </code> if reading methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canReadLog();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeLogEntryView();


    /**
     *  A complete view of the <code> LogEntry </code> returns is desired. 
     *  Methods will return what is requested or result in an error. This view 
     *  is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryLogEntryView();


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
     *  restricts retrievals to this log only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedLogView();


    /**
     *  Gets the <code> LogEntry </code> specified by its <code> Id. </code> 
     *  In plenary mode, the exact <code> Id </code> is found or a <code> 
     *  NOT_FOUND </code> results. Otherwise, the returned <code> LogEntry 
     *  </code> may have a different <code> Id </code> than requested, such as 
     *  the case where a duplicate <code> Id </code> was assigned to a <code> 
     *  LogEntry </code> and retained for compatibility. 
     *
     *  @param object osid_id_Id $logEntryId the <code> Id </code> of the 
     *          <code> LogEntry </code> to rerieve 
     *  @return object osid_logging_LogEntry the returned <code> LogEntry 
     *          </code> 
     *  @throws osid_NotFoundException no <code> LogEntry </code> found with 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> logEntryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogEntry(osid_id_Id $logEntryId);


    /**
     *  Gets a <code> LogEntryList </code> corresponding to the given <code> 
     *  IdList. </code> In plenary mode, the returned list contains all of the 
     *  entries specified in the <code> Id </code> list, in the order of the 
     *  list, including duplicates, or an error results if an <code> Id 
     *  </code> in the supplied list is not found or inaccessible. Otherwise, 
     *  inaccessible logentries may be omitted from the list and may present 
     *  the elements in any order including returning a unique set. 
     *
     *  @param object osid_id_IdList $logEntryIdLIst the list of <code> Ids 
     *          </code> to rerieve 
     *  @return object osid_logging_LogEntryList the returned <code> LogEntry 
     *          list </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> logEntryIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogEntriesByIds(osid_id_IdList $logEntryIdLIst);


    /**
     *  Gets a <code> LogEntryList </code> corresponding to the given content 
     *  <code> Type. </code> In plenary mode, the returned list contains all 
     *  known entries or an error results. Otherwise, the returned list may 
     *  contain only those entries that are accessible through this session. 
     *  In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $contentType a log entry content type 
     *  @return object osid_logging_LogEntryList the returned <code> LogEntry 
     *          list </code> 
     *  @throws osid_NullArgumentException <code> contentType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogEntriesByContentType(osid_type_Type $contentType);


    /**
     *  Gets a <code> LogEntryList </code> corresponding to the given priority 
     *  <code> Type. </code> In plenary mode, the returned list contains all 
     *  known entries or an error results. Otherwise, the returned list may 
     *  contain only those entries that are accessible through this session. 
     *  In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $priorityType a log entry priority type 
     *  @return object osid_logging_LogEntryList the returned <code> LogEntry 
     *          list </code> 
     *  @throws osid_NullArgumentException <code> contentType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogEntriesByPriorityType(osid_type_Type $priorityType);


    /**
     *  Gets a <code> LogEntryList </code> corresponding to the given time 
     *  interval. <code> </code> In plenary mode, the returned list contains 
     *  all known entries or an error results. Otherwise, the returned list 
     *  may contain only those entries that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_calendaring_DateTime $start a starting time 
     *  @param object osid_calendaring_DateTime $end a starting time 
     *  @return object osid_logging_LogEntryList the returned <code> LogEntry 
     *          list </code> 
     *  @throws osid_InvalidArgumentException <code> start </code> is greater 
     *          than <code> end </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogEntriesByTime(osid_calendaring_DateTime $start, 
                                        osid_calendaring_DateTime $end);


    /**
     *  Gets all log entries. In plenary mode, the returned list contains all 
     *  known entries or an error results. Otherwise, the returned list may 
     *  contain only those entries that are accessible through this session. 
     *  In both cases, the order of the set is not specified. 
     *
     *  @return object osid_logging_LogEntryList a list of log emntries 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogEntries();

}
