<?php

/**
 * osid_dictionary_DictionaryManager
 * 
 *     Specifies the OSID definition for osid_dictionary_DictionaryManager.
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

require_once(dirname(__FILE__)."/../OsidManager.php");
require_once(dirname(__FILE__)."/DictionaryProfile.php");

/**
 *  <p>The dictionary manager provides access to dictionary sessions and 
 *  provides interoperability tests for various aspects of this service. The 
 *  sessions included in this manager are: 
 *  <ul>
 *      <li> <code> EntryRetrievalSession: </code> a basic session for 
 *      retrieving dictoonary entries </li> 
 *      <li> <code> EntryLookupSession: </code> a session for looking up 
 *      dictionary entries </li> 
 *      <li> <code> EntrySearchSession: </code> a session for searching for 
 *      dictionary entries </li> 
 *      <li> <code> EntryAdminSession: </code> a session for creating, 
 *      updating, and delting dictionary entries </li> 
 *      <li> <code> EntryNotificationSession: </code> a session for 
 *      subscribing to notifications about dictionary entries </li> 
 *      <li> <code> DictionaryLookupSession </code> a session for looking up 
 *      dictionaries </li> 
 *      <li> <code> DictionarySearchSession </code> a session for 
 *      searchingamong dictionaries </li> 
 *      <li> <code> DictionaryAdminSession </code> a session creating, 
 *      updating or deleting dictionaries </li> 
 *      <li> <code> DictionaryNotificationSession: </code> a session for 
 *      subscribing to adds and changes of dictionaries </li> 
 *      <li> <code> DictionaryHierarchySession: </code> a session for 
 *      traversing the hierarchy of dictionaries </li> 
 *      <li> <code> DictionaryHierarchyDesignSession: </code> a session for 
 *      managing the dictionary hierarchy </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.dictionary
 */
