<?php

/**
 * osid_resource_ResourceProfile
 * 
 *     Specifies the OSID definition for osid_resource_ResourceProfile.
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

require_once(dirname(__FILE__)."/../OsidProfile.php");

/**
 *  <p>The resource profile describes interoperability among resource 
 *  services. </p>
 * 
 * @package org.osid.resource
 */
interface osid_resource_ResourceProfile
    extends osid_OsidProfile
{


    /**
     *  Tests if federation is visible. 
     *
     *  @return boolean <code> true </code> if visible federation is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsVisibleFederation();


    /**
     *  Tests if resource lookup is supported. 
     *
     *  @return boolean <code> true </code> if resource lookup is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsResourceLookup();


    /**
     *  Tests if resource search is supported. 
     *
     *  @return boolean <code> true </code> if resource search is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsResourceSearch();


    /**
     *  Tests if resource administration is supported. 
     *
     *  @return boolean <code> true </code> if resource administration is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsResourceAdmin();


    /**
     *  Tests if resource notification is supported. Messages may be sent when 
     *  resources are created, modified, or deleted. 
     *
     *  @return boolean <code> true </code> if resource notification is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsResourceNotification();


    /**
     *  Tests if rerieving mappings of resource and bins is supported. 
     *
     *  @return boolean <code> true </code> if resource bin mapping retrieval 
     *          is supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsResourceBin();


    /**
     *  Tests if managing mappings of resources and bins is supported. 
     *
     *  @return boolean <code> true </code> if resource bin assignment is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsResourceBinAssignment();


    /**
     *  Tests if bin lookup is supported. 
     *
     *  @return boolean <code> true </code> if bin lookup is supported <code> 
     *          , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsBinLookup();


    /**
     *  Tests if bin search is supported. 
     *
     *  @return boolean <code> true </code> if bin search is supported <code> 
     *          , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsBinSearch();


    /**
     *  Tests if bin administration is supported. 
     *
     *  @return boolean <code> true </code> if bin administration is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsBinAdmin();


    /**
     *  Tests if bin notification is supported. Messages may be sent when 
     *  <code> Bin </code> objects are created, deleted or updated. 
     *  Notifications for resources within bins are sent via the resource 
     *  notification session. 
     *
     *  @return boolean <code> true </code> if bin notification is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsBinNotification();


    /**
     *  Tests if a bin hierarchy traversal is supported. 
     *
     *  @return boolean <code> true </code> if a bin hierarchy traversal is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsBinHierarchy();


    /**
     *  Tests if a bin hierarchy design is supported. 
     *
     *  @return boolean <code> true </code> if a bin hierarchy design is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsBinHierarchyDesign();


    /**
     *  Tests if the bin hierarchy supports node sequencing. 
     *
     *  @return boolean <code> true </code> if bin hierarchy node sequencing 
     *          is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsBinHierarchySequencing();


    /**
     *  Gets all the resource record types supported. 
     *
     *  @return object osid_type_TypeList the list of supported resource 
     *          record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceRecordTypes();


    /**
     *  Tests if a given resource record type is supported. 
     *
     *  @param object osid_type_Type $resourceRecordType the resource type 
     *  @return boolean <code> true </code> if the resource record type is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsResourceRecordType(osid_type_Type $resourceRecordType);


    /**
     *  Gets all the resource search record types supported. 
     *
     *  @return object osid_type_TypeList the list of supported resource 
     *          search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceSearchRecordTypes();


    /**
     *  Tests if a given resource search type is supported. 
     *
     *  @param object osid_type_Type $resourceSearchRecordType the resource 
     *          search type 
     *  @return boolean <code> true </code> if the resource search record type 
     *          is supported <code> , </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsResourceSearchRecordType(osid_type_Type $resourceSearchRecordType);


    /**
     *  Gets all the bin record types supported. 
     *
     *  @return object osid_type_TypeList the list of supported bin record 
     *          types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getBinRecordTypes();


    /**
     *  Tests if a given bin record type is supported. 
     *
     *  @param object osid_type_Type $binRecordType the bin record type 
     *  @return boolean <code> true </code> if the bin record type is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsBinRecordType(osid_type_Type $binRecordType);


    /**
     *  Gets all the bin search record types supported. 
     *
     *  @return object osid_type_TypeList the list of supported bin search 
     *          record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getBinSearchRecordTypes();


    /**
     *  Tests if a given bin search record type is supported. 
     *
     *  @param object osid_type_Type $binSearchRecordType the bin search 
     *          record type 
     *  @return boolean <code> true </code> if the bin search record type is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsBinSearchRecordType(osid_type_Type $binSearchRecordType);

}
