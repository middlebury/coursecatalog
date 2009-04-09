<?php

/**
 * osid_repository_Asset
 * 
 *     Specifies the OSID definition for osid_repository_Asset.
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

require_once(dirname(__FILE__)."/../OsidObject.php");

/**
 *  <p>An <code> Asset </code> represents some digital content. Example assets 
 *  might be a text document, an image, or a movie. The content data, and 
 *  metadata related directly to the content format and quality, is accessed 
 *  through <code> AssetContent. Assets </code> , like all <code> OsidObjects, 
 *  </code> include a type an interface extension to qualify the <code> Asset 
 *  </code> and include additional data. The division between the <code> Asset 
 *  </code> <code> Type </code> and <code> AssetContent </code> is to separate 
 *  data describing the asset from data describing the format of the contents, 
 *  allowing a consumer to select among multiple formats, sizes or levels of 
 *  fidelity. </p> 
 *  
 *  <p> An example is a photograph of the Bay Bridge. The content may deliver 
 *  a JPEG in multiple resolutions where the <code> AssetContent </code> may 
 *  also desribe size or compression factor for each one. The content may also 
 *  include an uncompressed TIFF version. The <code> Asset </code> <code> Type 
 *  </code> may be "photograph" indicating that the photo itself is the asset 
 *  managed in this repository. Since an Asset may have multiple <code> 
 *  AssetContent </code> structures, the decision of how many things to stuff 
 *  inside a single asset comes down to if the content is actually a different 
 *  format, or size, or quality, falling under the same creator, copyright, 
 *  publisher and distribution rights as the original. This may, in some 
 *  cases, provide a means to implement some accessibility, it doesn't handle 
 *  the case where, to meet an accesibility requirement, one asset needs to be 
 *  substituted for another. The Repository OSID manages this aspect outside 
 *  the scope of the core <code> Asset </code> definition. <code> Assets 
 *  </code> map to <code> AssetSubjects. </code> <code> AssetSubjects </code> 
 *  are <code> OsidObjects </code> that capture a subject matter. In the above 
 *  example, an <code> AssetSubject </code> may be defined for the Bay Bridge 
 *  and include data describing the bridge. The single subject can map to 
 *  multiple assets depicting the bridge providing a single entry for a search 
 *  and a single place to describe a bridge. Bridges, as physical items, may 
 *  also be described using the Resource OSID in which case the use of the 
 *  <code> AssetSubject </code> interface acts as a cover for the underlying 
 *  <code> Resource </code> to assist repository-only consumers. </p> 
 *  
 *  <p> The <code> Asset </code> definition includes some basic copyright and 
 *  related licensing information to assist in finding free-to-use content, or 
 *  to convey the distribution restrictions that may be placed on the asset. 
 *  Generally, if no data is available it is to be assumed that all rights are 
 *  reserved. <code> Assets </code> provide methods for conveying the persons 
 *  or organizations involved with the creation, provision and publishing the 
 *  content. Creators and Authors that would be found in a bibliographic entry 
 *  or other attribution are available in <code> getPrincipalCredits(). 
 *  </code> Each <code> AssetCredit </code> is typed to indicate the 
 *  particular role played in the asset production. </p> 
 *  
 *  <p> A publisher is applicable if the content of this <code> Asset </code> 
 *  has been published. Not all <code> Assets </code> in this <code> 
 *  Repository </code> may have a published status and such a status may 
 *  effect the applicability of copyright law. To trace the source of an 
 *  <code> Asset, </code> both a provider and source are defined. The provider 
 *  indicates where this repository acquired the asset and the source 
 *  indicates the original provider or copyright owner. In the case of a 
 *  published asset, the source is the publisher. <code> Assets </code> also 
 *  define methods to facilitate searches over time and space as it relates to 
 *  the subject matter. This may at times be redundant with the <code> 
 *  AssetSubject. </code> In the case of the Bay Bridge photograph, the 
 *  temporal coverage may include 1936, when it opened, and/or indicate when 
 *  the photo was taken to capture a current event of the bridge. The decision 
 *  largeley depends on what desired effect is from a search. The spatial 
 *  coverage may describe the gps coordinates of the bridge or describe the 
 *  spatial area encompassed in the view. In either case, a "photograph" type 
 *  may unambiguously defined methods to describe the exact time the 
 *  photograph was taken and the location of the photographer. </p> 
 *  
 *  <p> The core Asset defines methods to perform general searches and 
 *  construct bibliographic entries without knowledge of a particular <code> 
 *  Asset </code> or <code> AssetContent </code> record <code> Type. </code> 
 *  </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_Asset
    extends osid_OsidObject
{


    /**
     *  Gets the proper title of this asset. This may be the same as the 
     *  display name or the display name may be used for a less formal label. 
     *
     *  @return string the title of this asset 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTitle();


    /**
     *  Tests if the copyright status is known. 
     *
     *  @return boolean <code> true </code> if the copyright status of this 
     *          asset is known, <code> false </code> otherwise. If <code> 
     *          false, isPublidDomain(), </code> <code> 
     *          canDistributeVerbatim(), canDistributeAlterations() and 
     *          canDistributeCompositions() </code> may also be <code> false. 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isCopyrightStatusKnown();


    /**
     *  Tests if this asset is in the public domain. An asset is in the public 
     *  domain if copyright is not applicable, the copyright has expired, or 
     *  the copyright owner has expressly relinquished the copyright. 
     *
     *  @return boolean <code> true </code> if this asset is in the public 
     *          domain, <code> false </code> otherwise. If <code> true, 
     *          </code> <code> canDistributeVerbatim(), 
     *          canDistributeAlterations() and canDistributeCompositions() 
     *          </code> must also be <code> true. </code> 
     *  @throws osid_IllegalStateException <code> isCopyrightStatusKnown() 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isPublicDomain();


    /**
     *  Gets the copyright statement and of this asset which identifies the 
     *  current copyright holder. For an asset in the public domain, this 
     *  method may return the original copyright statement although it may be 
     *  no longer valid. 
     *
     *  @return string the copyright statement or an empty string if none 
     *          available. An empty string does not imply the asset is not 
     *          protected by copyright. 
     *  @throws osid_IllegalStateException <code> isCopyrightStatusKnown() 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCopyright();


    /**
     *  Gets the copyright registration information for this asset. 
     *
     *  @return string the copyright registration. An empty string means the 
     *          registration status isn't known. 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCopyrightRegistration();


    /**
     *  Gets the terms of usage for this asset. If <code> 
     *  isCopyrightStatusKnown() </code> is <code> false </code> a statement 
     *  about the suspected copyright status may also be included. 
     *
     *  @return string the license statement 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLicense();


    /**
     *  Tests if there are any license restrictions on this asset that 
     *  restrict the distribution, re-publication or public display of this 
     *  asset, commercial or otherwise, without modification, alteration, or 
     *  inclusion in other works. This method is intended to offer consumers a 
     *  means of filtering out search results that restrict distribution for 
     *  any purpose. The scope of this method does not include licensing that 
     *  describes warranty disclaimers or attribution requirements. This 
     *  method is intended for informational purposes only and does not 
     *  replace or override the terms specified in a license agreement which 
     *  may specify exceptions or additional restrictions. 
     *
     *  @return boolean <code> true </code> if the asset can be distributed 
     *          verbatim, <code> false </code> otherwise. 
     *  @throws osid_IllegalStateException <code> isCopyrightStatusKnown() 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDistributeVerbatim();


    /**
     *  Tests if there are any license restrictions on this asset that 
     *  restrict the distribution, re-publication or public display of any 
     *  alterations or modifications to this asset, commercial or otherwise, 
     *  for any purpose. This method is intended to offer consumers a means of 
     *  filtering out search results that restrict the distribution or public 
     *  display of any modification or alteration of the content or its 
     *  metadata of any kind, including editing, translation, resampling, 
     *  resizing and cropping. The scope of this method does not include 
     *  licensing that describes warranty disclaimers or attribution 
     *  requirements. This method is intended for informational purposes only 
     *  and does not replace or override the terms specified in a license 
     *  agreement which may specify exceptions or additional restrictions. 
     *
     *  @return boolean <code> true </code> if the asset can be modified, 
     *          <code> false </code> otherwise. If <code> true, </code> <code> 
     *          canDistributeVerbatim() </code> must also be <code> true. 
     *          </code> 
     *  @throws osid_IllegalStateException <code> isCopyrightStatusKnown() 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDistributeAlterations();


    /**
     *  Tests if there are any license restrictions on this asset that 
     *  restrict the distribution, re-publication or public display of this 
     *  asset as an inclusion within other content or composition, commercial 
     *  or otherwise, for any purpose, including restrictions upon the 
     *  distribution or license of the resulting composition. This method is 
     *  intended to offer consumers a means of filtering out search results 
     *  that restrict the use of this asset within compositions. The scope of 
     *  this method does not include licensing that describes warranty 
     *  disclaimers or attribution requirements. This method is intended for 
     *  informational purposes only and does not replace or override the terms 
     *  specified in a license agreement which may specify exceptions or 
     *  additional restrictions. 
     *
     *  @return boolean <code> true </code> if the asset can be part of a 
     *          larger composition <code> false </code> otherwise. If <code> 
     *          true, </code> <code> canDistributeVerbatim() </code> must also 
     *          be <code> true. </code> 
     *  @throws osid_IllegalStateException <code> isCopyrightStatusKnown() 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDistributeCompositions();


    /**
     *  Gets the <code> Resource Id </code> of the provider, or immediate 
     *  source, of this asset. The provider is the entity that makes this 
     *  digital asset available in this repository but may or may not be the 
     *  publisher of the contents depicted in the asset. For example, a map 
     *  published by Ticknor & Fields in 1848 may have a provider of Library 
     *  of Congress. If copied from a repository at Middlebury College, the 
     *  provider would be Middlebury College. To maximize usefulness, a 
     *  repository should indicate the next-hop repository it retrieved the 
     *  asset from. 
     *  <br/><br/>
     *  
     *
     *  @return object osid_id_Id the provider <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getProviderId();


    /**
     *  Gets the <code> Resource </code> representing the provider of this 
     *  asset. 
     *
     *  @return object osid_resource_Resource the provider 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getProvider();


    /**
     *  Gets the <code> Resource Id </code> of the source of this asset. The 
     *  source is the original owner of the copyright of this asset and may 
     *  differ from the creator of this asset, or the named entities in the 
     *  copyright notice. The source for a published book written by Margaret 
     *  Mitchell would be Macmillan. The source for an unpublished painting by 
     *  Arthur Goodwin would be Arthur Goodwin. 
     *  <br/><br/>
     *  
     *
     *  @return object osid_id_Id the source <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSourceId();


    /**
     *  Gets the <code> Resource </code> representing the source of this 
     *  asset. 
     *
     *  @return object osid_resource_Resource the source 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSource();


    /**
     *  Gets the created date of this asset, which is generally not related to 
     *  when the object representing the asset was created. The date returned 
     *  may indicate that not much is known. 
     *
     *  @return object osid_calendaring_DateTime the created date 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCreatedDate();


    /**
     *  Tests if this asset has been published. Not all assets viewable in 
     *  this repository may have been published. The source of a published 
     *  asset indicates the publisher. 
     *
     *  @return boolean true if this asset has been published, <code> false 
     *          </code> if unpublished or its published status is not known 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isPublished();


    /**
     *  Gets the published date of this asset. Unpublished assets have no 
     *  published date. A published asset has a date available, however the 
     *  date returned may indicate that not much is known. 
     *
     *  @return object osid_calendaring_DateTime the published date 
     *  @throws osid_IllegalStateException <code> isPublished() </code> is 
     *          <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPublishedDate();


    /**
     *  Gets the credits of the principal people involved in the production of 
     *  this asset as a display string. 
     *
     *  @return string the principal credits 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPrincipalCreditString();


    /**
     *  Gets the content of this asset. 
     *
     *  @return object osid_repository_AssetContentList the asset contents 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetContents();


    /**
     *  Gets the record corresponding to the given <code> Asset </code> record 
     *  <code> Type. </code> This method must be used to retrieve an object 
     *  implementing the requested record interface along with all of its 
     *  ancestor interfaces. The <code> assetRecordType </code> may be the 
     *  <code> Type </code> returned in <code> getRecordTypes() </code> or any 
     *  of its parents in a <code> Type </code> hierarchy where <code> 
     *  hasRecordType(assetRecordType) </code> is <code> true </code> . 
     *
     *  @param object osid_type_Type $assetRecordType an asset record type 
     *  @return object osid_repository_AssetRecord the record 
     *  @throws osid_NullArgumentException <code> assetRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(assetRecordType) </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetRecord(osid_type_Type $assetRecordType);

}
