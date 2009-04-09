<?php

/**
 * osid_filing_DirectoryEntryQuery
 * 
 *     Specifies the OSID definition for osid_filing_DirectoryEntryQuery.
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
 * @package org.osid.filing
 */


/**
 *  <p><code> DirectoryEntryQuery </code> defines methods in common to both 
 *  <code> FileQuery </code> and <code> DirectoryQuery. </code> </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_DirectoryEntryQuery
{


    /**
     *  Gets the string matching types supported. 
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
     *  Matches entry names. Supplying multiple strings behaves like a boolean 
     *  <code> AND </code> among the elements each which must correspond to 
     *  the <code> stringMatchType. </code> An <code> OR </code> can be 
     *  performed with multiple query interfaces. 
     *
     *  @param string $name name to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> name </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> name </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchName($name, osid_type_Type $stringMatchType, $match);


    /**
     *  Matches an absolute pathname of a directory entry. Supplying multiple 
     *  strings behaves like a boolean <code> AND </code> among the elements 
     *  each which must correspond to the <code> stringMatchType. </code> An 
     *  <code> OR </code> can be performed with multiple query interfaces. 
     *
     *  @param string $path path to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> name </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> path </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchPath($path, osid_type_Type $stringMatchType, $match);


    /**
     *  Tests if a <code> DirectoryQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a directory query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDirectoryQuery();


    /**
     *  Gets the query interface for a directory to match the parent 
     *  directory. There is only one <code> DirectoryQuery </code> per <code> 
     *  DifrectoryEntryQuery. </code> Multiple retrievals return the same 
     *  object. 
     *
     *  @return object osid_filing_DirectoryQuery the directory query 
     *  @throws osid_UnimplementedException <code> supportsDirectoryQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDirectoryQuery() </code> is <code> true. </code> 
     */
    public function getDirectoryQuery();


    /**
     *  Matches aliases only. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAliases();


    /**
     *  Matches target files only, ommitting aliases. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchTargets();


    /**
     *  Matches both targets and aliases. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAll();


    /**
     *  Matches files whose entries are owned by the given agent id. 
     *
     *  @param object osid_id_Id $agentId the agent <code> Id </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchOwnerId(osid_id_Id $agentId, $match);


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
     *  Match directory entries that are created between the specified time 
     *  period. 
     *
     *  @param object osid_calendaring_DateTime $start start time of the query 
     *  @param object osid_calendaring_DateTime $end end time of the query 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> end </code> is les than 
     *          <code> start </code> 
     *  @throws osid_NullArgumentException <code> start </code> or <code> end 
     *          </code> is <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCreatedTime(osid_calendaring_DateTime $start, 
                                     osid_calendaring_DateTime $end, $match);


    /**
     *  Match directory entries that are modified between the specified time 
     *  period. 
     *
     *  @param object osid_calendaring_DateTime $start start time of the query 
     *  @param object osid_calendaring_DateTime $end end time of the query 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> end </code> is les than 
     *          <code> start </code> 
     *  @throws osid_NullArgumentException <code> start </code> or <code> end 
     *          </code> is <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchModifiedTime(osid_calendaring_DateTime $start, 
                                      osid_calendaring_DateTime $end, $match);


    /**
     *  Match directory entries that were last accessed between the specified 
     *  time period. 
     *
     *  @param object osid_calendaring_DateTime $start start time of the query 
     *  @param object osid_calendaring_DateTime $end end time of the query 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> end </code> is les than 
     *          <code> start </code> 
     *  @throws osid_NullArgumentException <code> start </code> or <code> end 
     *          </code> is <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchLastAccessTime(osid_calendaring_DateTime $start, 
                                        osid_calendaring_DateTime $end, $match);


    /**
     *  Sets a <code> Type </code> for querying objects of a given genus. 
     *
     *  @param object osid_type_Type $genusType the object genus type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> genusType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchGenusType(osid_type_Type $genusType, $match);


    /**
     *  Sets a <code> Type </code> for querying objects of a given record 
     *  type. 
     *
     *  @param object osid_type_Type $recordType the entry record type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchRecordType(osid_type_Type $recordType, $match);


    /**
     *  Tests if this query supports the given record <code> Type. </code> The 
     *  given record type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the record interface. 
     *
     *  @param object osid_type_Type $recordType a type 
     *  @return boolean <code> true </code> if a record query of the given 
     *          record <code> Type </code> is available, <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasRecordType(osid_type_Type $recordType);

}
