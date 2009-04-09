<?php

/**
 * osid_filing_AllocationAdminSession
 * 
 *     Specifies the OSID definition for osid_filing_AllocationAdminSession.
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
 * @package org.osid.filing
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session defines methods for managing quotas. </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_AllocationAdminSession
    extends osid_OsidSession
{


    /**
     *  Gets the absolute path of this directory. 
     *
     *  @return string the absolute path of this directory 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDirectoryPath();


    /**
     *  Gets the directory associated with this session. 
     *
     *  @return object osid_filing_Directory the directory associated with 
     *          this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDirectory();


    /**
     *  Tests if this user can perform lookup functions in this session. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if filing allocation lookup 
     *          methods are not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canAccessAllocations();


    /**
     *  Tests if the given user has a quota in this directory. 
     *
     *  @param object osid_id_Id $agentId the agent <code> Id </code> of the 
     *          user 
     *  @return boolean <code> true </code> if a quota exists, <code> false 
     *          </code> otherwise 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasQuota(osid_id_Id $agentId);


    /**
     *  Gets the quota allocation of the data store for a given user. The 
     *  directory available by the allocation interface may indicate a higher 
     *  level directory than the one requested if the one requested is a 
     *  sub-directory inside the qoutad data store. 
     *
     *  @param object osid_id_Id $agentId the agent <code> Id </code> of the 
     *          user 
     *  @return object osid_filing_Allocation the allocation 
     *  @throws osid_IllegalStateException <code> hasQuota(agentId) </code> is 
     *          <code> false </code> or this session has been closed 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getQuotaAllocation(osid_id_Id $agentId);


    /**
     *  Tests if this user can perform quota assignments in this session. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if filing allocation assignment 
     *          methods are not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canSetAllocations();


    /**
     *  Sets the quota allocation for a given user for this directory. 
     *
     *  @param object osid_id_Id $agentId the agent <code> Id </code> of the 
     *          user 
     *  @param integer $size space in bytes 
     *  @throws osid_NotFoundException <code> agentId </code> is not found 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setQuotaSpace(osid_id_Id $agentId, $size);


    /**
     *  Sets the quota allocation for a given user for this directory. 
     *
     *  @param object osid_id_Id $agentId the agent <code> Id </code> of the 
     *          user 
     *  @param integer $numFiles number of files 
     *  @throws osid_NotFoundException <code> agentId </code> is not found 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setQuotaFiles(osid_id_Id $agentId, $numFiles);

}
