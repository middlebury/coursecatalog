<?php

/**
 * osid_repository_AssetAlternate
 * 
 *     Specifies the OSID definition for osid_repository_AssetAlternate.
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


/**
 *  <p>An <code> AssetAlternate </code> contains an alternative asset for 
 *  another asset. </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetAlternate
{


    /**
     *  Gets the accessibility criteria this alternative meets. 
     *
     *  @return object osid_type_Type the accessibility type 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAccessibilityType();


    /**
     *  Gets a description pertaining to how this alternative differs from or 
     *  meets an accessibility requirement of the primary asset. 
     *
     *  @return object osid_type_Type the type of this credit 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDescription();


    /**
     *  Gets the <code> Id </code> of the primary asset in this asset group. 
     *  The primary asset may differ from the asset requested if the asset 
     *  requested was not the primary asset in this group. 
     *
     *  @return object osid_id_Id the primary <code> Asset </code> <code> Id 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPrimaryAssetId();


    /**
     *  Gets the primary asset in this asset group. The primary asset may 
     *  differ from the asset requested if the asset requested was not the 
     *  primary asset in this group. 
     *
     *  @return object osid_repository_Asset the primary <code> Asset </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPrimaryAsset();


    /**
     *  Gets the <code> Id </code> of the alternative asset. 
     *
     *  @return object osid_id_Id the alternative <code> Asset </code> <code> 
     *          Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAlternativeAssetId();


    /**
     *  Gets the alternative asset. 
     *
     *  @return object osid_repository_Asset the alternative <code> Asset 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAlternativeAsset();


    /**
     *  Gets a rating measuring the fit of the alternative asset to the 
     *  primary asset. 
     *
     *  @return object osid_grading_Grade a grade 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getGrade();

}
