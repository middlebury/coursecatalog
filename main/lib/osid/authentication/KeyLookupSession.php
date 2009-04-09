<?php

/**
 * osid_authentication_KeyLookupSession
 * 
 *     Specifies the OSID definition for osid_authentication_KeyLookupSession.
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
 *  <p>This session provides methods for retrieving <code> Key </code> 
 *  objects. The <code> Key </code> is associated with an <code> Agent </code> 
 *  and identified by the <code> Agent </code> Id. </p> 
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
 *  of precision. Keys may have an additional records indicated by their 
 *  respective record types. The record may not be accessed through a cast of 
 *  the <code> Key. </code> </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_KeyLookupSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can lookup <code> Keys. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known getting a <code> Key </code> will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt to offer key management functions to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if key management is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupKeys();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeKeyView();


    /**
     *  A complete view of the <code> Key </code> returns is desired. Methods 
     *  will return what is requested or result in an error. This view is used 
     *  when greater precision is desired at the expense of interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryKeyView();


    /**
     *  Tests if an agent has an associated key. 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> 
     *  @return boolean <code> true </code> if the agent has a key, <code> 
     *          false </code> otherwise 
     *  @throws osid_NotFoundException <code> agentId </code> is not found 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function hasKey(osid_id_Id $agentId);


    /**
     *  Gets the agent key. In plenary mode, the exact Id is found or a 
     *  NOT_FOUND results. Otherwise, the returned <code> Agent </code> via 
     *  the <code> Key </code> may have a different Id than requested, such as 
     *  the case where a duplicate <code> Id </code> was assigned to an Agent 
     *  and retained for compatibility. 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> 
     *  @return object osid_authentication_Key the key of the agent 
     *  @throws osid_NotFoundException <code> agentId </code> is not found or 
     *          no key exists 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKey(osid_id_Id $agentId);


    /**
     *  Gets a <code> KeyList </code> corresponding to the given agent <code> 
     *  IdList. </code> In plenary mode, the returned list contains all of the 
     *  keys for agents specified in the <code> Id </code> list, in the order 
     *  of the list, including duplicates, or an error results if an <code> Id 
     *  </code> in the supplied list is not found or inaccessible. Otherwise, 
     *  inaccessible <code> Keys </code> may be omitted from the list and may 
     *  present the elements in any order including returning a unique set. 
     *
     *  @param object osid_id_IdList $agentIdList the list of <code> Ids 
     *          </code> to rerieve 
     *  @return object osid_authentication_KeyList the returned <code> Key 
     *          list </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> agentIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeysByIds(osid_id_IdList $agentIdList);


    /**
     *  Gets a list of keys corresponding to the given key record <code> Type. 
     *  </code> The set of keys implementing the given record type is 
     *  returned. In plenary mode, the returned list contains all known keys 
     *  or an error results. Otherwise, the returned list may contain only 
     *  those keys that are accessible through this session. In both cases, 
     *  the order of the set is not specified. 
     *
     *  @param object osid_type_Type $keyRecordType a key record type 
     *  @return object osid_authentication_KeyList the returned <code> Key 
     *          list </code> 
     *  @throws osid_NullArgumentException <code> keyRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeysByRecordType(osid_type_Type $keyRecordType);


    /**
     *  Gets all <code> Keys. </code> In plenary mode, the returned list 
     *  contains all known keys or an error results. Otherwise, the returned 
     *  list may contain only those keys that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @return object osid_authentication_KeyList a list of <code> Keys 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeys();

}
