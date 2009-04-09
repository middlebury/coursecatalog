<?php

/**
 * osid_dictionary_DictionaryNotificationSession
 * 
 *     Specifies the OSID definition for osid_dictionary_DictionaryNotificationSession.
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
 *  <p>This session defines methods to receive notifications on adds/changes 
 *  to <code> Dictionary </code> objects. This session is intended for 
 *  consumers needing to synchronize their state with this service without the 
 *  use of polling. Notifications are cancelled when this session is closed. 
 *  </p>
 * 
 * @package org.osid.dictionary
 */
interface osid_dictionary_DictionaryNotificationSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can register for <code> Dictionary </code> 
     *  notifications. A return of true does not guarantee successful 
     *  authorization. A return of false indicates that it is known all 
     *  methods in this session will result in a <code> PERMISSION_DENIED. 
     *  </code> This is intended as a hint to an application that may opt not 
     *  to offer notification operations. 
     *
     *  @return boolean <code> false </code> if notification methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canRegisterForDictionaryNotifications();


    /**
     *  Register for notifications of new dictionaries. <code> 
     *  DictionaryReceiver.newDictionary() </code> is invoked when a new 
     *  <code> Dictionary </code> is created. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewDictionaries();


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  introduces a new ancestor of the specified dictionary. <code> 
     *  DictionaryReceiver.newAncestorDictionary() </code> is invoked when the 
     *  specified dictionary node gets a new ancestor. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> node to monitor 
     *  @throws osid_NotFoundException a dictionary node was not found 
     *          identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewDictionaryAncestors(osid_id_Id $dictionaryId);


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  introduces a new descendant of the specified dictionary. <code> 
     *  DictionaryReceiver.newDescendantDictionary() </code> is invoked when 
     *  the specified dictionary node gets a new descendant. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> node to monitor 
     *  @throws osid_NotFoundException a dictionary node was not found 
     *          identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewDictionaryDescendants(osid_id_Id $dictionaryId);


    /**
     *  Registers for notification of updated dictionaries. <code> 
     *  DictionaryReceiver.changedDictionary() </code> is invoked when a 
     *  dictionary is changed. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedDictionaries();


    /**
     *  Registers for notification of an updated dictionary. <code> 
     *  DictionaryReceiver.changedDictionary() </code> is invoked when the 
     *  specified dictionary is changed. A notification may be triggered for 
     *  any updated, deleted or new dictionary the specified dictionary 
     *  inherits data from. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> to monitor 
     *  @throws osid_NotFoundException a <code> Dictionary </code> was not 
     *          found identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedDictionary(osid_id_Id $dictionaryId);


    /**
     *  Registers for notification of deleted dictionaries. <code> 
     *  DictionaryReceiver.deletedDictionary() </code> is invoked when a 
     *  dictionary is deleted. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedDictionaries();


    /**
     *  Registers for notification of a deleted dictionary. <code> 
     *  DictionaryReceiver.changedDictionary() </code> is invoked when the 
     *  specified dictionary is changed. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> to monitor 
     *  @throws osid_NotFoundException a <code> Dictionary </code> was not 
     *          found identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedDictionary(osid_id_Id $dictionaryId);


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  removes an ancestor of the specified dictionary. <code> 
     *  DictionaryReceiver.deletedAncestor() </code> is invoked when the 
     *  specified dictionary node loses an ancestor. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> to monitor 
     *  @throws osid_NotFoundException a <code> Dictionary </code> was not 
     *          found identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedDictionaryAncestors(osid_id_Id $dictionaryId);


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  removes a descendant of the specified dictionary. <code> 
     *  DictionaryReceiver.deletedDescendant() </code> is invoked when the 
     *  specified dictionary node loses a descendant. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          <code> Dictionary </code> to monitor 
     *  @throws osid_NotFoundException a <code> Dictionary </code> was not 
     *          found identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedDictionaryDescendants(osid_id_Id $dictionaryId);

}
