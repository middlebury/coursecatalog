<?php

/**
 * osid_repository_AssetQuery
 * 
 *     Specifies the OSID definition for osid_repository_AssetQuery.
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

require_once(dirname(__FILE__)."/../OsidQuery.php");

/**
 *  <p>This is the query interface for searching assets. Each method specifies 
 *  an <code> AND </code> term while multiple invocations of the same method 
 *  produce a nested <code> OR. </code> The query record is identified by the 
 *  <code> Asset Type. </code> </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetQuery
    extends osid_OsidQuery
{


    /**
     *  Adds a title for this query. 
     *
     *  @param string $title title string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> title </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> title </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchTitle($title, osid_type_Type $stringMatchType, $match);


    /**
     *  Matches a title that has any value. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          title, <code> false </code> to match assets with no title 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyTitle($match);


    /**
     *  Matches assets marked as public domain. 
     *
     *  @param boolean $publicDomain public domain flag 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchPublicDomain($publicDomain);


    /**
     *  Matches assets with any public domain value. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          public domain value, <code> false </code> to match assets with 
     *          no public domain value 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyPublicDomain($match);


    /**
     *  Adds a copyright for this query. 
     *
     *  @param string $copyright copyright string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> copyright </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> copyright </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCopyright($copyright, osid_type_Type $stringMatchType, 
                                   $match);


    /**
     *  Matches assets with any copyright statement. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          copyright value, <code> false </code> to match assets with no 
     *          copyright value 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyCopyright($match);


    /**
     *  Adds a copyright registration for this query. 
     *
     *  @param string $registration copyright registration string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> registration </code> not 
     *          of <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> registration </code> or 
     *          <code> stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCopyrightRegistration($registration, 
                                               osid_type_Type $stringMatchType, 
                                               $match);


    /**
     *  Matches assets with any copyright registration. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          copyright registration value, <code> false </code> to match 
     *          assets with no copyright registration value 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyCopyrightRegistration($match);


    /**
     *  Adds a license for this query. 
     *
     *  @param string $license license string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> license </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> license </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchLicense($license, osid_type_Type $stringMatchType, 
                                 $match);


    /**
     *  Matches assets with any license statement. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          license value, <code> false </code> to match assets with no 
     *          license value 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyLicense($match);


    /**
     *  Matches assets marked as distributable. 
     *
     *  @param boolean $distributable distribute verbatim rights flag 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchDistributeVerbatim($distributable);


    /**
     *  Matches assets that whose alterations can be distributed. 
     *
     *  @param boolean $alterable distribute alterations rights flag 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchDistributeAlterations($alterable);


    /**
     *  Matches assets that can be distributed as part of other compositions. 
     *
     *  @param boolean $composable distribute compositions rights flag 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchDistributeCompositions($composable);


    /**
     *  Matches assets with any distribution rights. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          distribution rights values, <code> false </code> to match 
     *          assets with distribution rights values 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyDistributionRights($match);


    /**
     *  Sets the provider <code> Id </code> for this query. 
     *
     *  @param object osid_id_Id $providerId the provider <code> Id </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> providerId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchProviderId(osid_id_Id $providerId, $match);


    /**
     *  Matches assets with any provider. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          provider, <code> false </code> to match assets with no 
     *          providers 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyProvider($match);


    /**
     *  Tests if a <code> ResourceQuery </code> is available for the provider. 
     *
     *  @return boolean <code> true </code> if a resource query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsProviderQuery();


    /**
     *  Gets the query interface for the provider. Multiple queries can be 
     *  retrieved for a nested <code> OR </code> term. 
     *
     *  @return object osid_resource_ResourceQuery the provider query 
     *  @throws osid_UnimplementedException <code> supportsProviderQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsProviderQuery() </code> is <code> true. </code> 
     */
    public function getProviderQuery();


    /**
     *  Sets the source <code> Id </code> for this query. 
     *
     *  @param object osid_id_Id $sourceId the source <code> Id </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> sourceId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchSourceId(osid_id_Id $sourceId, $match);


    /**
     *  Matches assets with any source. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          source, <code> false </code> to match assets with no sources 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnySource($match);


    /**
     *  Tests if a <code> ResourceQuery </code> is available for the source. 
     *
     *  @return boolean <code> true </code> if a resource query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSourceQuery();


    /**
     *  Gets the query interface for the source. Multiple queries can be 
     *  retrieved for a nested <code> OR </code> term. 
     *
     *  @return object osid_resource_ResourceQuery the source query 
     *  @throws osid_UnimplementedException <code> supportsSourceQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSourceQuery() </code> is <code> true. </code> 
     */
    public function getSourceQuery();


    /**
     *  Match assets that are created between the specified time period. 
     *
     *  @param object osid_calendaring_DateTime $start start time of the query 
     *  @param object osid_calendaring_DateTime $end end time of the query 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> end </code> is les than 
     *          <code> start </code> 
     *  @throws osid_NullArgumentException <code> start </code> or <code> end 
     *          </code> is <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCreatedTime(osid_calendaring_DateTime $start, 
                                     osid_calendaring_DateTime $end, $match);


    /**
     *  Matches assets with any creation time. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          created time, <code> false </code> to match assets with no 
     *          cerated time 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyCreatedTime($match);


    /**
     *  Marks assets that are marked as published. 
     *
     *  @param boolean $published published flag 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchPublished($published);


    /**
     *  Match assets that are published between the specified time period. 
     *
     *  @param object osid_calendaring_DateTime $start start time of the query 
     *  @param object osid_calendaring_DateTime $end end time of the query 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> end </code> is les than 
     *          <code> start </code> 
     *  @throws osid_NullArgumentException <code> start </code> or <code> end 
     *          </code> is <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchPublishedTime(osid_calendaring_DateTime $start, 
                                       osid_calendaring_DateTime $end, $match);


    /**
     *  Matches assets with no published time. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          published time, <code> false </code> to match assets with no 
     *          published time 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyPublishedTime($match);


    /**
     *  Tests if an <code> AssetQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if an asset query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetCreditQuery();


    /**
     *  Gets the query interface for an asset credit. Multiple queries can be 
     *  retrieved for a nested <code> OR </code> term. 
     *
     *  @return object osid_repository_AssetCreditQuery the asset credit query 
     *  @throws osid_UnimplementedException <code> supportsAssetCreditQuery() 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetCreditQuery();


    /**
     *  Matches assets with any credits. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          credits, <code> false </code> to match assets with no credits 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyAssetCredits($match);


    /**
     *  Match assets that whose coverage falls between the specified time 
     *  period. 
     *
     *  @param object osid_calendaring_DateTime $start start time of the query 
     *  @param object osid_calendaring_DateTime $end end time of the query 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> end </code> is les than 
     *          <code> start </code> 
     *  @throws osid_NullArgumentException <code> start </code> or <code> end 
     *          </code> is <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchTemporalCoverage(osid_calendaring_DateTime $start, 
                                          osid_calendaring_DateTime $end, 
                                          $match);


    /**
     *  Matches assets with any temporal coverage. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          temporal coverage, <code> false </code> to match assets with 
     *          no temporal coverage 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyTemporalCoverage($match);


    /**
     *  Tests if a <code> SpatialUnitQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a spatial unit query interface 
     *          is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSpatialQuery();


    /**
     *  Gets the query interface for a spatial unit. Multiple queries can be 
     *  retrieved for a nested <code> OR </code> term. 
     *
     *  @return object osid_spatial_SpatialUnitQuery the spatial query 
     *  @throws osid_UnimplementedException <code> supportsSpatialQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSpatialQuery() </code> is <code> true. </code> 
     */
    public function getSpatialQuery();


    /**
     *  Matches assets with no spatial coverage. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          spatial coverage, <code> false </code> to match assets with no 
     *          spatial coverage 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnySpatialCoverage($match);


    /**
     *  Tests if an <code> AssetContentQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if an asset contents query 
     *          interface is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetContentQuery();


    /**
     *  Gets the query interface for the asset content. Multiple queries can 
     *  be retrieved for a nested <code> OR </code> term. 
     *
     *  @return object osid_repository_AssetContentQuery the asset contents 
     *          query 
     *  @throws osid_UnimplementedException <code> supportsAssetContentQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetContentQuery() </code> is <code> true. 
     *              </code> 
     */
    public function getAssetContentQuery();


    /**
     *  Matches assets with any content. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          content, <code> false </code> to match assets with no content 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyAssetContent($match);


    /**
     *  Sets the repository <code> Id </code> for this query. 
     *
     *  @param object osid_id_Id $repositoryId the repository <code> Id 
     *          </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> repositoryId </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchRepositoryId(osid_id_Id $repositoryId, $match);


    /**
     *  Tests if a <code> RepositoryQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a repository query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRepositoryQuery();


    /**
     *  Gets the query interface for a repository. Multiple queries can be 
     *  retrieved for a nested <code> OR </code> term. 
     *
     *  @return object osid_repository_RepositoryQuery the repository query 
     *  @throws osid_UnimplementedException <code> supportsRepositoryQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRepositoryQuery() </code> is <code> true. </code> 
     */
    public function getRepositoryQuery();


    /**
     *  Sets the subject <code> Id </code> for this query. 
     *
     *  @param object osid_id_Id $subjectId the subject <code> Id </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchSubjectId(osid_id_Id $subjectId, $match);


    /**
     *  Tests if a <code> SubjectQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a subject query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSubjectQuery();


    /**
     *  Gets the query interface for a subject. Multiple queries can be 
     *  retrieved for a nested <code> OR </code> term. 
     *
     *  @return object osid_repository_SubjectQuery the subject query 
     *  @throws osid_UnimplementedException <code> supportsSubjectQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectQuery() </code> is <code> true. </code> 
     */
    public function getSubjectQuery();


    /**
     *  Matches assets with any subject mappings. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          subject, <code> false </code> to match assets with no subjects 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnySubject($match);


    /**
     *  Sets the composition <code> Id </code> for this query. 
     *
     *  @param object osid_id_Id $compositionId the composition <code> Id 
     *          </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCompositionId(osid_id_Id $compositionId, $match);


    /**
     *  Tests if a <code> CompositionQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a composition query interface 
     *          is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCompositionQuery();


    /**
     *  Gets the query interface for a composition. Multiple queries can be 
     *  retrieved for a nested <code> OR </code> term. 
     *
     *  @return object osid_repository_CompositionQuery the composition query 
     *  @throws osid_UnimplementedException <code> supportsCompositionQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCompositionQuery() </code> is <code> true. </code> 
     */
    public function getCompositionQuery();


    /**
     *  Matches assets with any composition mappings. 
     *
     *  @param boolean $match <code> true </code> to match assets with any 
     *          composition, <code> false </code> to match assets with no 
     *          composition mappings 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyComposition($match);


    /**
     *  Gets the record query interface corresponding to the given <code> 
     *  Asset </code> record <code> Type. </code> Multiuple retrievals produce 
     *  a nested <code> OR </code> term. 
     *
     *  @param object osid_type_Type $assetRecordType an asset record type 
     *  @return object osid_repository_AssetQueryRecord the asset query record 
     *  @throws osid_NullArgumentException <code> assetRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(assetRecordType) </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAssetQueryRecord(osid_type_Type $assetRecordType);

}
