<?php

/**
 * osid_dictionary_DictionaryProfile
 * 
 *     Specifies the OSID definition for osid_dictionary_DictionaryProfile.
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

require_once(dirname(__FILE__)."/../OsidProfile.php");

/**
 *  <p>The <code> DictionaryProfile </code> describes the interoperability 
 *  among dictionary services. </p>
 * 
 * @package org.osid.dictionary
 */
interface osid_dictionary_DictionaryProfile
    extends osid_OsidProfile
{


    /**
     *  Tests if any dictionary federation is exposed. Federation is exposed 
     *  when a specific dictionary may be identified, selected and used to 
     *  create a lookup or admin session. Federation is not exposed when a set 
     *  of dictionaries appears as a single dictionary. 
     *
     *  @return boolean <code> true </code> if federation is visible <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsVisibleFederation();


    /**
     *  Tests if retrieving dictionary entries are supported. 
     *
     *  @return boolean <code> true </code> if entry retrieval is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsEntryRetrieval();


    /**
     *  Tests if searching dictionary entries are supported. 
     *
     *  @return boolean <code> true </code> if entry search is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsEntrySearch();


    /**
     *  Tests if looking up dictionary entries are supported. 
     *
     *  @return boolean <code> true </code> if entry lookup is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsEntryLookup();


    /**
     *  Tests if a dictionary <code> </code> entry <code> </code> 
     *  administrative service is supported. 
     *
     *  @return boolean <code> true </code> if dictionary entry administration 
     *          is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsEntryAdmin();


    /**
     *  Tests if a dictionary <code> </code> entry <code> </code> notification 
     *  service is supported. 
     *
     *  @return boolean <code> true </code> if dictionary entry notification 
     *          is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsEntryNotification();


    /**
     *  Tests if a dictionary <code> </code> lookup service is supported. 
     *
     *  @return boolean <code> true </code> if dictionary lookup is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDictionaryLookup();


    /**
     *  Tests if a dictionary <code> </code> search service is supported. 
     *
     *  @return boolean <code> true </code> if dictionary search is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDictionarySearch();


    /**
     *  Tests if a dictionary <code> </code> administrative service is 
     *  supported. 
     *
     *  @return boolean <code> true </code> if dictionary administration is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDictionaryAdmin();


    /**
     *  Tests if a dictionary <code> </code> notification service is 
     *  supported. 
     *
     *  @return boolean <code> true </code> if dictionary notification is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDictionaryNotification();


    /**
     *  Tests if a dictionary <code> </code> hierarchy traversal service is 
     *  supported. 
     *
     *  @return boolean <code> true </code> if dictionary hierarchy traversal 
     *          is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDictionaryHierachyTraversal();


    /**
     *  Tests if a dictionary <code> </code> hierarchy design service is 
     *  supported. 
     *
     *  @return boolean <code> true </code> if dictionary hierarchy design is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDictionaryHierachyDesign();


    /**
     *  Tests if the dictionary hierarchy supports node sequencing. 
     *
     *  @return boolean <code> true </code> if dictionary hierarchy node 
     *          sequencing is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDictionaryHierarchySequencing();


    /**
     *  Gets the supported <code> Entry </code> key types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Entry </code> key types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEntryKeyTypes();


    /**
     *  Tests if the given <code> Entry </code> key type is supported. 
     *
     *  @param object osid_type_Type $keyType a <code> Type </code> indicating 
     *          an <code> Entry </code> key type 
     *  @return boolean <code> true </code> if the given Type is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsEntryKeyType(osid_type_Type $keyType);


    /**
     *  Gets the supported <code> Entry </code> value types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Entry </code> value types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEntryValueTypes();


    /**
     *  Tests if the given <code> Entry </code> value type is supported. 
     *
     *  @param object osid_type_Type $valueType a <code> Type </code> 
     *          indicating an <code> Entry </code> value type 
     *  @return boolean <code> true </code> if the given Type is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsEntryValueType(osid_type_Type $valueType);


    /**
     *  Tests if the given <code> Entry </code> key and value types are 
     *  supported. 
     *
     *  @param object osid_type_Type $keyType a <code> Type </code> indicating 
     *          an <code> Entry </code> key type 
     *  @param object osid_type_Type $valueType a <code> Type </code> 
     *          indicating an <code> Entry </code> value type 
     *  @return boolean <code> true </code> if the given Types are supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsEntryTypes(osid_type_Type $keyType, 
                                       osid_type_Type $valueType);


    /**
     *  Gets the supported <code> Entry </code> search record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Entry </code> search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEntrySearchRecordTypes();


    /**
     *  Tests if the given <code> Entry </code> search record type is 
     *  supported. 
     *
     *  @param object osid_type_Type $entrySearchrecordType a <code> Type 
     *          </code> indicating a <code> Entry </code> search record type 
     *  @return boolean <code> true </code> if the given Type is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsEntrySearchRecordType(osid_type_Type $entrySearchrecordType);


    /**
     *  Gets the supported <code> Dictionary </code> record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Dictionary </code> record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionaryRecordTypes();


    /**
     *  Tests if the given <code> Dictionary </code> record type is supported. 
     *
     *  @param object osid_type_Type $dictionaryRecordType a <code> Type 
     *          </code> indicating a <code> Dictionary </code> record type 
     *  @return boolean <code> true </code> if the given record Type is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDictionaryrecordType(osid_type_Type $dictionaryRecordType);


    /**
     *  Gets the supported <code> Dictionary </code> search record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Dictionary </code> search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionarySearchRecordTypes();


    /**
     *  Tests if the given <code> Dictionary </code> search record type is 
     *  supported. 
     *
     *  @param object osid_type_Type $dictionarySearchRecordType a <code> Type 
     *          </code> indicating a <code> Dictionary </code> search record 
     *          type 
     *  @return boolean <code> true </code> if the given record <code> Type 
     *          </code> is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDictionarySearchRecordType(osid_type_Type $dictionarySearchRecordType);

}
