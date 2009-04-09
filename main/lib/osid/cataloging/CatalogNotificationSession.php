<?php

/**
 * osid_cataloging_CatalogNotificationSession
 * 
 *     Specifies the OSID definition for osid_cataloging_CatalogNotificationSession.
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
 * @package org.osid.cataloging
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session defines methods to receive notifications on adds/changes 
 *  to <code> Catalog </code> objects. This session is intended for consumers 
 *  needing to synchronize their state with this service without the use of 
 *  polling. Notifications are cancelled when this session is closed. </p> 
 *  
 *  <p> Notifications are triggered with changes to the <code> Catalog </code> 
 *  object itself. Adding and removing <code> Ids </code> result in 
 *  notifications available from the notification session for catalog entries. 
 *  </p>
 * 
 * @package org.osid.cataloging
 */
interface osid_cataloging_CatalogNotificationSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can register for <code> Catalog </code> 
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
    public function canRegisterForCatalogNotifications();


    /**
     *  Register for notifications of new catalogs. <code> 
     *  CatalogReceiver.newCatalog() </code> is invoked when a new <code> 
     *  Catalog </code> is created. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewCatalogs();


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  introduces a new ancestor of the specified catalog. <code> 
     *  CatalogReceiver.newAncestorCatalog() </code> is invoked when the 
     *  specified catalog node gets a new ancestor. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          <code> Catalog </code> node to monitor 
     *  @throws osid_NotFoundException a catalog node was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> catalogId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewCatalogAncestors(osid_id_Id $catalogId);


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  introduces a new descendant of the specified catalog. <code> 
     *  CatalogReceiver.newDescendantCatalog() </code> is invoked when the 
     *  specified catalog node gets a new descendant. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          <code> Catalog </code> node to monitor 
     *  @throws osid_NotFoundException a catalog node was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> catalogId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewCatalogDescendants(osid_id_Id $catalogId);


    /**
     *  Registers for notification of updated catalogs. <code> 
     *  CatalogReceiver.changedCatalog() </code> is invoked when a catalog is 
     *  changed. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedCatalogs();


    /**
     *  Registers for notification of an updated catalog. <code> 
     *  CatalogReceiver.changedCatalog() </code> is invoked when the specified 
     *  catalog is changed. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          <code> Catalog </code> to monitor 
     *  @throws osid_NotFoundException a catalog was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> catalogId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedCatalog(osid_id_Id $catalogId);


    /**
     *  Registers for notification of deleted catalogs. <code> 
     *  CatalogReceiver.deletedCatalog() </code> is invoked when a catalog is 
     *  deleted. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedCatalogs();


    /**
     *  Registers for notification of a deleted catalog. <code> 
     *  CatalogReceiver.deletedCatalog() </code> is invoked when the specified 
     *  catalog is deleted. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          <code> Catalog </code> to monitor 
     *  @throws osid_NotFoundException a catalog was not found identified by 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> catalogId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedCatalog(osid_id_Id $catalogId);


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  removes an ancestor of the specified catalog. <code> 
     *  CatalogReceiver.deletedAncestor() </code> is invoked when the 
     *  specified catalog node loses an ancestor. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          <code> Catalog </code> node to monitor 
     *  @throws osid_NotFoundException a catalog node was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> catalogId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedCatalogAncestors(osid_id_Id $catalogId);


    /**
     *  Registers for notification of an updated hierarchy structure that 
     *  removes a descendant of the specified catalog. <code> 
     *  CatalogReceiver.deletedDescendant() </code> is invoked when the 
     *  specified catalog node loses a descendant. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          <code> Catalog </code> node to monitor 
     *  @throws osid_NotFoundException a catalog node was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> catalogId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedCatalogDescendants(osid_id_Id $catalogId);

}
