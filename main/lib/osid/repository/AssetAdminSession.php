<?php

/**
 * osid_repository_AssetAdminSession
 * 
 *     Specifies the OSID definition for osid_repository_AssetAdminSession.
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
 *  <p>This session creates and removes assets. The data for create and update 
 *  is provided via the <code> AssetForm. </code> </p> 
 *  
 *  <p> The view of the administrative methods defined in this session is 
 *  determined by the provider. For an instance of this session where no 
 *  repository has been specified, it may not be parallel to the <code> 
 *  AssetLookupSession. </code> For example, a default <code> 
 *  AssetLookupSession </code> may view the entire repository hierarchy while 
 *  the default <code> AssetAdminSession </code> uses an isolated <code> 
 *  Repository </code> to create new <code> Assets </code> or <code> </code> a 
 *  specific repository to operate on a predetermined set of <code> Assets. 
 *  </code> Another scenario is a federated provider who does not wish to 
 *  permit administrative operations for the federation unaware. Example 
 *  create: </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       if (!session.canCreateAssets()) {
 *           return "asset creation not permitted";
 *       }
 *       
 *       Type types[1];
 *       types[0] = assetPhotographType; 
 *       if (!session.canCreateAssetWithRecordTypes(types)) {
 *           return "creating an asset with a photograph type is not supported";
 *       }
 *       
 *       AssetForm form = session.getAssetFormForCreate();
 *       Metadata metadata = form.getDisplayNameMetadata();
 *       if (metadata.isReadOnly()) {
 *           return "cannot set display name";
 *       }
 *       
 *       form.setDisplayName("my photo");
 *       
 *       PhotographRecordForm photoForm = (PhotographRecordForn) form.getRecordForm(assetPhotogaphType);
 *       Metadata metadata = form.getApertureMetadata();
 *       if (metadata.isReadOnly()) {
 *           return ("cannot set aperture");
 *       }
 *       
 *       photoForm.setAperture("5.6");
 *       if (!form.isValid()) {
 *           return form.getValidationMessage();
 *       }
 *       
 *       Asset newAsset = session.createAsset(form);
 *       
 *                   
 *       
 *  </pre>
 *  </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetAdminSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> Repository </code> <code> Id </code> associated with 
     *  this session. 
     *
     *  @return object osid_id_Id the <code> Repository Id </code> associated 
     *          with this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepositoryId();


    /**
     *  Gets the <code> Repository </code> associated with this session. 
     *
     *  @return object osid_repository_Repository the <code> Repository 
     *          </code> associated with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepository();


    /**
     *  Tests if this user can create <code> Assets. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating an <code> Asset </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer create operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Asset </code> ceration 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateAssets();


    /**
     *  Tests if this user can create a single <code> Asset </code> using the 
     *  desired record types. While <code> 
     *  RepositoryManager.getAssetRecordTypes() </code> can be used to examine 
     *  which records are supported, this method tests which record(s) are 
     *  required for creating a specific <code> Asset. </code> Providing an 
     *  empty array tests if an <code> Asset </code> can be created with no 
     *  records. 
     *
     *  @param array $assetRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> Asset </code> creation 
     *          using the specified record <code> Types </code> is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> assetRecordTypes </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateAssetWithRecordTypes(array $assetRecordTypes);


    /**
     *  Gets the asset form for creating new assets. A new form should be 
     *  requested for each create transaction. 
     *
     *  @return object osid_repository_AssetForm the asset form 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetFormForCreate();


    /**
     *  Creates a new <code> Asset. </code> 
     *
     *  @param object osid_repository_AssetForm $assetForm the form for this 
     *          <code> Asset </code> 
     *  @return object osid_repository_Asset the new <code> Asset </code> 
     *  @throws osid_AlreadyExistsException attempt at duplicating a property 
     *          the underlying system is enforcing to be unique 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> assetForm </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> assetForm </code> is not of 
     *          this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createAsset(osid_repository_AssetForm $assetForm);


    /**
     *  Tests if this user can update <code> Assets. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating an <code> Asset </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer update operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Asset </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateAssets();


    /**
     *  Tests if this user can update a specified <code> Asset. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known updating the <code> Asset </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer an update operation 
     *  to an unauthorized user for this <code> Asset. </code> 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @return boolean <code> false </code> if asset modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> assetId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> assetId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateAsset(osid_id_Id $assetId);


    /**
     *  Gets the asset form for updating an existing asset. A new asset form 
     *  should be requested for each update transaction. 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @return object osid_repository_AssetForm the asset form 
     *  @throws osid_NotFoundException <code> assetId </code> is not found 
     *  @throws osid_NullArgumentException <code> assetId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetFormForUpdate(osid_id_Id $assetId);


    /**
     *  Updates an existing asset. 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_repository_AssetForm $assetForm the form containing 
     *          the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> assetId </code> is not found 
     *  @throws osid_NullArgumentException <code> assetId </code> or <code> 
     *          assetForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> assetForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateAsset(osid_id_Id $assetId, 
                                osid_repository_AssetForm $assetForm);


    /**
     *  Tests if this user can delete <code> Assets. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting an <code> Asset </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer delete operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Asset </code> deletion 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteAssets();


    /**
     *  Tests if this user can delete a specified <code> Asset. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> Asset </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer an delete operation 
     *  to an unauthorized user for this asset. 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @return boolean <code> false </code> if deletion of this <code> Asset 
     *          </code> is not authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> assetId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> assetId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteAsset(osid_id_Id $assetId);


    /**
     *  Deletes an <code> Asset. </code> 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> to remove 
     *  @throws osid_NotFoundException <code> assetId </code> not found 
     *  @throws osid_NullArgumentException <code> assetId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteAsset(osid_id_Id $assetId);


    /**
     *  Adds an <code> Id </code> to an <code> Asset </code> for the purpose 
     *  of creating compatibility. The primary <code> Id </code> of the <code> 
     *  Asset </code> is determined by the provider. The new <code> Id </code> 
     *  performs as an alias to the primary <code> Id. </code> 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of an <code> 
     *          Asset </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> assetId </code> not found 
     *  @throws osid_NullArgumentException <code> assetId </code> or <code> 
     *          aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToAsset(osid_id_Id $assetId, osid_id_Id $aliasId);


    /**
     *  Tests if this user can create content for <code> Assets. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known creating an <code> Asset </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer create operations 
     *  to an unauthorized user. 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of an <code> 
     *          Asset </code> 
     *  @return boolean <code> false </code> if <code> Asset </code> content 
     *          ceration is not authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> assetId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateAssetContent(osid_id_Id $assetId);


    /**
     *  Tests if this user can create an <code> AssetContent </code> using the 
     *  desired record interface types. While <code> 
     *  RepositoryManager.getAssetContentRecordTypes() </code> can be used to 
     *  test which record interfaces are supported, this method tests which 
     *  record interface(s) are required for creating a specific <code> 
     *  AssetContent. </code> Providing an empty array tests if an <code> 
     *  AssetContent </code> can be created with no records. 
     *
     *  @param array $assetContentRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> AssetContent </code> 
     *          creation using the specified <code> Types </code> is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> assetContentRecordTypes 
     *          </code> is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateAssetContentWithRecordTypes(array $assetContentRecordTypes);


    /**
     *  Gets an asset content form for creating new assets. 
     *
     *  @return object osid_repository_AssetContentForm the asset content form 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetContentFormForCreate();


    /**
     *  Creates new <code> AssetContent </code> for a given asset. 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> to associat with this content 
     *  @param object osid_repository_AssetContentForm $assetContentForm the 
     *          form for this <code> AssetContent </code> 
     *  @return object osid_repository_AssetContent the new <code> 
     *          AssetContent </code> 
     *  @throws osid_AlreadyExistsException attempt at duplicating a property 
     *          the underlying system is enforcing to be unique 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> assetContentForm </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> assetContentForm </code> is 
     *          not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createAssetContent(osid_id_Id $assetId, 
                                       osid_repository_AssetContentForm $assetContentForm);


    /**
     *  Tests if this user can update <code> AssetContent. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating an <code> AssetContent </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer update operations 
     *  to an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> AssetContent </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateAssetContents();


    /**
     *  Tests if this user can update content for a specified <code> Asset. 
     *  </code> A return of true does not guarantee successful authorization. 
     *  A return of false indicates that it is known updating the <code> Asset 
     *  </code> content will result in a <code> PERMISSION_DENIED. </code> 
     *  This is intended as a hint to an application that may opt not to offer 
     *  an update operation to an unauthorized user for this <code> Asset. 
     *  </code> 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @return boolean <code> false </code> if asset content modification is 
     *          not authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> assetId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> assetId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateAssetContent(osid_id_Id $assetId);


    /**
     *  Gets the asset form for updating content for an existing asset. A new 
     *  asset content form should be requested for each update transaction. 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_id_Id $assetContentId the <code> Id </code> of the 
     *          <code> AssetContent </code> 
     *  @return object osid_repository_AssetContentForm the asset content form 
     *  @throws osid_NotFoundException <code> assetId </code> or <code> 
     *          assetContentId </code> is not found 
     *  @throws osid_NullArgumentException <code> assetId </code> or <code> 
     *          assetContentId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetContentFormForUpdate(osid_id_Id $assetId, 
                                                 osid_id_Id $assetContentId);


    /**
     *  Updates an existing asset. 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_id_Id $assetContentId the <code> Id </code> of the 
     *          <code> AssetContent </code> 
     *  @param object osid_repository_AssetContentForm $assetContentForm the 
     *          form containing the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> assetId </code> or <code> 
     *          assetContentId </code> is not found 
     *  @throws osid_NullArgumentException <code> assetId, assetContentId 
     *          </code> or <code> assetForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> assetContentForm </code> is 
     *          not supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateAssetContent(osid_id_Id $assetId, 
                                       osid_id_Id $assetContentId, 
                                       osid_repository_AssetContentForm $assetContentForm);


    /**
     *  Tests if this user can delete <code> AssetsContent </code> from <code> 
     *  Assets. </code> A return of true does not guarantee successful 
     *  authorization. A return of false indicates that it is known deleting 
     *  an <code> AssetContent </code> will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer delete operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> AssetContent </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteAssetContents();


    /**
     *  Tests if this user can delete content from a specified <code> Asset. 
     *  </code> A return of true does not guarantee successful authorization. 
     *  A return of false indicates that it is known deleting the <code> 
     *  AssetContent </code> will result in a <code> PERMISSION_DENIED. 
     *  </code> This is intended as a hint to an application that may opt not 
     *  to offer an delete operation to an unauthorized user for this asset. 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_id_Id $assetContentId the <code> Id </code> of the 
     *          <code> AssetContent </code> 
     *  @return boolean <code> false </code> if deletion of this <code> 
     *          AssetContent </code> is not authorized, <code> true </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> assetId </code> or <code> 
     *          assetContentId </code> is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> assetId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteAssetContent(osid_id_Id $assetId, 
                                          osid_id_Id $assetContentId);


    /**
     *  Deletes content from an <code> Asset. </code> 
     *
     *  @param object osid_id_Id $assetId the <code> Id </code> of the <code> 
     *          Asset </code> to remove 
     *  @param object osid_id_Id $assetContentId the <code> Id </code> of the 
     *          <code> AssetContent </code> 
     *  @throws osid_NotFoundException <code> assetId </code> or <code> 
     *          assetContentId </code> is not found 
     *  @throws osid_NullArgumentException <code> assetId </code> or <code> 
     *          assetContentId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteAssetContent(osid_id_Id $assetId, 
                                       osid_id_Id $assetContentId);

}
