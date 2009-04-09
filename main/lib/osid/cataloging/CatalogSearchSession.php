<?php

/**
 * osid_cataloging_CatalogSearchSession
 * 
 *     Specifies the OSID definition for osid_cataloging_CatalogSearchSession.
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
 *  <p>This session provides methods for searching <code> Catalog </code> 
 *  objects. The search query is constructed using the <code> CatalogQuery 
 *  </code> interface.The catalog interface <code> Type </code> also specifies 
 *  the interface for the catalog query. </p> 
 *  
 *  <p> <code> getCatalogsByQuery() </code> is the basic search method and 
 *  returns a list of <code> Catalog </code> elements. A more advanced search 
 *  may be performed with <code> getCatalogsBySearch(). </code> It accepts a 
 *  <code> CatalogSearch </code> interface in addition to the query interface 
 *  for the purpose of specifying additional options affecting the entire 
 *  search, such as ordering. <code> getCatalogsBySearch() </code> returns a 
 *  <code> CatalogSearchResults </code> interface that can be used to access 
 *  the resulting <code> CatalogList </code> or be used to perform a search 
 *  within the result set through <code> CatalogSearch. </code> Catalogs may 
 *  have a record query interface indicated by their respective record 
 *  interface types. The record query interface is accessed via the <code> 
 *  CatalogQuery. </code> </p>
 * 
 * @package org.osid.cataloging
 */
interface osid_cataloging_CatalogSearchSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> Catalog </code> searches. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer search operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if search methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canSearchCatalogs();


    /**
     *  Gets a catalog query interface. The returned query will not have an 
     *  extension query. 
     *
     *  @return object osid_cataloging_CatalogQuery the catalog query 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogQuery();


    /**
     *  Gets a list of <code> Catalogs </code> matching the given search 
     *  interface. 
     *
     *  @param object osid_cataloging_CatalogQuery $catalogQuery the search 
     *          query 
     *  @return object osid_cataloging_CatalogList the returned <code> 
     *          CatalogList </code> 
     *  @throws osid_NullArgumentException <code> catalogQuery </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> catalogQuery </code> is not 
     *          of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogsByQuery(osid_cataloging_CatalogQuery $catalogQuery);


    /**
     *  Gets a catalog search interface. The returned query only makes 
     *  available the core <code> CatalogSearch </code> interface and does not 
     *  support additional interface types. <code> 
     *  getCatalogSearchForInterfaceType() </code> should be used if 
     *  additional interface types are required. 
     *
     *  @return object osid_cataloging_CatalogSearch the catalog search 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogSearch();


    /**
     *  Gets a subject search order interface. The <code> CatalogSearchOrder 
     *  </code> is supplied to a <code> CatalogSearch </code> to specify the 
     *  ordering of results. 
     *
     *  @return object osid_cataloging_CatalogSearchOrder the catalog search 
     *          order interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogsSearchOrder();


    /**
     *  Gets the search results matching the given search interface. 
     *
     *  @param object osid_cataloging_CatalogQuery $catalogQuery the search 
     *          query 
     *  @param object osid_cataloging_CatalogSearch $catalogSearch the search 
     *          interface 
     *  @return object osid_cataloging_CatalogSearchResults the search results 
     *  @throws osid_NullArgumentException <code> catalogQuery </code> or 
     *          <code> catalogSearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> catalogQuery </code> or 
     *          <code> catalogSearch </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogsBySearch(osid_cataloging_CatalogQuery $catalogQuery, 
                                        osid_cataloging_CatalogSearch $catalogSearch);

}
