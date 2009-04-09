<?php

/**
 * osid_resource_GroupSession
 * 
 *     Specifies the OSID definition for osid_resource_GroupSession.
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
 *  <p>This session provides methods to retrieve <code> Resource </code> to 
 *  <code> Group </code> mappings. A <code> Resource </code> may appear in 
 *  multiple resource groups. A group is also represented by a resource 
 *  itself. </p>
 * 
 * @package org.osid.resource
 */
interface osid_resource_GroupSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform lookups of resource members. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known lookup methods in this session will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if looking up members is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupResourceMembers();


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
     *  include resources in groups which are children of the specified group 
     *  in the group hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedGroupView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to the specified group only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedGroupView();


    /**
     *  Gets the list of <code> Resource </code> <code> Ids </code> associated 
     *  with a <code> Resource. </code> In a federated view, 
     *
     *  @param object osid_id_Id $groupResourceId <code> Id </code> of the 
     *          <code> Resource </code> 
     *  @return object osid_id_IdList list of member resource <code> Ids 
     *          </code> 
     *  @throws osid_NotFoundException <code> groupResourceId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> groupResourceId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceIdsByGroup(osid_id_Id $groupResourceId);


    /**
     *  Gets the list of <code> Resources </code> associated with a <code> 
     *  Resource. </code> 
     *
     *  @param object osid_id_Id $groupResourceId <code> Id </code> of the 
     *          <code> Resource </code> 
     *  @return object osid_resource_ResourceList list of resourcememembers 
     *  @throws osid_NotFoundException <code> groupResourceId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> groupResourceId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourcesByGroup(osid_id_Id $groupResourceId);


    /**
     *  Gets the list of <code> Resource Ids </code> corresponding to a list 
     *  of <code> Resource </code> objects. 
     *
     *  @param object osid_id_IdList $groupResourceIdList list of resource 
     *          <code> Ids </code> 
     *  @return object osid_id_IdList list of resource <code> Ids </code> 
     *  @throws osid_NullArgumentException <code> groupResourceIdList </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceIdsByGroups(osid_id_IdList $groupResourceIdList);


    /**
     *  Gets the list of <code> Resources </code> corresponding to a list of 
     *  <code> Resource </code> objects. 
     *
     *  @param object osid_id_IdList $groupResourceIdList list of resource 
     *          <code> Ids </code> 
     *  @return object osid_resource_ResourceList list of resources 
     *  @throws osid_NullArgumentException <code> groupResourceIdLIst </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourcesByGroups(osid_id_IdList $groupResourceIdList);


    /**
     *  Gets the list of <code> Resource </code> <code> Ids </code> mapped to 
     *  a <code> Resource. </code> 
     *
     *  @param object osid_id_Id $resourceId <code> Id </code> of a <code> 
     *          Resource </code> 
     *  @return object osid_id_IdList list of group resource <code> Ids 
     *          </code> 
     *  @throws osid_NotFoundException <code> resourceId </code> is not found 
     *  @throws osid_NullArgumentException <code> resourceId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getGroupIdsByResource(osid_id_Id $resourceId);


    /**
     *  Gets the list of <code> Resource </code> objects mapped to a <code> 
     *  Resource. </code> 
     *
     *  @param object osid_id_Id $resourceId <code> Id </code> of a <code> 
     *          Resource </code> 
     *  @return object osid_resource_ResourceList list of group resources 
     *  @throws osid_NotFoundException <code> resourceId </code> is not found 
     *  @throws osid_NullArgumentException <code> resourceId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getGroupsByResource(osid_id_Id $resourceId);

}
