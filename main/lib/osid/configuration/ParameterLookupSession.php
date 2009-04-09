<?php

/**
 * osid_configuration_ParameterLookupSession
 * 
 *     Specifies the OSID definition for osid_configuration_ParameterLookupSession.
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
 *  <p>This session is used to retrieve parameters from a registry of 
 *  parameters. </p> 
 *  
 *  <p> This lookup session defines several views. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete and ordered result set or is an 
 *      error condition </li> 
 *      <li> isolated function view: All parameter methods in this session 
 *      operate, retrieve and pertain to parameters defined explicitly in the 
 *      current configuration. Using an isolated view is useful for managing 
 *      <code> Parameters </code> with the <code> ParameterAdminSession. 
 *      </code> </li> 
 *      <li> federated function view; All parameter methods in this session 
 *      operate, retrieve and pertain to all parameters defined in this 
 *      configuration and any other parameters implicitly available in this 
 *      configuration through vault inheritence. </li> 
 *  </ul>
 *  The methods <code> federateParameterView() </code> and <code> 
 *  isolateParameterView() </code> behave as a radio group and one should be 
 *  selected before invoking any lookup methods. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ParameterLookupSession
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
     *  Tests if this user can perform <code> Parameter </code> lookups. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupParameters();


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
     *  Federates the view for methods in this session. A federated view will 
     *  include paramaters from parent configurations in the configuration 
     *  hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedRegistryView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to this configuration only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedRegistryView();


    /**
     *  Gets the <code> Parameter </code> specified by its <code> Id. </code> 
     *  In plenary mode, the exact <code> Id </code> is found or a <code> 
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Parameter 
     *  </code> may have a different <code> Id </code> than requested, such as 
     *  the case where a duplicate <code> Id </code> was assigned to a <code> 
     *  Parameter </code> and retained for compatibility. 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> to rerieve 
     *  @return object osid_configuration_Parameter the returned <code> 
     *          Parameter </code> 
     *  @throws osid_NotFoundException no <code> Parameter </code> found with 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParameter(osid_id_Id $parameterId);


    /**
     *  Gets a <code> ParameterList </code> corresponding to the given <code> 
     *  IdList. </code> In plenary mode, the returned list contains all of the 
     *  parameters specified in the <code> Id </code> list, in the order of 
     *  the list, including duplicates, or an error results if an <code> Id 
     *  </code> in the supplied list is not found or inaccessible. Otherwise, 
     *  inaccessible <code> Parameters </code> may be omitted from the list 
     *  and may present the elements in any order including returning a unique 
     *  set. 
     *
     *  @param object osid_id_IdList $parameterIdList the list of <code> Ids 
     *          </code> to rerieve 
     *  @return object osid_configuration_ParameterList the returned <code> 
     *          Parameter list </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> parameterIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParametersByIds(osid_id_IdList $parameterIdList);


    /**
     *  Gets a <code> ParameterList </code> corresponding to the given 
     *  parameter <code> Type </code> which does not include parameters of 
     *  types derived from the specified <code> Type. </code> In plenary mode, 
     *  the returned list contains all known parameters or an error results. 
     *  Otherwise, the returned list may contain only those parameters that 
     *  are accessible through this session. In both cases, the order of the 
     *  set is not specified. 
     *
     *  @param object osid_type_Type $parameterType a parameter type 
     *  @return object osid_configuration_ParameterList the returned <code> 
     *          Parameter list </code> 
     *  @throws osid_NullArgumentException <code> parameterType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> parameterType </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParametersByType(osid_type_Type $parameterType);


    /**
     *  Gets all <code> Parameters. </code> In plenary mode, the returned list 
     *  contains all known parameters or an error results. Otherwise, the 
     *  returned list may contain only those parameters that are accessible 
     *  through this session. In both cases, the order of the set is not 
     *  specified. 
     *
     *  @return object osid_configuration_ParameterList a list of <code> 
     *          Parameters </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParameters();

}
