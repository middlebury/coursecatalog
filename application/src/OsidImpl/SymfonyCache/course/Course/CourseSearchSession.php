<?php

namespace Catalog\OsidImpl\SymfonyCache\course\Course;

use Catalog\OsidImpl\SymfonyCache\CachableSession;

/**
 *  <p>This session provides methods for searching among <code> Course </code>
 *  objects. The search query is constructed using the <code> CourseQuery
 *  </code> interface. <code> getCoursesByQuery() </code> is the basic search
 *  method and returns a list of <code> Courses. </code> A more advanced
 *  search may be performed with <code> getCoursesBySearch(). </code> It
 *  accepts a <code> CourseSearch </code> interface in addition to the query
 *  interface for the purpose of specifying additional options affecting the
 *  entire search, such as ordering. <code> getCoursesBySearch() </code>
 *  returns a <code> CourseSearchResults </code> interface that can be used to
 *  access the resulting <code> CourseList </code> or be used to perform a
 *  search within the result set through <code> CourseSearch. </code> </p>.
 *
 *  <p> This session defines views that offer differing behaviors for
 *  searching. </p>
 *
 *  <p>
 *  <ul>
 *      <li> federated course catalog view: searches include courses in course
 *      catalogs of which this course catalog is an ancestor in the course
 *      catalog hierarchy </li>
 *      <li> isolated course catalog view: searches are restricted to courses
 *      in this course catalog </li>
 *  </ul>
 *  Courses may have a record query interface indicated by their respective
 *  record interface types. The record query interface is accessed via the
 *  <code> CourseQuery. </code> </p>
 */
class CourseSearchSession extends CachableSession implements \osid_course_CourseSearchSession
{
    private \osid_course_CourseSearchSession $session;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(\osid_course_CourseManager $manager, \osid_course_CourseSearchSession $session)
    {
        $this->session = $session;
        parent::__construct($manager);
    }

    /*********************************************************
     * Methods from \osid_course_CourseSearchSession
     *********************************************************/
    /**
     *  Gets the <code> CourseCatalog </code> <code> Id </code> associated
     *  with this session.
     *
     * @return object \osid_id_Id the <code> CourseCatalog Id </code>
     *                associated with this session
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseCatalogId()
    {
        return $this->session->getCourseCatalogId();
    }

    /**
     *  Gets the <code> CourseCatalog </code> associated with this session.
     *
     * @return object \osid_course_CourseCatalog the course catalog
     *
     * @throws \osid_OperationFailedException  unable to complete request
     * @throws \osid_PermissionDeniedException authorization failure
     * @throws \osid_IllegalStateException     this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseCatalog()
    {
        return $this->session->getCourseCatalog();
    }

    /**
     *  Tests if this user can perform <code> Course </code> searches. A
     *  return of true does not guarantee successful authorization. A return
     *  of false indicates that it is known all methods in this session will
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a
     *  hint to an application that may opt not to offer search operations to
     *  unauthorized users.
     *
     * @return bool <code> false </code> if search methods are not
     *                     authorized, <code> true </code> otherwise
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function canSearchCourses()
    {
        return $this->session->canSearchCourses();
    }

    /**
     *  Federates the view for methods in this session. A federated view will
     *  include courses in course catalog which are children of this course
     *  catalog in the course catalog hierarchy.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useFederatedCourseCatalogView()
    {
        $this->session->useFederatedCourseCatalogView();
    }

    /**
     *  Isolates the view for methods in this session. An isolated view
     *  restricts lookups to this course catalog only.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useIsolatedCourseCatalogView()
    {
        $this->session->useIsolatedCourseCatalogView();
    }

    /**
     *  Gets a course query interface.
     *
     * @return object \osid_course_CourseQuery the course query interface
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseQuery()
    {
        return $this->session->getCourseQuery();
    }

    /**
     *  Gets a list of <code> Courses </code> matching the given search
     *  interface.
     *
     *  @param object \osid_course_CourseQuery $courseQuery the search query
     *
     * @return object \osid_course_CourseList the returned <code> CourseList
     *                </code>
     *
     * @throws \osid_NullArgumentException <code> courseQuery </code> is
     *                                            <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure
     * @throws \osid_UnsupportedException <code>  courseQuery </code> is not of
     *                                            this service
     * @throws \osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesByQuery(\osid_course_CourseQuery $courseQuery)
    {
        // Wrap course objects as cachable versions
        return new CourseList(
            $this->manager->getCourseLookupSessionForCatalog($this->getCourseCatalogId()),
            $this->session->getCoursesByQuery($courseQuery),
        );
    }

    /**
     *  Gets a course search interface.
     *
     * @return object \osid_course_CourseSearch the course search interface
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseSearch()
    {
        return $this->session->getCourseSearch();
    }

    /**
     *  Gets a course search order interface. The <code> CourseSearchOrder
     *  </code> is supplied to a <code> CourseSearch </code> to specify the
     *  ordering of results.
     *
     * @return object \osid_course_CourseSearchOrder the course search order
     *                interface
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseSearchOrder()
    {
        return $this->session->getCourseSearchOrder();
    }

    /**
     *  Gets the search results matching the given search query using the
     *  given search.
     *
     *  @param object \osid_course_CourseQuery $courseQuery the course query
     *  @param object \osid_course_CourseSearch $courseSearch the course search
     *          interface
     *
     * @return object \osid_course_CourseSearchResults the returned search
     *                results
     *
     * @throws \osid_NullArgumentException <code> courseQuery </code> or
     *                                            <code> courseSearch </code> is <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure
     * @throws \osid_UnsupportedException <code>  courseQuery </code> or <code>
     *                                            courseSearch </code> is not of this service
     * @throws \osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesBySearch(\osid_course_CourseQuery $courseQuery,
        \osid_course_CourseSearch $courseSearch)
    {
        // Wrap course objects as cachable versions
        return new CourseSearchResults(
            $this->manager->getCourseLookupSessionForCatalog($this->getCourseCatalogId()),
            $this->session->getCoursesBySearch($courseQuery, $courseSearch)
        );
    }
}
