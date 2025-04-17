<?php

namespace Catalog\OsidImpl\SymfonyCache\course\CourseOffering;

use Catalog\OsidImpl\SymfonyCache\CachableSession;

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
class CourseOfferingSearchSession extends CachableSession implements \osid_course_CourseOfferingSearchSession
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(
        \osid_course_CourseManager $manager,
        private \osid_course_CourseOfferingSearchSession $session,
    ) {
        parent::__construct($manager);
    }

    /*********************************************************
     * Methods from \osid_course_CourseOfferingSearchSession
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
     *  Tests if this user can perform <code> CourseOffering </code> lookups.
     *  A return of true does not guarantee successful authorization. A return
     *  of false indicates that it is known all methods in this session will
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a
     *  hint to an application that may not offer lookup operations to
     *  unauthorized users.
     *
     * @return bool <code> false </code> if search methods are not
     *                     authorized, <code> true </code> otherwise
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function canSearchCourseOfferings()
    {
        return $this->session->canSearchCourseOfferings();
    }

    /**
     *  Federates the view for methods in this session. A federated view will
     *  include course offerings in course catalog which are children of this
     *  course catalog in the course catalog hierarchy.
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
     *  Gets a course offering query interface.
     *
     * @return object \osid_course_CourseOfferingQuery the course offering
     *                query interface
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingQuery()
    {
        return $this->session->getCourseOfferingQuery();
    }

    /**
     *  Gets a list of <code> Courses </code> matching the given search
     *  interface.
     *
     *  @param object \osid_course_CourseOfferingQuery $courseQuery the search
     *          query
     *
     * @return object \osid_course_CourseOfferingList the returned <code>
     *                CourseOfferingList </code>
     *
     * @throws \osid_NullArgumentException <code> courseOfferingQuery </code>
     *                                            is <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure
     * @throws \osid_UnsupportedException <code>  courseOfferingQuery </code>
     *                                            is not of this service
     * @throws \osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsByQuery(\osid_course_CourseOfferingQuery $courseQuery)
    {
        // Wrap course objects as APCu versions
        return new CourseOfferingList(
            $this->manager->getCourseOfferingLookupSessionForCatalog($this->getCourseCatalogId()),
            $this->session->getCourseOfferingsByQuery($courseQuery),
        );
    }

    /**
     *  Gets a course offering search interface.
     *
     * @return object \osid_course_CourseOfferingSearch the course offering
     *                search interface
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingSearch()
    {
        return $this->session->getCourseOfferingSearch();
    }

    /**
     *  Gets a course search order interface. The <code>
     *  CourseOfferingSearchOrder </code> is supplied to a <code>
     *  CourseOfferingSearch </code> to specify the ordering of results.
     *
     * @return object \osid_course_CourseOfferingSearchOrder the course
     *                offering search order interface
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingSearchOrder()
    {
        return $this->session->getCourseOfferingSearchOrder();
    }

    /**
     *  Gets the search results matching the given search query using the
     *  given search.
     *
     *  @param object \osid_course_CourseOfferingQuery $courseOfferingQuery the
     *          course offering query
     *  @param object \osid_course_CourseOfferingSearch $courseOfferingSearch
     *          the course offering search interface
     *
     * @return object \osid_course_CourseOfferingSearchResults the returned
     *                search results
     *
     * @throws \osid_NullArgumentException <code> courseOfferingQuery </code>
     *                                            or <code> courseOfferingSearch </code> is <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure
     * @throws \osid_UnsupportedException <code>  courseOfferingQuery </code>
     *                                            or <code> courseOfferingSearch </code> is not of this service
     * @throws \osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsBySearch(\osid_course_CourseOfferingQuery $courseOfferingQuery,
        \osid_course_CourseOfferingSearch $courseOfferingSearch)
    {
        // Wrap course objects as APCu versions
        return new CourseOfferingSearchResults(
            $this->manager->getCourseOfferingLookupSessionForCatalog($this->getCourseCatalogId()),
            $this->session->getCourseOfferingsBySearch($courseOfferingQuery, $courseOfferingSearch)
        );
    }

    /*********************************************************
     * Support for building full-text indices
     *********************************************************/

    /**
     * Build or rebuild the full-text course-offering index.
     *
     * @param optional boolean $displayStatus If true, status output will be printed
     *
     * @return bool true on success
     */
    public function buildIndex($displayStatus = false)
    {
        // Pass through index building to our underlying implementation.
        return $this->session->buildIndex($displayStatus);
    }
}
