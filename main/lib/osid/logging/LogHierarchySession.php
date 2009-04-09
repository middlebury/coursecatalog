<?php

/**
 * osid_logging_LogHierarchySession
 * 
 *     Specifies the OSID definition for osid_logging_LogHierarchySession.
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
 * @package org.osid.logging
 */

require_once(dirname(__FILE__)."/../hierarchy/HierarchyTraversalSession.php");

/**
 *  <p>This session defines methods for traversing a hiercrhy of <code> Log 
 *  </code> objects. Each node in the hierarchy is a unique <code> Log. 
 *  </code> The hierarchy may be traversed recursively to establish the tree 
 *  structure through <code> getParents() </code> and <code> getChildren(). 
 *  </code> To relate these <code> Ids </code> to another OSID, <code> 
 *  getAncestors() </code> and <code> getDescendants() </code> can be used for 
 *  retrievals that can be used for bulk lookups in other OSIDs. Any <code> 
 *  Log </code> available in the Log OSID is known to this hierarchy but does 
 *  not appear in the hierarchy traversal until added as a root node or a 
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
 *      <li> comparative view: log elements may be silently omitted or 
 *      re-ordered </li> 
 *      <li> plenary view: provides a complete set or is an error condition 
 *      </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.logging
 */
interface osid_logging_LogHierarchySession
    extends osid_hierarchy_HierarchyTraversalSession
{


    /**
     *  The returns from the log methods may omit or translate elements based 
     *  on this session, such as authorization, and not result in an error. 
     *  This view is used when greater interoperability is desired at the 
     *  expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeLogView();


    /**
     *  A complete view of the <code> Log </code> returns is desired. Methods 
     *  will return what is requested or result in an error. This view is used 
     *  when greater precision is desired at the expense of interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryLogView();


    /**
     *  Gets the root logs in the log hierarchy. A node with no parents is an 
     *  orphan. While all log <code> Ids </code> are known to the hierarchy, 
     *  an orphan does not appear in the hierarchy unless explicitly added as 
     *  a root node or child of another node. 
     *
     *  @return object osid_logging_LogList the root logs 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getRootLogs();


    /**
     *  Gets the parent logs of the given <code> id. </code> 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> to query 
     *  @return object osid_logging_LogList the parent logs of the <code> id 
     *          </code> 
     *  @throws osid_NotFoundException a <code> Log </code> identified by 
     *          <code> Id is </code> not found 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParentLogs(osid_id_Id $logId);


    /**
     *  Gets the ancestor logs of the given <code> id. </code> 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> to query 
     *  @param integer $levels the maximum number of levels to include. A 
     *          value of 1 returns the same set as <code> getParentLogs(). 
     *          </code> 
     *  @return object osid_logging_LogList the ancestor logs of the <code> id 
     *          </code> 
     *  @throws osid_NotFoundException a <code> Log </code> identified by 
     *          <code> Id is </code> not found 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAncestorLogs(osid_id_Id $logId, $levels);


    /**
     *  Gets the child logs of the given <code> id. </code> 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> to query 
     *  @return object osid_logging_LogList the child logs of the <code> id 
     *          </code> 
     *  @throws osid_NotFoundException a <code> Log </code> identified by 
     *          <code> Id is </code> not found 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getChildLogs(osid_id_Id $logId);


    /**
     *  Gets the descendant logs of the given <code> id. </code> 
     *
     *  @param object osid_id_Id $logId the <code> Id </code> of the <code> 
     *          Log </code> to query 
     *  @param integer $levels the maximum number of levels to include. A 
     *          value of 1 returns the same set as <code> getChildLogs(). 
     *          </code> 
     *  @return object osid_logging_LogList the decsendant logs of the <code> 
     *          id </code> 
     *  @throws osid_NotFoundException a <code> Log </code> identified by 
     *          <code> Id is </code> not found 
     *  @throws osid_NullArgumentException <code> logId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDescendantLogs(osid_id_Id $logId, $levels);

}
