<?php

/**
 * osid_resource_ResourceBinSession
 * 
 *     Specifies the OSID definition for osid_resource_ResourceBinSession.
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
 *  <code> Bin </code> mappings. A <code> Resource </code> may appear in 
 *  multiple <code> Bins. </code> Each Repository may have its own 
 *  authorizations governing who is allowed to look at it. </p> 
 *  
 *  <p> This lookup session defines several views: </p> 
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
 * @package org.osid.resource
 */
interface osid_resource_ResourceBinSession
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
    public function useComparativeBinView();


    /**
     *  A complete view of the <code> Resource </code> and <code> Bin </code> 
     *  returns is desired. Methods will return what is requested or result in 
     *  an error. This view is used when greater precision is desired at the 
     *  expense of interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryBinView();


    /**
     *  Tests if this user can perform lookups of resource/bin mappings. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known lookup methods in this session 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer lookup operations 
     *  to unauthorized users. 
     *
     *  @return boolean <code> false </code> if looking up mappings is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupResourceBinMappings();


    /**
     *  Gets the list of <code> Resource </code> <code> Ids </code> associated 
     *  with a <code> Bin. </code> 
     *
     *  @param object osid_id_Id $binId <code> Id </code> of a <code> Bin 
     *          </code> 
     *  @return object osid_id_IdList list of related resource <code> Ids 
     *          </code> 
     *  @throws osid_NotFoundException <code> binId </code> is not found 
     *  @throws osid_NullArgumentException <code> binId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceIdsByBin(osid_id_Id $binId);


    /**
     *  Gets the list of <code> Resources </code> associated with a <code> 
     *  Bin. </code> 
     *
     *  @param object osid_id_Id $binId <code> Id </code> of a <code> Bin 
     *          </code> 
     *  @return object osid_resource_ResourceList list of related resources 
     *  @throws osid_NotFoundException <code> binId </code> is not found 
     *  @throws osid_NullArgumentException <code> binId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourcesByBin(osid_id_Id $binId);


    /**
     *  Gets the list of <code> Resource Ids </code> corresponding to a list 
     *  of <code> Bin </code> objects. 
     *
     *  @param object osid_id_IdList $binIdList list of bin <code> Ids </code> 
     *  @return object osid_id_IdList list of resource <code> Ids </code> 
     *  @throws osid_NullArgumentException <code> binIdList </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceIdsByBins(osid_id_IdList $binIdList);


    /**
     *  Gets the list of <code> Resources </code> corresponding to a list of 
     *  <code> Bins. </code> 
     *
     *  @param object osid_id_IdList $binIdList list of bin <code> Ids </code> 
     *  @return object osid_resource_ResourceList list of resources 
     *  @throws osid_NullArgumentException <code> binIdList </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetsByBins(osid_id_IdList $binIdList);


    /**
     *  Gets the list of <code> Bin </code> <code> Ids </code> mapped to a 
     *  <code> Resource. </code> 
     *
     *  @param object osid_id_Id $resourceId <code> Id </code> of a <code> 
     *          Resource </code> 
     *  @return object osid_id_IdList list of bin <code> Ids </code> 
     *  @throws osid_NotFoundException <code> resourceId </code> is not found 
     *  @throws osid_NullArgumentException <code> resourceId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getBinIdsByResource(osid_id_Id $resourceId);


    /**
     *  Gets the list of <code> Bin </code> objects mapped to a <code> 
     *  Resource. </code> 
     *
     *  @param object osid_id_Id $resourceId <code> Id </code> of a <code> 
     *          Resource </code> 
     *  @return object osid_resource_BinList list of bins 
     *  @throws osid_NotFoundException <code> resourceId </code> is not found 
     *  @throws osid_NullArgumentException <code> resourceId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getBinsByResource(osid_id_Id $resourceId);

}
