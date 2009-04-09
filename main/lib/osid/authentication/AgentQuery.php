<?php

/**
 * osid_authentication_AgentQuery
 * 
 *     Specifies the OSID definition for osid_authentication_AgentQuery.
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

require_once(dirname(__FILE__)."/../OsidQuery.php");

/**
 *  <p>This is the query interface for searching agents. Each method specifies 
 *  an <code> AND </code> term while multiple invocations of the same method 
 *  produce a nested <code> OR. </code> </p> 
 *  
 *  <p> The following example returns agents whose display name begins with 
 *  "Tom" and whose "login name" is "tom" or "tjcoppet" in an interface 
 *  specified by <code> companyAgentType. </code> 
 *  <pre>
 *       
 *       
 *       Agent Query query = session.getAgentQuery();
 *       
 *       query.matchDisplayName("Tom*", wildcardStringMatchType, true);
 *       
 *       companyAgentQuery = query.getAgentQueryRecord(companyAgentType);
 *       companyAgentQuery.matchLoginName("tom");
 *       companyAgentQuery = query.getAgentQueryRecord(companyAgentType);
 *       companyAgentQuery.matchLoginName("tjcoppet");
 *       
 *       AgentList agentList = session.getAgentsByQuery(query);
 *       
 *                   
 *       
 *  </pre>
 *  </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_AgentQuery
    extends osid_OsidQuery
{


    /**
     *  Matches agents with any key value. 
     *
     *  @return boolean <code> true </code> if to match agents with a key, 
     *          <code> false </code> to match agents with no key 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyKey();


    /**
     *  Tests if an <code> KeyQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a key query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsKeyQuery();


    /**
     *  Includes an agent query for making relations with <code> Keys. </code> 
     *  Multiple rerievals return separate query terms nested inside this 
     *  query term, each which are treated as a boolean <code> OR. </code> For 
     *  example, <code> AgentQuery.description AND (AgentQuery.KeyQuery1.name 
     *  OR AgentQuery.KeyQuery2.name) </code> 
     *
     *  @return object osid_authentication_KeyQuery the query extension 
     *  @throws osid_UnimplementedException <code> supportsKeyQuery() </code> 
     *          is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeyQuery();


    /**
     *  Gets the record query interface corresponding to the given <code> 
     *  Agent </code> record <code> Type. </code> Multiple retrievals produce 
     *  a nested <code> OR </code> term. 
     *
     *  @param object osid_type_Type $agentRecordType an agent record type 
     *  @return object osid_authentication_AgentQueryRecord the agent query 
     *          record 
     *  @throws osid_NullArgumentException <code> agentRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(agentRecordType) </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentQueryRecord(osid_type_Type $agentRecordType);

}
