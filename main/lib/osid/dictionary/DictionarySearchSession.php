<?php

/**
 * osid_dictionary_DictionarySearchSession
 * 
 *     Specifies the OSID definition for osid_dictionary_DictionarySearchSession.
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
 *  <p>This session provides methods for searching <code> Dictionary </code> 
 *  objects. The search query is constructed using the <code> DictionaryQuery 
 *  </code> interface.The dictionary interface <code> Type </code> also 
 *  specifies the interface for the dictionary query. </p> 
 *  
 *  <p> <code> getDictionariesByQuery() </code> is the basic search method and 
 *  returns a list of <code> Dictionary </code> elements. A more advanced 
 *  search may be performed with <code> getDictionaresBySearch(). </code> It 
 *  accepts a <code> DictionarySearch </code> interface in addition to the 
 *  query interface for the purpose of specifying additional options affecting 
 *  the entire search, such as ordering. <code> getDictionariesBySearch() 
 *  </code> returns a <code> DictionarySearchResults </code> interface that 
 *  can be used to access the resulting <code> DictionaryList </code> or be 
 *  used to perform a search within the result set through <code> 
 *  DictionarySearch. </code> Dictionaries may have a record query interface 
 *  indicated by their respective record types. The record query interface is 
 *  accessed via the <code> DictionaryQuery. </code> </p>
 * 
 * @package org.osid.dictionary
 */
interface osid_dictionary_DictionarySearchSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> Dictionary </code> searches. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer search operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if search methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canSearchDictionaries();


    /**
     *  Gets a dictionary query interface. 
     *
     *  @return object osid_dictionary_DictionaryQuery the dictionary query 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionaryQuery();


    /**
     *  Gets a list of <code> Dictionary </code> elements matching the given 
     *  query. 
     *
     *  @param object osid_dictionary_DictionaryQuery $dictionaryQuery the 
     *          search query 
     *  @return object osid_dictionary_DictionaryList the returned <code> 
     *          DictionaryList </code> 
     *  @throws osid_NullArgumentException <code> dictionaryQuery </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> dictionaryQuery </code> is 
     *          not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionariesByQuery(osid_dictionary_DictionaryQuery $dictionaryQuery);


    /**
     *  Gets a dictionary search interface. 
     *
     *  @return object osid_dictionary_DictionarySearch the dictionary search 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionarySearch();


    /**
     *  Gets a dictionary search order interface. The <code> 
     *  DictionarySearchOrder </code> is supplied to a <code> DictionarySearch 
     *  </code> to specify the ordering of results. 
     *
     *  @return object osid_dictionary_DictionarySearchOrder the dictionary 
     *          search order interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionarySearchOrder();


    /**
     *  Gets the search results matching the given search query using the 
     *  given search. 
     *
     *  @param object osid_dictionary_DictionaryQuery $dictionaryQuery the 
     *          search query 
     *  @param object osid_dictionary_DictionarySearch $dictionarySearch the 
     *          search interface 
     *  @return object osid_dictionary_DictionarySearchResults the returned 
     *          search results 
     *  @throws osid_NullArgumentException <code> dictionaryQuery </code> or 
     *          <code> dictionarySearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> dictionarySearch </code> or 
     *          <code> dictionaryQuery </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionariesBySearch(osid_dictionary_DictionaryQuery $dictionaryQuery, 
                                            osid_dictionary_DictionarySearch $dictionarySearch);

}
