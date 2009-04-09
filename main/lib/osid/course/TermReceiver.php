<?php

/**
 * osid_course_TermReceiver
 * 
 *     Specifies the OSID definition for osid_course_TermReceiver.
 * 
 * Copyright (C) 2009 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.course
 */

require_once(dirname(__FILE__)."/../OsidReceiver.php");

/**
 *  <p>The term receiver is the consumer supplied interface for receiving 
 *  notifications pertaining to new, updated or deleted <code> Term </code> 
 *  objects. </p>
 * 
 * @package org.osid.course
 */
interface osid_course_TermReceiver
    extends osid_OsidReceiver
{


    /**
     *  The callback for notifications of new terms. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the new 
     *          <code> Term </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newTerm(osid_id_Id $termId);


    /**
     *  The callback for notifications of new term ancestors. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the <code> 
     *          Term </code> 
     *  @param object osid_id_Id $ancestorId the <code> Id </code> of the new 
     *          <code> Term </code> ancestor 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newAncestorTerm(osid_id_Id $termId, osid_id_Id $ancestorId);


    /**
     *  The callback for notifications of new term descendants. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the <code> 
     *          Term </code> 
     *  @param object osid_id_Id $descendantId the <code> Id </code> of the 
     *          new <code> Term </code> descendant 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newDescendantTerm(osid_id_Id $termId, 
                                      osid_id_Id $descendantId);


    /**
     *  The callback for notification of updated terms. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the updated 
     *          <code> Term </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function changedTerm(osid_id_Id $termId);


    /**
     *  The callback for notification of deleted terms. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the deleted 
     *          <code> Term </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedTerm(osid_id_Id $termId);


    /**
     *  The callback for notifications of deleted term ancestors. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the <code> 
     *          Term </code> 
     *  @param object osid_id_Id $ancestorId the <code> Id </code> of the 
     *          removed <code> Term </code> ancestor 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedAncestorTerm(osid_id_Id $termId, 
                                        osid_id_Id $ancestorId);


    /**
     *  The callback for notifications of deleted term descendants. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> of the <code> 
     *          Term </code> 
     *  @param object osid_id_Id $descendantId the <code> Id </code> of the 
     *          removed <code> Term </code> descendant 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedDescendantTerm(osid_id_Id $termId, 
                                          osid_id_Id $descendantId);

}
