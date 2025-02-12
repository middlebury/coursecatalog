<?php

/**
 * @since 5/04/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>This session provides methods for searching among <code> CourseOffering
 *  </code> objects. The search query is constructed using the <code>
 *  CourseOfferingQuery </code> interface. <code> getCourseOfferingsByQuery()
 *  </code> is the basic search method and returns a list of <code>
 *  CourseOfferings. </code> A more advanced search may be performed with
 *  <code> getCourseOfferingsBySearch(). </code> It accepts a <code>
 *  CourseOfferingSearch </code> interface in addition to the query interface
 *  for the purpose of specifying additional options affecting the entire
 *  search, such as ordering. <code> getCourseOfferingsBySearch() </code>
 *  returns a <code> CourseOfferingSearchResults </code> interface that can be
 *  used to access the resulting <code> CourseOfferingList </code> or be used
 *  to perform a search within the result set through <code>
 *  CourseOfferingSearch. </code> </p>.
 *
 *  <p> This session defines views that offer differing behaviors for
 *  searching. </p>
 *
 *  <p>
 *  <ul>
 *      <li> federated course catalog view: searches include course offerings
 *      in course catalogs of which this course catalog is an ancestor in the
 *      course catalog hierarchy </li>
 *      <li> isolated course catalog view: searches are restricted to course
 *      offerings in this course catalog </li>
 *  </ul>
 *  Course Offerings may have a record query interface indicated by their
 *  respective record interface types. The record query interface is accessed
 *  via the <code> CourseOfferingQuery. </code> </p>
 */
class banner_course_CourseOffering_Search_CombinedSession extends banner_course_CourseOffering_Search_Session
{
    /**
     * Constructor.
     *
     * @return void
     *
     * @since 4/10/09
     */
    public function __construct(banner_course_CourseManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getCombinedCatalogId());
    }

    /**
     *  Gets a list of <code> Courses </code> matching the given search
     *  interface.
     *
     *  @param object osid_course_CourseOfferingQuery $courseQuery the search
     *          query
     *
     * @return object osid_course_CourseOfferingList the returned <code>
     *                CourseOfferingList </code>
     *
     * @throws osid_NullArgumentException <code> courseOfferingQuery </code>
     *                                           is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_UnsupportedException <code>  courseOfferingQuery </code>
     *                                           is not of this service
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsByQuery(osid_course_CourseOfferingQuery $courseQuery)
    {
        if ($this->usesIsolatedView()) {
            throw new osid_NotFoundException('This catalog does not directly contain any courses. Use useFederatedView() to access courses in child catalogs.');
        }

        return parent::getCourseOfferingsByQuery($courseQuery);
    }

    /**
     *  Gets the search results matching the given search query using the
     *  given search.
     *
     *  @param object osid_course_CourseOfferingQuery $courseOfferingQuery the
     *          course offering query
     *  @param object osid_course_CourseOfferingSearch $courseOfferingSearch
     *          the course offering search interface
     *
     * @return object osid_course_CourseOfferingSearchResults the returned
     *                search results
     *
     * @throws osid_NullArgumentException <code> courseOfferingQuery </code>
     *                                           or <code> courseOfferingSearch </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_UnsupportedException <code>  courseOfferingQuery </code>
     *                                           or <code> courseOfferingSearch </code> is not of this service
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsBySearch(osid_course_CourseOfferingQuery $courseOfferingQuery,
        osid_course_CourseOfferingSearch $courseOfferingSearch)
    {
        if ($this->usesIsolatedView()) {
            throw new osid_NotFoundException('This catalog does not directly contain any courses. Use useFederatedView() to access courses in child catalogs.');
        }

        return parent::getCourseOfferingsBySearch($courseOfferingQuery, $courseOfferingSearch);
    }
}
