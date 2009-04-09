<?php

/**
 * osid_course_TopicSearch
 * 
 *     Specifies the OSID definition for osid_course_TopicSearch.
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

require_once(dirname(__FILE__)."/../OsidSearch.php");

/**
 *  <p>The search interface for governing topic searches. </p>
 * 
 * @package org.osid.course
 */
interface osid_course_TopicSearch
    extends osid_OsidSearch
{


    /**
     *  Execute this search using a previous search result. 
     *
     *  @param object osid_course_TopicSearchResults $results results from a 
     *          query 
     *  @throws osid_InvalidArgumentException <code> results </code> is not 
     *          valid 
     *  @throws osid_NullArgumentException <code> results </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchWithinTopicResults(osid_course_TopicSearchResults $results);


    /**
     *  Execute this search among the given list of topics. 
     *
     *  @param object osid_id_IdList $topicIds list of topics 
     *  @throws osid_NullArgumentException <code> topicIds </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchAmongTopics(osid_id_IdList $topicIds);


    /**
     *  Specify an ordering to the search results. 
     *
     *  @param object osid_course_TopicSearchOrder $topicSearchOrder topic 
     *          search order 
     *  @throws osid_NullArgumentException <code> topicSearchOrder </code> is 
     *          <code> null </code> 
     *  @throws osid_UnsupportedException <code> topicSearchOrder </code> is 
     *          not of this service 
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderTopicResults(osid_course_TopicSearchOrder $topicSearchOrder);


    /**
     *  Gets the record corresponding to the given topic search record <code> 
     *  Type. </code> This method must be used to retrieve an object 
     *  implementing the requested record interface along with all of its 
     *  ancestor interfaces. 
     *
     *  @param object osid_type_Type $topicSearchRecordType a topic search 
     *          record type 
     *  @return object osid_course_TopicSearchRecord the topic search 
     *          interface 
     *  @throws osid_NullArgumentException <code> topicSearchRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasSearchRecordType(topicSearchRecordType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicSearchRecord(osid_type_Type $topicSearchRecordType);

}
