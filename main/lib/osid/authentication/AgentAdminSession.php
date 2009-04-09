<?php

/**
 * osid_authentication_AgentAdminSession
 * 
 *     Specifies the OSID definition for osid_authentication_AgentAdminSession.
 * 
 * Copyright (C) 2002-2008 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.authentication
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session provides methods to create, delete and modify <code> Agent 
 *  </code> objects. The data for create and update is provided by the 
 *  consumer via the form object. </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_AgentAdminSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can create <code> Agents. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating an <code> Agent </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer create operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Agent </code> creation 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateAgents();


    /**
     *  Tests if this user can create a single <code> Agent </code> using the 
     *  desired record interface types. While <code> 
     *  AuthenticationManager.getAgentRecordTypes() </code> can be used to 
     *  examine which record interfaces are supported, this method tests which 
     *  record(s) are required for creating a specific <code> Agent. </code> 
     *  Providing an empty array tests if an <code> Agent </code> can be 
     *  created with no records. 
     *
     *  @param array $agentRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> Agent </code> creation 
     *          using the specified record <code> Types </code> is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> agentRecordTypes </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateAgentWithRecordTypes(array $agentRecordTypes);


    /**
     *  Gets the agent form for creating new agents. A new form should be 
     *  requested for each create transaction. 
     *
     *  @return object osid_authentication_AgentForm the agent form 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentFormForCreate();


    /**
     *  Creates a new <code> Agent. </code> 
     *
     *  @param object osid_authentication_AgentForm $agentForm the forms for 
     *          this <code> Agent </code> 
     *  @return object osid_authentication_Agent the new <code> Agent </code> 
     *  @throws osid_AlreadyExistsException attempt at duplicating a property 
     *          the underlying system is enforcing to be unique 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> agentForm </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> agentForm </code> is not of 
     *          this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createAgent(osid_authentication_AgentForm $agentForm);


    /**
     *  Tests if this user can update <code> Agents. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating an <code> Agent </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer update operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if agent modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateAgents();


    /**
     *  Tests if this user can update a specified agent. A return of true does 
     *  not guarantee successful authorization. A return of false indicates 
     *  that it is known updating the agent will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer an update operation to an 
     *  unauthorized user for this agent. 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> 
     *  @return boolean <code> false </code> if agent modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> agentId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateAgent(osid_id_Id $agentId);


    /**
     *  Gets the agent form for updating an existing agent. A new agent form 
     *  should be requested for each update transaction. 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> 
     *  @return object osid_authentication_AgentForm the agent form 
     *  @throws osid_NotFoundException <code> agentId </code> is not found 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentFormForUpdate(osid_id_Id $agentId);


    /**
     *  Updates an existing agent. 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> 
     *  @param object osid_authentication_AgentForm $agentForm the form 
     *          containing the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> agentId </code> is not found 
     *  @throws osid_NullArgumentException <code> agentId </code> or <code> 
     *          agentForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> agentForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateAgent(osid_id_Id $agentId, 
                                osid_authentication_AgentForm $agentForm);


    /**
     *  Tests if this user can delete <code> Agents. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting an <code> Agent </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer delete operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Agent </code> deletion 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteAgents();


    /**
     *  Tests if this user can delete a specified <code> Agent. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> Agent </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer an delete operation 
     *  to an unauthorized user for this agent. 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> 
     *  @return boolean <code> false </code> if <code> Agent </code> deletion 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> agentId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteAgent(osid_id_Id $agentId);


    /**
     *  Deletes the <code> Agent </code> identified by the given <code> Id. 
     *  </code> 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> to delete 
     *  @throws osid_NotFoundException an <code> Agent </code> was not found 
     *          identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteAgent(osid_id_Id $agentId);


    /**
     *  Adds an <code> Id </code> to an <code> Agent </code> for the purpose 
     *  of creating compatibility. The primary <code> Id </code> of the <code> 
     *  Agent </code> is determined by the provider. The new <code> Id </code> 
     *  performs as an alias to the primary <code> Id. </code> 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of an <code> 
     *          Agent </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> agentId </code> not found 
     *  @throws osid_NullArgumentException <code> agentId </code> or <code> 
     *          aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToAgent(osid_id_Id $agentId, osid_id_Id $aliasId);

}
