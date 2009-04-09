<?php

/**
 * osid_authentication_KeySearchSession
 * 
 *     Specifies the OSID definition for osid_authentication_KeySearchSession.
 * 
 * Copyright (C) 2002-2008 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.authentication
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session provides methods for searching <code> Key </code> objects. 
 *  The search query is constructed using the <code> KeyQuery </code> 
 *  interface. The key <code> Type </code> also specifies the interface for 
 *  the key query. </p> 
 *  
 *  <p> <code> getKeysByQuery() </code> is the basic search method and returns 
 *  a list of <code> Keys. </code> A more advanced search may be performed 
 *  with <code> getKeysBySearch(). </code> It accepts an <code> KeySearch 
 *  </code> interface in addition to the query interface for the purpose of 
 *  specifying additional options affecting the entire search, such as 
 *  ordering. <code> getKeysBySearch() </code> returns a <code> 
 *  KeySearchResult </code> interface that can be used to access the resulting 
 *  <code> KeyList </code> or be used to perform a search within the result 
 *  set through <code> KeySearch. </code> Keys may have a record query 
 *  interface indicated by their respective record types. The record query 
 *  interface is accessed via the KeyQuery. </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_KeySearchSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> Key </code> searches. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer search operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if search methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canSearchKeys();


    /**
     *  Gets a key query interface. 
     *
     *  @return object osid_authentication_KeyQuery the key query 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeyQuery();


    /**
     *  Gets a list of <code> Keys </code> matching the given search 
     *  interface. 
     *
     *  @param object osid_authentication_KeyQuery $keyQuery the search query 
     *  @return object osid_authentication_KeyList the returned <code> KeyList 
     *          </code> 
     *  @throws osid_NullArgumentException <code> keyQuery </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> keyQuery </code> is not of 
     *          this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeysByQuery(osid_authentication_KeyQuery $keyQuery);


    /**
     *  Gets a key query interface. 
     *
     *  @return object osid_authentication_KeySearch the key search interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeySearch();


    /**
     *  Gets a key search order interface. The <code> KeySearchOrder </code> 
     *  is supplied to a <code> KeySearch </code> to specify the ordering of 
     *  results. 
     *
     *  @return object osid_authentication_KeySearchOrder the key search order 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeySearchOrder();


    /**
     *  Gets a list of <code> Keys </code> matching the given search 
     *  interface. 
     *
     *  @param object osid_authentication_KeyQuery $keyQuery the search query 
     *  @param object osid_authentication_KeySearch $keySearch the search 
     *          interface 
     *  @return object osid_authentication_KeySearchResults the returned 
     *          search results 
     *  @throws osid_NullArgumentException <code> keyQuery </code> or <code> 
     *          keySearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> keySearch </code> or a <code> 
     *          keyQuery </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeysBySearch(osid_authentication_KeyQuery $keyQuery, 
                                    osid_authentication_KeySearch $keySearch);

}
