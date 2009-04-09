<?php

/**
 * osid_cataloging_CatalogAdminSession
 * 
 *     Specifies the OSID definition for osid_cataloging_CatalogAdminSession.
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
 *  <p>This session creates and removes catalogs. The data for create and 
 *  update is provided by the consumer via the form object. </p>
 * 
 * @package org.osid.cataloging
 */
interface osid_cataloging_CatalogAdminSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can create <code> Catalogs. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating a <code> Catalog </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may not wish to offer create operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> Catalog </code> 
     *          creation is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateCatalogs();


    /**
     *  Tests if this user can create a single <code> Catalog </code> using 
     *  the desired record interface types. While <code> 
     *  CatalogingManager.getCatalogRecordTypes() </code> can be used to 
     *  examine which record interfaces are supported, this method tests which 
     *  record(s) are required for creating a specific <code> Catalog. </code> 
     *  Providing an empty array tests if a <code> Catalog </code> can be 
     *  created with no records. 
     *
     *  @param array $catalogRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> Catalog </code> creation 
     *          using the specified record <code> Types </code> is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> catalogRecordTypes </code> 
     *          is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateCatalogWithRecordTypes(array $catalogRecordTypes);


    /**
     *  Gets the catalog form for creating new catalogs. A new form should be 
     *  requested for each create transaction. 
     *
     *  @return object osid_cataloging_CatalogForm the catalog form 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogFormForCreate();


    /**
     *  Creates a new <code> Catalog. </code> 
     *
     *  @param object osid_cataloging_CatalogForm $catalogForm the forms for 
     *          this <code> Catalog </code> 
     *  @return object osid_cataloging_Catalog the new <code> Catalog </code> 
     *  @throws osid_AlreadyExistsException attempt at duplicating a property 
     *          the underlying system is enforcing to be unique 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> catalogForm </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> catalogForm </code> is not of 
     *          this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createCatalog(osid_cataloging_CatalogForm $catalogForm);


    /**
     *  Tests if this user can update <code> Catalogs. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a <code> Catalog </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may not wish to offer update operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> Catalog </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateCatalogs();


    /**
     *  Tests if this user can update a specified <code> Catalog. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known updating the <code> Catalog 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may not wish to offer update 
     *  operations to unauthorized users. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          <code> Catalog </code> 
     *  @return boolean <code> false </code> if catalog modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> catalogId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> catalogId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an update 
     *          available. 
     */
    public function canUpdateCatalog(osid_id_Id $catalogId);


    /**
     *  Gets the catalog form for updating an existing catalog. A new catalog 
     *  form should be requested for each update transaction. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          <code> Catalog </code> 
     *  @return object osid_cataloging_CatalogForm the catalog form 
     *  @throws osid_NullArgumentException <code> catalogId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogFormForUpdate(osid_id_Id $catalogId);


    /**
     *  Updates an existing catalog. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          <code> Catalog </code> 
     *  @param object osid_cataloging_CatalogForm $catalogForm the form 
     *          containing the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> catalogId </code> is not found 
     *  @throws osid_NullArgumentException <code> catalogId </code> or <code> 
     *          catalogForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> catalogForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateCatalog(osid_id_Id $catalogId, 
                                  osid_cataloging_CatalogForm $catalogForm);


    /**
     *  Tests if this user can delete <code> Catalogs. </code> A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting a <code> Catalog </code> will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may not wish to offer delete operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if <code> Catalog </code> 
     *          deletion is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteCatalogs();


    /**
     *  Tests if this user can delete a specified <code> Catalog. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> Catalog 
     *  </code> will result in a <code> PERMISSION_DENIED. </code> This is 
     *  intended as a hint to an application that may not wish to offer delete 
     *  operations to unauthorized users. 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          <code> Catalog </code> 
     *  @return boolean <code> false </code> if deletion of this <code> 
     *          Catalog </code> is not authorized, <code> true </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> catalogId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> catalogId </code> is not found, then it is 
     *          acceptable to return false to indicate the lack of an delete 
     *          available. 
     */
    public function canDeleteCatalog(osid_id_Id $catalogId);


    /**
     *  Deletes a <code> Catalog. </code> 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of the 
     *          <code> Catalog </code> to remove 
     *  @throws osid_NotFoundException <code> catalogId </code> not found 
     *  @throws osid_NullArgumentException <code> catalogId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteCatalog(osid_id_Id $catalogId);


    /**
     *  Adds an <code> Id </code> to a <code> Catalog </code> for the purpose 
     *  of creating compatibility. The primary <code> Id </code> of the <code> 
     *  Catalog </code> is determined by the provider. The new <code> Id 
     *  </code> performs as an alias to the primary <code> Id. </code> 
     *
     *  @param object osid_id_Id $catalogId the <code> Id </code> of a <code> 
     *          Catalog </code> 
     *  @param object osid_id_Id $aliasId the alias <code> Id </code> 
     *  @throws osid_AlreadyExistsException <code> aliasId </code> is already 
     *          assigned 
     *  @throws osid_NotFoundException <code> catalogId </code> not found 
     *  @throws osid_NullArgumentException <code> catalogId </code> or <code> 
     *          aliasId </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addIdToCatalog(osid_id_Id $catalogId, osid_id_Id $aliasId);

}
