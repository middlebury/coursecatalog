<?php

/**
 * osid_course_TermHierarchySession
 * 
 *     Specifies the OSID definition for osid_course_TermHierarchySession.
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
 *  <p>This session defines methods for traversing a hiercrhy of <code> Term 
 *  </code> objects. Each term in the hierarchy is a unique <code> Term. 
 *  </code> The hierarchy may be traversed recursively to establish the tree 
 *  structure through <code> getParents() </code> and <code> getChildren(). 
 *  </code> To relate these <code> Ids </code> to another OSID, <code> 
 *  getAncestors() </code> and <code> getDescendants() </code> can be used for 
 *  retrievals that can be used for bulk lookups in other OSIDs. Any <code> 
 *  Term </code> available in the Course OSID is known to this hierarchy but 
 *  does not appear in the hierarchy traversal until added as a root node or a 
 *  child of another node. </p> 
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
 *      <li> comparative view: term elements may be silently omitted or 
 *      re-ordered </li> 
 *      <li> plenary view: provides a complete set or is an error condition 
 *      </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.course
 */
interface osid_course_TermHierarchySession
    extends osid_hierarchy_HierarchyTraversalSession
{


    /**
     *  The returns from the term methods may omit or translate elements based 
     *  on this session, such as authorization, and not result in an error. 
     *  This view is used when greater interoperability is desired at the 
     *  expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeTermView();


    /**
     *  A complete view of the <code> Terms </code> returns is desired. 
     *  Methods will return what is requested or result in an error. This view 
     *  is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryTermView();


    /**
     *  Gets the root terms in the term hierarchy. A node with no parents is 
     *  an orphan. While all term <code> Ids </code> are known to the 
     *  hierarchy, an orphan does not appear in the hierarchy unless 
     *  explicitly added as a root node or child of another node. 
     *
     *  @return object osid_course_TermList the root terms 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getRootTerms();


    /**
     *  Gets the parents of the given term. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> to query 
     *  @return object osid_course_TermList the parents of the term 
     *  @throws osid_NotFoundException <code> termId </code> not found 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParentTerms(osid_id_Id $termId);


    /**
     *  Gets the ancestors of the given term. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> to query 
     *  @param integer $levels the maximum number of levels to include. A 
     *          value of 1 returns the same set as <code> getParentTerms(). 
     *          </code> 
     *  @return object osid_course_TermList the ancestors of the term 
     *  @throws osid_NotFoundException <code> termId </code> not found 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAncestorTerms(osid_id_Id $termId, $levels);


    /**
     *  Gets the children of the given term. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> to query 
     *  @return object osid_course_TermList the children of the term 
     *  @throws osid_NotFoundException <code> termId </code> not found 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getChildTerms(osid_id_Id $termId);


    /**
     *  Gets the descendants of the given term. 
     *
     *  @param object osid_id_Id $termId the <code> Id </code> to query 
     *  @param integer $levels the maximum number of levels to include. A 
     *          value of 1 returns the same set as <code> getChildTerms(). 
     *          </code> 
     *  @return object osid_course_TermList the descendants of the term 
     *  @throws osid_NotFoundException <code> termId </code> not found 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDescendantTerms(osid_id_Id $termId, $levels);

}
