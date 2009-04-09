<?php

/**
 * osid_resource_ResourceQuery
 * 
 *     Specifies the OSID definition for osid_resource_ResourceQuery.
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
 * @package org.osid.resource
 */

require_once(dirname(__FILE__)."/../OsidQuery.php");

/**
 *  <p>This is the query interface for searching resources. Each method 
 *  specifies an <code> AND </code> term while multiple invocations of the 
 *  same method produce a nested <code> OR. </code> </p>
 * 
 * @package org.osid.resource
 */
interface osid_resource_ResourceQuery
    extends osid_OsidQuery
{


    /**
     *  Matches resources that are also groups. 
     *
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchGroup($match);


    /**
     *  Sets the agent <code> Id </code> for this query. 
     *
     *  @param object osid_id_Id $agentId the agent <code> Id </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAgentId(osid_id_Id $agentId, $match);


    /**
     *  Matches resources with any agent. 
     *
     *  @param boolean $match <code> true </code> to match any agent, <code> 
     *          false </code> to match resources with no agent 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyAgent($match);


    /**
     *  Tests if an <code> AgentQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if an agent query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAgentQuery();


    /**
     *  Gets the query interface for an agent. Multiple retrievals produce a 
     *  nested <code> OR </code> term. 
     *
     *  @return object osid_authentication_AgentQuery the agent query 
     *  @throws osid_UnimplementedException <code> supportsAgentQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAgentQuery() </code> is <code> true. </code> 
     */
    public function getAgentQuery();


    /**
     *  Sets the bin <code> Id </code> for this query. 
     *
     *  @param object osid_id_Id $binId the bin <code> Id </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> binId </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchBinId(osid_id_Id $binId, $match);


    /**
     *  Tests if a <code> BinQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a bin query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsBinQuery();


    /**
     *  Gets the query interface for a bin. Multiple retrievals produce a 
     *  nested <code> OR </code> term. 
     *
     *  @return object osid_resource_BinQuery the bin query 
     *  @throws osid_UnimplementedException <code> supportsBinQuery() </code> 
     *          is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsBinQuery() </code> is <code> true. </code> 
     */
    public function getBinQuery();


    /**
     *  Gets the record query interface corresponding to the given <code> 
     *  Resource </code> record <code> Type. </code> Multiple retrievals 
     *  produce a nested <code> OR </code> term. 
     *
     *  @param object osid_type_Type $resourceRecordType a resource record 
     *          type 
     *  @return object osid_resource_ResourceQueryRecord the resource query 
     *          record 
     *  @throws osid_NullArgumentException <code> resourceRecordType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(resourceRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceQueryRecord(osid_type_Type $resourceRecordType);

}
