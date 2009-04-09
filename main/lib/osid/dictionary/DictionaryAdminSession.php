<?php

/**
 * osid_dictionary_DictionaryAdminSession
 * 
 *     Specifies the OSID definition for osid_dictionary_DictionaryAdminSession.
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
 *  <p><code> DictionaryAdminSession </code> defines an interface to create, 
 *  update and delete <code> Dictionary </code> objects. </p>
 * 
 * @package org.osid.dictionary
 */
interface osid_dictionary_DictionaryAdminSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can create <code> Dictionaries. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating a <code> Dictionary </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer create operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Dictionary </code> 
     *          creation is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateDictionaries();


    /**
     *  Tests if this user can create a single <code> Dictionary </code> using 
     *  the desired record types. While <code> 
     *  DictionaryManager.getDictionaryRecordTypes() </code> can be used to 
     *  examine which records are supported, this method tests which record(s) 
     *  are required for creating a specific <code> Dictionary. </code> 
     *  Providing an empty array tests if a <code> Dictionary </code> can be 
     *  created with no records. 
     *
     *  @param array $dictionaryRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> Dictionary </code> 
     *          creation using the specified record <code> Types </code> is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> dictionaryRecordTypes 
     *          </code> is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateDictionaryWithRecordTypes(array $dictionaryRecordTypes);


    /**
     *  Gets the dictionary form for creating new dictionaries. A new form 
     *  should be requested for each create transaction. 
     *
     *  @return object osid_dictionary_DictionaryForm the dictionary form 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionaryFormForCreate();


    /**
     *  Creates a new <code> Dictionary. </code> 
     *
     *  @param array $dictionaryForms the forms for this new <code> Dictionary 
     *          </code> 
     *  @return object osid_dictionary_Dictionary the new <code> Dictionary 
     *          </code> 
     *  @throws osid_AlreadyExistsException attempt to add a <code> Dictionary 
     *          </code> when one by that name or unique property already 
     *          exists 
     *  @throws osid_InvalidArgumentException the caisson contains an invalid 
     *          value 
     *  @throws osid_NullArgumentException <code> dictionaryForms </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException a <code> dictionaryForm </code> is 
     *          not supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createDictionary(array $dictionaryForms);


    /**
     *  Tests if this user can update <code> Dictionaries. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a Dictionary will result in a 
     *  <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer update operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Dictionary </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateDictionaries();


    /**
     *  Tests if this user can update a specified <code> Dictionary. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known updating the <code> Dictionary 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may opt not to offer update 
     *  operations to an unauthoirzed user. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          dictionary 
     *  @return boolean <code> false </code> if dictionary modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> dictionaryId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateDictionary(osid_id_Id $dictionaryId);


    /**
     *  Gets the dictionary form for updating an existing dictionary. A new 
     *  dictionary form should be requested for each update transaction. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> 
     *  @return object osid_dictionary_DictionaryForm the dictionary form 
     *  @throws osid_NotFoundException <code> dictionaryId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionaryFormForUpdate(osid_id_Id $dictionaryId);


    /**
     *  Updates an existing dictionary. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the D 
     *          <code> ictionary </code> to update 
     *  @param object osid_dictionary_DictionaryForm $dictionaryForm the form 
     *          containing the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> dictionaryId </code> not found 
     *  @throws osid_NullArgumentException <code> dictionaryForm </code> or 
     *          <code> dictionaryId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> dictionaryForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateDictionary(osid_id_Id $dictionaryId, 
                                     osid_dictionary_DictionaryForm $dictionaryForm);


    /**
     *  Tests if this user can delete <code> Dictionaries. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting a <code> Dictionary </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer delete operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Dictionary </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteDictionaries();


    /**
     *  Tests if this user can delete a specified <code> Dictionary. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> Dictionary 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may opt not to offer delete 
     *  operations to an unauthorized user. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> 
     *  @return boolean <code> false </code> if dictionary deletion is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> configId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> dictionaryId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteDictionary(osid_id_Id $dictionaryId);


    /**
     *  Deletes a <code> Dictionary. </code> 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> to delete 
     *  @throws osid_NotFoundException <code> dictionaryId </code> not found 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteDictionary(osid_id_Id $dictionaryId);


    /**
     *  Adds an <code> Id </code> to a <code> Dictionary </code> for the 
     *  purpose of creating compatibility. The primary <code> Id </code> of 
     *  the <code> Dictionary </code> is determined by the provider. The new 
     *  <code> Id </code> performs as an alias to the primary <code> Id. 
     *  </code> 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of an 
     *          <code> Dictionary </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> dictionaryId </code> not found 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> or 
     *          <code> aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToDictionary(osid_id_Id $dictionaryId, 
                                      osid_id_Id $aliasId);

}
