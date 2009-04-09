<?php

/**
 * osid_logging_LogEntryQuery
 * 
 *     Specifies the OSID definition for osid_logging_LogEntryQuery.
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


/**
 *  <p>This is the query interface for searching log entries. Each method 
 *  specifies an <code> AND </code> term while multiple invocations of the 
 *  same method produce a nested <code> OR. </code> </p>
 * 
 * @package org.osid.logging
 */
interface osid_logging_LogEntryQuery
{


    /**
     *  Gets the string matching types supported. A string match type 
     *  specifies the syntax of the string query, such as matching a word or 
     *  including a wildcard or regular expression. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          string match types 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getStringMatchTypes();


    /**
     *  Tests if the given string matching type is supported. 
     *
     *  @param object osid_type_Type $searchType a <code> Type </code> 
     *          indicating a string match type 
     *  @return boolean <code> true </code> if the given Type is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsStringMatchType(osid_type_Type $searchType);


    /**
     *  Adds a keyword to match. Multiple keywords can be added to perform a 
     *  boolean OR among them. A keyword may be applied to any of the elements 
     *  defined in this object such as the display name, description or any 
     *  method defined in an interface implemented by this object. 
     *
     *  @param string $keyword keyword to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> keyword is </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> keyword </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchKeyword($keyword, osid_type_Type $stringMatchType, 
                                 $match);


    /**
     *  Matches a priority <code> Type </code> for the log entry. 
     *
     *  @param object osid_type_Type $priorityType <code> Type </code> to 
     *          match 
     *  @param boolean $match <code> true </code> if for a positive match, 
     *          <code> false </code> for negative match 
     *  @throws osid_NullArgumentException <code> priorityType </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchPriority(osid_type_Type $priorityType, $match);


    /**
     *  Matches a priority <code> Type </code> in this log entry. 
     *
     *  @param object osid_calendaring_DateTime $startTime start time 
     *  @param object osid_calendaring_DateTime $endTime end time 
     *  @param boolean $match <code> true </code> if for a positive match, 
     *          <code> false </code> for negative match 
     *  @throws osid_InvalidArgumentException <code> startTime </code> is 
     *          greater than <code> endTime </code> 
     *  @throws osid_NullArgumentException <code> startTime </code> or <code> 
     *          endTime </code> is <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchTimestamp(osid_calendaring_DateTime $startTime, 
                                   osid_calendaring_DateTime $endTime, $match);


    /**
     *  Matches an agent in this log entry. 
     *
     *  @param object osid_id_Id $agentId <code> Id </code> to match 
     *  @param boolean $match <code> true </code> if for a positive match, 
     *          <code> false </code> for negative match 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAgentId(osid_id_Id $agentId, $match);


    /**
     *  Tests if an <code> AgentQuery </code> is available for querying 
     *  agents. 
     *
     *  @return boolean <code> true </code> if an agent query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAgentQuery();


    /**
     *  Gets the query interface for an agent. 
     *
     *  @return object osid_authentication_AgentQuery the agent query 
     *  @throws osid_UnimplementedException <code> supportsAgentQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAgentQuery() </code> is <code> true. </code> 
     */
    public function getAgentQuery();


    /**
     *  Matches the content type for this log entry. 
     *
     *  @param object osid_type_Type $contentType <code> Type </code> to match 
     *  @param boolean $match <code> true </code> if for a positive match, 
     *          <code> false </code> for negative match 
     *  @throws osid_NullArgumentException <code> contentType </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchContentType(osid_type_Type $contentType, $match);


    /**
     *  Tests if a content query is available. 
     *
     *  @return boolean <code> true </code> if a content query is available, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsContentQuery();


    /**
     *  Gets the content query record corresponding to the content interface 
     *  <code> Type </code> provided in the search session. Multiple 
     *  retrievale produce a nested boolean <code> OR </code> term. 
     *
     *  @return object the content query 
     *  @throws osid_UnimplementedException <code> supportsContentQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsContentQuery() </code> is <code> true. </code> 
     */
    public function getContentQuery();

}
