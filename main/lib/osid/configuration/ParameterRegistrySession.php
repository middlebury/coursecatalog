<?php

/**
 * osid_configuration_ParameterRegistrySession
 * 
 *     Specifies the OSID definition for osid_configuration_ParameterRegistrySession.
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
 *  <p>This session defines methods for accessing the registries of a 
 *  parameter. </p> 
 *  
 *  <p> This lookup session defines two views: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete result set or is an error 
 *      condition </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ParameterRegistrySession
    extends osid_OsidSession
{


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeParameterView();


    /**
     *  A complete view of the <code> Parameter </code> returns is desired. 
     *  Methods will return what is requested or result in an error. This view 
     *  is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryParameterView();


    /**
     *  Tests if this user can perform lookups on registries of parameters. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer lookup operations. 
     *
     *  @return boolean <code> false </code> if lookups are not authorized, 
     *          <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupParameterRegistries();


    /**
     *  Gets the list of <code> Parameter </code> <code> Ids </code> 
     *  associated with a <code> Registry. </code> 
     *
     *  @param object osid_id_Id $registryId <code> Id </code> of the <code> 
     *          Registry </code> 
     *  @return object osid_id_IdList list of matching parameter <code> Ids 
     *          </code> 
     *  @throws osid_NotFoundException <code> registryId </code> is not found 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParameterIdsByRegistry(osid_id_Id $registryId);


    /**
     *  Gets the list of <code> Parameters </code> associated with a <code> 
     *  Registry. </code> 
     *
     *  @param object osid_id_Id $registryId <code> Id </code> of the <code> 
     *          Registry </code> 
     *  @return object osid_configuration_ParameterList list of matching 
     *          parameters 
     *  @throws osid_NotFoundException <code> registryId </code> is not found 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParametersByRegistry(osid_id_Id $registryId);


    /**
     *  Gets the list of <code> Parameter Ids </code> associated with a list 
     *  of registries. 
     *
     *  @param object osid_id_IdList $registryIdList list of registries 
     *  @return object osid_id_IdList list of parameter <code> Ids </code> 
     *  @throws osid_NullArgumentException <code> parameterIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParameterIdsByRegistries(osid_id_IdList $registryIdList);


    /**
     *  Gets the list of <code> Parameters </code> associated with a list of 
     *  registries. 
     *
     *  @param object osid_id_IdList $registryIdList list of registries 
     *  @return object osid_configuration_ParameterList list of parameters 
     *  @throws osid_NullArgumentException <code> registryIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParametersByRegistries(osid_id_IdList $registryIdList);


    /**
     *  Gets the <code> Registry Ids </code> mapped to a <code> Parameter. 
     *  </code> 
     *
     *  @param object osid_id_Id $parameterId <code> Id </code> of a <code> 
     *          Parameter </code> 
     *  @return object osid_id_IdList list of registry <code> Ids </code> 
     *  @throws osid_NotFoundException <code> parameterId </code> is not found 
     *  @throws osid_NullArgumentException <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistryIdsByParameter(osid_id_Id $parameterId);


    /**
     *  Gets the registries mapped to a <code> Parameter. </code> 
     *
     *  @param object osid_id_Id $parameterId <code> Id </code> of a <code> 
     *          Parameter </code> 
     *  @return object osid_configuration_RegistryList list of registries 
     *  @throws osid_NotFoundException <code> parameterId </code> is not found 
     *  @throws osid_NullArgumentException <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistriesByParameter(osid_id_Id $parameterId);

}
