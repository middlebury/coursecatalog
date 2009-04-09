<?php

/**
 * osid_configuration_ParameterConfigurationAssignmentSession
 * 
 *     Specifies the OSID definition for osid_configuration_ParameterConfigurationAssignmentSession.
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
 *  <p>This session provides methods to re-assign <code> Parameters </code> to 
 *  <code> Configurations. </code> A <code> Parameter </code> may appear in 
 *  multiple <code> Configurations </code> and removing the last reference to 
 *  a <code> Parameter </code> is the equivalent of deleting it which may or 
 *  may not be permitted. Each <code> Configuration </code> may have its own 
 *  authorizations as to who is allowed to operate on it. </p> 
 *  
 *  <p> Moving or adding a reference of a <code> Parameter </code> to another 
 *  <code> Configuration </code> is not a copy operation (eg: does not change 
 *  its <code> Id </code> ). </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ParameterConfigurationAssignmentSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can change parameter configuration mappings. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may not wish to offer management 
     *  operations. 
     *
     *  @return boolean <code> false </code> if parameter configuration 
     *          management is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canManageParameterConfigurations();


    /**
     *  Adds an existing <code> Parameter </code> to a <code> Configuration. 
     *  </code> 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> 
     *  @param object osid_id_Id $configurationId the <code> Id </code> of the 
     *          <code> Configuration </code> 
     *  @throws osid_AlreadyExistsException <code> parameterId </code> and 
     *          <code> configurationId </code> already mapped 
     *  @throws osid_NotFoundException <code> parameterId </code> or <code> 
     *          configurationId </code> not found 
     *  @throws osid_NullArgumentException <code> parameterId </code> or 
     *          <code> configurationId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function assignParameterToConfiguration(osid_id_Id $parameterId, 
                                                   osid_id_Id $configurationId);


    /**
     *  Removes a <code> Parameter </code> from a <code> Configuration. 
     *  </code> 
     *
     *  @param object osid_id_Id $parameterId the Id of the <code> Parameter 
     *          </code> 
     *  @param object osid_id_Id $configurationId the Id of the <code> 
     *          Configuration </code> 
     *  @throws osid_NotFoundException <code> parameterId </code> or <code> 
     *          configurationId </code> not found or is not mapped 
     *  @throws osid_NullArgumentException <code> parameterId </code> or 
     *          <code> configurationId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function unassignParameterFromConfiguration(osid_id_Id $parameterId, 
                                                       osid_id_Id $configurationId);

}
