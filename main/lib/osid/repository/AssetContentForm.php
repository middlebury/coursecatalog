<?php

/**
 * osid_repository_AssetContentForm
 * 
 *     Specifies the OSID definition for osid_repository_AssetContentForm.
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

require_once(dirname(__FILE__)."/../OsidForm.php");

/**
 *  <p>This is the form for creating and updating content for <code> 
 *  AssetContent. </code> Like all <code> OsidForm </code> objects, various 
 *  data elements may be set here for use in the create and update methods in 
 *  the <code> AssetAdminSession. </code> For each data element that may be 
 *  set, metadata may be examined to provide display hints or data 
 *  constraints. </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetContentForm
    extends osid_OsidForm
{


    /**
     *  Gets the metadata for an accessibility type. 
     *
     *  @return object osid_Metadata metadata for the accessibility types 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAccessibilityTypeMetadata();


    /**
     *  Adds an accessibility type. Multiple types can be added. 
     *
     *  @param object osid_type_Type $accessibilityType a new accessibility 
     *          type 
     *  @throws osid_InvalidArgumentException <code> accessibilityType </code> 
     *          is invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> accessibilityTYpe </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addAccessibilityType(osid_type_Type $accessibilityType);


    /**
     *  Removes an accessibility type. 
     *
     *  @param object osid_type_Type $accessibilityType accessibility type to 
     *          remove 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NotFoundException acessibility type not found 
     *  @throws osid_NullArgumentException <code> accessibilityType </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function removeAccessibilityType(osid_type_Type $accessibilityType);


    /**
     *  Removes all accessibility types. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearAccessibilityTypes();


    /**
     *  Gets the metadata for the content data. 
     *
     *  @return object osid_Metadata metadata for the content data 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDataMetadata();


    /**
     *  Sets the content data. 
     *
     *  @param object osid_transport_DataInputStream $data the content data 
     *  @throws osid_InvalidArgumentException <code> data </code> is invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> data </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setContentData(osid_transport_DataInputStream $data);


    /**
     *  Removes the content data. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearContentData();


    /**
     *  Gets the metadata for the url. 
     *
     *  @return object osid_Metadata metadata for the url 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getURLMetadata();


    /**
     *  Sets the url. 
     *
     *  @param string $url the new copyright 
     *  @throws osid_InvalidArgumentException <code> url </code> is invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> url </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setURL($url);


    /**
     *  Removes the url. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearURL();


    /**
     *  Gets the <code> AssetContentFormRecord </code> interface corresponding 
     *  to the given asset content record interface <code> Type. </code> 
     *
     *  @param object osid_type_Type $assetContentRecordType an asset content 
     *          record type 
     *  @return object osid_repository_AssetContentFormRecord the asset 
     *          content form record 
     *  @throws osid_NullArgumentException <code> assetContentRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(assetContentRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetContentFormRecord(osid_type_Type $assetContentRecordType);

}
