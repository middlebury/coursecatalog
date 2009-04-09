<?php

/**
 * osid_dictionary_DictionaryLookupSession
 * 
 *     Specifies the OSID definition for osid_dictionary_DictionaryLookupSession.
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
 *  <p>This session provides methods for retrieving <code> Dictionary </code> 
 *  objects. The <code> Dictionary represents a collection of key/value 
 *  entries. </code> </p> 
 *  
 *  <p> This session defines two views which offer differing behaviors when 
 *  retrieving multiple objects. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete and ordered result set or is an 
 *      error condition </li> 
 *  </ul>
 *  Generally, the comparative view should be used for most applications as it 
 *  permits operation even if there is data out of sync. For example, a 
 *  hierarchy output can be plugged into a lookup method to retrieve all 
 *  objects known to a hierarchy, but it may not be necessary to break 
 *  execution if a node from the hierarchy no longer exists. However, some 
 *  administrative applications may need to know whether it had retrieved an 
 *  entire set of objects and may sacrifice some interoperability for the sake 
 *  of precision. </p>
 * 
 * @package org.osid.dictionary
 */
interface osid_dictionary_DictionaryLookupSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> Dictionary </code> lookups. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canAccessDictionaries();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeDictionaryView();


    /**
     *  A complete view of the <code> Dictionary </code> returns is desired. 
     *  Methods will return what is requested or result in an error. This view 
     *  is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryDictionaryView();


    /**
     *  Gets the <code> Dictionary </code> specified by its <code> Id. </code> 
     *  In plenary mode, the exact <code> Id </code> is found or a <code> 
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Dictionary 
     *  </code> may have a different <code> Id </code> than requested, such as 
     *  the case where a duplicate <code> Id </code> was assigned to a <code> 
     *  Dictionary </code> and retained for compatibility. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> to rerieve 
     *  @return object osid_dictionary_Dictionary the <code> Dictionary 
     *          </code> 
     *  @throws osid_NotFoundException no <code> Dictionary </code> found with 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> Id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionary(osid_id_Id $dictionaryId);


    /**
     *  Gets a <code> DictionaryList </code> corresponding to the given <code> 
     *  IdList. </code> In plenary mode, the returned list contains all of the 
     *  dictionaries specified in the <code> Id </code> list, in the order of 
     *  the list, including duplicates, or an error results if an <code> Id 
     *  </code> in the supplied list is not found or inaccessible. Otherwise, 
     *  inaccessible <code> Dictionary </code> elements may be omitted from 
     *  the list and may present the elements in any order including returning 
     *  a unique set. 
     *
     *  @param object osid_id_IdList $dictionaryIdList the list of <code> Ids 
     *          </code> to rerieve 
     *  @return object osid_dictionary_DictionaryList the returned <code> 
     *          Dictionary list </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> dictionaryIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionariesByIds(osid_id_IdList $dictionaryIdList);


    /**
     *  Gets a <code> DictionaryList </code> corresponding to the given 
     *  dictionary genus <code> Type </code> which does not include 
     *  dictionaries of genus types derived from the specified <code> Type. 
     *  </code> In plenary mode, the returned list contains all known 
     *  dictionaries or an error results. Otherwise, the returned list may 
     *  contain only those dictionaries that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $dictionaryGenusType a dictionary genus 
     *          type 
     *  @return object osid_dictionary_DictionaryList the returned <code> 
     *          Dictionary list </code> 
     *  @throws osid_NullArgumentException <code> dictionaryGenusType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionariesByGenusType(osid_type_Type $dictionaryGenusType);


    /**
     *  Gets a <code> DictionaryList </code> corresponding to the given 
     *  dictionary genus <code> Type </code> and include any additional 
     *  dictionaries with genus types derived from the specified <code> Type. 
     *  </code> In plenary mode, the returned list contains all known 
     *  dictionaries or an error results. Otherwise, the returned list may 
     *  contain only those dictionaries that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $dictionaryGenusType a dictionary genus 
     *          type 
     *  @return object osid_dictionary_DictionaryList the returned <code> 
     *          Dictionary list </code> 
     *  @throws osid_NullArgumentException <code> dictionaryGenusType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionariesByParentGenusType(osid_type_Type $dictionaryGenusType);


    /**
     *  Gets a <code> DictionaryList </code> containing the given dictionary 
     *  record <code> Type. </code> In plenary mode, the returned list 
     *  contains all known dictionaries or an error results. Otherwise, the 
     *  returned list may contain only those dictionaries that are accessible 
     *  through this session. In both cases, the order of the set is not 
     *  specified. 
     *
     *  @param object osid_type_Type $dictionaryRecordType a dictionary record 
     *          type 
     *  @return object osid_dictionary_DictionaryList the returned <code> 
     *          Dictionary list </code> 
     *  @throws osid_NullArgumentException <code> dictionaryRecordType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionariesByRecordType(osid_type_Type $dictionaryRecordType);


    /**
     *  Gets all <code> Dictionary </code> elements. In plenary mode, the 
     *  returned list contains all known dictionaries or an error results. 
     *  Otherwise, the returned list may contain only those dictionaries that 
     *  are accessible through this session. In both cases, the order of the 
     *  set is not specified. 
     *
     *  @return object osid_dictionary_DictionaryList a list of dictionaries 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDictionaries();

}
