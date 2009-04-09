<?php

/**
 * osid_repository_RepositoryProfile
 * 
 *     Specifies the OSID definition for osid_repository_RepositoryProfile.
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

require_once(dirname(__FILE__)."/../OsidProfile.php");

/**
 *  <p>The repository profile describes interoperability among repository 
 *  services. </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_RepositoryProfile
    extends osid_OsidProfile
{


    /**
     *  Tests if federation is visible. 
     *
     *  @return boolean <code> true </code> if visible federation is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsVisibleFederation();


    /**
     *  Tests if asset lookup is supported. 
     *
     *  @return boolean <code> true </code> if asset lookup is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetLookup();


    /**
     *  Tests if asset search is supported. 
     *
     *  @return boolean <code> true </code> if asset search is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetSearch();


    /**
     *  Tests if asset administration is supported. 
     *
     *  @return boolean <code> true </code> if asset administration is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetAdmin();


    /**
     *  Tests if asset notification is supported. A repository may send 
     *  messages when assets are created, modified, or deleted. 
     *
     *  @return boolean <code> true </code> if asset notification is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetNotification();


    /**
     *  Tests if rerieving mappings of assets and repositories is supported. 
     *
     *  @return boolean <code> true </code> if asset repository mapping 
     *          retrieval is supported <code> , </code> <code> false </code> 
     *          otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetRepository();


    /**
     *  Tests if managing mappings of assets and repositories is supported. 
     *
     *  @return boolean <code> true </code> if asset repository assignment is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetRepositoryAssignment();


    /**
     *  Tests if retrieving asset subject mappings is supported. 
     *
     *  @return boolean <code> true </code> if asset subject mappings is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetSubjects();


    /**
     *  Tests if mapping assets to subjects is supported. 
     *
     *  @return boolean <code> true </code> if mapping assets to subjects is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetSubjectAssignment();


    /**
     *  Tests if retrieving asset alternatives for accessibility is supported. 
     *
     *  @return boolean <code> true </code> if asset alternatives is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetAlternative();


    /**
     *  Tests if assigning asset alternatives for accessibility is supported. 
     *
     *  @return boolean <code> true </code> if assigning asset alternatives is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetAlternativeAssignment();


    /**
     *  Tests if assets support contain credits. 
     *
     *  @return boolean <code> true </code> if asset credits are supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetCredit();


    /**
     *  Tests if assigning asset credits is supported. 
     *
     *  @return boolean <code> true </code> if assigning asset credits is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetCreditAssignment();


    /**
     *  Tests if assets support temporal coverage. 
     *
     *  @return boolean <code> true </code> if temporal coverage supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTemporalAssets();


    /**
     *  Tests if assigning temporal coverage to an asset is supported. 
     *
     *  @return boolean <code> true </code> if assigning temporal coverage to 
     *          an asset is supported <code> , </code> <code> false </code> 
     *          otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetTemporalAssignment();


    /**
     *  Tests if assets support spatial coverage. 
     *
     *  @return boolean <code> true </code> if spatial coverage supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSpatialAssets();


    /**
     *  Tests if assigning spatial coverage to an asset is supported. 
     *
     *  @return boolean <code> true </code> if assigning spatial coverage to 
     *          an asset is supported <code> , </code> <code> false </code> 
     *          otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetSpatialAssignment();


    /**
     *  Tests if assets are included in compositions. 
     *
     *  @return boolean <code> true </code> if asset composition supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetComposition();


    /**
     *  Tests if creating and managing asset compositions is supported. 
     *
     *  @return boolean <code> true </code> if designing asset compositions is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetCompositionAssignment();


    /**
     *  Tests if subject lookup is supported. 
     *
     *  @return boolean <code> true </code> if subject lookup is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSubjectLookup();


    /**
     *  Tests if subject search is supported. 
     *
     *  @return boolean <code> true </code> if subject search is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSubjectSearch();


    /**
     *  Tests if subject administration is supported. 
     *
     *  @return boolean <code> true </code> if subject administration is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSubjectAdmin();


    /**
     *  Tests if subject notification is supported. 
     *
     *  @return boolean <code> true </code> if subject notification is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSubjectNotification();


    /**
     *  Tests if a subject hierarchy traversal is supported. 
     *
     *  @return boolean <code> true </code> if a subject hierarchy traversal 
     *          is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSubjectHierarchy();


    /**
     *  Tests if a subject hierarchy design is supported. 
     *
     *  @return boolean <code> true </code> if a subject hierarchy design is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSubjectHierarchyDesign();


    /**
     *  Tests if the subject hierarchy supports node sequencing. 
     *
     *  @return boolean <code> true </code> if subject hierarchy node 
     *          sequencing is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSubjectHierarchySequencing();


    /**
     *  Tests if retrieval of subject to repository mappings is supported. 
     *
     *  @return boolean <code> true </code> if subject to repository mapping 
     *          is supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSubjectRepository();


    /**
     *  Tests if assigning subjects to repository mappings is supported. 
     *
     *  @return boolean <code> true </code> if subject to repository 
     *          assignment is supported <code> , </code> <code> false </code> 
     *          otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSubjectRepositoryAssignment();


    /**
     *  Tests if repository lookup is supported. 
     *
     *  @return boolean <code> true </code> if repository lookup is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRepositoryLookup();


    /**
     *  Tests if repository search is supported. 
     *
     *  @return boolean <code> true </code> if repository search is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRepositorySearch();


    /**
     *  Tests if repository administration is supported. 
     *
     *  @return boolean <code> true </code> if repository administration is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRepositoryAdmin();


    /**
     *  Tests if repository notification is supported. Messages may be sent 
     *  when <code> Repository </code> objects are created, deleted or 
     *  updated. Notifications for assets within repositories are sent via the 
     *  asset notification session. 
     *
     *  @return boolean <code> true </code> if repository notification is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRepositoryNotification();


    /**
     *  Tests if a repository hierarchy traversal is supported. 
     *
     *  @return boolean <code> true </code> if a repository hierarchy 
     *          traversal is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRepositoryHierarchy();


    /**
     *  Tests if a repository hierarchy design is supported. 
     *
     *  @return boolean <code> true </code> if a repository hierarchy design 
     *          is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRepositoryHierarchyDesign();


    /**
     *  Tests if the repository hierarchy supports node sequencing. 
     *
     *  @return boolean <code> true </code> if repository hierarchy node 
     *          sequencing is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRepositoryHierarchySequencing();


    /**
     *  Tests if composition lookup is supported. 
     *
     *  @return boolean <code> true </code> if composition lookup is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionLookup();


    /**
     *  Tests if composition search is supported. 
     *
     *  @return boolean <code> true </code> if composition search is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionSearch();


    /**
     *  Tests if composition administration is supported. 
     *
     *  @return boolean <code> true </code> if composition administration is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionAdmin();


    /**
     *  Tests if composition notification is supported. 
     *
     *  @return boolean <code> true </code> if composition notification is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionNotification();


    /**
     *  Tests if a composition hierarchy is supported. 
     *
     *  @return boolean <code> true </code> if composition hierarchy is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionHierarchy();


    /**
     *  Tests if composition hierarchy design is supported. 
     *
     *  @return boolean <code> true </code> if composition hierarchy design is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionHierarchyDesign();


    /**
     *  Tests if the composition hierarchy supports node sequencing. 
     *
     *  @return boolean <code> true </code> if composition hierarchy node 
     *          sequencing is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionHierarchySequencing();


    /**
     *  Tests if retrieval of composition to repository mappings is supported. 
     *
     *  @return boolean <code> true </code> if composition to repository 
     *          mapping is supported <code> , </code> <code> false </code> 
     *          otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionRepository();


    /**
     *  Tests if assigning composition to repository mappings is supported. 
     *
     *  @return boolean <code> true </code> if composition to repository 
     *          assignment is supported <code> , </code> <code> false </code> 
     *          otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionRepositoryAssignment();


    /**
     *  Tests if retrieving composition subject mappings is supported. 
     *
     *  @return boolean <code> true </code> if composition subject mappings is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionSubjects();


    /**
     *  Tests if mapping assets to subjects is supported. 
     *
     *  @return boolean <code> true </code> if composition assets to subjects 
     *          is supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionSubjectAssignment();


    /**
     *  Gets all the asset interface types supported. 
     *
     *  @return object osid_type_TypeList the list of supported asset record 
     *          types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetRecordTypes();


    /**
     *  Tests if a given asset interface type is supported. 
     *
     *  @param object osid_type_Type $assetRecordType the asset record type 
     *  @return boolean <code> true </code> if the asset record type is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetRecordType(osid_type_Type $assetRecordType);


    /**
     *  Gets all the asset search record types supported. 
     *
     *  @return object osid_type_TypeList the list of supported asset search 
     *          record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetSearchRecordTypes();


    /**
     *  Tests if a given asset search record type is supported. 
     *
     *  @param object osid_type_Type $assetSearchRecordType the asset search 
     *          record type 
     *  @return boolean <code> true </code> if the asset search record type is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetSearchRecordType(osid_type_Type $assetSearchRecordType);


    /**
     *  Gets all the asset content record types supported. 
     *
     *  @return object osid_type_TypeList the list of supported asset content 
     *          record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetContentRecordTypes();


    /**
     *  Tests if a given asset content record type is supported. 
     *
     *  @param object osid_type_Type $assetContentRecordType the asset content 
     *          record type 
     *  @return boolean <code> true </code> if the asset content record type 
     *          is supported <code> , </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetContentRecordType(osid_type_Type $assetContentRecordType);


    /**
     *  Gets all the repository record types supported. 
     *
     *  @return object osid_type_TypeList the list of supported repository 
     *          record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepositoryRecordTypes();


    /**
     *  Tests if a given repository record type is supported. 
     *
     *  @param object osid_type_Type $repositoryRecordType the repository 
     *          record type 
     *  @return boolean <code> true </code> if the repository record type is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRepositoryRecordType(osid_type_Type $repositoryRecordType);


    /**
     *  Gets all the repository search record types supported. 
     *
     *  @return object osid_type_TypeList the list of supported repository 
     *          search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepositorySearchRecordTypes();


    /**
     *  Tests if a given repository search record type is supported. 
     *
     *  @param object osid_type_Type $repositorySearchRecordType the 
     *          repository search type 
     *  @return boolean <code> true </code> if the repository search record 
     *          type is supported <code> , </code> <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRepositorySearchRecordType(osid_type_Type $repositorySearchRecordType);


    /**
     *  Gets all the subject record types supported. 
     *
     *  @return object osid_type_TypeList the list of supported subject record 
     *          types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectRecordTypes();


    /**
     *  Tests if a given subject record type is supported. 
     *
     *  @param object osid_type_Type $subjectRecordType the subject record 
     *          type 
     *  @return boolean <code> true </code> if the subject record type is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSubjectRecordType(osid_type_Type $subjectRecordType);


    /**
     *  Gets all the subject search record types supported. 
     *
     *  @return object osid_type_TypeList the list of supported subject search 
     *          record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubjectSearchRecordTypes();


    /**
     *  Tests if a given subject search record type is supported. 
     *
     *  @param object osid_type_Type $subjectSearchRecordType the subject 
     *          serach type 
     *  @return boolean <code> true </code> if the subject search record type 
     *          is supported <code> , </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSubjectSearchRecordType(osid_type_Type $subjectSearchRecordType);


    /**
     *  Gets all the composition record types supported. 
     *
     *  @return object osid_type_TypeList the list of supported composition 
     *          record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionRecordTypes();


    /**
     *  Tests if a given composition record type is supported. 
     *
     *  @param object osid_type_Type $compositionRecordType the composition 
     *          record type 
     *  @return boolean <code> true </code> if the composition record type is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionRecordType(osid_type_Type $compositionRecordType);


    /**
     *  Gets all the composition search record types supported. 
     *
     *  @return object osid_type_TypeList the list of supported composition 
     *          search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionSearchRecordTypes();


    /**
     *  Tests if a given composition search record type is supported. 
     *
     *  @param object osid_type_Type $compositionSearchRecordType the 
     *          composition serach type 
     *  @return boolean <code> true </code> if the composition search record 
     *          type is supported <code> , </code> <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionSearchRecordType(osid_type_Type $compositionSearchRecordType);

}
