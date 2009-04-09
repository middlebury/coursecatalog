<?php

/**
 * osid_repository_CompositionAdminSession
 * 
 *     Specifies the OSID definition for osid_repository_CompositionAdminSession.
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

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session creates and removes compositions. The data for create and 
 *  update is provided by the consumer via the form object. </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_CompositionAdminSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> Repository </code> <code> Id </code> associated with 
     *  this session. 
     *
     *  @return object osid_id_Id the <code> Repository Id </code> associated 
     *          with this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepositoryId();


    /**
     *  Gets the <code> Repository </code> associated with this session. 
     *
     *  @return object osid_repository_Repository the <code> Repository 
     *          </code> associated with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepository();


    /**
     *  Tests if this user can create <code> Compositions. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating a <code> Composition </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may not wish to offer create operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> Composition </code> 
     *          creation is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateCompositions();


    /**
     *  Tests if this user can create a single <code> Composition </code> 
     *  using the desired record interface types. While <code> 
     *  RepositoryManager.getCompositionRecordTypes() </code> can be used to 
     *  examine which record interfaces are supported, this method tests which 
     *  record(s) are required for creating a specific <code> Composition. 
     *  </code> Providing an empty array tests if a <code> Composition </code> 
     *  can be created with no records. 
     *
     *  @param array $compositionRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> Composition </code> 
     *          creation using the specified <code> Types </code> is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> compositionRecordTypes 
     *          </code> is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateCompositionWithRecordTypes(array $compositionRecordTypes);


    /**
     *  Gets the composition form for creating new compositions. A new form 
     *  should be requested for each create transaction. 
     *
     *  @return object osid_repository_CompositionForm the composition form 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionFormForCreate();


    /**
     *  Creates a new <code> Composition. </code> 
     *
     *  @param array $composiitonForms the forms for this <code> Composition 
     *          </code> 
     *  @return object osid_repository_Composition the new <code> Composition 
     *          </code> 
     *  @throws osid_AlreadyExistsException attempt at duplicating a property 
     *          the underlying system is enforcing to be unique 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> compositionForms </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException a <code> compositionForm </code> is 
     *          not supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createComposition(array $composiitonForms);


    /**
     *  Tests if this user can update <code> Compositions. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a <code> Composition </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may not wish to offer update operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> Composition </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateCompositions();


    /**
     *  Tests if this user can update a specified <code> Composition. </code> 
     *  A return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known updating the <code> Composition 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may not wish to offer update 
     *  operations to unauthorized users. 
     *
     *  @param object osid_id_Id $compositionId the <code> Id </code> of the 
     *          <code> Composition </code> 
     *  @return boolean <code> false </code> if repository modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> compositionId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateComposition(osid_id_Id $compositionId);


    /**
     *  Gets the composition form for updating an existing composition. A new 
     *  composition form should be requested for each update transaction. 
     *
     *  @param object osid_id_Id $compositionId the <code> Id </code> of the 
     *          <code> Composition </code> 
     *  @return object osid_repository_CompositionForm the composition form 
     *  @throws osid_NotFoundException <code> compositionId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionFormForUpdate(osid_id_Id $compositionId);


    /**
     *  Updates an existing composition. 
     *
     *  @param object osid_id_Id $compositionId the <code> Id </code> of the 
     *          <code> Composition </code> 
     *  @param object osid_repository_CompositionForm $composiitonForm the 
     *          form containing the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> compositionId </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> compositionId </code> or 
     *          <code> compositionForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> compositionForm </code> is 
     *          not supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateComposition(osid_id_Id $compositionId, 
                                      osid_repository_CompositionForm $composiitonForm);


    /**
     *  Tests if this user can delete <code> Compositions. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting a <code> Composition </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may not wish to offer delete operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> Composition </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteCompositions();


    /**
     *  Tests if this user can delete a specified <code> Composition. </code> 
     *  A return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> Composition 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may not wish to offer delete 
     *  operations to unauthorized users. 
     *
     *  @param object osid_id_Id $composiitonForm the <code> Id </code> of the 
     *          <code> Composition </code> 
     *  @return boolean <code> false </code> if deletion of this <code> 
     *          Composition </code> is not authorized, <code> true </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> compositionId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteComposition(osid_id_Id $composiitonForm);


    /**
     *  Deletes a <code> Composition. </code> 
     *
     *  @param object osid_id_Id $compositionId the <code> Id </code> of the 
     *          <code> Composition </code> to remove 
     *  @throws osid_NotFoundException <code> compositionId </code> not found 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteComposition(osid_id_Id $compositionId);


    /**
     *  Adds an <code> Id </code> to a <code> Composition </code> for the 
     *  purpose of creating compatibility. The primary <code> Id </code> of 
     *  the <code> Composiiton </code> is determined by the provider. The new 
     *  <code> Id </code> performs as an alias to the primary <code> Id. 
     *  </code> 
     *
     *  @param object osid_id_Id $compositionId the <code> Id </code> of a 
     *          <code> Composition </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> compositionId </code> not found 
     *  @throws osid_NullArgumentException <code> compositionId </code> or 
     *          <code> aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToComposition(osid_id_Id $compositionId, 
                                       osid_id_Id $aliasId);

}
