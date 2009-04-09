<?php

/**
 * osid_repository_AssetForm
 * 
 *     Specifies the OSID definition for osid_repository_AssetForm.
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
 *  <p>This is the form for creating and updating <code> Assets. </code> Like 
 *  all <code> OsidForm </code> objects, various data elements may be set here 
 *  for use in the create and update methods in the <code> AssetAdminSession. 
 *  </code> For each data element that may be set, metadata may be examined to 
 *  provide display hints or data constraints. </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetForm
    extends osid_OsidForm
{


    /**
     *  Gets the metadata for an asset title. 
     *
     *  @return object osid_Metadata metadata for the title 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTitleMetadata();


    /**
     *  Sets the title. 
     *
     *  @param string $title the new title 
     *  @throws osid_InvalidArgumentException <code> title </code> is invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> title </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setTitle($title);


    /**
     *  Removes the title. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearTitle();


    /**
     *  Gets the metadata for the public domain flag. 
     *
     *  @return object osid_Metadata metadata for the public domain 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPublicDomainMetadata();


    /**
     *  Sets the public domain flag. 
     *
     *  @param boolean $publicDomain the public domain status 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setPublicDomain($publicDomain);


    /**
     *  Removes the public domain status. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearPublicDomain();


    /**
     *  Gets the metadata for the copyright. 
     *
     *  @return object osid_Metadata metadata for the copyright 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCopyrightMetadata();


    /**
     *  Sets the copyright. 
     *
     *  @param string $copyright the new copyright 
     *  @throws osid_InvalidArgumentException <code> copyright </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> copyright </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setCopyright($copyright);


    /**
     *  Removes the copyright. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearCopyright();


    /**
     *  Gets the metadata for the asset license. 
     *
     *  @return object osid_Metadata metadata for the license 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLicenseMetadata();


    /**
     *  Sets the license statement. 
     *
     *  @param string $license the new license 
     *  @throws osid_InvalidArgumentException <code> license </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> license </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setLicense($license);


    /**
     *  Removes the license statement. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearLicense();


    /**
     *  Gets the metadata for the distribution rights. 
     *
     *  @return object osid_Metadata metadata for the distribution rights 
     *          fields 
     *  @compliance mandatory This method must be implemented. 
     */
    public function geDistributionMetadata();


    /**
     *  Sets the distribution rights. 
     *
     *  @param boolean $distributeVerbatim right to distribute verbatim copies 
     *  @param boolean $distributeMods right to distribute modifications 
     *  @param boolean $distributeComps right to distribute compositions 
     *  @throws osid_InvalidArgumentException right specified as <code> false 
     *          </code> when public domain is <code> true </code> 
     *  @throws osid_NoAccessException authorization failure 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setDistributionRights($distributeVerbatim, $distributeMods, 
                                          $distributeComps);


    /**
     *  Removes the distribution rights. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearDistributionRights();


    /**
     *  Gets the metadata for the provider. 
     *
     *  @return object osid_Metadata metadata for the provider 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getProviderMetadata();


    /**
     *  Sets the provider. 
     *
     *  @param object osid_id_Id $providerId the new publisher 
     *  @throws osid_InvalidArgumentException <code> providerId </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> providerId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setProvider(osid_id_Id $providerId);


    /**
     *  Removes the provider. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearProvider();


    /**
     *  Gets the metadata for the source. 
     *
     *  @return object osid_Metadata metadata for the source 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSourceMetadata();


    /**
     *  Sets a source. 
     *
     *  @param object osid_id_Id $sourceId the new source 
     *  @throws osid_InvalidArgumentException <code> sourceId </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> sourceId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setSource(osid_id_Id $sourceId);


    /**
     *  Removes the source. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearSource();


    /**
     *  Gets the metadata for the asset creation date. 
     *
     *  @return object osid_Metadata metadata for the created date 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCreatedDateMetadata();


    /**
     *  Sets the created date. 
     *
     *  @param object osid_calendaring_DateTime $createdDate the new created 
     *          date 
     *  @throws osid_InvalidArgumentException <code> createdDate </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> createdDate </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setCreatedDate(osid_calendaring_DateTime $createdDate);


    /**
     *  Removes the created date. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearCreatedDate();


    /**
     *  Gets the metadata for the published status. 
     *
     *  @return object osid_Metadata metadata for the published field 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPublishedMetadata();


    /**
     *  Sets the published status. 
     *
     *  @param boolean $published the published status 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setPublished($published);


    /**
     *  Removes the published status. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearPublished();


    /**
     *  Gets the metadata for the published date. 
     *
     *  @return object osid_Metadata metadata for the published date 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPublishedDateMetadata();


    /**
     *  Sets the published date. 
     *
     *  @param object osid_calendaring_DateTime $publishedDate the new 
     *          published date 
     *  @throws osid_InvalidArgumentException <code> publishedDate </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @throws osid_NullArgumentException <code> publishedDate </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setPublishedDate(osid_calendaring_DateTime $publishedDate);


    /**
     *  Removes the puiblished date. 
     *
     *  @throws osid_NoAccessException <code> Metadata.isRequired() </code> is 
     *          <code> true </code> or <code> Metadata.isReadOnly() </code> is 
     *          <code> true </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearPublishedDate();


    /**
     *  Gets the <code> AssetFormRecord </code> interface corresponding to the 
     *  given <code> Asset </code> record interface <code> Type. </code> 
     *
     *  @param object osid_type_Type $assetRecordType an asset record type 
     *  @return object osid_repository_AssetFormRecord the record 
     *  @throws osid_NullArgumentException <code> assetRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(assetRecordType) </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetFormRecord(osid_type_Type $assetRecordType);

}
