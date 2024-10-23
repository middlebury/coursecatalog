<?php
/**
 * @since 4/9/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>This session provides methods for searching among <code> Topic </code>
 *  objects. The search query is constructed using the <code> TopicQuery
 *  </code> interface. <code> getTopicsByQuery() </code> is the basic search
 *  method and returns a list of <code> Topics. </code> A more advanced search
 *  may be performed with <code> getTopicsBySearch(). </code> It accepts a
 *  <code> TopicSearch </code> interface in addition to the query interface
 *  for the purpose of specifying additional options affecting the entire
 *  search, such as ordering. <code> getTopicsBySearch() </code> returns a
 *  <code> TopicsSearchResults </code> interface that can be used to access
 *  the resulting <code> TopicList </code> or be used to perform a search
 *  within the result set through <code> TopicSearch. </code> </p>.
 *
 *  <p> This session defines views that offer differing behaviors for
 *  searching. </p>
 *
 *  <p>
 *  <ul>
 *      <li> federated course catalog view: searches include topics in course
 *      catalogs of which this course catalog is an ancestor in the course
 *      catalog hierarchy </li>
 *      <li> isolated course catalog view: searches are restricted to topics
 *      in this course catalog </li>
 *  </ul>
 *  Topics may have a record query interface indicated by their respective
 *  record interface types. The record query interface is accessed via the
 *  <code> TopicQuery. </code> </p>
 */
class banner_course_Topic_Search_Session extends banner_course_AbstractSession implements osid_course_TopicSearchSession
{
    /**
     * Constructor.
     *
     * @return void
     *
     * @since 4/10/09
     */
    public function __construct(banner_course_CourseManagerInterface $manager, osid_id_Id $catalogId)
    {
        parent::__construct($manager, 'section.');

        $this->catalogId = $catalogId;
    }

    /**
     *  Gets the <code> CourseCatalog </code> <code> Id </code> associated
     *  with this session.
     *
     * @return object osid_id_Id the <code> CourseCatalog Id </code>
     *                associated with this session
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseCatalogId()
    {
        return $this->catalogId;
    }

    /**
     *  Gets the <code> CourseCatalog </code> associated with this session.
     *
     * @return object osid_course_CourseCatalog the course catalog
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     * @throws osid_IllegalStateException     this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseCatalog()
    {
        if (!isset($this->catalog)) {
            $lookup = $this->manager->getCourseCatalogLookupSession();
            $lookup->usePlenaryView();
            $this->catalog = $lookup->getCourseCatalog($this->getCourseCatalogId());
        }

        return $this->catalog;
    }

    /**
     *  Tests if this user can perform <code> Topic </code> lookups. A return
     *  of true does not guarantee successful authorization. A return of false
     *  indicates that it is known all methods in this session will result in
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an
     *  application that may not offer lookup operations to unauthorized
     *  users.
     *
     * @return boolean <code> false </code> if search methods are not
     *                        authorized, <code> true </code> otherwise
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function canSearchTopics()
    {
        return true;
    }

    /**
     *  Federates the view for methods in this session. A federated view will
     *  include topics in course catalogs which are children of this course
     *  catalog in the course catalog hierarchy.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useFederatedCourseCatalogView()
    {
        $this->useFederatedView();
    }

    /**
     *  Isolates the view for methods in this session. An isolated view
     *  restricts lookups to this course catalog only.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useIsolatedCourseCatalogView()
    {
        $this->useIsolatedView();
    }

    /**
     *  Gets a topic query interface.
     *
     * @return object osid_course_TopicQuery the topic query interface
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopicQuery()
    {
        return new banner_course_Topic_Search_Query($this);
    }

    /**
     *  Gets a list of <code> Topics </code> matching the given search
     *  interface.
     *
     *  @param object osid_course_TopicQuery $topicQuery the search query
     *
     * @return object osid_course_TopicList the returned <code> TopicList
     *                </code>
     *
     * @throws osid_NullArgumentException <code> topicQuery </code> is <code>
     *                                           null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_UnsupportedException <code>  topicQuery </code> is not of
     *                                           this service
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopicsByQuery(osid_course_TopicQuery $topicQuery)
    {
        return new banner_course_Topic_Search_List($this->manager->getDB(), $this, $this->getCourseCatalogId(), $topicQuery, $this->getTopicSearch());
    }

    /**
     *  Gets a topic search interface.
     *
     * @return object osid_course_TopicSearch the topic search interface
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopicSearch()
    {
        return new banner_course_Topic_Search_Search($this);
    }

    /**
     *  Gets a topic search order interface. The <code> TopicSearchOrder
     *  </code> is supplied to a <code> TopicSearch </code> to specify the
     *  ordering of results.
     *
     * @return object osid_course_TopicSearchOrder the topic search order
     *                interface
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopicSearchOrder()
    {
        return new banner_course_Topic_Search_Order($this);
    }

    /**
     *  Gets the search results matching the given search query using the
     *  given search.
     *
     *  @param object osid_course_TopicQuery $topicQuery the topic query
     *  @param object osid_course_TopicSearch $topicSearch the topic search
     *          interface
     *
     * @return object osid_course_TopicSearchResults the returned search
     *                results
     *
     * @throws osid_NullArgumentException <code> topicQuery </code> or <code>
     *                                           topicSearch </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_UnsupportedException <code>  topicQuery </code> or <code>
     *                                           topicSearch </code> is not of this service
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopicsBySearch(osid_course_TopicQuery $topicQuery,
        osid_course_TopicSearch $topicSearch)
    {
        return new banner_course_Topic_Search_List($this->manager->getDB(), $this, $this->getCourseCatalogId(), $topicQuery, $topicSearch);
    }
}
