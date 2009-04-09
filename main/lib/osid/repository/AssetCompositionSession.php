<?php

/**
 * osid_repository_AssetCompositionSession
 * 
 *     Specifies the OSID definition for osid_repository_AssetCompositionSession.
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
 * @package org.osid.repository
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session defines methods for looking up <code> Asset </code> to 
 *  <code> Composition </code> mappings. A <code> Composition </code> 
 *  represents a collection of <code> Assets. </code> </p> 
 *  
 *  <p> This lookup session defines several views: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete result set or is an error 
 *      condition </li> 
 *      <li> isolated view: only assets in the specified composition are 
 *      accessible </li> 
 *      <li> federated view: includes assets in compositions that are 
 *      descendants of the specified asset </li> 
 *  </ul>
 *  The methods <code> useFederatedAssetCompositionView() </code> and <code> 
 *  useIsolatedAssetCompositiontView() </code> behave as a radio group and one 
 *  should be selected before invoking any lookup methods. </p> 
 *  
 *  <p> </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetCompositionSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform composition lookups. A return of true 
     *  does not guarantee successful authorization. A return of false 
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
    public function canAccessAssetCompositions();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeAssetCompositionView();


    /**
     *  A complete view of the returns is desired. Methods will return what is 
     *  requested or result in an error. This view is used when greater 
     *  precision is desired at the expense of interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryAssetCompositionView();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include assets in compositions of which this composition is a child in 
     *  the composition hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedAssetCompositionView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to this repository only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedAssetCompositionView();


    /**
     *  Gets the list of assets mapped to the given Composition. In plenary 
     *  mode, the exact <code> Id </code> is found or a <code> NOT_FOUND 
     *  </code> results. Otherwise, the returned <code> AssetList </code> may 
     *  have a different <code> Id </code> than requested, such as the case 
     *  where a duplicate <code> Id </code> was assigned to a <code> 
     *  Repository </code> and retained for compatibility. 
     *
     *  @param object osid_id_Id $compositionId <code> Id </code> of the 
     *          <code> Composition </code> 
     *  @return object osid_repository_AssetList list of assets 
     *  @throws osid_NotFoundException <code> compositionId </code> not found 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getCompositionAssets(osid_id_Id $compositionId);


    /**
     *  Gets the <code> Repository </code> specified by its <code> Id. </code> 
     *  In plenary mode, the exact <code> Id </code> is found or a <code> 
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Repository 
     *  </code> may have a different <code> Id </code> than requested, such as 
     *  the case where a duplicate <code> Id </code> was assigned to a <code> 
     *  Repository </code> and retained for compatibility. 
     *
     *  @param object osid_id_Id $assetId <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @return object osid_repository_CompositionList the returned <code> 
     *          Composition list </code> 
     *  @throws osid_NotFoundException <code> assetId </code> is not found 
     *  @throws osid_NullArgumentException <code> assetId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionsByAsset(osid_id_Id $assetId);

}
