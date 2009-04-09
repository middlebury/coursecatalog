<?php

/**
 * osid_authentication_KeyAdminSession
 * 
 *     Specifies the OSID definition for osid_authentication_KeyAdminSession.
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
 *  <p>This session provides methods to creating, updating and deleting <code> 
 *  Key </code> objects. Keys are managed separately from the <code> Agent. 
 *  </code> Each <code> Agent </code> maps to a zero or one <code> Key </code> 
 *  and every <code> Key </code> maps to one <code> Agent. </code> <code> Keys 
 *  </code> are identified from their counterpart <code> Agent </code> <code> 
 *  Id. </code> </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_KeyAdminSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can create <code> Keys. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating a <code> Key </code> will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer create operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Key </code> creation is 
     *          not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateKeys();


    /**
     *  Tests if this user can create a single <code> Key </code> using the 
     *  desired record interface types. While <code> 
     *  AuthenticationManager.getKeyRecordTypes() </code> can be used to 
     *  examine which record interfaces are supported, this method tests which 
     *  record(s) are required for creating a specific <code> Key. </code> 
     *  Providing an empty array tests if a <code> Key </code> can be created 
     *  with no records. 
     *
     *  @param array $keyRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> Key </code> creation 
     *          using the specified record <code> Types </code> is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> keyRecordTypes </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateKeyWithRecordTypes(array $keyRecordTypes);


    /**
     *  Gets the key form for creating and updating new keys. A new form 
     *  should be requested for each create transaction. 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> 
     *  @return object osid_authentication_KeyForm the key form 
     *  @throws osid_NotFoundException <code> agentId </code> is not found 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeyFormForCreate(osid_id_Id $agentId);


    /**
     *  Creates a new <code> Key. </code> 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> 
     *  @param object osid_authentication_KeyForm $keyForm the form for this 
     *          <code> Key </code> 
     *  @return object osid_authentication_Key the new <code> Key </code> 
     *  @throws osid_AlreadyExistsException agent already has a key 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> agentId </code> or <code> 
     *          keyForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException a <code> keyForm </code> is not of 
     *          this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createKey(osid_id_Id $agentId, 
                              osid_authentication_KeyForm $keyForm);


    /**
     *  Tests if this user can update <code> Keys. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a Key will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer update operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if key modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateKeys();


    /**
     *  Tests if this user can update a specified key. A return of true does 
     *  not guarantee successful authorization. A return of false indicates 
     *  that it is known updating the key will result in a <code> 
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
    public function canUpdateKey(osid_id_Id $agentId);


    /**
     *  Gets the key form for updating an existing key. 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> 
     *  @return object osid_authentication_KeyForm the key form 
     *  @throws osid_NotFoundException <code> agentId </code> is not found 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeyFormForUpdate(osid_id_Id $agentId);


    /**
     *  Updates a key for an agent. 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> 
     *  @param object osid_authentication_KeyForm $keyForm the form containing 
     *          the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> agentId </code> is not found 
     *  @throws osid_NullArgumentException <code> agentId </code> or <code> 
     *          keyForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> keyForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateKey(osid_id_Id $agentId, 
                              osid_authentication_KeyForm $keyForm);


    /**
     *  Tests if this user can delete <code> Keys. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting a <code> Key </code> will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer delete operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Key </code> deletion is 
     *          not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteKeys();


    /**
     *  Tests if this user can delete a specified <code> Key. </code> A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting the <code> Key </code> will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer an delete operation to an 
     *  unauthorized user for this agent. 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> 
     *  @return boolean <code> false </code> if <code> Key </code> deletion is 
     *          not authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> agentId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteKey(osid_id_Id $agentId);


    /**
     *  Deletes the <code> Key </code> associated with the given agent <code> 
     *  Id. </code> 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> whose key to delete 
     *  @throws osid_NotFoundException an <code> Agent </code> was not found 
     *          identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteKey(osid_id_Id $agentId);

}
