<?php

/**
 * osid_configuration_ParameterSearchSession
 * 
 *     Specifies the OSID definition for osid_configuration_ParameterSearchSession.
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
 *  <p>This session provides methods for searching <code> Parameter </code> 
 *  objects. The search query is constructed using the <code> ParameterQuery 
 *  </code> interface. Multiple <code> ParaneterQuery </code> interfaces may 
 *  be submitted into a search to perform a boolean OR. If more than one 
 *  search element is specified within a single <code> ParameterQuery </code> 
 *  these elements form a boolean AND. </p> 
 *  
 *  <p> <code> getParametersByQuery() </code> is the basic search method and 
 *  returns a list of <code> Parameters. </code> A more advanced search may be 
 *  performed with <code> getParametersBySearch(). </code> It accepts a <code> 
 *  ParaneterSearch </code> interface in addition to the query interface for 
 *  the purpose of specifying additional options affecting the entire search, 
 *  such as ordering. <code> getParametersBySearch() </code> returns a <code> 
 *  ParameterSearchResults </code> interface that can be used to access the 
 *  resulting <code> ParameterList </code> or be used to perform a search 
 *  within the result set through <code> ParameterSearch. </code> Two views of 
 *  the configuration data are defined; </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> federated: parameters defined in configurations that are a parent 
 *      of this configuration in the configuration hierarchy are included 
 *      </li> 
 *      <li> isolated: parameters are contained to within this configuration 
 *      </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ParameterSearchSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> Registry </code> <code> Id </code> associated with 
     *  this session. 
     *
     *  @return object osid_id_Id the <code> Registry </code> <code> Id 
     *          </code> associated with this session 
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
     *  Tests if this user can perform <code> Parameter </code> searches. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer search operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canSearchParameters();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include parameters from parent configurations in the registry 
     *  hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedRegistryView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts searches to this configuration only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedRegistryView();


    /**
     *  Gets a paraameter query interface. 
     *
     *  @return object osid_configuration_ValueQuery the value query 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParameterQuery();


    /**
     *  Gets a list of <code> Parameters </code> matching the given query 
     *  interface. Each element in the array is OR'd. 
     *
     *  @param array $parameterQueries the search query array 
     *  @return object osid_configuration_ParameterList the returned <code> 
     *          ParameterList </code> 
     *  @throws osid_NullArgumentException <code> parameterQueries </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException a query form is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParametersByQuery(array $parameterQueries);


    /**
     *  Gets a parameter search interface. The returned query only makes 
     *  available the core <code> Paraneter </code> interface and does not 
     *  support additional interface types. <code> 
     *  getParameterSearchForInterfaceType() </code> should be used if 
     *  additional interface types are required. 
     *
     *  @return object osid_configuration_ParameterSearch the paraneter search 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParameterSearch();


    /**
     *  Gets a parameter search interface of the specified type. The returned 
     *  query contains the search interface extension corresponding to the 
     *  specified <code> Type. </code> 
     *
     *  @param object osid_type_Type $parameterSearchInterfaceType the type of 
     *          search interface to retrieve 
     *  @return object osid_configuration_ParameterSearch the search results 
     *  @throws osid_NullArgumentException <code> parameterSearchInterfaceType 
     *          </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          ConfigurationManager.supportsParameterType(parameterSearchInterfaceType) 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getValueSearchForInterfaceType(osid_type_Type $parameterSearchInterfaceType);


    /**
     *  Gets a list of <code> Values </code> matching the given search query 
     *  using the given search interface. Each element in the array is OR'd. 
     *
     *  @param array $parameterQueries the search query array 
     *  @param object osid_configuration_ValueSearch $parameterSearch the 
     *          search options 
     *  @return object osid_configuration_ParameterSearchResults the results 
     *  @throws osid_NullArgumentException <code> parameterQueries </code> or 
     *          <code> parameterSearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> parameterSearch </code> or a 
     *          query form is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParametersBySearch(array $parameterQueries, 
                                          osid_configuration_ValueSearch $parameterSearch);

}
