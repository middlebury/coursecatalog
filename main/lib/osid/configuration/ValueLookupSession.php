<?php

/**
 * osid_configuration_ValueLookupSession
 * 
 *     Specifies the OSID definition for osid_configuration_ValueLookupSession.
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
 *  <p>This session is used to retrieve configuration values. Two views of the 
 *  configuration data are defined; </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> federated: parameters defined in configurations that are a parent 
 *      of this configuration in the configuration hierarchy are included 
 *      </li> 
 *      <li> isolated: parameters are contained to within this configuration 
 *      </li> 
 *  </ul>
 *  Values are not OSID objects and are obtained using a reference to a 
 *  Parameter. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ValueLookupSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> Configuration </code> <code> Id </code> associated 
     *  with this session. 
     *
     *  @return object osid_configuration_Configuration the <code> 
     *          Configuration </code> <code> Id </code> associated with this 
     *          session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationId();


    /**
     *  Gets the <code> Configuration </code> associated with this session. 
     *
     *  @return object osid_configuration_Configuration the <code> 
     *          Configuration </code> associated with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfiguration();


    /**
     *  Tests if this user can perform <code> Value </code> lookups. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupValues();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include values from parent configurations in the configuration 
     *  hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedConfigurationView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to this configuration only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedConifgurationView();


    /**
     *  Gets the <code> Values </code> with the given <code> Id. </code> 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> to retrieve 
     *  @return object osid_configuration_ValueList the value list 
     *  @throws osid_NotFoundException the <code> parameterId </code> not 
     *          found 
     *  @throws osid_NullArgumentException the <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParameterValues(osid_id_Id $parameterId);


    /**
     *  Gets the <code> Parameter </code> values with the given <code> Id. 
     *  </code> The returned array is sorted by the value index. 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> to retrieve 
     *  @return array of objects the value list 
     *  @throws osid_NotFoundException the <code> parameterId </code> not 
     *          found 
     *  @throws osid_NullArgumentException the <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getValues(osid_id_Id $parameterId);

}
