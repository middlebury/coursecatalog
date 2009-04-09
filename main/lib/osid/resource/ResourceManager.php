<?php

/**
 * osid_resource_ResourceManager
 * 
 *     Specifies the OSID definition for osid_resource_ResourceManager.
 * 
 * Copyright (C) 2008 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.resource
 */

require_once(dirname(__FILE__)."/../OsidManager.php");
require_once(dirname(__FILE__)."/ResourceProfile.php");

/**
 *  <p>The resource manager provides access to resource lookup and creation 
 *  sessions and provides interoperability tests for various aspects of this 
 *  service. The sessions included in this manager are: 
 *  <ul>
 *      <li> <code> ResourceLookupSession: </code> a session to retrieve 
 *      resources </li> 
 *      <li> <code> ResourceSearchSession: </code> a session to search for 
 *      resources </li> 
 *      <li> <code> ResourceAdminSession: </code> a session to create and 
 *      delete resources </li> 
 *      <li> <code> ResourceNotificationSession: </code> a session to receive 
 *      notifications pertaining to resource changes </li> 
 *      <li> <code> ResourceBinSession: </code> a session to look up resource 
 *      to bin mappings </li> 
 *      <li> <code> ResourceBinAssignmentSession: </code> a session to manage 
 *      resource to bin mappings </li> 
 *  </ul>
 *  
 *  <ul>
 *      <li> <code> BinLookupSession: a </code> session to retrieve bins </li> 
 *      <li> <code> BinSearchSession: </code> a session to search for bins 
 *      </li> 
 *      <li> <code> BinAdminSession: </code> a session to create, update and 
 *      delete bins </li> 
 *      <li> <code> BinNotificationSession: </code> a session to receive 
 *      notifications pertaining to changes in bins </li> 
 *      <li> <code> BinHierarchySession: </code> a session to traverse bin 
 *      hierarchies </li> 
 *      <li> <code> BinHierarchyDesignSession: </code> a session to manage bin 
 *      hierarchies </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.resource
 */
