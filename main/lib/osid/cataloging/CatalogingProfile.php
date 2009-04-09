<?php

/**
 * osid_cataloging_CatalogingProfile
 * 
 *     Specifies the OSID definition for osid_cataloging_CatalogingProfile.
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

require_once(dirname(__FILE__)."/../OsidProfile.php");

/**
 *  <p>The cataloging profile describes the interoperability among cataloging 
 *  services. </p>
 * 
 * @package org.osid.cataloging
 */
interface osid_cataloging_CatalogingProfile
    extends osid_OsidProfile
{


    /**
     *  Tests for the availability of a cataloging service retrieving <code> 
     *  Id </code> to <code> Catalog </code> mappings. 
     *
     *  @return boolean <code> true </code> if cataloging is available, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCatalog();


    /**
     *  Tests for the availability of a cataloging service for mapping <code> 
     *  Ids </code> to <code> Catalogs. </code> 
     *
     *  @return boolean <code> true </code> if catalog assignment is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCatalogAssignment();


    /**
     *  Tests for the availability of a cataloging notification service for 
     *  mapping <code> Ids </code> to <code> Catalogs. </code> 
     *
     *  @return boolean <code> true </code> if catalog assignment notification 
     *          is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCatalogAssignmentNotification();


    /**
     *  Tests for the availability of a catalog lookup service. 
     *
     *  @return boolean <code> true </code> if catalog lookup is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCatalogLookup();


    /**
     *  Tests for the availability of a catalog search service that defines 
     *  more comprehensive queries. 
     *
     *  @return boolean <code> true </code> if catalog search is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCatalogSearch();


    /**
     *  Tests for the availability of a catalog administration service for the 
     *  addition and deletion of catalogs. 
     *
     *  @return boolean <code> true </code> if catalog administration is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCatalogAdmin();


    /**
     *  Tests for the availability of a catalog notification service. 
     *
     *  @return boolean <code> true </code> if catalog notification is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCatalogNotification();


    /**
     *  Tests for the availability of a catalog hierarchy traversal service. 
     *
     *  @return boolean <code> true </code> if catalog hierarchy traversal is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCatalogHierarchy();


    /**
     *  Tests for the availability of a catalog hierarchy design service. 
     *
     *  @return boolean <code> true </code> if catalog hierarchy design is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented in all 
     *              providers. 
     */
    public function supportsCatalogHierarchyDesign();


    /**
     *  Tests if the configuration hierarchy supports node sequencing. 
     *
     *  @return boolean <code> true </code> if configuration hierarchy node 
     *          sequencing is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsConfigurationHierarchySequencing();


    /**
     *  Gets the supported <code> Catalog </code> record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Catalog </code> record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogRecordTypes();


    /**
     *  Tests if the given <code> Catalog </code> record type is supported. 
     *
     *  @param object osid_type_Type $catalogRecordType a <code> Type </code> 
     *          indicating a <code> Catalog </code> record type 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCatalogRecordType(osid_type_Type $catalogRecordType);


    /**
     *  Gets the supported catalog search reciord types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCatalogSearchRecordTypes();


    /**
     *  Tests if the given catalog search record type is supported. 
     *
     *  @param object osid_type_Type $catalogSearchRecordType a <code> Type 
     *          </code> indicating a catalog search record type 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCatalogSearchRecordType(osid_type_Type $catalogSearchRecordType);

}
