<?php

/**
 * osid_authentication_AgentLookupSession
 * 
 *     Specifies the OSID definition for osid_authentication_AgentLookupSession.
 * 
 * Copyright (C) 2002-2008 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.authentication
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session provides methods for retrieving <code> Agent </code> 
 *  objects. The <code> Agent </code> represents the authenticated entity. 
 *  Agents generally map to resources although this isn't always the case. 
 *  </p> 
 *  
 *  <p> This session defines two views which offer differing behaviors when 
 *  retrieving multiple objects. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete and ordered result set or is an 
 *      error condition </li> 
 *  </ul>
 *  Generally, the comparative view should be used for most applications as it 
 *  permits operation even if there a particular element is inaccessible. For 
 *  example, a hierarchy output can be plugged into a lookup method to 
 *  retrieve all objects known to a hierarchy, but it may not be necessary to 
 *  break execution if a node from the hierarchy no longer exists. However, 
 *  some administrative applications may need to know whether it had retrieved 
 *  an entire set of objects and may sacrifice some interoperability for the 
 *  sake of precision. Agents may have an additional records indicated by 
 *  their respective record types. The record may not be accessed through a 
 *  cast of the <code> Agent. </code> </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_AgentLookupSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> Agent </code> lookups. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupAgents();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeAgentView();


    /**
     *  A complete view of the <code> Agent </code> returns is desired. 
     *  Methods will return what is requested or result in an error. This view 
     *  is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryAgentView();


    /**
     *  Gets the <code> Agent </code> specified by its <code> Id. </code> In 
     *  plenary mode, the exact <code> Id </code> is found or a <code> 
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Agent 
     *  </code> may have a different <code> Id </code> than requested, such as 
     *  the case where a duplicate <code> Id </code> was assigned to an <code> 
     *  Agent </code> and retained for compatibility. 
     *
     *  @param object osid_id_Id $agentId the <code> Id </code> of the <code> 
     *          Agent </code> to rerieve 
     *  @return object osid_authentication_Agent the returned <code> Agent 
     *          </code> 
     *  @throws osid_NotFoundException no <code> Agent </code> found with the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgent(osid_id_Id $agentId);


    /**
     *  Gets an <code> AgentList </code> corresponding to the given <code> 
     *  IdList. </code> In plenary mode, the returned list contains all of the 
     *  agents specified in the <code> Id </code> list, in the order of the 
     *  list, including duplicates, or an error results if an <code> Id 
     *  </code> in the supplied list is not found or inaccessible. Otherwise, 
     *  inaccessible <code> Agents </code> may be omitted from the list and 
     *  may present the elements in any order including returning a unique 
     *  set. 
     *
     *  @param object osid_id_IdList $agentIdList the list of <code> Ids 
     *          </code> to rerieve 
     *  @return object osid_authentication_AgentList the returned <code> Agent 
     *          list </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> agentIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentsByIds(osid_id_IdList $agentIdList);


    /**
     *  Gets an <code> AgentList </code> corresponding to the given agent 
     *  genus <code> Type </code> which does not include agents of genus types 
     *  derived from the specified <code> Type. </code> In plenary mode, the 
     *  returned list contains all known agents or an error results. 
     *  Otherwise, the returned list may contain only those agents that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @param object osid_type_Type $agentGenusType an agent genus type 
     *  @return object osid_authentication_AgentList the returned <code> Agent 
     *          list </code> 
     *  @throws osid_NullArgumentException <code> agentGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentsByGenusType(osid_type_Type $agentGenusType);


    /**
     *  Gets an <code> AgentList </code> corresponding to the given agent 
     *  genus <code> Type </code> and include any additional agents with genus 
     *  types derived from the specified <code> Type. </code> In plenary mode, 
     *  the returned list contains all known agents or an error results. 
     *  Otherwise, the returned list may contain only those agents that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @param object osid_type_Type $agentGenusType an agent genus type 
     *  @return object osid_authentication_AgentList the returned <code> Agent 
     *          list </code> 
     *  @throws osid_NullArgumentException <code> agentGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentsByParentGenusType(osid_type_Type $agentGenusType);


    /**
     *  Gets an <code> AgentList </code> containing the given repository 
     *  record <code> Type. </code> In plenary mode, the returned list 
     *  contains all known agents or an error results. Otherwise, the returned 
     *  list may contain only those agents that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $agentRecordType an agent record type 
     *  @return object osid_authentication_AgentList the returned <code> Agent 
     *          list </code> 
     *  @throws osid_NullArgumentException <code> agentRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentsByRecordType(osid_type_Type $agentRecordType);


    /**
     *  Gets all <code> Agents. </code> In plenary mode, the returned list 
     *  contains all known agents or an error results. Otherwise, the returned 
     *  list may contain only those agents that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @return object osid_authentication_AgentList a list of <code> Agents 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgents();

}
