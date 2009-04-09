<?php

/**
 * osid_hierarchy_HierarchyLookupSession
 * 
 *     Specifies the OSID definition for osid_hierarchy_HierarchyLookupSession.
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

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session provides methods for retrieving <code> Hierarchy </code> 
 *  objects. The <code> Hierarchy </code> represents a structure of OSID 
 *  <code> Ids. </code> </p> 
 *  
 *  <p> This session defines views that offer differing behaviors when 
 *  retrieving multiple objects. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete set or is an error condition 
 *      </li> 
 *  </ul>
 *  Generally, the comparative view should be used for most applications as it 
 *  permits operation even if there is data that cannot be accessed. For 
 *  example, a browsing application may only need to examine the <code> 
 *  Hierarchies </code> objects it can access, without breaking execution. 
 *  However, an assessment may only be useful if all <code> Hierarchy </code> 
 *  objects referenced by it are available, and a test-taking applicationmay 
 *  sacrifice some interoperability for the sake of precision. </p>
 * 
 * @package org.osid.hierarchy
 */
interface osid_hierarchy_HierarchyLookupSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> Hierarchy </code> lookups. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupHierarchies();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeHierarchyView();


    /**
     *  A complete view of the <code> Hierarchy </code> returns is desired. 
     *  Methods will return what is requested or result in an error. This view 
     *  is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryHierarchyView();


    /**
     *  Gets the <code> Hierarchy </code> specified by its <code> Id. </code> 
     *  In plenary mode, the exact <code> Id </code> is found or a <code> 
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Hierarchy 
     *  </code> may have a different <code> Id </code> than requested, such as 
     *  the case where a duplicate <code> Id </code> was assigned to a <code> 
     *  Hierarchy </code> and retained for compati 
     *
     *  @param object osid_id_Id $hierarchyId the <code> Id </code> of the 
     *          <code> Hierarchy </code> to rerieve 
     *  @return object osid_hierarchy_Hierarchy the returned <code> Hierarchy 
     *          </code> 
     *  @throws osid_NotFoundException no <code> Hierarchy </code> found with 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> hierarchyId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchy(osid_id_Id $hierarchyId);


    /**
     *  Gets a <code> Hierarchy </code> corresponding to the given <code> 
     *  IdList. </code> In plenary mode, the returned list contains all of the 
     *  hierarchies specified in the <code> Id </code> list, in the order of 
     *  the list, including duplicates, or an error results if an <code> Id 
     *  </code> in the supplied list is not found or inaccessible. Otherwise, 
     *  inaccessible <code> Hierarchy </code> objects may be omitted from the 
     *  list and may present the elements in any order including returning a 
     *  unique set. 
     *
     *  @param object osid_id_IdList $hierarchyIdList the list of <code> Ids 
     *          </code> to rerieve 
     *  @return object osid_hierarchy_HierarchyList the returned <code> 
     *          Hierarchy list </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> hierarchyIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchiesByIds(osid_id_IdList $hierarchyIdList);


    /**
     *  Gets a <code> HierarchyList </code> corresponding to the given genus 
     *  <code> Type </code> which does not include hierarchies of types 
     *  derived from the specified <code> Type. </code> In plenary mode, the 
     *  returned list contains all known hierarchies or an error results. 
     *  Otherwise, the returned list may contain only those repositories that 
     *  are accessible through this session. In both cases, the order of the 
     *  set is not specified. 
     *
     *  @param object osid_type_Type $hierarchyGenusType a hierarchy genus 
     *          type 
     *  @return object osid_hierarchy_HierarchyList the returned <code> 
     *          Hierarchy list </code> 
     *  @throws osid_NullArgumentException <code> hierarchyGenusType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchiesByGenusType(osid_type_Type $hierarchyGenusType);


    /**
     *  Gets a <code> HierarchyList </code> corresponding to the given 
     *  hierarchy genus <code> Type </code> and include any additional 
     *  hierarchies with types derived from the specified <code> Type. </code> 
     *  In plenary mode, the returned list contains all known hierarchies or 
     *  an error results. Otherwise, the returned list may contain only those 
     *  hierarchies that are accessible through this session. In both cases, 
     *  the order of the set is not specified. 
     *
     *  @param object osid_type_Type $hierarchyGenusType a hierarchy genus 
     *          type 
     *  @return object osid_hierarchy_HierarchyList the returned <code> 
     *          Hierarchy list </code> 
     *  @throws osid_NullArgumentException <code> hierarchyGenusType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchiesByParentGenusType(osid_type_Type $hierarchyGenusType);


    /**
     *  Gets a <code> HierarchyList </code> corresponding to the given 
     *  hierarchy record <code> Type. </code> The set of hierarchies 
     *  implementing the given record type are returned. <code> </code> In 
     *  plenary mode, the returned list contains all known hierarchies or an 
     *  error results. Otherwise, the returned list may contain only those 
     *  hierarchies that are accessible through this session. In both cases, 
     *  the order of the set is not specified. 
     *
     *  @param object osid_type_Type $hierarchyRecordType a hierarchy record 
     *          type 
     *  @return object osid_hierarchy_HierarchyList the returned <code> 
     *          Hierarchy list </code> 
     *  @throws osid_NullArgumentException <code> hierarchyRecordType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchiesByRecordType(osid_type_Type $hierarchyRecordType);


    /**
     *  Gets all <code> Repositories. </code> In plenary mode, the returned 
     *  list contains all known hierarchies or an error results. Otherwise, 
     *  the returned list may contain only those hierarchies that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @return object osid_hierarchy_HierarchyList a list of <code> 
     *          Hierarchies </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getHierarchies();

}
