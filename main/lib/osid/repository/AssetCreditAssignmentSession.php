<?php

/**
 * osid_repository_AssetCreditAssignmentSession
 * 
 *     Specifies the OSID definition for osid_repository_AssetCreditAssignmentSession.
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
 *  <p>This session defines methods to assign credits to assets. </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetCreditAssignmentSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can change asset credits. A return of true does not 
     *  guarantee successful authorization. A return of false indicates that 
     *  it is known all methods in this session will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations. 
     *
     *  @return boolean <code> false </code> if credit management is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canManageCredits();


    /**
     *  Adds a credit to an asset. 
     *
     *  @param object osid_id_Id $assetId <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_type_Type $creditType type of the credit 
     *  @param object osid_id_Id $resourceId <code> Id </code> of the <code> 
     *          Resource </code> representing a person or organization 
     *  @param object osid_id_Id $aliasId <code> Id </code> of the <code> 
     *          Resource </code> representing an alias (such as an actor's 
     *          role). Use <code> resourceId </code> if there is none. 
     *  @param boolean $principal <code> true </code> if this is a principal 
     *          credit. A principal credit is included in the return of <code> 
     *          getPrincipalCredits(). </code> 
     *  @param integer $sequence a sequence number used for ordering results. 
     *          Specifying a sequence number the same as another performs an 
     *          insert before the existing sequence and sequences of 
     *          subsequent credits may change as a result. Numbers do not need 
     *          to be contiguous. 
     *  @return object osid_repository_AssetCredit the new credit 
     *  @throws osid_AlreadyExistsException a credit on this asset with the 
     *          <code> creditType, </code> <code> resourceId </code> and 
     *          <code> aliasId </code> already exists 
     *  @throws osid_NotFoundException <code> assetId, resourceId </code> or 
     *          <code> aliasId </code> is not found 
     *  @throws osid_NullArgumentException <code> null </code> argument 
     *          provided 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> creditType </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addCredit(osid_id_Id $assetId, osid_type_Type $creditType, 
                              osid_id_Id $resourceId, osid_id_Id $aliasId, 
                              $principal, $sequence);


    /**
     *  Removes a credit from an asset. 
     *
     *  @param object osid_id_Id $assetId <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_type_Type $creditType type of the asset credit 
     *  @param object osid_id_Id $resourceId <code> Id </code> of the <code> 
     *          Resource </code> representing a person or organization 
     *  @param object osid_id_Id $aliasId <code> Id </code> of the <code> 
     *          Resource </code> representing an alias (such as an actor's 
     *          role) 
     *  @return object osid_repository_AssetCreditList all the credits of the 
     *          given type 
     *  @throws osid_NotFoundException credit identified by <code> assetId, 
     *          </code> <code> creditType, </code> <code> resourceId </code> 
     *          and <code> aliasId </code> is not found 
     *  @throws osid_NullArgumentException <code> null </code> argument 
     *          provided 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function removeCredit(osid_id_Id $assetId, 
                                 osid_type_Type $creditType, 
                                 osid_id_Id $resourceId, osid_id_Id $aliasId);


    /**
     *  Updates a credit. 
     *
     *  @param object osid_id_Id $assetId <code> Id </code> of the <code> 
     *          Asset </code> to update 
     *  @param object osid_type_Type $creditType existing type of the credit 
     *  @param object osid_id_Id $resourceId existing <code> Id </code> of the 
     *          <code> Resource </code> 
     *  @param object osid_id_Id $aliasId existing <code> Id </code> of the 
     *          <code> Resource </code> 
     *  @param boolean $principal new principal flag 
     *  @param integer $sequence new sequence number 
     *  @throws osid_NotFoundException credit identified by <code> assetId, 
     *          </code> <code> creditType, </code> <code> resourceId </code> 
     *          and <code> aliasId </code> is not found 
     *  @throws osid_NullArgumentException <code> null </code> argument 
     *          provided 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateCredit(osid_id_Id $assetId, 
                                 osid_type_Type $creditType, 
                                 osid_id_Id $resourceId, osid_id_Id $aliasId, 
                                 $principal, $sequence);

}
