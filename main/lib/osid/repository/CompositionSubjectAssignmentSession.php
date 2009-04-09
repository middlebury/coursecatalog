<?php

/**
 * osid_repository_CompositionSubjectAssignmentSession
 * 
 *     Specifies the OSID definition for osid_repository_CompositionSubjectAssignmentSession.
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
 *  <p>This session defines methods for managing composition to subject 
 *  mappings. </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_CompositionSubjectAssignmentSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can change composition subject mappings. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may not wish to offer management operations. 
     *
     *  @return boolean <code> false </code> if composition subject management 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canManageCompositionSubjects();


    /**
     *  Adds a <code> Composition </code> to a <code> Subject. </code> 
     *
     *  @param object osid_id_Id $compositionId <code> Id </code> of the 
     *          <code> Composition </code> 
     *  @param object osid_type_Type $subjectId <code> Id </code> of the 
     *          <code> Subject </code> 
     *  @throws osid_AlreadyExistsException composition is already mapped to 
     *          subject 
     *  @throws osid_NotFoundException <code> compositionId </code> or <code> 
     *          subjectId </code> is not found 
     *  @throws osid_NullArgumentException <code> compositionId </code> or 
     *          <code> subjectId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function assignCompositionToSubject(osid_id_Id $compositionId, 
                                               osid_type_Type $subjectId);


    /**
     *  Removes a <code> Composition </code> from a <code> Subject. </code> 
     *
     *  @param object osid_id_Id $compositionId <code> Id </code> of the 
     *          <code> Composition </code> 
     *  @param object osid_type_Type $subjectId <code> Id </code> of the 
     *          <code> Subject </code> 
     *  @throws osid_NotFoundException <code> compositionId </code> or <code> 
     *          subjectId </code> is not found, or not mapped 
     *  @throws osid_NullArgumentException <code> compositionId </code> or 
     *          <code> subjectId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function unassignCompositionFromSubject(osid_id_Id $compositionId, 
                                                   osid_type_Type $subjectId);

}
