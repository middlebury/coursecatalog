<?php

/**
 * osid_logging_LogQuery
 * 
 *     Specifies the OSID definition for osid_logging_LogQuery.
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

require_once(dirname(__FILE__)."/../OsidCatalogQuery.php");

/**
 *  <p>This is the query interface for searching for logs. Each method 
 *  specifies an <code> AND </code> term while multiple invocations of the 
 *  same method produce a nested <code> OR. </code> </p>
 * 
 * @package org.osid.logging
 */
interface osid_logging_LogQuery
    extends osid_OsidCatalogQuery
{


    /**
     *  Tests if a log entry query is available. 
     *
     *  @return boolean <code> true </code> if a log entry query is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLogEntryQuery();


    /**
     *  Gets the query interface for a log entry. 
     *
     *  @return object osid_logging_LogEntryQuery the log entry query 
     *  @throws osid_UnimplementedException <code> supportsLogEntryQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLogEntryQuery() </code> is <code> true. </code> 
     */
    public function getLogEntryQuery();


    /**
     *  Gets the record query interface corresponding to the given <code> Log 
     *  </code> record <code> Type. </code> Multiple record retrievals produce 
     *  a nested boolean <code> OR </code> term. 
     *
     *  @param object osid_type_Type $logRecordType a log record type 
     *  @return object osid_logging_LogQueryRecord the log query record 
     *  @throws osid_NullArgumentException <code> logRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> hasRecordType(logRecordType) 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLogQueryRecord(osid_type_Type $logRecordType);

}
