<?php

/**
 * osid_repository_AssetContent
 * 
 *     Specifies the OSID definition for osid_repository_AssetContent.
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
 *  <p><code> AssetContent </code> represents a version of content represented 
 *  by an <code> Asset. </code> Although <code> AssetContent </code> is a 
 *  separate <code> OsidObject </code> with its own <code> Id </code> to 
 *  distuinguish it from other content inside an <code> Asset, AssetContent 
 *  </code> can only be accessed through an <code> Asset. </code> </p> 
 *  
 *  <p> Once an <code> Asset </code> is selected, multiple contents should be 
 *  negotiated using the size, fidelity, accessibility requirements or 
 *  application evnironment. </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetContent
    extends osid_OsidObject
{


    /**
     *  Gets the <code> Asset </code> corresponding to this content. 
     *
     *  @return object osid_repository_Asset the asset 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAsset();


    /**
     *  Gets the accessibility types associated with this content. 
     *
     *  @return object osid_type_TypeList list of content accesibility types 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAccesibilityTypes();


    /**
     *  Tests if a data length is available. 
     *
     *  @return boolean <code> true </code> if a length is available for this 
     *          content, <code> false </code> otherwise. 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasDataLength();


    /**
     *  Gets the length of the data represented by this content. 
     *
     *  @return integer the length of the data stream 
     *  @throws osid_IllegalStateException <code> hasDataLength() </code> is 
     *          <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDataLength();


    /**
     *  Gets the asset content data. 
     *
     *  @return object osid_transport_DataInputStream the length of the 
     *          content data 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getData();


    /**
     *  Tests if a URL is associated with this content. 
     *
     *  @return boolean <code> true </code> if a URL is available, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasURL();


    /**
     *  Gets the URL associated with this content for web-based retrieval. 
     *
     *  @return string the url for this data 
     *  @throws osid_IllegalStateException <code> hasURL() </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getURL();


    /**
     *  Gets the record corresponding to the given <code> AssetContent </code> 
     *  record <code> Type. </code> This method must be used to retrieve an 
     *  object implementing the requested record interface along with all of 
     *  its ancestor interfaces. The <code> assetRecordType </code> may be the 
     *  <code> Type </code> returned in <code> getRecordTypes() </code> or any 
     *  of its parents in a <code> Type </code> hierarchy where <code> 
     *  hasRecordType(assetRecordType) </code> is <code> true </code> . 
     *
     *  @param object osid_type_Type $assetContentContentInterfaceType the 
     *          type of the record to retrieve 
     *  @return object osid_repository_AssetContentRecord the asset content 
     *          record 
     *  @throws osid_NullArgumentException <code> assetContentRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(assetContentRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetContentRecord(osid_type_Type $assetContentContentInterfaceType);

}
