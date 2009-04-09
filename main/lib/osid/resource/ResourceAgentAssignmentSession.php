<?php

/**
 * osid_resource_ResourceAgentAssignmentSession
 * 
 *     Specifies the OSID definition for osid_resource_ResourceAgentAssignmentSession.
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
 * @package org.osid.resource
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session provides methods to re-assign <code> Resource </code> to 
 *  <code> Agents. </code> A <code> Resource </code> may be associated with 
 *  multiple <code> Agents. </code> An <code> Agent </code> may map to only 
 *  one <code> Resource. </code> </p>
 * 
 * @package org.osid.resource
 */
interface osid_resource_ResourceAgentAssignmentSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can alter resource/agent mappings. A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known mapping methods in this session will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if mapping is not authorized, 
     *          <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canAssignAgents();


    /**
     *  Adds an existing <code> Agent </code> to a <code> Resource. </code> 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> 
     *  @param object osid_id_Id $resourceId the <code> Id </code> of the 
     *          <code> Resource </code> 
     *  @throws osid_AlreadyExistsException this agent is already assigned to 
     *          a resource 
     *  @throws osid_NotFoundException <code> agentId </code> or <code> 
     *          resourceId </code> not found 
     *  @throws osid_NullArgumentException <code> agentId </code> or <code> 
     *          resourceId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function assignAgentToResource(osid_id_Id $agentId, 
                                          osid_id_Id $resourceId);


    /**
     *  Removes an <code> Agent </code> from a <code> Resource. </code> 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> 
     *  @param object osid_id_Id $resourceId the <code> Id </code> of the 
     *          <code> Resource </code> 
     *  @throws osid_NotFoundException <code> agentId </code> or <code> 
     *          resourceId </code> not found 
     *  @throws osid_NullArgumentException <code> agentId </code> or <code> 
     *          resourceId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function unassignAgentFromResource(osid_id_Id $agentId, 
                                              osid_id_Id $resourceId);

}
