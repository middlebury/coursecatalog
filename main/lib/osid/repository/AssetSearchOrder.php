<?php

/**
 * osid_repository_AssetSearchOrder
 * 
 *     Specifies the OSID definition for osid_repository_AssetSearchOrder.
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

require_once(dirname(__FILE__)."/../OsidSearchOrder.php");

/**
 *  <p>An interface for specifying the ordering of search results. </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetSearchOrder
    extends osid_OsidSearchOrder
{


    /**
     *  Specifies a preference for ordering the result set by asset title. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByTitle();


    /**
     *  Specifies a preference for grouping the result set by published 
     *  domain. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByPublicDomain();


    /**
     *  Specifies a preference for grouping the result set by the ability to 
     *  distribute copies. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByDistributeVerbatim();


    /**
     *  Specifies a preference for grouping the result set by the ability to 
     *  distribute alterations. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByDistributeAlterations();


    /**
     *  Specifies a preference for grouping the result set by the ability to 
     *  distribute compositions. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByDistributeCompositions();


    /**
     *  Specifies a preference for ordering the result set by asset provider. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByProvider();


    /**
     *  Tests if a provider order interface is available. 
     *
     *  @return boolean <code> true </code> if a provider order interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsProviderSearchOrder();


    /**
     *  Gets the provider order interface. 
     *
     *  @return object osid_resource_ResourceSearchOrder the resource search 
     *          order interface for the provider 
     *  @throws osid_UnimplementedException <code> 
     *          supportsProviderSearchOrder() </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsProviderSearchOrder() </code> is <code> true. 
     *              </code> 
     */
    public function getProviderSearchOrder();


    /**
     *  Specifies a preference for ordering the result set by source. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderBySource();


    /**
     *  Tests if a source order interface is available. 
     *
     *  @return boolean <code> true </code> if a source order interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSourceSearchOrder();


    /**
     *  Gets the source order interface. 
     *
     *  @return object osid_resource_ResourceSearchOrder the resource search 
     *          order interface for the source 
     *  @throws osid_UnimplementedException <code> supportsSourceSearchOrder() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSourceSearchOrder() </code> is <code> true. 
     *              </code> 
     */
    public function getSourceSearchOrder();


    /**
     *  Specifies a preference for ordering the result set by created date. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByCreatedDate();


    /**
     *  Specifies a preference for grouping the result set by published 
     *  status. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByPublished();


    /**
     *  Specifies a preference for ordering the result set by published date. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByPublishedDate();


    /**
     *  Specifies a preference for ordering the result set by temporal 
     *  coverage. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByTemporalCoverage();


    /**
     *  Specifies a preference for ordering the result set by spatial 
     *  coverage. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderBySpatialCoverage();


    /**
     *  Gets the asset search order record corresponding to the given asset 
     *  record <code> Type. </code> Multiple retrievals return the same 
     *  underlying object. 
     *
     *  @param object osid_type_Type $assetRecordType an asset record type 
     *  @return object osid_repository_AssetSearchOrderRecord the asset search 
     *          order record interface 
     *  @throws osid_NullArgumentException <code> assetRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(assetRecordType) </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetSearchOrderRecord(osid_type_Type $assetRecordType);

}
