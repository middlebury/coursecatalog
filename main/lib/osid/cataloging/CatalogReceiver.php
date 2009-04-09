<?php

/**
 * osid_cataloging_CatalogReceiver
 * 
 *     Specifies the OSID definition for osid_cataloging_CatalogReceiver.
 * 
 * Copyright (C) 2008 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.cataloging
 */

require_once(dirname(__FILE__)."/../OsidReceiver.php");

/**
 *  <p>The catalog receiver is the consumer supplied interface for receiving 
 *  notifications pertaining to new, updated or deleted <code> Catalog </code> 
 *  objects. </p>
 * 
 * @package org.osid.cataloging
 */
interface osid_cataloging_CatalogReceiver
    extends osid_OsidReceiver
{


    /**
     *  The callback for notifications of new catalogs. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the new 
     *          <code> Catalog </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newCatalog(osid_id_Id $catalogId);


    /**
     *  The callback for notifications of new ancestors of a catalog. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          registered <code> Catalog </code> 
     *  @param object osid_id_Id $ancestorId the <code> Id </code> of the new 
     *          ancestor catalog 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newAncestorCatalog(osid_id_Id $catalogId, 
                                       osid_id_Id $ancestorId);


    /**
     *  The callback for notifications of new descendant of a catalog. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          registered <code> Catalog </code> 
     *  @param object osid_id_Id $descendantId the <code> Id </code> of the 
     *          new descendant catalog 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newDescendantCatalog(osid_id_Id $catalogId, 
                                         osid_id_Id $descendantId);


    /**
     *  The callback for notification of updated catalogs. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          updated <code> Catalog </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function changedCatalog(osid_id_Id $catalogId);


    /**
     *  the callback for notification of deleted catalogs. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          deleted <code> Catalog </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedCatalog(osid_id_Id $catalogId);


    /**
     *  The callback for notifications of deleted ancestors of a catalog. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          registered <code> Catalog </code> 
     *  @param object osid_id_Id $ancestorId the <code> Id </code> of the 
     *          removed ancestor catalog 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedAncestorCatalog(osid_id_Id $catalogId, 
                                           osid_id_Id $ancestorId);


    /**
     *  The callback for notifications of deleted descendants of a catalog. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          registered <code> Catalog </code> 
     *  @param object osid_id_Id $descendantId the <code> Id </code> of the 
     *          deleted descendant catalog 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedDescendantCatalog(osid_id_Id $catalogId, 
                                             osid_id_Id $descendantId);

}
