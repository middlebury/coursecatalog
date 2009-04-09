<?php

/**
 * osid_repository_AssetCompositionAssignmentSession
 * 
 *     Specifies the OSID definition for osid_repository_AssetCompositionAssignmentSession.
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
 *  <p>This session provides the means for adding assets to an asset 
 *  composiiton. The asset is identified inside a composition using its own 
 *  Id. To add the same asset to the composition, multiple compositions should 
 *  be used and placed at the same level in the <code> Composition </code> 
 *  hierarchy. </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetCompositionAssignmentSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can manage mapping of <code> Assets </code> to 
     *  <code> Compositions. </code> A return of true does not guarantee 
     *  successful authorization. A return of false indicates that it is known 
     *  all methods in this session will result in a <code> PERMISSION_DENIED. 
     *  </code> This is intended as an application hint that may opt not to 
     *  offer hierarchy operations. 
     *
     *  @return boolean <code> false </code> if asset composiion is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canComposeAssets();


    /**
     *  Appends an asset to a composition. 
     *
     *  @param object osid_id_Id $assetId <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_id_Id $compositionId <code> Id </code> of the 
     *          <code> Composition </code> 
     *  @throws osid_AlreadyExistsException composition already contains asset 
     *  @throws osid_NotFoundException <code> assetId </code> or <code> 
     *          compositionId </code> not found 
     *  @throws osid_NullArgumentException <code> assetId </code> or <code> 
     *          compositionId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization fauilure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addAsset(osid_id_Id $assetId, osid_id_Id $compositionId);


    /**
     *  Reorders assets in a composition by moving the specified asset in 
     *  front of a reference asset. 
     *
     *  @param object osid_id_Id $assetId <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_id_Id $compositionId <code> Id </code> of the 
     *          <code> Composition </code> 
     *  @param object osid_id_Id $referenceId <code> Id </code> of the 
     *          reference <code> Asset </code> 
     *  @throws osid_NotFoundException <code> assetId </code> or <code> 
     *          referenceId </code> <code> not found in composition </code> 
     *  @throws osid_NullArgumentException <code> assetId, referenceId </code> 
     *          or <code> compositionId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization fauilure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function moveAssetAhead(osid_id_Id $assetId, 
                                   osid_id_Id $compositionId, 
                                   osid_id_Id $referenceId);


    /**
     *  Reorders assets in a composition by moving the specified asset behind 
     *  of a reference asset. 
     *
     *  @param object osid_id_Id $assetId <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_id_Id $compositionId <code> Id </code> of the 
     *          <code> Composition </code> 
     *  @param object osid_id_Id $referenceId <code> Id </code> of the 
     *          reference <code> Asset </code> 
     *  @throws osid_NotFoundException <code> assetId </code> or <code> 
     *          referenceId </code> <code> not found in composition </code> 
     *  @throws osid_NullArgumentException <code> assetId, referenceId </code> 
     *          or <code> compositionId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization fauilure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function moveAssetBehind(osid_id_Id $assetId, 
                                    osid_id_Id $compositionId, 
                                    osid_id_Id $referenceId);


    /**
     *  Removes an <code> Asset </code> from a <code> Composition. </code> 
     *
     *  @param object osid_id_Id $assetId <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_id_Id $compositionId <code> Id </code> of the 
     *          <code> Composition </code> 
     *  @throws osid_NotFoundException <code> assetId </code> <code> not found 
     *          in composition </code> 
     *  @throws osid_NullArgumentException <code> assetId </code> or <code> 
     *          compositionId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization fauilure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function removeAsset(osid_id_Id $assetId, osid_id_Id $compositionId);

}
