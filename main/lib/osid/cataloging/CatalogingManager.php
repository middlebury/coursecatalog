<?php

/**
 * osid_cataloging_CatalogingManager
 * 
 *     Specifies the OSID definition for osid_cataloging_CatalogingManager.
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

require_once(dirname(__FILE__)."/../OsidManager.php");
require_once(dirname(__FILE__)."/CatalogingProfile.php");

/**
 *  <p>The cataloging manager provides access to cataloging sessions and 
 *  provides interoperability tests for various aspects of this service. The 
 *  sessions included in this manager are: 
 *  <ul>
 *      <li> <code> CatalogSession: </code> a session to lookup mappings to 
 *      catalogs </li> 
 *      <li> <code> CatalogAssignmentSession: </code> a session to manage Id 
 *      to Catalog mappings </li> 
 *      <li> <code> CatalogAssignmentNotificationSession: </code> a session to 
 *      receive notification of changed mappings </li> 
 *      <li> <code> CatalogLookupSession: </code> a session to retrieve 
 *      catalog objects </li> 
 *      <li> <code> CatalogSearchSession: </code> a session to search for 
 *      catalogs </li> 
 *      <li> <code> CatalogAdminSession: </code> a session to create, update 
 *      and delete catalogs </li> 
 *      <li> <code> CatalogNotificationSession: </code> a session to receive 
 *      notifications for changes in catalogs </li> 
 *      <li> <code> CatalogHierarchyTraversalSession: </code> a session to 
 *      traverse hierarchies of catalogs </li> 
 *      <li> <code> CatalogHierarchyDesignSession: </code> a session to manage 
 *      hierarchues of catalogs </li> 
 *  </ul>
 *  The cataloging manager also provides a profile for determing the supported 
 *  search types supported by this service. </p>
 * 
 * @package org.osid.cataloging
 */
interface osid_cataloging_CatalogingManager
    extends osid_OsidManager,
            osid_cataloging_CatalogingProfile
{


    /**
     *  Gets the cataloging session for retrieving mappings to catalogs. 
     *
     *  @return object osid_cataloging_CatalogSession a <code> CatalogSession 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsCatalog() </code> 
     *          is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCatalog() </code> is <code> true. </code> 
     */
    public function getCatalogSession();


    /**
     *  Gets the cataloging session for adding and removing mappings to 
     *  catalogs. 
     *
     *  @return object osid_cataloging_CatalogAssignmentSession a <code> 
     *          CatalogAssignmentSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsCatalogAssignment() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCatalogAssignment() </code> is <code> true. 
     *              </code> 
     */
    public function getCatalogAssignmentSession();


    /**
     *  Gets the notification session for subscribing to changes to catalogs. 
     *
     *  @param object osid_cataloging_CatalogEntryReceiver $receiver the 
     *          notification callback 
     *  @return object osid_cataloging_CatalogNotificationSession a <code> 
     *          CatalogAssignmentNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCatalogAssignmentNotification() </code> is <code> 
     *          false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCatalogAssignmentNotification() </code> is <code> 
     *              true. </code> 
     */
    public function getCatalogAssignmentNotificationSession(osid_cataloging_CatalogEntryReceiver $receiver);


    /**
     *  Gets the catalog lookup session. 
     *
     *  @return object osid_cataloging_CatalogLookupSession a <code> 
     *          CatalogLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsCatalogLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCatalogLookup() </code> is <code> true. </code> 
     */
    public function getCatalogLookupSession();


    /**
     *  Gets the catalog search session. 
     *
     *  @return object osid_cataloging_CatalogSearchSession a <code> 
     *          CatalogSearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsCatalogSearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCatalogSearch() </code> is <code> true. </code> 
     */
    public function getCatalogSearchSession();


    /**
     *  Gets the catalog administrative session for creating, updating and 
     *  deleting catalogs. 
     *
     *  @return object osid_cataloging_CatalogAdminSession a <code> 
     *          CatalogAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsCatalogAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCatalogAdmin() </code> is <code> true. </code> 
     */
    public function getCatalogAdminSession();


    /**
     *  Gets the notification session for subscribing to changes to catalogs. 
     *
     *  @param object osid_cataloging_CatalogReceiver $receiver the 
     *          notification callback 
     *  @return object osid_cataloging_CatalogNotificationSession a <code> 
     *          CatalogNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCatalogNotification() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCatalogNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getCatalogNotificationSession(osid_cataloging_CatalogReceiver $receiver);


    /**
     *  Gets the catalog hierarchy traversal session. 
     *
     *  @return object osid_cataloging_CatalogHierarchySession a <code> 
     *          CatalogHierarchySession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsCatalogHierarchy() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCatalogHierarchy() </code> is <code> true. </code> 
     */
    public function getCatalogHierarchySession();


    /**
     *  Gets the catalog hierarchy design session. 
     *
     *  @return object osid_cataloging_CatalogHierarchyDesignSession a <code> 
     *          CatalogHierarchyDesignSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCatalogHierarchyDesign() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCatalogHierarchyDesign() </code> is <code> true. 
     *              </code> 
     */
    public function getCatalogHierarchyDesignSession();

}
