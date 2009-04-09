<?php

/**
 * osid_configuration_ParameterAdminSession
 * 
 *     Specifies the OSID definition for osid_configuration_ParameterAdminSession.
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
 * @package org.osid.configuration
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session creates, updates and removes parameters from a registry. 
 *  The data for create and update is provided by the consumer via the form 
 *  object. The parameter type specifies the format for associated values. 
 *  </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ParameterAdminSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> Registry </code> <code> Id </code> associated with 
     *  this session. 
     *
     *  @return object osid_id_Id the <code> Registry Id </code> associated 
     *          with this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistryId();


    /**
     *  Gets the <code> Registry </code> associated with this session. 
     *
     *  @return object osid_configuration_Registry the <code> Registry </code> 
     *          associated with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistry();


    /**
     *  Gets the paramater form for creating new parameters. 
     *
     *  @return object osid_configuration_ParameterForm the parameter form 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParameterFormForCreate();


    /**
     *  Creates a new <code> Parameter. </code> 
     *
     *  @param object osid_configuration_ParameterForm $parameterForm the form 
     *          for this <code> Parameter </code> 
     *  @return object osid_configuration_Parameter the new <code> Parameter 
     *          </code> 
     *  @throws osid_AlreadyExistsException attempt at duplicating a property 
     *          the underlying system is enforcing to be unique 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> parameterForm </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> parameterForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createParamater(osid_configuration_ParameterForm $parameterForm);


    /**
     *  Tests if this user can update <code> Parameters. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a <code> Parameter </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer update operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Parameter </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateParameters();


    /**
     *  Tests if this user can update a specified <code> Parameter. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known updating the <code> Parameter 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may opt not to offer update 
     *  operations to an unauthoirzed user. 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> 
     *  @return boolean <code> false </code> if parameter modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> parameterId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateParameter(osid_id_Id $parameterId);


    /**
     *  Gets the parameter form for updating an existing parameters. 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> 
     *  @return object osid_configuration_ParameterForm the parameter form 
     *  @throws osid_NullArgumentException <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParameterFormForUpdate(osid_id_Id $parameterId);


    /**
     *  Updates an existing parameter. 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> 
     *  @param object osid_configuration_ParameterForm $parameterForm the form 
     *          containing the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> parameterId </code> is not found 
     *  @throws osid_NullArgumentException <code> parameterId </code> or 
     *          <code> parameterForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> form </code> is not supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateParameter(osid_id_Id $parameterId, 
                                    osid_configuration_ParameterForm $parameterForm);


    /**
     *  Tests if this user can delete <code> Parameters. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting a <code> Parameter </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer delete operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Parameter </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteParameters();


    /**
     *  Tests if this user can delete a specified <code> Parameter. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> Parameter 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may opt not to offer delete 
     *  operations to an unauthorized user. 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> 
     *  @return boolean <code> false </code> if <code> Parameter </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> parameterId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteParameter(osid_id_Id $parameterId);


    /**
     *  Deletes a <code> Parameter. </code> 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> to remove 
     *  @throws osid_NotFoundException <code> parameterId </code> not found 
     *  @throws osid_NullArgumentException <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteParameter(osid_id_Id $parameterId);


    /**
     *  Adds an <code> Id </code> to a <code> Parameter </code> for the 
     *  purpose of creating compatibility. The primary <code> Id </code> of 
     *  the <code> Parameter </code> is determined by the provider. The new 
     *  <code> Id </code> performs as an alias to the primary <code> Id. 
     *  </code> 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of a 
     *          <code> Parameter </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> parameterId </code> not found 
     *  @throws osid_NullArgumentException <code> parameterId </code> or 
     *          <code> aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToParameter(osid_id_Id $parameterId, 
                                     osid_id_Id $aliasId);

}
