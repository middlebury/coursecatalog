<?php

/**
 * osid_dictionary_EntryRetrievalSession
 * 
 *     Specifies the OSID definition for osid_dictionary_EntryRetrievalSession.
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
 * @package org.osid.dictionary
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p><code> EntryRetrievalSession </code> is used to query dictionary 
 *  entries. A dictionary entry contains a key and a value. The uniqeness of 
 *  the entry depends on the key, the key type and the value type. </p> 
 *  
 *  <p> This session defines two views which offer differing behaviors when 
 *  retrieving multiple objects. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> federated view: entries are accessible from the <code> Dictionary 
 *      </code> associated with this session and any descedant dictionaries in 
 *      the <code> Dictionary </code> hierarchy </li> 
 *      <li> isolated view: entries are accessible from this <code> Dictionary 
 *      </code> only </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.dictionary
 */
interface osid_dictionary_EntryRetrievalSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> Dictionary </code> <code> Id </code> associated with 
     *  this session. 
     *
     *  @return object osid_id_Id the <code> Dictionary </code> <code> Id 
     *          </code> associated with this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionaryId();


    /**
     *  Gets the <code> Dictionary </code> associated with this session. 
     *
     *  @return object osid_dictionary_Dictionary the <code> Dictionary 
     *          </code> associated with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionary();


    /**
     *  Tests if this user can perform <code> Entry </code> lookups. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canAccessEntries();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include entries from descendant dictionaries in the dictionary 
     *  hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedDictionaryView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to this dictionary only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedDictionaryView();


    /**
     *  Gets the <code> Dictionary </code> entry associated with the given key 
     *  and types. The <code> keyType </code> indicates the key object type 
     *  and the <code> valueType </code> indicates the value object return. 
     *  Unlike the general pattern that defines core <code> OsidObject </code> 
     *  interfaces, keys and values are completely defined outside the 
     *  specification. Casting may be used directly from the return of this 
     *  method in accordance with the object specified by the <code> 
     *  valueType. </code> 
     *
     *  @param object $key the key of the entry to rerieve 
     *  @param object osid_type_Type $keyType the key type of the entry to 
     *          rerieve 
     *  @param object osid_type_Type $valueType the value type of the entry to 
     *          rerieve 
     *  @return object the returned <code> object </code> 
     *  @throws osid_InvalidArgumentException <code> key </code> is not of 
     *          <code> keyType </code> 
     *  @throws osid_NotFoundException no entry found 
     *  @throws osid_NullArgumentException <code> key, keyType </code> or 
     *          <code> valueType </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> 
     *          DictionaryManager.supportsEntryTypes(keyType, valueType) 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEntry($key, osid_type_Type $keyType, 
                             osid_type_Type $valueType);

}
