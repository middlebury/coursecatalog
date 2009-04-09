<?php

/**
 * osid_course_CourseCatalogHierarchySession
 * 
 *     Specifies the OSID definition for osid_course_CourseCatalogHierarchySession.
 * 
 * Copyright (C) 2009 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.course
 */

require_once(dirname(__FILE__)."/../hierarchy/HierarchyTraversalSession.php");

/**
 *  <p>This session defines methods for traversing a hiercrhy of <code> 
 *  Repository </code> objects. Each node in the hierarchy is a unique <code> 
 *  Repository. </code> The hierarchy may be traversed recursively to 
 *  establish the tree structure through <code> getParents() </code> and 
 *  <code> getChildren(). </code> To relate these <code> Ids </code> to 
 *  another OSID, <code> getAncestors() </code> and <code> getDescendants() 
 *  </code> can be used for retrievals that can be used for bulk lookups in 
 *  other OSIDs. Any <code> CourseCatalog </code> available in the Course OSID 
 *  is known to this hierarchy but does not appear in the hierarchy traversal 
 *  until added as a root node or a child of another node. </p> 
 *  
 *  <p> A user may not be authorized to traverse the entire hierarchy. Parts 
 *  of the hierarchy may be made invisible through omission from the returns 
 *  of <code> getParents() </code> or <code> getChildren() </code> in lieu of 
 *  a <code> PERMISSION_DENIED </code> error that may disrupt the traversal 
 *  through authorized pathways. </p> 
 *  
 *  <p> This session defines views that offer differing behaviors when 
 *  retrieving multiple objects. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: repository elements may be silently omitted or 
 *      re-ordered </li> 
 *      <li> plenary view: provides a complete set or is an error condition 
 *      </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseCatalogHierarchySession
    extends osid_hierarchy_HierarchyTraversalSession
{


    /**
     *  The returns from the course catalog methods may omit or translate 
     *  elements based on this session, such as authorization, and not result 
     *  in an error. This view is used when greater interoperability is 
     *  desired at the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeCourseCatalogView();


    /**
     *  A complete view of the <code> CourseCatalogs </code> returns is 
     *  desired. Methods will return what is requested or result in an error. 
     *  This view is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryCourseCatalogView();


    /**
     *  Gets the root course catalogs in the course catalog hierarchy. A node 
     *  with no parents is an orphan. While all course catalog Ids are known 
     *  to the hierarchy, an orphan does not appear in the hierarchy unless 
     *  explicitly added as a root node or child of another node. 
     *
     *  @return object osid_course_CourseCatalogList the root course catalogs 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getRootCourseCatalogs();


    /**
     *  Gets the parents of the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> to 
     *          query 
     *  @return object osid_course_CourseCatalogList the parents of the course 
     *          catalog 
     *  @throws osid_NotFoundException <code> courseCatalogId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParentCourseCatalogs(osid_id_Id $courseCatalogId);


    /**
     *  Gets the ancestors of the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> to 
     *          query 
     *  @param integer $levels the maximum number of levels to include. A 
     *          value of 1 returns the same set as <code> 
     *          getParentCourseCatalogs(). </code> 
     *  @return object osid_course_CourseCatalogList the ancestors of the 
     *          course catalog 
     *  @throws osid_NotFoundException <code> courseCatalogId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAncestorCourseCatalogs(osid_id_Id $courseCatalogId, 
                                              $levels);


    /**
     *  Gets the children of the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> to 
     *          query 
     *  @return object osid_course_CourseCatalogList the children of the 
     *          course catalog 
     *  @throws osid_NotFoundException <code> courseCatalogId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getChildCourseCatalogs(osid_id_Id $courseCatalogId);


    /**
     *  Gets the descendants of the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> to 
     *          query 
     *  @param integer $levels the maximum number of levels to include. A 
     *          value of 1 returns the same set as <code> 
     *          getChildCourseCatalogs(). </code> 
     *  @return object osid_course_CourseCatalogList the descendants of the 
     *          course catalog 
     *  @throws osid_NotFoundException <code> courseCatalogId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDescendantCourseCatalogs(osid_id_Id $courseCatalogId, 
                                                $levels);

}
