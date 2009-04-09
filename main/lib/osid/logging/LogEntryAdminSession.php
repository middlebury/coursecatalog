<?php

/**
 * osid_logging_LogEntryAdminSession
 * 
 *     Specifies the OSID definition for osid_logging_LogEntryAdminSession.
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
 *  <p>This session removes log entries. The data for create and update is 
 *  provided by the consumer via the form object. </p>
 * 
 * @package org.osid.logging
 */
interface osid_logging_LogEntryAdminSession
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
     *  Tests if this user can delete log entries. A return of true does not 
     *  guarantee successful authorization. A return of false indicates that 
     *  it is known deleting a <code> LogEntry </code> will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may not wish to offer delete operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> LogEntry </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteLogEntries();


    /**
     *  Tests if this user can delete a specified <code> LogEntry. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> LogEntry 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may not wish to offer delete 
     *  operations to unauthorized users. 
     *
     *  @param object osid_id_Id $logEntryId the <code> Id </code> of the 
     *          <code> LogEntry </code> 
     *  @return boolean <code> false </code> if deletion of this <code> 
     *          LogEntry </code> is not authorized, <code> true </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> logEntryId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> logEntryId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteLogEntry(osid_id_Id $logEntryId);


    /**
     *  Deletes a <code> LogEntry. </code> 
     *
     *  @param object osid_id_Id $logEntryId the <code> Id </code> of the 
     *          <code> logEntryId </code> to remove 
     *  @throws osid_NotFoundException <code> logEntryId </code> not found 
     *  @throws osid_NullArgumentException <code> logEntryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteLogEntry(osid_id_Id $logEntryId);


    /**
     *  Removes log entries based on the priority type. All entries with 
     *  priorities equal to or less than the supplied priority are deleted. 
     *
     *  @param object osid_type_Type $priorityType the logging priority 
     *  @throws osid_NullArgumentException <code> priorityType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteLogEntriesByPriorityType(osid_type_Type $priorityType);


    /**
     *  Removes log entries between the supplied dates. 
     *
     *  @param object osid_calendaring_DateTime $start the start time 
     *  @param object osid_calendaring_DateTime $end the end time 
     *  @throws osid_InvalidArgumentException <code> start </code> is greate 
     *          <code> r than end </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_NullArgumentException <code> start </code> or <code> end 
     *          </code> is <code> null </code> 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteLogEntriesByTimestamp(osid_calendaring_DateTime $start, 
                                                osid_calendaring_DateTime $end);


    /**
     *  Removes log entries between the supplied dates for a given priority 
     *  type. 
     *
     *  @param object osid_type_Type $priorityType the logging priority 
     *  @param object osid_calendaring_DateTime $start the start time 
     *  @param object osid_calendaring_DateTime $end the end time 
     *  @throws osid_InvalidArgumentException <code> start </code> is greate 
     *          <code> r than end </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_NullArgumentException <code> priorityType, start </code> 
     *          or <code> end </code> is <code> null </code> 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteLogEntriesByPriorityAndTimestamp(osid_type_Type $priorityType, 
                                                           osid_calendaring_DateTime $start, 
                                                           osid_calendaring_DateTime $end);


    /**
     *  Adds an <code> Id </code> to a <code> LogEntry </code> for the purpose 
     *  of creating compatibility. The primary <code> Id </code> of the <code> 
     *  LogEntry </code> is determined by the provider. The new <code> Id 
     *  </code> performs as an alias to the primary <code> Id. </code> 
     *
     *  @param object osid_id_Id $logEntryId the <code> Id </code> of a <code> 
     *          LogEntry </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> logEntryId </code> not found 
     *  @throws osid_NullArgumentException <code> logEntryId </code> or <code> 
     *          aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToLogEntry(osid_id_Id $logEntryId, 
                                    osid_id_Id $aliasId);

}
