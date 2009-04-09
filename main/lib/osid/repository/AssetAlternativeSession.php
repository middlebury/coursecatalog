<?php

/**
 * osid_repository_AssetAlternativeSession
 * 
 *     Specifies the OSID definition for osid_repository_AssetAlternativeSession.
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
 *  <p>This session provides methods to lookup mappings among <code> Assets 
 *  </code> for the purpose of classifying alternative content suitable for 
 *  accessibility. The consumer may look up alternatives if the desired <code> 
 *  AssetContent </code> isn't suitable in format or accessibility type. The 
 *  accessibility <code> Type </code> indicates an accessibility 
 *  characteristic that is tagged in the <code> AssetContent. </code> </p> 
 *  
 *  <p> The alternative asset may include a variety of content meeting the 
 *  accessibility criteria. An audio-based alternative may be available in 
 *  different formats, for example. As such, a content negotiation in the 
 *  <code> Asset </code> repeats following the retrieval of an alternative. 
 *  </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetAlternativeSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> Asset </code> alternative 
     *  lookups. A return of true does not guarantee successful authorization. 
     *  A return of false indicates that it is known all methods in this 
     *  session will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may opt not to offer lookup 
     *  operations. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupAssetAlternatives();


    /**
     *  Finds alternative assets corresponding to the given <code> Asset Id. 
     *  </code> 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @return object osid_repository_AssetAlternateList list of asset 
     *          alternatives 
     *  @throws osid_NotFoundException <code> assetId </code> not found 
     *  @throws osid_NullArgumentException <code> assetId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAlternativeAssets(osid_id_Id $assetId);


    /**
     *  Finds alternative assets that have content corresponding the given 
     *  accessibilitytype. 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_type_Type $accessibilityType the <code> Type 
     *          </code> indicating the accessibility requirement 
     *  @return object osid_repository_AssetAlternateList list of asset 
     *          alternatives 
     *  @throws osid_NotFoundException <code> assetId </code> not found 
     *  @throws osid_NullArgumentException <code> assetId </code> or <code> 
     *          accessibilityType </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAlternativeAssetsByAccessibilityType(osid_id_Id $assetId, 
                                                            osid_type_Type $accessibilityType);

}
