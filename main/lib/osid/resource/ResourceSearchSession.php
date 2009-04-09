<?php

/**
 * osid_resource_ResourceSearchSession
 * 
 *     Specifies the OSID definition for osid_resource_ResourceSearchSession.
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
 *  <p>This session provides methods for searching among <code> Resource 
 *  </code> objects. The search query is constructed using the <code> 
 *  ResourceQuery </code> interface. <code> getResourcesByQuery() </code> is 
 *  the basic search method and returns a list of <code> Resources. </code> A 
 *  more advanced search may be performed with <code> getResourcesBySearch(). 
 *  </code> It accepts an <code> ResourceSearch </code> interface in addition 
 *  to the query interface for the purpose of specifying additional options 
 *  affecting the entire search, such as ordering. <code> 
 *  getResourcesBySearch() </code> returns an <code> ResourceSearchResults 
 *  </code> interface that can be used to access the resulting <code> 
 *  ResourceList </code> or be used to perform a search within the result set 
 *  through <code> ResourceList. </code> </p> 
 *  
 *  <p> This session defines views that offer differing behaviors for 
 *  searching. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> federated bin view: searches include resources in bins of which 
 *      this bin is a ancestor in the bin hierarchy </li> 
 *      <li> isolated bin view: searches are restricted to resources in this 
 *      bin </li> 
 *  </ul>
 *  Resources may have a record query interface indicated by their respective 
 *  interface types. The record query interface is accessed via the <code> 
 *  ResourceQuery. </code> </p>
 * 
 * @package org.osid.resource
 */
interface osid_resource_ResourceSearchSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> Bin </code> <code> Id </code> associated with this 
     *  session. 
     *
     *  @return object osid_id_Id the <code> Bin Id </code> associated with 
     *          this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getBinId();


    /**
     *  Gets the <code> Bin </code> associated with this session. 
     *
     *  @return object osid_resource_Bin the <code> Bin </code> associated 
     *          with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getBin();


    /**
     *  Tests if this user can perform <code> Resource </code> searches. A 
     *  return of true does not guarantee successful authorization. A return 
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
    public function canSearchResources();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include resources in bins which are children of this bin in the bin 
     *  hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedBinView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to this bin only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedBinView();


    /**
     *  Gets a resource query interface. The returned query will not have an 
     *  extension query. 
     *
     *  @return object osid_resource_ResourceQuery the resource query 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceQuery();


    /**
     *  Gets a list of <code> Resources </code> matching the given search 
     *  interface. 
     *
     *  @param object osid_resource_ResourceQuery $resourceQuery the search 
     *          query 
     *  @return object osid_resource_ResourceList the returned <code> 
     *          ResourceList </code> 
     *  @throws osid_NullArgumentException <code> resourceQuery </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> resourceQuery </code> is not 
     *          of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourcesByQuery(osid_resource_ResourceQuery $resourceQuery);


    /**
     *  Gets a resource search interface. 
     *
     *  @return object osid_resource_ResourceSearch the resource search 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceSearch();


    /**
     *  Gets a resource search order interface. The <code> ResourceSearchOrder 
     *  </code> is supplied to a <code> ResourceSearch </code> to specify the 
     *  ordering of results. 
     *
     *  @return object osid_resource_ResourceSearchOrder the resource search 
     *          order interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceSearchOrder();


    /**
     *  Gets the search results matching the given search query using the 
     *  given search. 
     *
     *  @param object osid_resource_ResourceQuery $resourceQuery the search 
     *          query 
     *  @param object osid_resource_ResourceSearch $resourceSearch the search 
     *          interface 
     *  @return object osid_resource_ResourceSearchResults the returned search 
     *          results 
     *  @throws osid_NullArgumentException <code> resourceQuery </code> or 
     *          <code> resourceSearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> resourceQuery </code> or 
     *          <code> resourceSearch </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourcesBySearch(osid_resource_ResourceQuery $resourceQuery, 
                                         osid_resource_ResourceSearch $resourceSearch);

}
