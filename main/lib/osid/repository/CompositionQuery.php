<?php

/**
 * osid_repository_CompositionQuery
 * 
 *     Specifies the OSID definition for osid_repository_CompositionQuery.
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
 *  <p>This is the query interface for searching compositions. Each method 
 *  specifies an <code> AND </code> term while multiple invocations of the 
 *  same method produces a nested <code> OR. </code> </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_CompositionQuery
    extends osid_OsidQuery
{


    /**
     *  Sets the asset <code> Id </code> for this query. 
     *
     *  @param object osid_id_Id $assetId the asset <code> Id </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> assetId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAssetId(osid_id_Id $assetId, $match);


    /**
     *  Tests if an <code> AssetQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if an asset query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAssetQuery();


    /**
     *  Gets the query interface for an asset. Multiple retrievals produce a 
     *  nested <code> OR </code> term. 
     *
     *  @return object osid_repository_AssetQuery the asset query 
     *  @throws osid_UnimplementedException <code> supportsAssetQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAssetQuery() </code> is <code> true. </code> 
     */
    public function getAssetQuery();


    /**
     *  Matches compositions that has any asset mapping. 
     *
     *  @param boolean $match <code> true </code> to match compositions with 
     *          any asset, <code> false </code> to match compositions with no 
     *          asset 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyAsset($match);


    /**
     *  Sets the repository <code> Id </code> for this query. 
     *
     *  @param object osid_id_Id $repositoryId the repository <code> Id 
     *          </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for negative match 
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
     *  Gets the query interface for a repository. Multiple retrievals produce 
     *  a nested <code> OR </code> term. 
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
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> subjectId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchSubjecttId(osid_id_Id $subjectId, $match);


    /**
     *  Tests if an <code> SubjectQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a subject query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsSubjectQuery();


    /**
     *  Gets the query interface for a subject. Multiple retrievals produce a 
     *  nested <code> OR </code> term. 
     *
     *  @return object osid_repository_SubjectQuery the subject query 
     *  @throws osid_UnimplementedException <code> supportsSubjectQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsSubjectQuery() </code> is <code> true. </code> 
     */
    public function getSubjectQuery();


    /**
     *  Matches compositions that has any subject mapping. 
     *
     *  @param boolean $match <code> true </code> to match compositions with 
     *          any subject, <code> false </code> to match compositions with 
     *          no subject 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnySubject($match);


    /**
     *  Gets the record query interface corresponding to the given <code> 
     *  Composition </code> record <code> Type. </code> Multiple retrievals 
     *  produce a nested <code> OR </code> term. 
     *
     *  @param object osid_type_Type $compositionRecordType a composition 
     *          record type 
     *  @return object osid_repository_CompositionQueryRecord the composition 
     *          query record 
     *  @throws osid_NullArgumentException <code> compositionRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(compositionRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionQueryRecord(osid_type_Type $compositionRecordType);

}
