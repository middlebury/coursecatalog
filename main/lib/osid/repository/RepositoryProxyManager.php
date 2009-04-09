<?php

/**
 * osid_repository_RepositoryProxyManager
 * 
 *     Specifies the OSID definition for osid_repository_RepositoryProxyManager.
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
 * @package org.osid.repository
 */

require_once(dirname(__FILE__)."/../OsidProxyManager.php");
require_once(dirname(__FILE__)."/RepositoryProfile.php");

/**
 *  <p>The repository manager provides access to asset lookup and creation 
 *  session and provides interoperability tests for various aspects of this 
 *  service. Methods in this manager support the passing of an Authentication 
 *  object for the purposes of proxy authentication. The sessions included in 
 *  this manager are: 
 *  <ul>
 *      <li> <code> AssetLookupSession: </code> a session to retrieve assets 
 *      </li> 
 *      <li> <code> AssetSearchSession: </code> a session to search for assets 
 *      </li> 
 *      <li> <code> AssetAdminSession: </code> a session to create and delete 
 *      assets </li> 
 *      <li> <code> AssetNotificationSession: </code> a session to receive 
 *      notifications pertaining to asset changes </li> 
 *      <li> <code> AssetRepositorySession: </code> a session to look up asset 
 *      to repository mappings </li> 
 *      <li> <code> AssetRepositoryAssignmentSession: </code> a session to 
 *      manage asset to repository mappings </li> 
 *      <li> <code> AssetCreditSession: </code> a session to look up asset 
 *      credits </li> 
 *      <li> <code> AssetCreditAssignmentSession: </code> a session to manage 
 *      asset credits </li> 
 *      <li> <code> AssetTemporalSession: </code> a session to access the 
 *      temporal coverage of an asset </li> 
 *      <li> <code> AssetTemporalAssignmentSession: </code> a session to 
 *      manage the temporal coverage of an asset </li> 
 *      <li> <code> AssetSpatialSession: </code> a session to access the 
 *      spatial coverage of an asset </li> 
 *      <li> <code> AssetSpatialAssignmentSession: </code> a session to manage 
 *      the spatial coverage of an asset </li> 
 *  </ul>
 *  
 *  <ul>
 *      <li> <code> SubjectLookupSession: </code> a session to retrieve 
 *      subjects </li> 
 *      <li> <code> SubjectSearchSession: </code> a session to search for 
 *      subjects </li> 
 *      <li> <code> SubjectAdminSession: </code> a session to create and 
 *      delete subjects </li> 
 *      <li> <code> SubjectNotificationSession: </code> a session to receive 
 *      notifications pertaining to subjects changes </li> 
 *      <li> <code> SubjectHierarchySession: </code> a session to traverse a 
 *      hierarchy of subjects </li> 
 *      <li> <code> SubjectHierarchyDesignSession: </code> a session to manage 
 *      a subject hierarchy </li> 
 *      <li> <code> SubjectRepositorySession: </code> a session to look up 
 *      subject to repository mappings </li> 
 *      <li> <code> SubjectRepositoryAssignmentSession: </code> a session to 
 *      manage subject to repository mappings </li> 
 *  </ul>
 *  
 *  <ul>
 *      <li> <code> RepositoryLookupSession: a </code> session to retrieve 
 *      repositories </li> 
 *      <li> <code> RepositorySearchSession: </code> a session to search for 
 *      repositories </li> 
 *      <li> <code> RepositoryAdminSession: </code> a session to create, 
 *      update and delete repositories </li> 
 *      <li> <code> RepositoryNotificationSession: </code> a session to 
 *      receive notifications pertaining to changes in repositories </li> 
 *      <li> <code> RepositoryHierarchySession: </code> a session to traverse 
 *      repository hierarchies </li> 
 *      <li> <code> RepositoryHierarchyDesignSession: </code> a session to 
 *      manage repository hierarchies </li> 
 *  </ul>
 *  
 *  <ul>
 *      <li> <code> CompositionLookupSession: a </code> session to retrieve 
 *      compositions </li> 
 *      <li> <code> CompositionSearchSession: </code> a session to search for 
 *      compositions </li> 
 *      <li> <code> CompositionAdminSession: </code> a session to create, 
 *      update and delete compositions </li> 
 *      <li> <code> CompositionNotificationSession: </code> a session to 
 *      receive notifications pertaining to changes in compositions </li> 
 *      <li> <code> CompositionHierarchySession: </code> a session to traverse 
 *      composition hierarchies </li> 
 *      <li> <code> CompositionHierarchyDesignSession: </code> a session to 
 *      manage composition hierarchies </li> 
 *  </ul>
 *  In addition to these sessions, repository leverages other service 
 *  definitions to fulfill its service. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> <code> ResourceManager: </code> provides the interfaces to find, 
 *      create and modify resources known to this service </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_RepositoryProxyManager
    extends osid_OsidProxyManager,
            osid_repository_RepositoryProfile
{


    /**
     *  Gets the <code> OsidSession </code> associated with the asset lookup 
     *  service. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetLookupSession the new <code> 
     *          AssetLookupSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAssetLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetLookup() </code> is <code> true. </code> 
     */
    public function getAssetLookupSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the <code> OsidSession </code> associated with the asset lookup 
     *  service for the given repository. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          repository 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetLookupSession the new <code> 
     *          AssetLookupSession </code> 
     *  @throws osid_NotFoundException <code> repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> repositoryId </code> or 
     *          <code> authentication </code> is <code> null </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAssetLookup() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetLookup() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getAssetLookupSessionForRepository(osid_id_Id $repositoryId, 
                                                       osid_authentication_Authentication $authentication);


    /**
     *  Gets an asset search session. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetSearchSession an <code> 
     *          AssetSearchSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAssetSearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetSearch() </code> is <code> true. </code> 
     */
    public function getAssetSearchSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets an asset search session for the given repository. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          repository 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetSearchSession an <code> 
     *          AssetSearchSession </code> 
     *  @throws osid_NotFoundException <code> repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> repositoryId </code> or 
     *          <code> authentication </code> is <code> null </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAssetSearch() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetSearch() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getAssetSearchSessionForRepository(osid_id_Id $repositoryId, 
                                                       osid_authentication_Authentication $authentication);


    /**
     *  Gets an asset administration session for creating, updating and 
     *  deleting assets. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetAdminSession an <code> 
     *          AssetAdminSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAssetAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetAdmin() </code> is <code> true. </code> 
     */
    public function getAssetAdminSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets an asset administration session for the given repository. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          repository 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetAdminSession an <code> 
     *          AssetAdminSession </code> 
     *  @throws osid_NotFoundException <code> repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> repositoryId </code> or 
     *          <code> authentication </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAssetAdmin() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetAdmin() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getAssetAdminSessionForRepository(osid_id_Id $repositoryId, 
                                                      osid_authentication_Authentication $authentication);


    /**
     *  Gets the notification session for notifications pertaining to asset 
     *  changes. 
     *
     *  @param object osid_repository_AssetReceiver $receiver notification 
     *          callback 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetNotificationSession an <code> 
     *          AssetNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> or <code> 
     *          authentication </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAssetNotification() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getAssetNotificationSession(osid_repository_AssetReceiver $receiver, 
                                                osid_authentication_Authentication $authentication);


    /**
     *  Gets the asset notification session for the given repository. 
     *
     *  @param object osid_repository_AssetReceiver $receiver notification 
     *          callback 
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          repository 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetNotificationSession an <code> 
     *          AssetNotificationSession </code> 
     *  @throws osid_NotFoundException <code> repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> receiver, repositoryId 
     *          </code> or <code> authentication </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAssetNotification() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetNotfication() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getAssetNotificationSessionForRepository(osid_repository_AssetReceiver $receiver, 
                                                             osid_id_Id $repositoryId, 
                                                             osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for retrieving asset to repository mappings. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetRepositorySession an <code> 
     *          AssetRepositorySession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAssetRepository() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetRepository() </code> is <code> true. </code> 
     */
    public function getAssetRepositorySession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for assigning asset to repository mappings. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetRepositoryAssignmentSession an 
     *          <code> AssetRepositoryAsignmentSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsAssetRepositoryAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetRepositoryAssignment() </code> is <code> 
     *              true. </code> 
     */
    public function getAssetRepositoryAssignmentSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for retrieving asset to subject mappings. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetSubjectSession an <code> 
     *          AssetSubjectSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAssetSubject() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetSubject() </code> is <code> true. </code> 
     */
    public function getAssetSubjectSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for assigning asset to subject mappings. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetSubjectAssignmentSession an <code> 
     *          AssetSubjectAsignmentSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsAssetSubjectAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetSubjectAssignment() </code> is <code> true. 
     *              </code> 
     */
    public function getAssetSubjectAssignmentSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for retrieving asset alternatives. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetAlternativeSession an <code> 
     *          AssetAlternativeSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAssetAlternative() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetAlternative() </code> is <code> true. </code> 
     */
    public function getAssetAlternativeSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for assigning asset alternatives. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetAlternativeAssignmentSession an 
     *          <code> AssetAlternativeAssignmentSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsAssetAlternativeAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetAlternativeAssignment() </code> is <code> 
     *              true. </code> 
     */
    public function getAssetAlternativeAssignmentSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for retrieving asset credits. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetCreditSession an <code> 
     *          AssetCreditSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAssetCredit() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetCredit() </code> is <code> true. </code> 
     */
    public function getAssetCreditSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for assigning asset credits. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetCreditAssignmentSession an <code> 
     *          AssetCreditAssignmentSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsAssetCreditAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetCreditAssignment() </code> is <code> true. 
     *              </code> 
     */
    public function getAssetCreditAssignmentSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for retrieving temporal coverage of an asset. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetTemporalSession an <code> 
     *          AssetTemporalSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsTemporalAssets() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTemporalAssets() </code> is <code> true. </code> 
     */
    public function getAssetTemporalSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for assigning temporal coverage to an asset. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetTemporalAssignmentSession an 
     *          <code> AssetTemporalAssignmentSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsAssetTemporalAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetTemporalAssignment() </code> is <code> true. 
     *              </code> 
     */
    public function getAssetTemporalAssignmentSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for retrieving spatial coverage of an asset. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetSpatialSession an <code> 
     *          AssetSpatialSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsSpatialAssets() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSpatialAssets() </code> is <code> true. </code> 
     */
    public function getAssetSpatialSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for assigning spatial coverage to an asset. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetSpatialAssignmentSession an <code> 
     *          AssetSpatialAssignmentSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsAssetSpatialAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetSpatialAssignment() </code> is <code> true. 
     *              </code> 
     */
    public function getAssetSpatialAssignmentSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for retrieving asset compositions. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetCompositionSession an <code> 
     *          AssetCompositionSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAssetComposition() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetComposition() </code> is <code> true. </code> 
     */
    public function getAssetCompositionSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for creating asset compositions. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_AssetCompositionAssignmentSession an 
     *          <code> AssetCompositionAssignmentSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsAssetCompositionAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetCompositionAssignment() </code> is <code> 
     *              true. </code> 
     */
    public function getAssetCompositionAssignmentSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the <code> OsidSession </code> associated with the subject lookup 
     *  service. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_SubjectLookupSession the new <code> 
     *          SubjectLookupSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsSubjectLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectLookup() </code> is <code> true. </code> 
     */
    public function getSubjectLookupSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the <code> OsidSession </code> associated with the subject lookup 
     *  service for the given repository. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          repository 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_SubjectLookupSession the new <code> 
     *          SubjectLookupSession </code> 
     *  @throws osid_NotFoundException <code> repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> repositoryId </code> or 
     *          <code> authentication </code> s <code> null </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsSubjectLookup() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectLookup() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getSubjectLookupSessionForRepository(osid_id_Id $repositoryId, 
                                                         osid_authentication_Authentication $authentication);


    /**
     *  Gets a subject search session. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_SubjectSearchSession a <code> 
     *          SubjectSearchSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsSubjectSearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectSearch() </code> is <code> true. </code> 
     */
    public function getSubjectSearchSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets a subject search session for the given repository. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          repository 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_SubjectSearchSession a <code> 
     *          SubjectSearchSession </code> 
     *  @throws osid_NotFoundException <code> repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> repositoryId </code> or 
     *          <code> authentication </code> s <code> null </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsSubjectSearch() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectSearch() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getSubjectSearchSessionForRepository(osid_id_Id $repositoryId, 
                                                         osid_authentication_Authentication $authentication);


    /**
     *  Gets a subject administration session for creating, updating and 
     *  deleting subjects. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_SubjectAdminSession a <code> 
     *          SubjectAdminSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsSubjectAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectAdmin() </code> is <code> true. </code> 
     */
    public function getSubjectAdminSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets a subject administrative session for the given repository. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          repository 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_SubjectAdminSession a <code> 
     *          SubjectSearchSession </code> 
     *  @throws osid_NotFoundException <code> repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> repositoryId </code> or 
     *          <code> authentication </code> s <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsSubjectAdmin() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectAdmin() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getSubjectAdminSessionForRepository(osid_id_Id $repositoryId, 
                                                        osid_authentication_Authentication $authentication);


    /**
     *  Gets the notification session for notifications pertaining to subject 
     *  changes. 
     *
     *  @param object osid_repository_SubjectReceiver $receiver notification 
     *          callback 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_SubjectNotificationSession a <code> 
     *          SubjectNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> or <code> 
     *          authentication </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsSubjectNotification() </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getSubjectNotificationSession(osid_repository_SubjectReceiver $receiver, 
                                                  osid_authentication_Authentication $authentication);


    /**
     *  Gets the subject notification session for the given repository. 
     *
     *  @param object osid_repository_SubjectReceiver $receiver notification 
     *          callback 
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          repository 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_SubjectNotificationSession a <code> 
     *          SubjectNotificationSession </code> 
     *  @throws osid_NotFoundException <code> repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> receiver, repositoryId 
     *          </code> or <code> authentication </code> s <code> null </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsSubjectNotification() </code> or <code> 
     *          supportsVisibleFederation() </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectNotfication() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getSubjectNotificationSessionForRepository(osid_repository_SubjectReceiver $receiver, 
                                                               osid_id_Id $repositoryId, 
                                                               osid_authentication_Authentication $authentication);


    /**
     *  Gets the subject hierarchy traversal session. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_SubjectHierarchySession <code> a 
     *          SubjectHierarchySession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsSubjectHierarchy() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectHierarchy() </code> is <code> true. </code> 
     */
    public function getSubjectHierarchySession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the subject hierarchy design session. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_SubjectHierarchyDesignSession a <code> 
     *          SubjectHierarchyDesignSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsSubjectHierarchyDesign() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectHierarchyDesign() </code> is <code> true. 
     *              </code> 
     */
    public function getSubjectHierarchyDesignSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for retrieving subject to repository mappings. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_SubjectRepositorySession a <code> 
     *          SubjectRepositorySession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsSubjectRepository() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectRepository() </code> is <code> true. 
     *              </code> 
     */
    public function getSubjectRepositorySession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for assigning subject to repository mappings. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_SubjectRepositoryAssignmentSession a 
     *          <code> SubjectRepositoryAsignmentSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsSubjectRepositoryAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectRepositoryAssignment() </code> is <code> 
     *              true. </code> 
     */
    public function getSubjectRepositoryAssignmentSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the repository lookup session. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_RepositoryLookupSession a <code> 
     *          RepositoryLookupSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsRepositoryLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRepositoryLookup() </code> is <code> true. </code> 
     */
    public function getRepositoryLookupSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the repository search session. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_RepositorySearchSession a <code> 
     *          RepositorySearchSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsRepositorySearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRepositorySearch() </code> is <code> true. </code> 
     */
    public function getRepositorySearchSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the repository administrative session for creating, updating and 
     *  deleteing repositories. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_RepositoryAdminSession a <code> 
     *          RepositoryAdminSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsRepositoryAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRepositoryAdmin() </code> is <code> true. </code> 
     */
    public function getRepositoryAdminSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the notification session for subscribing to changes to a 
     *  repository. 
     *
     *  @param object osid_repository_RepositoryReceiver $receiver 
     *          notification callback 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_RepositoryNotificationSession a <code> 
     *          RepositoryNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> or <code> 
     *          authentication </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsRepositoryNotification() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRepositoryNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getRepositoryNotificationSession(osid_repository_RepositoryReceiver $receiver, 
                                                     osid_authentication_Authentication $authentication);


    /**
     *  Gets the repository hierarchy traversal session. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_RepositoryHierarchySession <code> a 
     *          RepositoryHierarchySession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsRepositoryHierarchy() </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRepositoryHierarchy() </code> is <code> true. 
     *              </code> 
     */
    public function getRepositoryHierarchySession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the repository hierarchy design session. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_RepositoryHierarchyDesignSession a 
     *          <code> RepostoryHierarchyDesignSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsRepositoryHierarchyDesign() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRepositoryHierarchyDesign() </code> is <code> 
     *              true. </code> 
     */
    public function getRepositoryHierarchyDesignSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the <code> OsidSession </code> associated with the composition 
     *  lookup service. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionLookupSession the new <code> 
     *          CompositionLookupSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsCompositionLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionLookup() </code> is <code> true. 
     *              </code> 
     */
    public function getCompositionLookupSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the <code> OsidSession </code> associated with the composition 
     *  lookup service for the given repository. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          repository 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionLookupSession the new <code> 
     *          CompositionLookupSession </code> 
     *  @throws osid_NotFoundException <code> repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> repositoryId </code> or 
     *          <code> authentication </code> is <code> null </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsCompositionLookup() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionLookup() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getCompositionLookupSessionForRepository(osid_id_Id $repositoryId, 
                                                             osid_authentication_Authentication $authentication);


    /**
     *  Gets a composition search session. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionSearchSession a <code> 
     *          CompositionSearchSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsCompositionSearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionSearch() </code> is <code> true. 
     *              </code> 
     */
    public function getCompositionSearchSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets a composition search session for the given repository. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          repository 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionSearchSession a <code> 
     *          CompositionSearchSession </code> 
     *  @throws osid_NotFoundException <code> repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> repositoryId </code> or 
     *          <code> authentication </code> is <code> null </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsCompositionSearch() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionSearch() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getCompositionSearchSessionForRepository(osid_id_Id $repositoryId, 
                                                             osid_authentication_Authentication $authentication);


    /**
     *  Gets a composition administration session for creating, updating and 
     *  deleting compositions. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionAdminSession a <code> 
     *          CompositionAdminSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsCompositionAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionAdmin() </code> is <code> true. </code> 
     */
    public function getCompositionAdminSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets a composiiton administrative session for the given repository. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          repository 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionAdminSession a <code> 
     *          CompositionAdminSession </code> 
     *  @throws osid_NotFoundException <code> repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> repositoryId </code> or 
     *          <code> authentication </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsCompositionAdmin() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionAdmin() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getCompositionAdminSessionForRepository(osid_id_Id $repositoryId, 
                                                            osid_authentication_Authentication $authentication);


    /**
     *  Gets the notification session for notifications pertaining to 
     *  composition changes. 
     *
     *  @param object osid_repository_CompositionReceiver $receiver 
     *          notification callback 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionNotificationSession a <code> 
     *          CompositionNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> or <code> 
     *          authentication </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCompositionNotification() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getCompositionNotificationSession(osid_repository_CompositionReceiver $receiver, 
                                                      osid_authentication_Authentication $authentication);


    /**
     *  Gets the composition notification session for the given repository. 
     *
     *  @param object osid_repository_CompositionReceiver $receiver 
     *          notification callback 
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          repository 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionNotificationSession a <code> 
     *          CompositionNotificationSession </code> 
     *  @throws osid_NotFoundException <code> repositoryId </code> not found 
     *  @throws osid_NullArgumentException <code> receiver, repositoryId 
     *          </code> or <code> authentication </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCompositionNotification() </code> or <code> 
     *          supportsVisibleFederation() </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionNotfication() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getCompositionNotificationSessionForRepository(osid_repository_CompositionReceiver $receiver, 
                                                                   osid_id_Id $repositoryId, 
                                                                   osid_authentication_Authentication $authentication);


    /**
     *  Gets the session traversing composition hierarchies. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionHierarchySession a <code> 
     *          CompositionHierarchySession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCompositionHierarchy() </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionHierarchy() </code> is <code> true. 
     *              </code> 
     */
    public function getCompositionHierarchySession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session designing composition hierarchies. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionHierarchyDesignSession a 
     *          <code> CompositionHierarchyDesignSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCompositionHierarchyDesign() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionHierarchyDesign() </code> is <code> 
     *              true. </code> 
     */
    public function getCompositionHierarchyDesignSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for retrieving composition to repository mappings. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionRepositorySession a <code> 
     *          CompositionRepositorySession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCompositionRepository() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionRepository() </code> is <code> true. 
     *              </code> 
     */
    public function getCompositionRepositorySession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for assigning composition to repository mappings. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionRepositoryAssignmentSession 
     *          a <code> CompositionRepositoryAssignmentSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCompositionRepositoryAssignment() </code> is <code> 
     *          false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionRepositoryAssignment() </code> is 
     *              <code> true. </code> 
     */
    public function getCompositionRepositoryAssignmentSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for retrieving composition to subject mappings. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionSubjectSession a <code> 
     *          CompositionSubjectSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCompositionSubject() </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionSubject() </code> is <code> true. 
     *              </code> 
     */
    public function getCompositionSubjectSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for assigning composition to subject mappings. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_repository_CompositionSubjectAssignmentSession a 
     *          <code> CompositionSubjectAsignmentSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCompositionSubjectAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionSubjectAssignment() </code> is <code> 
     *              true. </code> 
     */
    public function getCompositionSubjectAssignmentSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the resource service for accessing resources to use in 
     *  authorizations. 
     *
     *  @return object osid_resource_ResourceManager a <code> ResourceManager 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented <code> . </code> 
     */
    public function getResourceManager();

}
