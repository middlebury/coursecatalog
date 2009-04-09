<?php

/**
 * osid_repository_RepositoryReceiver
 * 
 *     Specifies the OSID definition for osid_repository_RepositoryReceiver.
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

require_once(dirname(__FILE__)."/../OsidReceiver.php");

/**
 *  <p>The repository receiver is the consumer supplied interface for 
 *  receiving notifications pertaining to new, updated or deleted <code> 
 *  Repository </code> objects. </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_RepositoryReceiver
    extends osid_OsidReceiver
{


    /**
     *  The callback for notifications of new repositories. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          new <code> Repository </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newRepository(osid_id_Id $repositoryId);


    /**
     *  The callback for notifications of new repository ancestors. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          <code> Repository </code> 
     *  @param object osid_id_Id $ancestorId the <code> Id </code> of the new 
     *          <code> Repository </code> ancestor 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newAncestorRepository(osid_id_Id $repositoryId, 
                                          osid_id_Id $ancestorId);


    /**
     *  The callback for notifications of new repository descendants. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          <code> Repository </code> 
     *  @param object osid_id_Id $descendantId the <code> Id </code> of the 
     *          new <code> Repository </code> descendant 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newDescendantRepository(osid_id_Id $repositoryId, 
                                            osid_id_Id $descendantId);


    /**
     *  The callback for notification of updated repositories. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          updated <code> Repository </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function changedRepository(osid_id_Id $repositoryId);


    /**
     *  The callback for notification of deleted repositories. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          deleted <code> Repository </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedRepository(osid_id_Id $repositoryId);


    /**
     *  The callback for notifications of deleted repository ancestors. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          <code> Repository </code> 
     *  @param object osid_id_Id $ancestorId the <code> Id </code> of the 
     *          removed <code> Repository </code> ancestor 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedAncestorRepository(osid_id_Id $repositoryId, 
                                              osid_id_Id $ancestorId);


    /**
     *  The callback for notifications of deleted repository descendants. 
     *
     *  @param object osid_id_Id $repositoryId the <code> Id </code> of the 
     *          <code> Repository </code> 
     *  @param object osid_id_Id $descendantId the <code> Id </code> of the 
     *          removed <code> Repository </code> descendant 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedDescendantRepository(osid_id_Id $repositoryId, 
                                                osid_id_Id $descendantId);

}
