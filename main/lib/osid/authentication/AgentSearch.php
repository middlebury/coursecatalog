<?php

/**
 * osid_authentication_AgentSearch
 * 
 *     Specifies the OSID definition for osid_authentication_AgentSearch.
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

require_once(dirname(__FILE__)."/../OsidSearch.php");

/**
 *  <p>AgentSearch defines the interface for specifying agent search options. 
 *  This eample gets a limited set of squid-like agents. <code> </code> </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       AgentSearch as = session.getAgentSearch();
 *       as.limitResultSet(25, 50);
 *       
 *       AgentQuery queries[1];
 *       queries[0] = session.getAgentQuery();
 *       String kword = "squid";
 *       queries[0].matchKeywords(kword, true);
 *       
 *       AgentSearchResults results = session.getAgentsBySearch(queries, as);
 *       AgentList list = results.getAgents();
 *       
 *                   
 *       
 *  </pre>
 *  </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_AgentSearch
    extends osid_OsidSearch
{


    /**
     *  Execute this search using a previous search result. 
     *
     *  @param object osid_authentication_AgentSearchResults $results results 
     *          from a query 
     *  @throws osid_InvalidArgumentException <code> results </code> is not 
     *          valid 
     *  @throws osid_NullArgumentException <code> results </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchWithinAgentResults(osid_authentication_AgentSearchResults $results);


    /**
     *  Execute this search among the given list of agents. 
     *
     *  @param object osid_id_IdList $agentIds list of agents 
     *  @throws osid_NullArgumentException <code> agentIds </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchAmongAgents(osid_id_IdList $agentIds);


    /**
     *  Specify an ordering to the search results. 
     *
     *  @param object osid_authentication_AgentSearchOrder $agentSearchOrder 
     *          agent search order 
     *  @throws osid_NullArgumentException <code> agentSearchOrder </code> is 
     *          <code> null </code> 
     *  @throws osid_UnsupportedException <code> agentSearchOrder </code> is 
     *          not of this service 
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderAgentResults(osid_authentication_AgentSearchOrder $agentSearchOrder);


    /**
     *  Gets the record corresponding to the given agent search record <code> 
     *  Type. </code> This method must be used to retrieve an object 
     *  implementing the requested record interface along with all of its 
     *  ancestor interfaces. 
     *
     *  @param object osid_type_Type $agentSearchRecordType an agent search 
     *          record type 
     *  @return object osid_authentication_AgentSearchRecord the agent search 
     *          interface 
     *  @throws osid_NullArgumentException <code> agentSearchRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasSearchRecordType(agentSearchRecordType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentSearchRecord(osid_type_Type $agentSearchRecordType);

}
