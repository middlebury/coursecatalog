<?php

/**
 * osid_resource_BinReceiver
 * 
 *     Specifies the OSID definition for osid_resource_BinReceiver.
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

require_once(dirname(__FILE__)."/../OsidReceiver.php");

/**
 *  <p>The bin receiver is the consumer supplied interface for receiving 
 *  notifications pertaining to new, updated or deleted <code> Bin </code> 
 *  objects. </p>
 * 
 * @package org.osid.resource
 */
interface osid_resource_BinReceiver
    extends osid_OsidReceiver
{


    /**
     *  The callback for notifications of new bins. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the new 
     *          <code> Bin </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newBin(osid_id_Id $binId);


    /**
     *  The callback for notifications of new bin ancestors. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the <code> 
     *          Bin </code> 
     *  @param object osid_id_Id $ancestorId the <code> Id </code> of the new 
     *          <code> Bin </code> ancestor 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newAncestorBin(osid_id_Id $binId, osid_id_Id $ancestorId);


    /**
     *  The callback for notifications of new bin descendants. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the <code> 
     *          Bin </code> 
     *  @param object osid_id_Id $descendantId the <code> Id </code> of the 
     *          new <code> Bin </code> descendant 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newDescendantBin(osid_id_Id $binId, 
                                     osid_id_Id $descendantId);


    /**
     *  The callback for notification of updated bins. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the updated 
     *          <code> Bin </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function changedBin(osid_id_Id $binId);


    /**
     *  The callback for notification of deleted bins. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the deleted 
     *          <code> Bin </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedBin(osid_id_Id $binId);


    /**
     *  The callback for notifications of deleted bin ancestors. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the <code> 
     *          Bin </code> 
     *  @param object osid_id_Id $ancestorId the <code> Id </code> of the 
     *          removed <code> Bin </code> ancestor 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedAncestorBin(osid_id_Id $binId, 
                                       osid_id_Id $ancestorId);


    /**
     *  The callback for notifications of deleted bin descendants. 
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the <code> 
     *          Bin </code> 
     *  @param object osid_id_Id $descendantId the <code> Id </code> of the 
     *          removed <code> Bin </code> descendant 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedDescendantBin(osid_id_Id $binId, 
                                         osid_id_Id $descendantId);

}
