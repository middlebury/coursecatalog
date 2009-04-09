<?php

/**
 * osid_authentication_AgentSearchSession
 * 
 *     Specifies the OSID definition for osid_authentication_AgentSearchSession.
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
 *  <p>This session provides methods for searching <code> Agent </code> 
 *  objects. The search query is constructed using the <code> AgentQuery 
 *  </code> interface. The agent interface <code> Type </code> also specifies 
 *  the interface for the agent query. </p> 
 *  
 *  <p> <code> getAgentsByQuery() </code> is the basic search method and 
 *  returns a list of <code> Agents. </code> A more advanced search may be 
 *  performed with <code> getAgentsBySearch(). </code> It accepts an <code> 
 *  AgentSearch </code> interface in addition to the query interface for the 
 *  purpose of specifying additional options affecting the entire search, such 
 *  as ordering. <code> getAgentsBySearch() </code> returns an <code> 
 *  AgentSearchResults </code> interface that can be used to access the 
 *  resulting <code> AgentList </code> or be used to perform a search within 
 *  the result set through <code> AgentSearch. </code> Agents may have a 
 *  record query interface indicated by their respective record types. The 
 *  record query interface is accessed via the <code> AgentQuery. </code> </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_AgentSearchSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> Agent </code> searches. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer search operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if search methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canSearchAgents();


    /**
     *  Gets an agent query interface. 
     *
     *  @return object osid_authentication_AgentQuery the agent query 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentQuery();


    /**
     *  Gets a list of <code> Agents </code> matching the given query 
     *  interface. 
     *
     *  @param object osid_authentication_AgentQuery $agentQuery the search 
     *          query 
     *  @return object osid_authentication_AgentList the returned <code> 
     *          AgentList </code> 
     *  @throws osid_NullArgumentException <code> agentQuery </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> AgentQuery </code> is not of 
     *          this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentsByQuery(osid_authentication_AgentQuery $agentQuery);


    /**
     *  Gets an agent search interface. 
     *
     *  @return object osid_authentication_AgentSearch the agent search 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentSearch();


    /**
     *  Gets an agent search order interface. The <code> AgentSearchOrder 
     *  </code> is supplied to an <code> AgentSearch </code> to specify the 
     *  ordering of results. 
     *
     *  @return object osid_authentication_AgentSearchOrder the agent search 
     *          order interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentSearchOrder();


    /**
     *  Gets the search results matching the given search query using the 
     *  given search. 
     *
     *  @param object osid_authentication_AgentQuery $agentQuery the search 
     *          query 
     *  @param object osid_authentication_AgentSearch $agentSearch the search 
     *          interface 
     *  @return object osid_authentication_AgentSearchResults the returned 
     *          search results 
     *  @throws osid_NullArgumentException <code> agentQuery </code> or <code> 
     *          agentSearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> agentSearch </code> or <code> 
     *          agentQuery </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentsBySearch(osid_authentication_AgentQuery $agentQuery, 
                                      osid_authentication_AgentSearch $agentSearch);

}
