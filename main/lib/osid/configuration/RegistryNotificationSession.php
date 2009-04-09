<?php

/**
 * osid_configuration_RegistryNotificationSession
 * 
 *     Specifies the OSID definition for osid_configuration_RegistryNotificationSession.
 * 
 * Copyright (C) 2008 Massachusetts Institute of Technology. All Rights 
 * Reserved. 
 * 
 *     This Work is being provided by the copyright holder(s) subject to the 
 *     following license. By obtaining, using and/or copying this Work, you 
 *     agree that you have read, understand, and will comply with the 
 *     following terms and conditions. 
 *     
 *     This Work and the information contained herein is provided on an ""AS 
 *     IS"" basis. The Massachusetts Institute of Technology, the Open 
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
 * @package org.osid.configuration
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session defines methods to receive notifications on adds/changes 
 *  to <code> Registry </code> objects. Notifications pertaining to the adding 
 *  or removal of parameters are handled through the <code> 
 *  ParameterNotificationSession.This </code> session is intended for adapters 
 *  and providers needing to synchronize their state with this service without 
 *  the use of polling. Notifications are cancelled when this session is 
 *  closed. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_RegistryNotificationSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can register for <code> Registry </code> 
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
    public function canRegisterForRegistryNotifications();


    /**
     *  Registers for notifications of new registries. <code> 
     *  RegistryReceiver.newRegistry() </code> is invoked when a new <code> 
     *  Registry </code> is created. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewRegistries();


    /**
     *  Registers for notification if an ancestor is added to the specified 
     *  registry in the registry hierarchy. <code> 
     *  RegistryReceiver.newRegistryAncestor() </code> is invoked when the 
     *  specified registry experiences an addition in ancestry. 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          registry to monitor 
     *  @throws osid_NotFoundException a registry was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewRegistryAncestors(osid_id_Id $registryId);


    /**
     *  Registers for notification if a descedant is added to the specified 
     *  registry in the registry hierarchy. <code> 
     *  RegistryReceiver.newRegistryDescendant() </code> is invoked when the 
     *  specified registry experiences an addition in descendants. 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          registry to monitor 
     *  @throws osid_NotFoundException a registry was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewRegistryDescendants(osid_id_Id $registryId);


    /**
     *  Registers for notification of updated registries. <code> 
     *  RegistryReceiver.changedRegistry() </code> is invoked when a registry 
     *  is changed. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedRegistries();


    /**
     *  Registers for notifications of an update to a registry. <code> 
     *  RegistryReceiver.changedRegistry() </code> is invoked when the 
     *  specified <code> Registry </code> is changed. 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          <code> Registry </code> to monitor 
     *  @throws osid_NotFoundException a <code> Registry </code> was not found 
     *          identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedRegistry(osid_id_Id $registryId);


    /**
     *  Registers for notification of deleted registries. <code> 
     *  RegistryReceiver.deletedRegistry() </code> is invoked when a <code> 
     *  Registry </code> is deleted. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedRegistries();


    /**
     *  Registers for notifications of a deleted registry. <code> 
     *  RegistryReceiver.deletedRegistry() </code> is invoked when the 
     *  specified registry is deleted. 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          <code> Registry </code> to monitor 
     *  @throws osid_NotFoundException a <code> Registry </code> was not found 
     *          identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedRegistry(osid_id_Id $registryId);


    /**
     *  Registers for notification if an ancestor is removed from the 
     *  specified registry in the registry hierarchy. <code> 
     *  RegistryReceiver.deletedRegistryAncestor() </code> is invoked when the 
     *  specified registry experiences a removal of an ancestor. 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          registry to monitor 
     *  @throws osid_NotFoundException a registry was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedRegistryAncestors(osid_id_Id $registryId);


    /**
     *  Registers for notification if a descendant is removed from fthe 
     *  specified registry in the registry hierarchy. <code> 
     *  RegistryReceiver.deletedregistryDescednant() </code> is invoked when 
     *  the specified registry experiences a removal of one of its 
     *  descendants. 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          registry to monitor 
     *  @throws osid_NotFoundException a registry was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedRegistryDescendants(osid_id_Id $registryId);

}
