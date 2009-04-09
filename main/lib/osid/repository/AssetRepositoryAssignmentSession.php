<?php

/**
 * osid_repository_AssetRepositoryAssignmentSession
 * 
 *     Specifies the OSID definition for osid_repository_AssetRepositoryAssignmentSession.
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
 *  <p>This session provides methods to re-assign <code> Assets </code> to 
 *  <code> Repositories. </code> An <code> Asset </code> may map to multiple 
 *  <code> Repository </code> objects and removing the last reference to an 
 *  <code> Asset </code> is the equivalent of deleting it. Each <code> 
 *  Repository </code> may have its own authorizations governing who is 
 *  allowed to operate on it. </p> 
 *  
 *  <p> Moving or adding a reference of an <code> Asset </code> to another 
 *  <code> Repository </code> is not a copy operation (eg: does not change its 
 *  <code> Id </code> ). </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetRepositoryAssignmentSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can alter asset/repository mappings. A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known mapping methods in this session will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if mapping is not authorized, 
     *          <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canAssignAssets();


    /**
     *  Adds an existing <code> Asset </code> to a <code> Repository. </code> 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          <code> Repository </code> 
     *  @throws osid_NotFoundException <code> assetId </code> or <code> 
     *          repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> assetId </code> or <code> 
     *          repositoryId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function assignAssetToRepository(osid_id_Id $assetId, 
                                            osid_id_Id $repositoryId);


    /**
     *  Removes an <code> Asset </code> from a <code> Repository. </code> 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          <code> Repository </code> 
     *  @throws osid_NotFoundException <code> assetId </code> or <code> 
     *          repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> assetId </code> or <code> 
     *          repositoryId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function unassignAssetFromRepository(osid_id_Id $assetId, 
                                                osid_id_Id $repositoryId);

}
