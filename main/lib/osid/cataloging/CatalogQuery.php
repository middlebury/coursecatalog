<?php

/**
 * osid_cataloging_CatalogQuery
 * 
 *     Specifies the OSID definition for osid_cataloging_CatalogQuery.
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

require_once(dirname(__FILE__)."/../OsidCatalogQuery.php");

/**
 *  <p>This is the query interface for searching catalogs. Each method 
 *  specifies an <code> AND </code> term while multiple invocations of the 
 *  same method produces a nested <code> OR. </code> </p>
 * 
 * @package org.osid.cataloging
 */
interface osid_cataloging_CatalogQuery
    extends osid_OsidCatalogQuery
{


    /**
     *  Matches an <code> Id </code> in this catalog. Multiple Ids are treated 
     *  as a boolean <code> OR. </code> 
     *
     *  @param object osid_id_Id $id <code> Id </code> to match 
     *  @param boolean $match <code> true </code> if for a positive match, 
     *          <code> false </code> for negative match 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchId(osid_id_Id $id, $match);


    /**
     *  Gets the record query interface corresponding to the given <code> 
     *  Catalog </code> record <code> Type. </code> Multiple record retrievals 
     *  produce a boolean <code> OR </code> term. 
     *
     *  @param object osid_type_Type $catalogRecordType a catalog record type 
     *  @return object osid_cataloging_CatalogQueryRecord the catalog query 
     *          record 
     *  @throws osid_NullArgumentException <code> catalogRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(catalogRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogQueryRecord(osid_type_Type $catalogRecordType);

}
