<?php

/**
 * osid_logging_LoggingProfile
 * 
 *     Specifies the OSID definition for osid_logging_LoggingProfile.
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

require_once(dirname(__FILE__)."/../OsidProfile.php");

/**
 *  <p>The logging profile describes the interoperability among logging 
 *  services. </p>
 * 
 * @package org.osid.logging
 */
interface osid_logging_LoggingProfile
    extends osid_OsidProfile
{


    /**
     *  Tests if visible federation is supported. 
     *
     *  @return boolean <code> true </code> if visible federation is 
     *          supproted, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsVisibleFederation();


    /**
     *  Tests if logging is supported. 
     *
     *  @return boolean <code> true </code> if logging is supported, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLogging();


    /**
     *  Tests if reading logs is supported. 
     *
     *  @return boolean <code> true </code> if reading logs is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLogReading();


    /**
     *  Tests if searching log entries is supported. 
     *
     *  @return boolean <code> true </code> if searching log entries is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLogEntrySearch();


    /**
     *  Tests if log entry notification is supported,. 
     *
     *  @return boolean <code> true </code> if log etry notification is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLogEntryNotification();


    /**
     *  Tests for the availability of a log lookup service. 
     *
     *  @return boolean <code> true </code> if log lookup is available, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLogLookup();


    /**
     *  Tests if searching for logs is available. 
     *
     *  @return boolean <code> true </code> if log search is available, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLogSearch();


    /**
     *  Tests for the availability of a log administrative service for 
     *  creating and deleting logs. 
     *
     *  @return boolean <code> true </code> if log administration is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLogAdmin();


    /**
     *  Tests for the availability of a log notification service. 
     *
     *  @return boolean <code> true </code> if log notification is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented in all 
     *              providers. 
     */
    public function supportsLogNotification();


    /**
     *  Tests for the availability of a log hierarchy traversal service. 
     *
     *  @return boolean <code> true </code> if log hierarchy traversal is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLogHierarchy();


    /**
     *  Tests for the availability of a log hierarchy design service. 
     *
     *  @return boolean <code> true </code> if log hierarchy design is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented in all 
     *              providers. 
     */
    public function supportsLogHierarchyDesign();


    /**
     *  Tests if the log hierarchy supports node sequencing. 
     *
     *  @return boolean <code> true </code> if log hierarchy node sequencing 
     *          is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLogHierarchySequencing();


    /**
     *  Gets the supported log entry search record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported log 
     *          entry search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogEntrySearchRecordTypes();


    /**
     *  Tests if the given log entry search record type is supported. 
     *
     *  @param object osid_type_Type $logEntrySearchRecordType a <code> Type 
     *          </code> indicating a log entry record type 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLogEntrySearchRecordType(osid_type_Type $logEntrySearchRecordType);


    /**
     *  Gets the supported <code> Log </code> record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported log 
     *          record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogRecordTypes();


    /**
     *  Tests if the given <code> Log </code> record type is supported. 
     *
     *  @param object osid_type_Type $logRecordType a <code> Type </code> 
     *          indicating a <code> Log </code> record type 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLogRecordType(osid_type_Type $logRecordType);


    /**
     *  Gets the supported log search record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported log 
     *          search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogSearchRecordTypes();


    /**
     *  Tests if the given log search record type is supported. 
     *
     *  @param object osid_type_Type $logSearchRecordType a <code> Type 
     *          </code> indicating a log record type 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLogSearchRecordType(osid_type_Type $logSearchRecordType);


    /**
     *  Gets the priority types supported, in ascending order of the priority 
     *  level. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          priority types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPriorityTypes();


    /**
     *  Tests if the priority type is supported. 
     *
     *  @param object osid_type_Type $priorityType a <code> Type </code> 
     *          indicating a priority type 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsPriorityType(osid_type_Type $priorityType);


    /**
     *  Gets the log entry content types supported. 
     *
     *  @return object osid_type_TypeList a list containing the supported log 
     *          entry content types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getContentTypes();


    /**
     *  Tests if the log entry content type is supported. 
     *
     *  @param object osid_type_Type $contentType a <code> Type </code> 
     *          indicating a log entry content type 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsContentType(osid_type_Type $contentType);

}
