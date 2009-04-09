<?php

/**
 * osid_dictionary_EntryNotificationSession
 * 
 *     Specifies the OSID definition for osid_dictionary_EntryNotificationSession.
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
 *  to <code> Entry </code> objects within a <code> Dictionary. </code> This 
 *  session is intended for consumers needing to synchronize their state with 
 *  this service without the use of polling. Notifications are cancelled when 
 *  this session is closed. </p>
 * 
 * @package org.osid.dictionary
 */
interface osid_dictionary_EntryNotificationSession
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
     *  Tests if this user can register for <code> Entry </code> 
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
    public function canRegisterForEntryNotifications();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include entries from parent dictionaries in the dictionary hierarchy. 
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
     *  Registers for notifications of new entries. <code> 
     *  EntryReceiver.newEntry(key, keyType) </code> is invoked when a new 
     *  <code> Entry </code> is created. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewEntries();


    /**
     *  Regsiters for notification of updated entries. <code> 
     *  EntryReceiver.changedEntry(key, keyType) </code> is invoked when an 
     *  <code> Entry </code> is changed. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedEntries();


    /**
     *  Registers for notifications of an update to an <code> Entry. </code> 
     *  <code> EntryReceiver.changedEntry(key, keyType) </code> is invoked 
     *  when the specified <code> Entry </code> is changed. 
     *
     *  @param object $key the entry key 
     *  @param object osid_type_Type $keyInterfaceType the entry key type 
     *  @param object osid_type_Type $valueInterfaceType the entry key type 
     *  @throws osid_NotFoundException entry not found 
     *  @throws osid_NullArgumentException <code> key </code> or <code> 
     *          keyType </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedEntry($key, 
                                            osid_type_Type $keyInterfaceType, 
                                            osid_type_Type $valueInterfaceType);


    /**
     *  Registers for notification of deleted dictionaries. <code> 
     *  EntryReceiver.deletedEntry(key, keyType) </code> is invoked when the 
     *  specified <code> Entry </code> is deleted. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedEntries();


    /**
     *  Registers for notifications of a deleted <code> Entry. </code> <code> 
     *  EntryReceiver.deletedEntry(key, keyType) </code> is invoked when the 
     *  specified <code> Entry </code> is deleted. 
     *
     *  @param object $key the entry key 
     *  @param object osid_type_Type $keyInterfaceType the entry key interface 
     *          type 
     *  @param object osid_type_Type $valueInterfaceType the entry key 
     *          interface type 
     *  @throws osid_NotFoundException entry not found 
     *  @throws osid_NullArgumentException <code> key, keyInterfaceType or 
     *          valueInterfaceType </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedEntry($key, 
                                            osid_type_Type $keyInterfaceType, 
                                            osid_type_Type $valueInterfaceType);

}
