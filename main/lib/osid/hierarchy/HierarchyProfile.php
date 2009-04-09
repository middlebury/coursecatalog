<?php

/**
 * osid_hierarchy_HierarchyProfile
 * 
 *     Specifies the OSID definition for osid_hierarchy_HierarchyProfile.
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
 * @package org.osid.hierarchy
 */

require_once(dirname(__FILE__)."/../OsidProfile.php");

/**
 *  <p>The hierarchy profile describes the interoperability among hierarchy 
 *  services. </p>
 * 
 * @package org.osid.hierarchy
 */
interface osid_hierarchy_HierarchyProfile
    extends osid_OsidProfile
{


    /**
     *  Tests if federation is visible. Visible federation allows for 
     *  selecting among multiple hierarchies. 
     *
     *  @return boolean <code> true </code> if visible federation is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsVisibleFederation();


    /**
     *  Tests if hierarchy traversal is supported. 
     *
     *  @return boolean <code> true </code> if hierarchy traversal is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsHierarchyTraversal();


    /**
     *  Tests if hierarchy design is supported. 
     *
     *  @return boolean <code> true </code> if hierarchy design is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsHierarchyDesign();


    /**
     *  Tests if hierarchy structure notification is supported. 
     *
     *  @return boolean <code> true </code> if hierarchy structure 
     *          notification is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsHierarchyStructureNotification();


    /**
     *  Tests if a hierarchy lookup is supported. 
     *
     *  @return boolean <code> true </code> if hierarchy lookup is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsHierarchyLookup();


    /**
     *  Tests if a hierarchy search is supported. 
     *
     *  @return boolean <code> true </code> if hierarchy search is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsHierarchySearch();


    /**
     *  Tests if a hierarchy administration is supported. 
     *
     *  @return boolean <code> true </code> if hierarchy administration is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsHierarchyAdmin();


    /**
     *  Tests if hierarchy notification is supported. Messages may be sent 
     *  when hierarchies are created, modified, or deleted. 
     *
     *  @return boolean <code> true </code> if hierarchy notification is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsHierarchyNotification();


    /**
     *  Tests if hierarchy node sequencing for ordering nodes is supported. 
     *
     *  @return boolean <code> true </code> if node sequencing is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsNodeSequencing();


    /**
     *  Gets the supported <code> Hierarchy </code> types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Hierarchy </code> record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchyRecordTypes();


    /**
     *  Tests if the given <code> Hierarchy </code> record type is supported. 
     *
     *  @param object osid_type_Type $hierarchyRecordType a <code> Type 
     *          </code> indicating a <code> Hierarchy </code> record type 
     *  @return boolean <code> true </code> if the given record Type is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsHierarchyRecordType(osid_type_Type $hierarchyRecordType);


    /**
     *  Gets the supported <code> Hierarchy </code> search record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Hierarchy </code> search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchySearchRecordTypes();


    /**
     *  Tests if the given <code> Hierarchy </code> search record type is 
     *  supported. 
     *
     *  @param object osid_type_Type $hierarchySearchRecordType a <code> Type 
     *          </code> indicating a <code> Hierarchy </code> search record 
     *          type 
     *  @return boolean <code> true </code> if the given Type is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsHierarchySearchRecordType(osid_type_Type $hierarchySearchRecordType);

}
