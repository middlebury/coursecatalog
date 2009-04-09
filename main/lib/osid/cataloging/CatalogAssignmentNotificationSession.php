<?php

/**
 * osid_cataloging_CatalogAssignmentNotificationSession
 * 
 *     Specifies the OSID definition for osid_cataloging_CatalogAssignmentNotificationSession.
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
 *  to a catalog assignment. This session is intended for consumers needing to 
 *  synchronize their state with this service without the use of polling. 
 *  Notifications are cancelled when this session is closed. </p> 
 *  
 *  <p> Notifications are triggered with changes to assignments of <code> Ids 
 *  </code> to this catalog. For notifications of changes to the <code> 
 *  Catalog </code> object use <code> CatalogNotificationSession. </code> </p>
 * 
 * @package org.osid.cataloging
 */
interface osid_cataloging_CatalogAssignmentNotificationSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can register for <code> Catalog </code> entry 
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
    public function canRegisterForCatalogEntryNotifications();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include entries from parent catalogs in the catalog hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedCatalogView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts notifications for entries to this catalog only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedCatalogView();


    /**
     *  Register for notifications of new catalogs. <code> 
     *  CatalogEntryReceiver.newCatalogEntry() </code> is invoked when a new 
     *  <code> Catalog </code> entry is created. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the C 
     *          <code> atalog </code> node to monitor 
     *  @throws osid_NotFoundException a catalog node was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> catalogId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForNewCatalogEntries(osid_id_Id $catalogId);


    /**
     *  Registers for notification of deleted catalogs entries. <code> 
     *  CatalogEntryReceiver.deletedCatalogEntry() </code> is invoked when a 
     *  catalog entry is deleted. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the C 
     *          <code> atalog </code> node to monitor 
     *  @throws osid_NotFoundException a catalog node was not found identified 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> catalogId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForDeletedCatalogEntries(osid_id_Id $catalogId);

}
