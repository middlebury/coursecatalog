<?php

/**
 * osid_repository_AssetAlternativeAssignmentSession
 * 
 *     Specifies the OSID definition for osid_repository_AssetAlternativeAssignmentSession.
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
 *  <p>This session provides methods to manage mappings among <code> Assets 
 *  </code> for the purpose of classifying alternative content suitable for 
 *  accessibility. The mapping is defined as a tuple among the primary asset, 
 *  the alternative asset, and the accessibility type. </p> 
 *  
 *  <p> The alternative asset may include a variety of content meeting the 
 *  accessibility criteria. An audio-based alternative may be available in 
 *  different formats, for example. As such, a content negotiation in the 
 *  <code> Asset </code> repeats following the retrieval of an alternative. 
 *  </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetAlternativeAssignmentSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can change <code> Asset </code> alternative 
     *  mappings. A return of true does not guarantee successful 
     *  authorization. A return of false indicates that it is known all 
     *  methods in this session will result in a <code> PERMISSION_DENIED. 
     *  </code> This is intended as a hint to an application that may opt not 
     *  to offer lookup operations. 
     *
     *  @return boolean <code> false </code> if mapping not authorized, <code> 
     *          true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canManageAssetAlternatives();


    /**
     *  Adds an existing asset as an alternative to another asset. 
     *
     *  @param object osid_id_Id $alternativeAssetId the <code> Id </code> of 
     *          the <code> Asset </code> alternative 
     *  @param object osid_id_Id $primaryAssetId the <code> Id </code> of the 
     *          primary <code> Asset </code> 
     *  @param object osid_type_Type $accessibilityType accessibility type 
     *  @throws osid_NotFoundException <code> primartyAssetId </code> or 
     *          <code> alternativeAssetId </code> not found 
     *  @throws osid_NullArgumentException <code> primaryAssetId, 
     *          alternativeAssetId </code> or <code> accessibilityType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> accessibilityType </code> is 
     *          not supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function assignAlternativeToAsset(osid_id_Id $alternativeAssetId, 
                                             osid_id_Id $primaryAssetId, 
                                             osid_type_Type $accessibilityType);


    /**
     *  Removes an alternative asset from an asset. 
     *
     *  @param object osid_id_Id $alternativeAssetId the <code> Id </code> of 
     *          the <code> Asset </code> alternative 
     *  @param object osid_id_Id $primaryAssetId the <code> Id </code> of the 
     *          primary <code> Asset </code> 
     *  @param object osid_type_Type $accessibilityType accessibility type 
     *  @throws osid_NotFoundException <code> primartyAssetId </code> or 
     *          <code> alternativeAssetId </code> not found 
     *  @throws osid_NullArgumentException <code> primaryAssetId </code> or 
     *          <code> alternativeAssetId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function unassignAlternativeFromAsset(osid_id_Id $alternativeAssetId, 
                                                 osid_id_Id $primaryAssetId, 
                                                 osid_type_Type $accessibilityType);


    /**
     *  Assigns a rating in the form of a grade to the suitability of the 
     *  alternative. 
     *
     *  @param object osid_id_Id $primaryAssetId the <code> Id </code> of the 
     *          primary <code> Asset </code> 
     *  @param object osid_id_Id $alternativeAssetId the <code> Id </code> of 
     *          the <code> Asset </code> alternative 
     *  @param object osid_type_Type $accessibilityType accessibility type 
     *  @param object osid_id_Id $gradeId <code> Id </code> of a <code> Grade 
     *          </code> 
     *  @throws osid_NotFoundException <code> primartyAssetId, 
     *          alternativeAssetId </code> or <code> gradeId </code> not found 
     *  @throws osid_NullArgumentException <code> primaryAssetId, 
     *          alternativeAssetId, </code> <code> accessibilityType </code> 
     *          or <code> gradeId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function rateAssetAlternative(osid_id_Id $primaryAssetId, 
                                         osid_id_Id $alternativeAssetId, 
                                         osid_type_Type $accessibilityType, 
                                         osid_id_Id $gradeId);

}
