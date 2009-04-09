<?php

/**
 * osid_course_CourseOfferingHierarchySession
 * 
 *     Specifies the OSID definition for osid_course_CourseOfferingHierarchySession.
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
 *  CourseOffering </code> objects. Each node in the hierarchy is a unique 
 *  <code> CourseOffering. </code> The hierarchy may be traversed recursively 
 *  to establish the tree structure through <code> getParents() </code> and 
 *  <code> getChildren(). </code> To relate these <code> Ids </code> to 
 *  another OSID, <code> getAncestors() </code> and <code> getDescendants() 
 *  </code> can be used for retrievals that can be used for bulk lookups in 
 *  other OSIDs. Any <code> CourseOffering </code> available in the Course 
 *  OSID is known to this hierarchy but does not appear in the hierarchy 
 *  traversal until added as a root node or a child of another node. </p> 
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
 *      <li> comparative view: course offering elements may be silently 
 *      omitted or re-ordered </li> 
 *      <li> plenary view: provides a complete set or is an error condition 
 *      </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseOfferingHierarchySession
    extends osid_hierarchy_HierarchyTraversalSession
{


    /**
     *  The returns from the course offering methods may omit or translate 
     *  elements based on this session, such as authorization, and not result 
     *  in an error. This view is used when greater interoperability is 
     *  desired at the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeCourseOfferingView();


    /**
     *  A complete view of the <code> CourseOfferings </code> returns is 
     *  desired. Methods will return what is requested or result in an error. 
     *  This view is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryCourseOfferingView();


    /**
     *  Gets the root course offerings in the course offering hierarchy. A 
     *  node with no parents is an orphan. While all course offering Ids are 
     *  known to the hierarchy, an orphan does not appear in the hierarchy 
     *  unless explicitly added as a root node or child of anot 
     *
     *  @return object osid_course_CourseOfferingList the root course 
     *          offerings 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getRootCourseOfferings();


    /**
     *  Gets the parents of the given course offering. 
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> to 
     *          query 
     *  @return object osid_course_CourseOfferingList the parents of the 
     *          course offering 
     *  @throws osid_NotFoundException <code> courseOfferingId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParentCourseOfferings(osid_id_Id $courseOfferingId);


    /**
     *  Gets the ancestors of the given course offering. 
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> to 
     *          query 
     *  @param integer $levels the maximum number of levels to include. A 
     *          value of 1 returns the same set as <code> 
     *          getParentCourseOfferings(). </code> 
     *  @return object osid_course_CourseOfferingList the ancestors of the 
     *          course offering 
     *  @throws osid_NotFoundException <code> courseOfferingId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAncestorCourseOfferings(osid_id_Id $courseOfferingId, 
                                               $levels);


    /**
     *  Gets the children of the given course offering. 
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> to 
     *          query 
     *  @return object osid_course_CourseOfferingList the children of the 
     *          course offering 
     *  @throws osid_NotFoundException <code> courseOfferingId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getChildCourseOfferings(osid_id_Id $courseOfferingId);


    /**
     *  Gets the descendants of the given course offering. 
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> to 
     *          query 
     *  @param integer $levels the maximum number of levels to include. A 
     *          value of 1 returns the same set as <code> 
     *          getChildCourseOfferings(). </code> 
     *  @return object osid_course_CourseOfferingList the descendants of the 
     *          course offering 
     *  @throws osid_NotFoundException <code> courseOfferingId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDescendantCourseOfferings(osid_id_Id $courseOfferingId, 
                                                 $levels);

}