interface osid_resource_ResourceManager
    extends osid_OsidManager,
            osid_resource_ResourceProfile
{


    /**
     *  Gets the <code> OsidSession </code> associated with the resource 
     *  lookup service. 
     *
     *  @return object osid_resource_ResourceLookupSession <code> a 
     *          ResourceLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsResourceLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsResourceLookup() </code> is <code> true. </code> 
     */
    public function getResourceLookupSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the resource 
     *  lookup service for the given bin. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the bin 
     *  @return object osid_resource_ResourceLookupSession <code> a 
     *          ResourceLookupSession </code> 
     *  @throws osid_NotFoundException <code> binId </code> not found 
     *  @throws osid_NullArgumentException <code> binId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_UnimplementedException <code> supportsResourceLookup() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsResourceLookup() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getResourceLookupSessionForBin(osid_id_Id $binId);


    /**
     *  Gets a resource search session. 
     *
     *  @return object osid_resource_ResourceSearchSession <code> a 
     *          ResourceSearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsResourceSearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsResourceSearch() </code> is <code> true. </code> 
     */
    public function getResourceSearchSession();


    /**
     *  Gets a resource search session for the given bin. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the bin 
     *  @return object osid_resource_ResourceSearchSession <code> a 
     *          ResourceSearchSession </code> 
     *  @throws osid_NotFoundException <code> binId </code> not found 
     *  @throws osid_NullArgumentException <code> binId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_UnimplementedException <code> supportsResourceSearch() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsResourceSearch() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getResourceSearchSessionForBin(osid_id_Id $binId);


    /**
     *  Gets a resource administration session for creating, updating and 
     *  deleting resources. 
     *
     *  @return object osid_resource_ResourceAdminSession <code> a 
     *          ResourceAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsResourceAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsResourceAdmin() </code> is <code> true. </code> 
     */
    public function getResourceAdminSession();


    /**
     *  Gets a resource administration session for the given bin. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the bin 
     *  @return object osid_resource_ResourceAdminSession <code> a 
     *          ResourceAdminSession </code> 
     *  @throws osid_NotFoundException <code> binId </code> not found 
     *  @throws osid_NullArgumentException <code> binId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsResourceAdmin() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsResourceAdmin() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getResourceAdminSessionForBin(osid_id_Id $binId);


    /**
     *  Gets the notification session for notifications pertaining to resource 
     *  changes. 
     *
     *  @param object osid_resource_ResourceReceiver $receiver the 
     *          notification callback 
     *  @return object osid_resource_ResourceNotificationSession <code> a 
     *          ResourceNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsResourceNotification() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsResourceNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getResourceNotificationSession(osid_resource_ResourceReceiver $receiver);


    /**
     *  Gets the resource notification session for the given bin. 
     *
     *  @param object osid_resource_ResourceReceiver $receiver the 
     *          notification callback 
     *  @param object osid_id_Id $binId the <code> Id </code> of the bin 
     *  @return object osid_resource_ResourceNotificationSession <code> a 
     *          ResourceNotificationSession </code> 
     *  @throws osid_NotFoundException <code> binId </code> not found 
     *  @throws osid_NullArgumentException <code> receiver </code> or <code> 
     *          binId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_UnimplementedException <code> 
     *          supportsResourceNotification() </code> or <code> 
     *          supportsVisibleFederation() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsResourceNotfication() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getResourceNotificationSessionForBin(osid_resource_ResourceReceiver $receiver, 
                                                         osid_id_Id $binId);


    /**
     *  Gets the session for retrieving resource to bin mappings. 
     *
     *  @return object osid_resource_ResourceBinSession a <code> 
     *          ResourceBinSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsResourceBin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsResourceBin() </code> is <code> true. </code> 
     */
    public function getResourceBinSession();


    /**
     *  Gets the session for assigning resource to bin mappings. 
     *
     *  @return object osid_resource_ResourceBinAssignmentSession a <code> 
     *          ResourceBinAssignmentSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsResourceBinAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsResourceBinAssignment() </code> is <code> true. 
     *              </code> 
     */
    public function getResourceBinAssignmentSession();


    /**
     *  Gets the bin lookup session. 
     *
     *  @return object osid_resource_BinLookupSession a <code> 
     *          BinLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsBinLookup() </code> 
     *          is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsBinLookup() </code> is <code> true. </code> 
     */
    public function getBinLookupSession();


    /**
     *  Gets the bin search session. 
     *
     *  @return object osid_resource_BinSearchSession a <code> 
     *          BinSearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsBinSearch() </code> 
     *          is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsBinSearch() </code> is <code> true. </code> 
     */
    public function getBinSearchSession();


    /**
     *  Gets the bin administrative session for creating, updating and 
     *  deleteing bins. 
     *
     *  @return object osid_resource_BinAdminSession a <code> BinAdminSession 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsBinAdmin() </code> 
     *          is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsBinAdmin() </code> is <code> true. </code> 
     */
    public function getBinAdminSession();


    /**
     *  Gets the notification session for subscribing to changes to a bin. 
     *
     *  @param object osid_resource_BinReceiver $receiver the notification 
     *          callback 
     *  @return object osid_resource_BinNotificationSession a <code> 
     *          BinNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsBinNotification() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsBinNotification() </code> is <code> true. </code> 
     */
    public function getBinNotificationSession(osid_resource_BinReceiver $receiver);


    /**
     *  Gets the bin hierarchy traversal session. 
     *
     *  @return object osid_resource_BinHierarchySession <code> a 
     *          BinHierarchySession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsBinHierarchy() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsBinHierarchy() </code> is <code> true. </code> 
     */
    public function getBinHierarchySession();


    /**
     *  Gets the bin hierarchy design session. 
     *
     *  @return object osid_resource_BinHierarchyDesignSession a <code> 
     *          BinHierarchyDesignSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsBinHierarchyDesign() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsBinHierarchyDesign() </code> is <code> true. 
     *              </code> 
     */
    public function getBinHierarchyDesignSession();

}
