<?php

/**
 * osid_dictionary_EntryAdminSession
 * 
 *     Specifies the OSID definition for osid_dictionary_EntryAdminSession.
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
 *  <p><code> EntryAdminSession </code> defines an interface to create, update 
 *  and delete dictionary entrees. </p>
 * 
 * @package org.osid.dictionary
 */
interface osid_dictionary_EntryAdminSession
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
     *  Tests if this user can create entries A return of true does not 
     *  guarantee successful authorization. A return of false indicates that 
     *  it is known creating an <code> Entry </code> will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer create operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if entry ceration is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateEntries();


    /**
     *  Tests if this user can create a single <code> Entry </code> using the 
     *  desired types. 
     *
     *  @param object osid_type_Type $keyType key type 
     *  @param object osid_type_Type $entryType entry type 
     *  @return boolean <code> true </code> if <code> Entry </code> creation 
     *          using the specified <code> Types </code> is supported, <code> 
     *          false </code> otherwise 
     *  @throws osid_NullArgumentException <code> keyType </code> or <code> 
     *          entryType </code> is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateEntryWithTypes(osid_type_Type $keyType, 
                                            osid_type_Type $entryType);


    /**
     *  Creates a new <code> Dictionary </code> entry with the given key and 
     *  value. 
     *
     *  @param object $key the key of the entry 
     *  @param object osid_type_Type $keyType the type of key 
     *  @param object $value the value of the entry 
     *  @param object osid_type_Type $valueType the type of value 
     *  @return object osid_dictionary_Entry the created entry 
     *  @throws osid_AlreadyExistsException an entry by this <code> key, 
     *          </code> <code> keyType </code> and <code> valueType </code> 
     *          already exists. 
     *  @throws osid_InvalidArgumentException <code> key </code> is not of 
     *          <code> keyType </code> or <code> value </code> is not of 
     *          <code> valueType </code> 
     *  @throws osid_NullArgumentException <code> null </code> argument 
     *          provided 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> 
     *          DictionarManager.supportsEntryTypes(keyType, valueType) 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createEntry($key, osid_type_Type $keyType, $value, 
                                osid_type_Type $valueType);


    /**
     *  Tests if this user can update entries. A return of true does not 
     *  guarantee successful authorization. A return of false indicates that 
     *  it is known updating an <code> Entry </code> will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer update operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if entry modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateEntries();


    /**
     *  Updates an existing <code> Dictionary </code> entry identified with 
     *  the specified key with the given value. 
     *
     *  @param object $key the key of the entry 
     *  @param object osid_type_Type $keyType the type of key 
     *  @param object $value the new value of the entry 
     *  @param object osid_type_Type $valueType the type of value 
     *  @throws osid_InvalidArgumentException <code> key </code> is not of 
     *          <code> keyType </code> or <code> value </code> is not of 
     *          <code> valueType </code> 
     *  @throws osid_NotFoundException entry is not found 
     *  @throws osid_NullArgumentException <code> null </code> argument 
     *          provided 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateEntry($key, osid_type_Type $keyType, $value, 
                                osid_type_Type $valueType);


    /**
     *  Tests if this user can delete <code> Entries. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting an <code> Entry </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer delete operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Entry </code> deletion 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteEntries();


    /**
     *  Updates an existing <code> Dictionary </code> entry identified with 
     *  the specified key with the given value. 
     *
     *  @param object $key the key of the entry 
     *  @param object osid_type_Type $keyType the type of key 
     *  @param object osid_type_Type $valueType the type of value 
     *  @throws osid_InvalidArgumentException <code> key </code> is not of 
     *          <code> keyType </code> 
     *  @throws osid_NotFoundException <code> key </code> not found 
     *  @throws osid_NullArgumentException <code> key </code> or <code> 
     *          keyType </code> or <code> valueType </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteEntry($key, osid_type_Type $keyType, 
                                osid_type_Type $valueType);

}
