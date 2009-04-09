<?php

/**
 * osid_logging_LoggingManager
 * 
 *     Specifies the OSID definition for osid_logging_LoggingManager.
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

require_once(dirname(__FILE__)."/../OsidManager.php");
require_once(dirname(__FILE__)."/LoggingProfile.php");

/**
 *  <p>The logging manager provides access to logging sessions and provides 
 *  interoperability tests for various aspects of this service. The sessions 
 *  included in this manager are: 
 *  <ul>
 *      <li> <code> LoggingSession: </code> a session to write to a log </li> 
 *      <li> <code> LogReadingSession: </code> a session to read a log </li> 
 *      <li> <code> LogEntrySearchSession: </code> a session to search a log 
 *      </li> 
 *      <li> <code> LogEntryAdminSession: </code> a session to manage log 
 *      entries in a log </li> 
 *      <li> <code> LogEntryNotificationSession: </code> a session to 
 *      subscribe to notifications of new log entries </li> 
 *      <li> <code> LogLookupSession: </code> a session to retrieve log 
 *      objects </li> 
 *      <li> <code> LogSearchSession: </code> a session to search for logs 
 *      </li> 
 *      <li> <code> LogAdminSession: </code> a session to create, update and 
 *      delete logs </li> 
 *      <li> <code> LogNotificationSession: </code> a session to receive 
 *      notifications for changes in logs </li> 
 *      <li> <code> LogHierarchyTraversalSession: </code> a session to 
 *      traverse hierarchies of logs </li> 
 *      <li> <code> LogHierarchyDesignSession: </code> a session to manage 
 *      hierarchues of logs </li> 
 *  </ul>
 *  The logging manager also provides a profile for determing the supported 
 *  search types supported by this service. </p>
 * 
 * @package org.osid.logging
 */
interface osid_logging_LoggingManager
    extends osid_OsidManager,
            osid_logging_LoggingProfile
{


    /**
     *  Gets the <code> OsidSession </code> associated with the logging 
     *  service. 
     *
     *  @return object osid_logging_LoggingSession a <code> LoggingSession 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLogging() </code> 
     *          is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogging() </code> is <code> true. </code> 
     */
    public function getLoggingSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the logging 
     *  service for the given log. 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> 
     *  @return object osid_logging_LoggingSession a <code> LoggingSession 
     *          </code> 
     *  @throws osid_NotFoundException no <code> Log </code> found by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLogging() </code> 
     *          or <code> supportsVisibleFederation() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogging() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getLoggingSessionForLog(osid_id_Id $logId);


    /**
     *  Gets the <code> OsidSession </code> associated with the logging 
     *  reading service. 
     *
     *  @return object osid_logging_LogReadingSession a <code> 
     *          LogReadingSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLogReading() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogReading() </code> is <code> true. </code> 
     */
    public function getLogReadingSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the log reading 
     *  service for the given log. 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> 
     *  @return object osid_logging_LogReadingSession a <code> 
     *          LogReadingSession </code> 
     *  @throws osid_NotFoundException no <code> Log </code> found by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLogReading() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogReading() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getLogReadingSessionForLog(osid_id_Id $logId);


    /**
     *  Gets the <code> OsidSession </code> associated with the logging entry 
     *  search service. 
     *
     *  @return object osid_logging_LogEntrySearchSession a <code> 
     *          LogEntrySearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLogEntrySearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogEntrySearch() </code> is <code> true. </code> 
     */
    public function getLogEntrySearchSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the log entry 
     *  search service for the given log. 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> 
     *  @return object osid_logging_LogEntrySearchSession a <code> 
     *          LogEntrySearchSession </code> 
     *  @throws osid_NotFoundException no <code> Log </code> found by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLogEntrySearch() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogEntrySearch() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getLogEntrySearchSessionForLog(osid_id_Id $logId);


    /**
     *  Gets the <code> OsidSession </code> associated with the logging entry 
     *  administrative service. 
     *
     *  @return object osid_logging_LogEntryAdminSession a <code> 
     *          LogEntryAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLogEntryAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogEntryAdmin() </code> is <code> true. </code> 
     */
    public function getLogEntryAdminSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the log entry 
     *  administrative service for the given log. 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> 
     *  @return object osid_logging_LogEntryAdminSession a <code> 
     *          LogEntryAdminSession </code> 
     *  @throws osid_NotFoundException no <code> Log </code> found by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLogEntryAdmin() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogEntryAdmin() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getLogEntryAdminSessionForLog(osid_id_Id $logId);


    /**
     *  Gets the <code> OsidSession </code> associated with the logging entry 
     *  notification service. 
     *
     *  @return object osid_logging_LogEntryNotificationSession a <code> 
     *          LogEntryNotificationSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsLogEntryNotification() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogEntryNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getLogEntryNotificationSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the log entry 
     *  notification service for the given log. 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> 
     *  @return object osid_logging_LogEntryNotificationSession a <code> 
     *          LogEntryNotificationSession </code> 
     *  @throws osid_NotFoundException no <code> Log </code> found by the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsLogEntryNotification() </code> or <code> 
     *          supportsVisibleFederation() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogEntryNotification() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getLogEntryNotificationSessionForLog(osid_id_Id $logId);


    /**
     *  Gets the <code> OsidSession </code> associated with the log lookup 
     *  service. 
     *
     *  @return object osid_logging_LogLookupSession a <code> LogLookupSession 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLogLookup() </code> 
     *          is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogLookup() </code> is <code> true. </code> 
     */
    public function getLogLookupSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the log search 
     *  service. 
     *
     *  @return object osid_logging_LogSearchSession a <code> LogSearchSession 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLogSearch() </code> 
     *          is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogSearch() </code> is <code> true. </code> 
     */
    public function getLogSearchSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the log 
     *  administrative service. 
     *
     *  @return object osid_logging_LogAdminSession a <code> LogAdminSession 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLogAdmin() </code> 
     *          is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogAdmin() </code> is <code> true. </code> 
     */
    public function getLogAdminSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the log 
     *  notification service. 
     *
     *  @return object osid_logging_LogNotificationSession a <code> 
     *          LogNotificationSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLogNotification() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogNotification() </code> is <code> true. </code> 
     */
    public function getLogNotificationSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the log hierarchy 
     *  service. 
     *
     *  @return object osid_logging_LogHierarchySession a <code> 
     *          LogHierarchySession </code> for logs 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLogHierarchy() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogHierarchy() </code> is <code> true. </code> 
     */
    public function getLogHierarchySession();


    /**
     *  Gets the <code> OsidSession </code> associated with the log hierarchy 
     *  design service. 
     *
     *  @return object osid_logging_LogHierarchyDesignSession a <code> 
     *          HierarchyDesignSession </code> for logs 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsLogHierarchyDesign() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogHierarchyDesign() </code> is <code> true. 
     *              </code> 
     */
    public function getLogHierarchyDesignSession();

}
