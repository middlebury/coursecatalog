<?php

/**
 * osid_hierarchy_HierarchySearch
 * 
 *     Specifies the OSID definition for osid_hierarchy_HierarchySearch.
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

require_once(dirname(__FILE__)."/../OsidSearch.php");

/**
 *  <p><code> HierarchySearch </code> defines the interface for specifying 
 *  hierarchy search options. </p>
 * 
 * @package org.osid.hierarchy
 */
interface osid_hierarchy_HierarchySearch
    extends osid_OsidSearch
{


    /**
     *  Execute this search using a previous search result. 
     *
     *  @param object osid_hierarchy_HierarchySearchResults $results results 
     *          from a query 
     *  @throws osid_InvalidArgumentException <code> results </code> is not 
     *          valid 
     *  @throws osid_NullArgumentException <code> results </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchWithinHierarchyResults(osid_hierarchy_HierarchySearchResults $results);


    /**
     *  Execute this search using a given list of hierarchies. 
     *
     *  @param object osid_id_IdList $hierarchyIds list of hierarchies 
     *  @throws osid_NullArgumentException <code> hierarchyIds </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchAmongHierarchies(osid_id_IdList $hierarchyIds);


    /**
     *  Specify an ordering to the search results. 
     *
     *  @param object osid_hierarchy_HierarchySearchOrder $hierarchySearchOrder 
     *          hierarchy search order 
     *  @throws osid_NullArgumentException <code> hierarchySearchOrder </code> 
     *          is <code> null </code> 
     *  @throws osid_UnsupportedException <code> hierarchySearchOrder </code> 
     *          is not of this service 
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderHierarchyResults(osid_hierarchy_HierarchySearchOrder $hierarchySearchOrder);


    /**
     *  Gets the record corresponding to the given hierarchy search record 
     *  <code> Type. </code> This method must be used to retrieve an object 
     *  implementing the requested record interface along with all of its 
     *  ancestor interfaces. 
     *
     *  @param object osid_type_Type $hierarchySearchRecordType a hierarchy 
     *          search record type 
     *  @return object osid_hierarchy_HierarchySearchRecord the hierarchy 
     *          search interface 
     *  @throws osid_NullArgumentException <code> hierarchySearchRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasSearchRecordType(hierarchyRecordType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchySearchRecord(osid_type_Type $hierarchySearchRecordType);

}
