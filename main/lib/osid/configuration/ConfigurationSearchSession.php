<?php

/**
 * osid_configuration_ConfigurationSearchSession
 * 
 *     Specifies the OSID definition for osid_configuration_ConfigurationSearchSession.
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
 *  <p>This session provides methods for searching among <code> Configuration 
 *  </code> objects. The search query is constructed using the <code> 
 *  ConfigurationQuery </code> interface. Multiple <code> ConfigurationQuery 
 *  </code> interfaces may be submitted into a search to perform a boolean 
 *  <code> OR. </code> If more than one search element is specified within a 
 *  single <code> ConfigurationQuery, </code> these elements form a boolean 
 *  <code> AND. </code> <code> getConfigurationsByQuery() </code> is the basic 
 *  search method and returns a list of <code> Configuration </code> objects.A 
 *  more advanced search may be performed with <code> 
 *  getConfigurationsBySearch(). </code> It accepts a <code> 
 *  ConfigurationSearch </code> interface in addition to the query interface 
 *  for the purpose of specifying additional options affecting the entire 
 *  search, such as ordering. <code> getConfigurationsBySearch() </code> 
 *  returns a <code> ConfigurationSearchResults </code> interface that can be 
 *  used to access the resulting <code> ConfigurationList </code> or be used 
 *  to perform a search within the result set through <code> 
 *  ConfigurationSearch. </code> </p> 
 *  
 *  <p> Configurations may have a query interface indicated by their 
 *  respective interface types. The typed query interface is accessed via the 
 *  <code> ConfigurationQuery. </code> The returns in this session may not be 
 *  cast directly to these interfaces. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ConfigurationSearchSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> Configuration </code> searches. 
     *  A return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer search operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if search methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canSearchConfigurations();


    /**
     *  Gets a configuration query interface. The returned query will not have 
     *  a typed extension query. 
     *
     *  @return object osid_configuration_ConfigurationQuery the configuration 
     *          query 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationQuery();


    /**
     *  Gets a configuration query interface of the specified interface type. 
     *  The returned query contains the method to access the query interface 
     *  corresponding to the specified <code> Type </code> but must not be 
     *  cast directly from this method return. 
     *
     *  @param object osid_type_Type $configurationInterfaceType the type of 
     *          query interface to retrieve 
     *  @return object osid_configuration_ConfigurationQuery the configuration 
     *          query 
     *  @throws osid_NullArgumentException <code> configurationInterfaceType 
     *          </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          ConfigurationManager.supportsConfigurationInterfaceType(configurationInterfaceType) 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationQueryForInterfaceType(osid_type_Type $configurationInterfaceType);


    /**
     *  Gets a list of <code> Configurations </code> matching the given search 
     *  interface. Each element in the array is OR'd. 
     *
     *  @param array $configurationQueries the search query array 
     *  @return object osid_configuration_ConfigurationList the returned 
     *          <code> ConfigurationList </code> 
     *  @throws osid_NullArgumentException <code> configurationQueries </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException a <code> configurationQuery </code> 
     *          is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationsByQuery(array $configurationQueries);


    /**
     *  Gets a configuration search interface. The returned query only makes 
     *  available the core <code> ConfigurationSearch </code> interface and 
     *  does not support additional interface types. <code> 
     *  getConfigurationSearchForInterfaceType() </code> should be used if 
     *  additional interface types are required. 
     *
     *  @return object osid_configuration_ConfigurationSearch the 
     *          configuration search interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationSearch();


    /**
     *  Gets a configuration search interface of the specified type. The 
     *  returned search interface provides access to the search interface 
     *  corresponding to the specified <code> Type </code> but may not be cast 
     *  directly from this method return. 
     *
     *  @param object osid_type_Type $configurationSearchInterfaceType the 
     *          type of query to retrieve 
     *  @return object osid_configuration_ConfigurationSearch the 
     *          configuration search options 
     *  @throws osid_NullArgumentException <code> configurationInterfaceType 
     *          </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          ConfigurationManager.supportsConfigurationSearchType(configurationSearchInterfaceType) 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationSearchForInterfaceType(osid_type_Type $configurationSearchInterfaceType);


    /**
     *  Gets a list of <code> Configurations </code> matching the given search 
     *  interface. Each element in the array is OR'd. 
     *
     *  @param array $configurationQueries the search query array 
     *  @param object osid_configuration_ConfigurationSearch $configurationSearch 
     *          the search options 
     *  @return object osid_configuration_ConfigurationSearchResults the 
     *          search results 
     *  @throws osid_NullArgumentException <code> configurationQueries </code> 
     *          or <code> configurationSearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException a <code> configurationQuery </code> 
     *          or <code> configurationSearch </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationsBySearch(array $configurationQueries, 
                                              osid_configuration_ConfigurationSearch $configurationSearch);

}