interface osid_dictionary_DictionaryManager
    extends osid_OsidManager,
            osid_dictionary_DictionaryProfile
{


    /**
     *  Gets the <code> OsidSession </code> used to retrieve dictionary 
     *  entries. 
     *
     *  @return object osid_dictionary_EntryRetrievalSession the new <code> 
     *          EntryRetrievalSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsEntryRetrieval() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsEntryRetrieval() </code> is <code> true. </code> 
     */
    public function getEntryRetrievalSession();


    /**
     *  Gets the <code> OsidSession </code> used to retrieve dictionary 
     *  entries for the specified <code> Dictionary. </code> 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> to use 
     *  @return object osid_dictionary_EntryRetrievalSession an <code> 
     *          EntryRetrievalSession </code> 
     *  @throws osid_NotFoundException no <code> Dictionary </code> found by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsEntryRetrieval() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsEntryRetrieval() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getEntryRetrievalSessionForDictionary(osid_id_Id $dictionaryId);


    /**
     *  Gets the <code> OsidSession </code> used to retrieve dictionary 
     *  entries. 
     *
     *  @return object osid_dictionary_EntryLookupSession an <code> 
     *          EntryLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsEntryLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsEntryLookup() </code> is <code> true. </code> 
     */
    public function getEntryLookupSession();


    /**
     *  Gets the <code> OsidSession </code> used to lookup dictionary entries 
     *  for the specified <code> Dictionary. </code> 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> to use 
     *  @return object osid_dictionary_EntryLookupSession an <code> 
     *          EntryLookupSession </code> 
     *  @throws osid_NotFoundException no <code> Dictionary </code> found by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsEntryLookup() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsEntryLookup() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getEntryLookupSessionForDictionary(osid_id_Id $dictionaryId);


    /**
     *  Gets the <code> OsidSession </code> used to search dictionary entries. 
     *
     *  @return object osid_dictionary_EntrySearchSession an <code> 
     *          EntrySearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsEntrySearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsEntrySearch() </code> is <code> true. </code> 
     */
    public function getEntrySearchSession();


    /**
     *  Gets the <code> OsidSession </code> used to search dictionary entries 
     *  for the specified <code> Dictionary. </code> 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> to use 
     *  @return object osid_dictionary_EntrySearchSession an <code> 
     *          EntrySearchSession </code> 
     *  @throws osid_NotFoundException no <code> Dictionary </code> found by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsEntrySearch() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsEntrySearch() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getEntrySearchSessionForDictionary(osid_id_Id $dictionaryId);


    /**
     *  Gets the <code> OsidSession </code> used to administer dictionary 
     *  entries. 
     *
     *  @return object osid_dictionary_EntryAdminSession an <code> 
     *          EntryAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsEntryAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsEntryAdmin() </code> is <code> true. </code> 
     */
    public function getEntryAdminSession();


    /**
     *  Gets the <code> OsidSession </code> used to administer dictionary 
     *  entries for the specified <code> Dictionary. </code> 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> to use 
     *  @return object osid_dictionary_EntryAdminSession an <code> 
     *          EntryAdminSession </code> 
     *  @throws osid_NotFoundException no <code> Dictionary </code> found by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsEntryAdmin() 
     *          </code> os <code> supportsVisibleFederration() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsEntryAdmin() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getEntryAdminSessionForDictionary(osid_id_Id $dictionaryId);


    /**
     *  Gets an <code> EntryNotificationSession </code> which is responsible 
     *  for subscribing to entry changes within a default <code> Dictionary. 
     *  </code> 
     *
     *  @param object osid_dictionary_EntryReceiver $receiver the notification 
     *          callback 
     *  @return object osid_dictionary_EntryNotificationSession an <code> 
     *          EntryNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsEntryNotification() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsEntryNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getEntryNotificationSession(osid_dictionary_EntryReceiver $receiver);


    /**
     *  Gets an <code> EntryNotificationSession </code> which is responsible 
     *  for subscribing to entry changes for a specified <code> Dictionary. 
     *  </code> 
     *
     *  @param object osid_dictionary_EntryReceiver $receiver the notification 
     *          callback 
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> to use 
     *  @return object osid_dictionary_EntryNotificationSession an <code> 
     *          EntryNotificationSession </code> 
     *  @throws osid_NotFoundException no <code> Dictionary </code> found by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> or <code> 
     *          dictionaryId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsEntryNotification() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsEntryNotification () </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getEntryNotificationSessionForDictionary(osid_dictionary_EntryReceiver $receiver, 
                                                             osid_id_Id $dictionaryId);


    /**
     *  Gets the <code> OsidSession </code> used to lookup dictionaries. 
     *
     *  @return object osid_dictionary_DictionaryLookupSession a <code> 
     *          DictionaryLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsDictionarySearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDictionaryLookup() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getDictionaryLookupSession();


    /**
     *  Gets the <code> OsidSession </code> used to search dictionaries. 
     *
     *  @return object osid_dictionary_DictionarySearchSession a <code> 
     *          DictionarySearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsDictionarySearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDictionarySearch() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getDictionarySearchSession();


    /**
     *  Gets the <code> OsidSession </code> used to administer dictionaries 
     *
     *  @return object osid_dictionary_DictionaryAdminSession a <code> 
     *          DictionaryAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsDictionaryAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDictionaryAdmin() </code> is <code> true. </code> 
     */
    public function getDictionaryAdminSession();


    /**
     *  Gets the <code> OsidSession </code> used to receive notifications of 
     *  dictionary changes. 
     *
     *  @param object osid_dictionary_DictionaryReceiver $receiver the 
     *          notification callback 
     *  @return object osid_dictionary_DictionaryNotificationSession a <code> 
     *          DictionaryNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsDictionaryNotification() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDictionaryNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getDictionaryNotificationSession(osid_dictionary_DictionaryReceiver $receiver);


    /**
     *  Gets hierarchy service for traversing the <code> Dictionary </code> 
     *  hierarchy. A parent includes all the dictionary entries of its 
     *  children. 
     *
     *  @return object osid_dictionary_DictionaryHierarchySession a Dictionary 
     *          <code> HierarchySession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsDictionaryHierarchyTraversal() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented. 
     */
    public function getDictionaryHierarchySession();


    /**
     *  Gets hierarchy service for structuring <code> Dictionary </code> 
     *  objects. 
     *
     *  @return object osid_dictionary_DictionaryHierarchyDesignSession a 
     *          <code> DictionaryHierarchyDesignSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsDictionaryHierarchyDesign() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented. 
     */
    public function getDictionaryHierarchyDesignSession();

}
