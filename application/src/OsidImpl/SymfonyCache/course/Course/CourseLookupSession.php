<?php

namespace Catalog\OsidImpl\SymfonyCache\course\Course;

use Catalog\OsidImpl\SymfonyCache\CachableSession;

/**
 *  <p>This session defines methods for retrieving courses. A <code> Course
 *  </code> is a canonical course listed in a course catalog. A <code>
 *  CourseOffering </code> is derived from a <code> Course </code> and maps to
 *  an offering time and registered students. </p>.
 *
 *  <p> This lookup session defines several views: </p>
 *
 *  <p>
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered
 *      </li>
 *      <li> plenary view: provides a complete result set or is an error
 *      condition </li>
 *      <li> isolated course catalog view: All course methods in this session
 *      operate, retrieve and pertain to courses defined explicitly in the
 *      current course catalog. Using an isolated view is useful for managing
 *      <code> Courses </code> with the <code> CourseAdminSession. </code>
 *      </li>
 *      <li> federated course catalog view: All course lookup methods in this
 *      session operate, retrieve and pertain to all courses defined in this
 *      course catalog and any other courses implicitly available in this
 *      course catalog through repository inheritence. </li>
 *  </ul>
 *  The methods <code> useFederatedCourseCatalogView() </code> and <code>
 *  useIsolatedCourseCatalogView() </code> behave as a radio group and one
 *  should be selected before invoking any lookup methods. Courses may have an
 *  additional records indicated by their respective record types. The record
 *  may not be accessed through a cast of the <code> Course. </code> </p>
 *
 * @copyright Copyright &copy; 2025, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class CourseLookupSession extends CachableSession implements \osid_course_CourseLookupSession
{
    private \osid_course_CourseLookupSession $session;

    /**
     * Constructor.
     *
     * @return void
     *
     * @since 4/10/09
     */
    public function __construct(\osid_course_CourseManager $manager, \osid_course_CourseLookupSession $session)
    {
        $this->session = $session;
        parent::__construct($manager);
    }

    /**
     * Helper method within the APC package.
     */
    public function getWrappedSession(): \osid_course_CourseLookupSession
    {
        return $this->session;
    }

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
     *  Tests if this user can perform <code> Course </code> lookups. A return
     *  of true does not guarantee successful authorization. A return of false
     *  indicates that it is known all methods in this session will result in
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an
     *  application that may not offer lookup operations to unauthorized
     *  users.
     *
     * @return bool <code> false </code> if lookup methods are not
     *                     authorized, <code> true </code> otherwise
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function canLookupCourses()
    {
        return $this->session->canLookupCourses();
    }

    /**
     *  The returns from the lookup methods may omit or translate elements
     *  based on this session, such as authorization, and not result in an
     *  error. This view is used when greater interoperability is desired at
     *  the expense of precision.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useComparativeCourseView()
    {
        return $this->session->useComparativeCourseView();
    }

    /**
     *  A complete view of the <code> Course </code> returns is desired.
     *  Methods will return what is requested or result in an error. This view
     *  is used when greater precision is desired at the expense of
     *  interoperability.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function usePlenaryCourseView()
    {
        return $this->session->usePlenaryCourseView();
    }

    /**
     *  Federates the view for methods in this session. A federated view will
     *  include courses in catalogs which are children of this catalog in the
     *  course catalog hierarchy.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useFederatedCourseCatalogView()
    {
        return $this->session->useFederatedCourseCatalogView();
    }

    /**
     *  Isolates the view for methods in this session. An isolated view
     *  restricts retrievals to this course catalog only.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useIsolatedCourseCatalogView()
    {
        return $this->session->useIsolatedCourseCatalogView();
    }

    /**
     *  Gets the <code> Course </code> specified by its <code> Id. </code> In
     *  plenary mode, the exact <code> Id </code> is found or a <code>
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Course
     *  </code> may have a different <code> Id </code> than requested, such as
     *  the case where a duplicate <code> Id </code> was assigned to a <code>
     *  Course </code> and retained for compatibility.
     *
     *  @param object \osid_id_Id $courseId the <code> Id </code> of the <code>
     *          Course </code> to rerieve
     *
     * @return object \osid_course_Course the returned <code> Course </code>
     *
     * @throws \osid_NotFoundException            no <code> Course </code> found with the
     *                                            given <code> Id </code>
     * @throws \osid_NullArgumentException <code> courseId </code> is <code>
     *                                            null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure
     * @throws \osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourse(\osid_id_Id $courseId)
    {
        return new Course($this, $courseId);
    }

    /**
     *  Gets a <code> CourseList </code> corresponding to the given <code>
     *  IdList. </code> In plenary mode, the returned list contains all of the
     *  courses specified in the <code> Id </code> list, in the order of the
     *  list, including duplicates, or an error results if an <code> Id
     *  </code> in the supplied list is not found or inaccessible. Otherwise,
     *  inaccessible <code> Courses </code> may be omitted from the list and
     *  may present the elements in any order including returning a unique
     *  set.
     *
     *  @param object \osid_id_IdList $courseIdList the list of <code> Ids
     *          </code> to rerieve
     *
     * @return object \osid_course_CourseList the returned <code> Course list
     *                </code>
     *
     * @throws \osid_NotFoundException            an <code> Id was </code> not found
     * @throws \osid_NullArgumentException <code> courseIdList </code> is
     *                                            <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure
     * @throws \osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesByIds(\osid_id_IdList $courseIdList)
    {
        // Wrap course objects as APCu versions
        return new CourseList($this, $this->session->getCoursesByIds($courseIdList));
    }

    /**
     *  Gets a <code> CourseList </code> corresponding to the given course
     *  genus <code> Type </code> which does not include courses of types
     *  derived from the specified <code> Type. </code> In plenary mode, the
     *  returned list contains all known courses or an error results.
     *  Otherwise, the returned list may contain only those courses that are
     *  accessible through this session. In both cases, the order of the set
     *  is not specified.
     *
     *  @param object \osid_type_Type $courseGenusType a course genus type
     *
     * @return object \osid_course_CourseList the returned <code> Course list
     *                </code>
     *
     * @throws \osid_NullArgumentException <code> courseGenusType </code> is
     *                                            <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure
     * @throws \osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesByGenusType(\osid_type_Type $courseGenusType)
    {
        // Wrap course objects as APCu versions
        return new CourseList($this, $this->session->getCoursesByGenusType($courseGenusType));
    }

    /**
     *  Gets a <code> CourseList </code> corresponding to the given course
     *  genus <code> Type </code> and include any additional courses with
     *  genus types derived from the specified <code> Type. </code> In plenary
     *  mode, the returned list contains all known courses or an error
     *  results. Otherwise, the returned list may contain only those courses
     *  that are accessible through this session. In both cases, the order of
     *  the set is not specified.
     *
     *  @param object \osid_type_Type $courseGenusType a course genus type
     *
     * @return object \osid_course_CourseList the returned <code> Course list
     *                </code>
     *
     * @throws \osid_NullArgumentException <code> courseGenusType </code> is
     *                                            <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure
     * @throws \osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesByParentGenusType(\osid_type_Type $courseGenusType)
    {
        // Wrap course objects as APCu versions
        return new CourseList($this, $this->session->getCoursesByParentGenusType($courseGenusType));
    }

    /**
     *  Gets a <code> CourseList </code> containing the given course record
     *  <code> Type. </code> In plenary mode, the returned list contains all
     *  known courses or an error results. Otherwise, the returned list may
     *  contain only those courses that are accessible through this session.
     *  In both cases, the order of the set is not specified.
     *
     *  @param object \osid_type_Type $courseRecordType a course record type
     *
     * @return object \osid_course_CourseList the returned <code> CourseList
     *                list </code>
     *
     * @throws \osid_NullArgumentException <code> courseRecordType </code> is
     *                                            <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure
     * @throws \osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesByRecordType(\osid_type_Type $courseRecordType)
    {
        // Wrap course objects as APCu versions
        return new CourseList($this, $this->session->getCoursesByRecordType($courseRecordType));
    }

    /**
     *  WARNING: This method was not in the OSID trunk as of 2009-04-27. A
     *  ticket requesting the addition of this method is available at:
     *  http://oki.assembla.com/spaces/osid-dev/tickets/18-osid-course---No-way-to-map-Topics-to-Courses-or-CourseOfferings-
     *  Gets a <code> CourseList </code> corresponding to the given topic
     *  <code> Id </code> . In plenary mode, the returned list contains all
     *  known courses or an error results. Otherwise, the returned list may
     *  contain only those courses that are accessible through this session.
     *  In both cases, the order of the set is not specified.
     *
     *  @param object \osid_id_Id $topicId a topic id
     *
     * @return object \osid_course_CourseList the returned <code> Course list
     *                </code>
     *
     * @throws \osid_NullArgumentException <code> courseGenusType </code> is
     *                                            <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure
     * @throws \osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesByTopic(\osid_id_Id $topicId)
    {
        // Wrap course objects as APCu versions
        return new CourseList($this, $this->session->getCoursesByTopic($topicId));
    }

    /**
     *  Gets all <code> Courses. </code> In plenary mode, the returned list
     *  contains all known courses or an error results. Otherwise, the
     *  returned list may contain only those courses that are accessible
     *  through this session. In both cases, the order of the set is not
     *  specifed.
     *
     * @return object \osid_course_CourseList a list of <code> Courses </code>
     *
     * @throws \osid_OperationFailedException  unable to complete request
     * @throws \osid_PermissionDeniedException authorization failure
     * @throws \osid_IllegalStateException     this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourses()
    {
        // Wrap course objects as APCu versions
        return new CourseList($this, $this->session->getCourses());
    }
}
