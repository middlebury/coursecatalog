<?php
/**
 * @since 6/11/09
 *
 * @copyright Copyright &copy {
 * throw new osid_UnimplementedException;
 * } 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * <##>.
 *
 * @since 6/11/09
 *
 * @copyright Copyright &copy {
 * throw new osid_UnimplementedException;
 * } 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_Topic_Search_Search extends banner_course_AbstractSearch implements osid_course_TopicSearch
{
    /*********************************************************
     * Methods from osid_OsidSearch
     *********************************************************/
    /**
     *  By default, searches return all matching results. This method
     *  restricts the number of results by setting the start and end of the
     *  result set, starting from 1. The starting and ending results can be
     *  used for paging results when a certain ordering is requested. The
     *  ending position must be greater than the starting position.
     *
     * @param int $start the start of the result set
     * @param int $end   the end of the result set
     *
     * @throws osid_InvalidArgumentException <code> end </code> is less than
     *                                              or equal to <code> start </code>
     * @throws osid_NullArgumentException           null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function limitResultSet($start, $end)
    {
        return parent::limitResultSet($start, $end);
    }

    /**
     *  Tests if this search supports the given record <code> Type. </code>
     *  The given record type may be supported by the object through
     *  interface/type inheritence. This method should be checked before
     *  retrieving the record interface.
     *
     *  @param object osid_type_Type $searchRecordType a type
     *
     * @return boolean <code> true </code> if a search record the given
     *                        record <code> Type </code> is available, <code> false </code>
     *                        otherwise
     *
     * @throws osid_NullArgumentException <code> searchRecordType </code> is
     *                                           <code> null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function hasSearchRecordType(osid_type_Type $searchRecordType)
    {
        return false;
    }

    /*********************************************************
     * Methods from osid_course_TopicSearch
     *********************************************************/

    /**
     *  Execute this search using a previous search result.
     *
     *  @param object osid_course_TopicSearchResults $results results from a
     *          query
     *
     * @throws osid_InvalidArgumentException <code> results </code> is not
     *                                              valid
     * @throws osid_NullArgumentException <code>    results </code> is <code>
     *                                              null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function searchWithinTopicResults(osid_course_TopicSearchResults $results)
    {
    }

    /**
     *  Execute this search among the given list of topics.
     *
     *  @param object osid_id_IdList $topicIds list of topics
     *
     * @throws osid_NullArgumentException <code> topicIds </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function searchAmongTopics(osid_id_IdList $topicIds)
    {
    }

    /**
     *  Specify an ordering to the search results.
     *
     *  @param object osid_course_TopicSearchOrder $topicSearchOrder topic
     *          search order
     *
     * @throws osid_NullArgumentException <code> topicSearchOrder </code> is
     *                                           <code> null </code>
     * @throws osid_UnsupportedException <code>  topicSearchOrder </code> is
     *                                           not of this service
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderTopicResults(osid_course_TopicSearchOrder $topicSearchOrder)
    {
        $this->order = $topicSearchOrder;
    }

    /**
     *  Gets the record corresponding to the given topic search record <code>
     *  Type. </code> This method must be used to retrieve an object
     *  implementing the requested record interface along with all of its
     *  ancestor interfaces.
     *
     *  @param object osid_type_Type $topicSearchRecordType a topic search
     *          record type
     *
     * @return object osid_course_TopicSearchRecord the topic search
     *                interface
     *
     * @throws osid_NullArgumentException <code> topicSearchRecordType
     *                                           </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasSearchRecordType(topicSearchRecordType) </code> is <code>
     *                                           false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopicSearchRecord(osid_type_Type $topicSearchRecordType)
    {
        throw new osid_UnsupportedException('The TopicSearchRecordType passed is not supported.');
    }
}
