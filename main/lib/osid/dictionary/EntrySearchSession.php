<?php

/**
 * osid_dictionary_EntrySearchSession
 * 
 *     Specifies the OSID definition for osid_dictionary_EntrySearchSession.
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
 *  <p>This session provides methods for searching among entries. The search 
 *  query is constructed using the <code> EntryQuery </code> interface. The 
 *  key <code> Type </code> also specifies the interface for the key query. 
 *  </p> 
 *  
 *  <p> <code> getEntryByQuery() </code> is the basic search method and 
 *  returns a list of <code> Entries. </code> A more advanced search may be 
 *  performed with <code> getEntriesBySearch(). </code> It accepts an <code> 
 *  EntrySearch </code> interface in addition to the query interface for the 
 *  purpose of specifying additional options affecting the entire search, such 
 *  as ordering. <code> getEntriesBySearch() </code> returns a <code> 
 *  EntrySearchResult </code> interface that can be used to access the 
 *  resulting <code> EntryList </code> or be used to perform a search within 
 *  the result set through <code> EntrySearch. </code> </p>
 * 
 * @package org.osid.dictionary
 */
interface osid_dictionary_EntrySearchSession
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
     *  Tests if this user can perform dictionary entry searches. A return of 
     *  true does not guarantee successful authorization. A return of false 
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
    public function canSearchEntries();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include entries from descendant dictionaries in the catalog hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedDictionaryView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts notifications for entries to this dictionary only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedDictionaryView();


    /**
     *  Gets an entry query interface. 
     *
     *  @return object osid_dictionary_EntryQuery the entry query 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEntryQuery();


    /**
     *  Gets a list of <code> Entry </code> elements matching the given search 
     *  interface. 
     *
     *  @param object osid_dictionary_EntryQuery $entryQuery the search query 
     *  @return object osid_dictionary_EntryList the returned <code> EntryList 
     *          </code> 
     *  @throws osid_NullArgumentException <code> entryQuery </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> entryQuery </code> is not of 
     *          this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEntriesByQuery(osid_dictionary_EntryQuery $entryQuery);


    /**
     *  Gets an entry search interface. This interface offers various options 
     *  to restrict or order the search results. The returned interface does 
     *  not have a search interface extension. <code> getEntrySearchForType() 
     *  </code> should be used if a typed extension is required. 
     *
     *  @return object osid_dictionary_EntrySearch the entry search interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEntrySearch();


    /**
     *  Gets an entry search order interface. The <code> EntrySearchOrder 
     *  </code> is supplied to a <code> EntrySearch </code> to specify the 
     *  ordering of results. 
     *
     *  @return object osid_dictionary_EntrySearchOrder the entry search order 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEntrySearchOrder();


    /**
     *  Gets a list of <code> Entry </code> elements matching the given search 
     *  interface. 
     *
     *  @param object osid_dictionary_EntryQuery $entryQuery the search query 
     *  @param object osid_dictionary_EntrySearch $entrySearch the search 
     *          interface 
     *  @return object osid_dictionary_EntrySearchResults the returned search 
     *          results 
     *  @throws osid_NullArgumentException <code> entryQuery </code> or <code> 
     *          entrySearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> entrySearch </code> or <code> 
     *          entryQuery </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEntriesBySearch(osid_dictionary_EntryQuery $entryQuery, 
                                       osid_dictionary_EntrySearch $entrySearch);

}
