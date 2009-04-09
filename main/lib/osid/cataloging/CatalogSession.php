<?php

/**
 * osid_cataloging_CatalogSession
 * 
 *     Specifies the OSID definition for osid_cataloging_CatalogSession.
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

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session provides methods to retrieve OSID <code> Id </code> to 
 *  <code> Catalog </code> mappings. An <code> Id </code> may appear in 
 *  multiple <code> Catalogs. </code> Each <code> Catalog </code> may have its 
 *  own authorizations as who who is allowed to look at it. </p> 
 *  
 *  <p> This lookup session defines several views: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> federated view: entries are accessible from the specified <code> 
 *      Catalog </code> and any descedant dictionaries in the <code> Catalog 
 *      </code> hierarchy </li> 
 *      <li> isolated view: entries are accessible from the specified Catalog 
 *      only </li> 
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete result set or is an error 
 *      condition </li> 
 *  </ul>
 *  Generally, the comparative view should be used for most applications as it 
 *  permits operation even if there a particular element is inaccessible. For 
 *  example, a hierarchy output can be plugged into a lookup method to 
 *  retrieve all objects known to a hierarchy, but it may not be necessary to 
 *  break execution if a node from the hierarchy no longer exists. However, 
 *  some administrative applications may need to know whether it had retrieved 
 *  an entire set of objects and may sacrifice some interoperability for the 
 *  sake of precision. </p>
 * 
 * @package org.osid.cataloging
 */
interface osid_cataloging_CatalogSession
    extends osid_OsidSession
{


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeCatalogView();


    /**
     *  A complete view of the <code> Id </code> and <code> Catalog </code> 
     *  returns is desired. Methods will return what is requested or result in 
     *  an error. This view is used when greater precision is desired at the 
     *  expense of interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryCatalogView();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include entries from descendant catalogs in the catalog hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedCatalogView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to the specified catalog only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedCatalogView();


    /**
     *  Tests if this user can perform lookups of <code> Id </code> to <code> 
     *  Catalog </code> mappings. A return of true does not guarantee 
     *  successful authorization. A return of false indicates that it is known 
     *  lookup methods in this session will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if looking up mappings is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupMappings();


    /**
     *  Gets the list of <code> Ids </code> map to a <code> Catalog. </code> 
     *
     *  @param object osid_id_Id $catalogId a catalog <code> Id </code> 
     *  @return object osid_id_IdList list of <code> Ids </code> mapped the 
     *          given <code> catalogId </code> 
     *  @throws osid_NotFoundException <code> catalogId </code> is not found 
     *  @throws osid_NullArgumentException <code> catalogId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getIdsByCatalog(osid_id_Id $catalogId);


    /**
     *  Gets the list of <code> Ids </code> map to a lst of <code> Catalogs. 
     *  </code> 
     *
     *  @param object osid_id_IdList $catalogIdList an <code> Id </code> 
     *  @return object osid_id_IdList list of catalogs containing the given 
     *          <code> Id </code> 
     *  @throws osid_NullArgumentException <code> idList </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getIdsByCatalogs(osid_id_IdList $catalogIdList);


    /**
     *  Gets the <code> Catalog Ids </code> mapped to an <code> Id. </code> 
     *
     *  @param object osid_id_Id $id an <code> Id </code> 
     *  @return object osid_id_IdList list of catalogs <code> Ids </code> 
     *          containing the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogIdsById(osid_id_Id $id);


    /**
     *  Gets the <code> Catalogs </code> mapped to an <code> Id. </code> 
     *
     *  @param object osid_id_Id $id an <code> Id </code> 
     *  @return object osid_cataloging_CatalogList list of catalogs containing 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> id </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogsById(osid_id_Id $id);

}
