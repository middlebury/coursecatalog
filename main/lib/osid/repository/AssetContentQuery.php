<?php

/**
 * osid_repository_AssetContentQuery
 * 
 *     Specifies the OSID definition for osid_repository_AssetContentQuery.
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

require_once(dirname(__FILE__)."/../OsidQuery.php");

/**
 *  <p>This is the query interface for searching asset contents. Each method 
 *  forms an <code> AND </code> term while multiple invocations of the same 
 *  method produce a nested <code> OR. </code> </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetContentQuery
    extends osid_OsidQuery
{


    /**
     *  Sets the accessibility types for this query. Supplying multiple types 
     *  behaves like a boolean OR among the elements. 
     *
     *  @param object osid_type_Type $accessibilityType an accessibilityType 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> accessibilityType </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAccesibilityType(osid_type_Type $accessibilityType, 
                                          $match);


    /**
     *  Matches asset content that has any accessibility type. 
     *
     *  @param boolean $match <code> true </code> to match content with any 
     *          accessibility type, <code> false </code> to match content with 
     *          no accessibility type 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyAccessibility($match);


    /**
     *  Matches content whose length of the data are inclusive of the given 
     *  range. 
     *
     *  @param integer $low low range 
     *  @param integer $high high range 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> low </code> is greater 
     *          than <code> high </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchDataLength($low, $high, $match);


    /**
     *  Matches content that has any data length. 
     *
     *  @param boolean $match <code> true </code> to match content with any 
     *          data length, <code> false </code> to match content with no 
     *          data length 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyDataLength($match);


    /**
     *  Matches data in this content. 
     *
     *  @param array $data list of matching strings 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> data </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchData(array $data, $match);


    /**
     *  Matches content that has any data. 
     *
     *  @param boolean $match <code> true </code> to match content with any 
     *          data, <code> false </code> to match content with no data 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyData($match);


    /**
     *  Sets the url for this query. Supplying multiple strings behaves like a 
     *  boolean <code> OR </code> among the elements each which must 
     *  correspond to the <code> stringMatchType. </code> 
     *
     *  @param array $url url string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> url </code> not of <code> 
     *          stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> url </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> supportsStringMatchType(url) 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchURL(array $url, osid_type_Type $stringMatchType, 
                             $match);


    /**
     *  Matches content that has any url. 
     *
     *  @param boolean $match <code> true </code> to match content with any 
     *          url, <code> false </code> to match content with no url 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyUrl($match);


    /**
     *  Gets the record query interface corresponding to the given <code> 
     *  AssetContent </code> record <code> Type. </code> Multiple record 
     *  retrievals produce a nested <code> OR </code> term. 
     *
     *  @param object osid_type_Type $assetContentRecordType an asset content 
     *          record type 
     *  @return object osid_repository_AssetContentQueryRecord the asset 
     *          content query record 
     *  @throws osid_NullArgumentException <code> assetContentRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(assetContentRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetContentQueryRecord(osid_type_Type $assetContentRecordType);

}
