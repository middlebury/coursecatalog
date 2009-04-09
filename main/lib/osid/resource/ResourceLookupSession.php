<?php

/**
 * osid_resource_ResourceLookupSession
 * 
 *     Specifies the OSID definition for osid_resource_ResourceLookupSession.
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
 *  <p>This session defines methods for retrieving resources. A <code> 
 *  Resource </code> is an arbitrary entity that may represent a person, place 
 *  or thing used to identify an object used in various services. </p> 
 *  
 *  <p> This lookup session defines several views: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete result set or is an error 
 *      condition </li> 
 *      <li> isolated bin view: All resource methods in this session operate, 
 *      retrieve and pertain to resources defined explicitly in the current 
 *      bin. Using an isolated view is useful for managing <code> Resources 
 *      </code> with the <code> ResourceAdminSession. </code> </li> 
 *      <li> federated bin view: All resource methods in this session operate, 
 *      retrieve and pertain to all resources defined in this bin and any 
 *      other resources implicitly available in this bin through bin 
 *      inheritence. </li> 
 *  </ul>
 *  The methods <code> useFederatedBinView() </code> and <code> 
 *  useIsolatedBinView() </code> behave as a radio group and one should be 
 *  selected before invoking any lookup methods. Resources may have an 
 *  additional records indicated by their respective record types. The record 
 *  may not be accessed through a cast of the <code> Resource. </code> </p>
 * 
 * @package org.osid.resource
 */
interface osid_resource_ResourceLookupSession
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
     *  Tests if this user can perform <code> Resource </code> lookups. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer lookup operations. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupResources();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeResourceView();


    /**
     *  A complete view of the <code> Resource </code> returns is desired. 
     *  Methods will return what is requested or result in an error. This view 
     *  is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryResourceView();


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
     *  Gets the <code> Resource </code> specified by its <code> Id. </code> 
     *  In plenary mode, the exact <code> Id </code> is found or a <code> 
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Resource 
     *  </code> may have a different <code> Id </code> than requested, such as 
     *  the case where a duplicate <code> Id </code> was assigned to a <code> 
     *  Resource </code> and retained for compatibility. 
     *
     *  @param object osid_id_Id $resourceId the <code> Id </code> of the 
     *          <code> Resource </code> to rerieve 
     *  @return object osid_resource_Resource the returned <code> Resource 
     *          </code> 
     *  @throws osid_NotFoundException no <code> Resource </code> found with 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> resourceId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResource(osid_id_Id $resourceId);


    /**
     *  Gets a <code> ResourceList </code> corresponding to the given <code> 
     *  IdList. </code> In plenary mode, the returned list contains all of the 
     *  resources specified in the <code> Id </code> list, in the order of the 
     *  list, including duplicates, or an error results if an <code> Id 
     *  </code> in the supplied list is not found or inaccessible. Otherwise, 
     *  inaccessible <code> Resources </code> may be omitted from the list and 
     *  may present the elements in any order including returning a unique 
     *  set. 
     *
     *  @param object osid_id_IdList $resourceIdList the list of <code> Ids 
     *          </code> to rerieve 
     *  @return object osid_resource_ResourceList the returned <code> Resource 
     *          list </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> resourceIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourcesByIds(osid_id_IdList $resourceIdList);


    /**
     *  Gets a <code> ResourceList </code> corresponding to the given resource 
     *  genus <code> Type </code> which does not include resources of types 
     *  derived from the specified <code> Type. </code> In plenary mode, the 
     *  returned list contains all known resources or an error results. 
     *  Otherwise, the returned list may contain only those resources that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @param object osid_type_Type $resourceGenusType a resource genus type 
     *  @return object osid_resource_ResourceList the returned <code> Resource 
     *          list </code> 
     *  @throws osid_NullArgumentException <code> resourceGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourcesByGenusType(osid_type_Type $resourceGenusType);


    /**
     *  Gets a <code> ResourceList </code> corresponding to the given resource 
     *  genus <code> Type </code> and include any additional resources with 
     *  genus types derived from the specified <code> Type. </code> In plenary 
     *  mode, the returned list contains all known resources or an error 
     *  results. Otherwise, the returned list may contain only those resources 
     *  that are accessible through this session. In both cases, the order of 
     *  the set is not specified. 
     *
     *  @param object osid_type_Type $resourceGenusType a resource genus type 
     *  @return object osid_resource_ResourceList the returned <code> Resource 
     *          list </code> 
     *  @throws osid_NullArgumentException <code> resourceGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourcesByParentGenusType(osid_type_Type $resourceGenusType);


    /**
     *  Gets a <code> ResourceList </code> containing the given resource 
     *  record <code> Type. </code> In plenary mode, the returned list 
     *  contains all known resources or an error results. Otherwise, the 
     *  returned list may contain only those resources that are accessible 
     *  through this session. In both cases, the order of the set is not 
     *  specified. 
     *
     *  @param object osid_type_Type $resourceRecordType a resource record 
     *          type 
     *  @return object osid_resource_ResourceList the returned <code> Resource 
     *          list </code> 
     *  @throws osid_NullArgumentException <code> resourceRecordType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourcesByRecordType(osid_type_Type $resourceRecordType);


    /**
     *  Gets all <code> Resources. </code> In plenary mode, the returned list 
     *  contains all known resources or an error results. Otherwise, the 
     *  returned list may contain only those resources that are accessible 
     *  through this session. In both cases, the order of the set is not 
     *  specifed. 
     *
     *  @return object osid_resource_ResourceList a list of <code> Resources 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResources();

}
