<?php
/**
 * @since 4/21/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>This session provides methods to retrieve <code> Course </code> to
 *  <code> CourseCatalog </code> mappings. A <code> Course </code> may appear
 *  in multiple <code> CourseCatalog </code> objects. Each catalog may have
 *  its own authorizations governing who is allowed to look at it. </p>.
 *
 *  <p> This lookup session defines several views: </p>
 *
 *  <p>
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered
 *      </li>
 *      <li> plenary view: provides a complete result set or is an error
 *      condition </li>
 *  </ul>
 *  </p>
 */
class banner_course_Course_Catalog_Session extends banner_course_Course_AbstractSession implements osid_course_CourseCatalogSession
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
        parent::__construct($manager, 'catalog.');
    }

    /**
     *  The returns from the lookup methods may omit or translate elements
     *  based on this session, such as authorization, and not result in an
     *  error. This view is used when greater interoperability is desired at
     *  the expense of precision.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useComparativeCourseCatalogView()
    {
        $this->useComparativeView();
    }

    /**
     *  A complete view of the <code> Course </code> and <code> CourseCatalog
     *  </code> returns is desired. Methods will return what is requested or
     *  result in an error. This view is used when greater precision is
     *  desired at the expense of interoperability.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function usePlenaryCourseCatalogView()
    {
        $this->usePlenaryView();
    }

    /**
     *  Tests if this user can perform lookups of course/course catalog
     *  mappings. A return of true does not guarantee successful
     *  authorization. A return of false indicates that it is known lookup
     *  methods in this session will result in a <code> PERMISSION_DENIED.
     *  </code> This is intended as a hint to an application that may opt not
     *  to offer lookup operations to unauthorized users.
     *
     * @return boolean <code> false </code> if looking up mappings is not
     *                        authorized, <code> true </code> otherwise
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function canLookupCourseCatalogMappings()
    {
        return true;
    }

    /**
     *  Gets the list of <code> Course Ids </code> associated with a <code>
     *  CourseCatalog. </code>.
     *
     *  @param object osid_id_Id $courseCatalogId <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_id_IdList list of related course <code> Ids
     *                </code>
     *
     * @throws osid_NotFoundException <code>     courseCatalogId </code> is not
     *                                           found
     * @throws osid_NullArgumentException <code> courseCatalogId </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseIdsByCatalog(osid_id_Id $courseCatalogId)
    {
        $ids = [];
        $courses = $this->getCoursesByCatalog($courseCatalogId);
        while ($courses->hasNext()) {
            $ids[] = $courses->getNextCourse()->getId();
        }

        return new phpkit_id_ArrayIdList($ids);
    }

    /**
     *  Gets the list of <code> Courses </code> associated with a <code>
     *  CourseCatalog. </code>.
     *
     *  @param object osid_id_Id $courseCatalogId <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_CourseList list of related courses
     *
     * @throws osid_NotFoundException <code>     courseCatalogId </code> is not
     *                                           found
     * @throws osid_NullArgumentException <code> courseCatalogId </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesByCatalog(osid_id_Id $courseCatalogId)
    {
        $lookupSession = $this->manager->getCourseLookupSessionForCatalog($courseCatalogId);
        $lookupSession->useIsolatedView();
        if ($this->usesPlenaryView()) {
            $lookupSession->usePlenaryCourseView();
        } else {
            $lookupSession->useComparativeCourseView();
        }

        return $lookupSession->getCourses();
    }

    /**
     *  Gets the list of <code> Course Ids </code> corresponding to a list of
     *  <code> CourseCatalog </code> objects.
     *
     *  @param object osid_id_IdList $courseCatalogIdList list of course
     *          catalog <code> Ids </code>
     *
     * @return object osid_id_IdList list of course <code> Ids </code>
     *
     * @throws osid_NullArgumentException <code> courseCatalogIdList </code>
     *                                           is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseIdsByCatalogs(osid_id_IdList $courseCatalogIdList)
    {
        $idList = new phpkit_CombinedList('osid_id_IdList');
        while ($courseCatalogIdList->hasNext()) {
            try {
                $idList->addList($this->getCourseIdsByCatalog($courseCatalogIdList->getNextId()));
            } catch (osid_NotFoundException $e) {
                if ($this->usesPlenaryView()) {
                    throw $e;
                }
            }
        }

        return $idList;
    }

    /**
     *  Gets the list of <code> Courses </code> corresponding to a list of
     *  <code> CourseCatalog </code> objects.
     *
     *  @param object osid_id_IdList $courseCatalogIdList list of course
     *          catalog <code> Ids </code>
     *
     * @return object osid_course_CourseList list of courses
     *
     * @throws osid_NullArgumentException <code> courseCatalogIdList </code>
     *                                           is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesByCatalogs(osid_id_IdList $courseCatalogIdList)
    {
        $courseList = new phpkit_CombinedList('osid_course_CourseList');
        while ($courseCatalogIdList->hasNext()) {
            try {
                $courseList->addList($this->getCoursesByCatalog($courseCatalogIdList->getNextId()));
            } catch (osid_NotFoundException $e) {
                if ($this->usesPlenaryView()) {
                    throw $e;
                }
            }
        }

        return $courseList;
    }

    /**
     *  Gets the <code> CourseCatalog </code> <code> Ids </code> mapped to a
     *  <code> Course. </code>.
     *
     *  @param object osid_id_Id $courseId <code> Id </code> of a <code>
     *          Course </code>
     *
     * @return object osid_id_IdList list of course catalog <code> Ids
     *                </code>
     *
     * @throws osid_NotFoundException <code>     courseId </code> is not found
     * @throws osid_NullArgumentException <code> courseId </code> is <code>
     *                                           null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCatalogIdsByCourse(osid_id_Id $courseId)
    {
        $parameters = [
            ':subject_code' => $this->getSubjectFromCourseId($courseId),
            ':course_number' => $this->getNumberFromCourseId($courseId),
        ];
        $statement = $this->getGetCatalogsStatement();
        $statement->execute($parameters);

        $ids = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $ids[] = $this->getOsidIdFromString($row['catalog_id'], 'catalog.');
        }
        $statement->closeCursor();

        return new phpkit_id_ArrayIdList($ids);
    }

    /**
     *  Gets the <code> CourseCatalog </code> objects mapped to a <code>
     *  Course. </code>.
     *
     *  @param object osid_id_Id $courseId <code> Id </code> of a <code>
     *          Course </code>
     *
     * @return object osid_course_CourseCatalogList list of course catalogs
     *
     * @throws osid_NotFoundException <code>     courseId </code> is not found
     * @throws osid_NullArgumentException <code> courseId </code> is <code>
     *                                           null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCatalogsByCourse(osid_id_Id $courseId)
    {
        $parameters = [
            ':subject_code' => $this->getSubjectFromCourseId($courseId),
            ':course_number' => $this->getNumberFromCourseId($courseId),
        ];
        $statement = $this->getGetCatalogsStatement();
        $statement->execute($parameters);

        $catalogs = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $catalogs[] = new banner_course_CourseCatalog(
                $this->getOsidIdFromString($row['catalog_id'], 'catalog.'),
                $row['catalog_title']);
        }
        $statement->closeCursor();

        return new phpkit_course_ArrayCourseCatalogList($catalogs);
    }

    private static $getCatalogsByCourse_stmt;

    /**
     * Answer the statement for fetching catalogs.
     *
     * @return void
     *
     * @since 4/23/09
     */
    private function getGetCatalogsStatement()
    {
        if (!isset(self::$getCatalogsByCourse_stmt)) {
            self::$getCatalogsByCourse_stmt = $this->manager->getDB()->prepare(
                "SELECT
	course_catalog.catalog_id,
	catalog_title
FROM
	SCBCRSE
	LEFT JOIN course_catalog_college ON SCBCRSE_COLL_CODE = coll_code
	LEFT JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id
WHERE
	SCBCRSE_SUBJ_CODE = :subject_code
	AND SCBCRSE_CRSE_NUMB = :course_number
	AND SCBCRSE_CSTA_CODE NOT IN (
		'C', 'I', 'P', 'T', 'X'
	)
GROUP BY SCBCRSE_SUBJ_CODE , SCBCRSE_CRSE_NUMB, catalog_id
");
        }

        return self::$getCatalogsByCourse_stmt;
    }
}
