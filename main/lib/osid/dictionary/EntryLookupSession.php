<?php

/**
 * osid_dictionary_EntryLookupSession
 * 
 *     Specifies the OSID definition for osid_dictionary_EntryLookupSession.
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

require_once(dirname(__FILE__)."/EntryRetrievalSession.php");

/**
 *  <p><code> EntryLookupSession </code> defines an interface to lookup 
 *  dictionary entries. This session includes the methods defined in the 
 *  EntryRetrievalSession. </p> 
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
 *  permits operation even if there is data out of sync or there is an 
 *  authorization block in a particular element. </p>
 * 
 * @package org.osid.dictionary
 */
interface osid_dictionary_EntryLookupSession
    extends osid_dictionary_EntryRetrievalSession
{


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeEntryView();


    /**
     *  A complete view of the <code> Entry </code> returns is desired. 
     *  Methods will return what is requested or result in an error. This view 
     *  is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryEntryView();


    /**
     *  Gets all the <code> Dictionary </code> entries <code> . </code> 
     *
     *  @return object osid_dictionary_EntryList the list of entries 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEntries();


    /**
     *  Gets all the <code> Dictionary </code> entries matching the given key 
     *  <code> Type. </code> 
     *
     *  @param object osid_type_Type $keyType the type of the key to match 
     *  @return object osid_dictionary_EntryList the list of entries matching 
     *          <code> keyType </code> 
     *  @throws osid_NullArgumentException <code> keyType </code> is null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEntriesByKeyType(osid_type_Type $keyType);


    /**
     *  Gets all the <code> Dictionary </code> entries matching the given key 
     *  and value <code> Type. </code> 
     *
     *  @param object osid_type_Type $keyType the type of the key to match 
     *  @param object osid_type_Type $valueType the type of the value to match 
     *  @return object osid_dictionary_EntryList the list of entries matching 
     *          <code> keyType </code> 
     *  @throws osid_NullArgumentException <code> keyType </code> or <code> 
     *          valueType </code> is null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEntriesByKeyAndValueType(osid_type_Type $keyType, 
                                                osid_type_Type $valueType);

}
