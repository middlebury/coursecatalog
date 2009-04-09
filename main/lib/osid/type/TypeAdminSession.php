<?php

/**
 * osid_type_TypeAdminSession
 * 
 *     Specifies the OSID definition for osid_type_TypeAdminSession.
 * 
 * Copyright (C) 2002-2007 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.type
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session is used to create, update and delete <code> Types </code> 
 *  in the registry. </p>
 * 
 * @package org.osid.type
 */
interface osid_type_TypeAdminSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can create <code> Types. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating a <code> Type </code> will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer create operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Type </code> creation 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateTypes();


    /**
     *  Gets the type form for creating new types. A new form should be 
     *  requested for each create transaction. 
     *
     *  @return object osid_type_TypeForm the type form 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTypeForm();


    /**
     *  Creates a new <code> Type. </code> 
     *
     *  @param string $authority the authority 
     *  @param string $identifierNS the namespace of the identifier 
     *  @param string $identifier the identifier 
     *  @param object osid_type_TypeForm $typeForm the type form 
     *  @return object osid_type_Type the created <code> Type </code> 
     *  @throws osid_InvalidArgumentException one or more of the arguments is 
     *          invalid 
     *  @throws osid_NullArgumentException one or more of the arguments is 
     *          null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createType($authority, $identifierNS, $identifier, 
                               osid_type_TypeForm $typeForm);


    /**
     *  Tests if this user can update types. A return of true does not 
     *  guarantee successful authorization. A return of false indicates that 
     *  it is known updating a <code> Type </code> will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer update operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if type modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateTypes();


    /**
     *  Updates a display name. 
     *
     *  @param object osid_type_Type $type the <code> Type </code> to be 
     *          updated 
     *  @param object osid_type_TypeForm $typeForm the type form 
     *  @throws osid_InvalidArgumentException <code> displayName </code> or 
     *          <code> displayLabel </code> is not valid 
     *  @throws osid_NotFoundException <code> type </code> is not found 
     *  @throws osid_NullArgumentException <code> type, displayName </code> or 
     *          <code> displayLabel </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateType(osid_type_Type $type, 
                               osid_type_TypeForm $typeForm);


    /**
     *  Tests if this user can delete <code> Types </code> from this <code> 
     *  ItemBank. </code> A return of true does not guarantee successful 
     *  authorization. A return of false indicates that it is known deleting a 
     *  <code> Type </code> will result in a <code> PERMISSION_DENIED. </code> 
     *  This is intended as a hint to an application that may opt not to offer 
     *  delete operations to an unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Item </code> deletion 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteTypes();


    /**
     *  Removes a <code> Type. </code> 
     *
     *  @param object osid_type_Type $type the <code> Type </code> to remove 
     *  @throws osid_NotFoundException <code> type </code> is not found 
     *  @throws osid_NullArgumentException <code> type </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteType(osid_type_Type $type);

}
