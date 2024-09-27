<?php
/**
 * @since 4/13/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

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
 * @since 4/13/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_Course_Lookup_Session extends banner_course_Course_AbstractSession implements osid_course_CourseLookupSession
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
        parent::__construct($manager, 'course.');

        $this->catalogId = $catalogId;
    }

    private $catalog;

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
     *  Tests if this user can perform <code> Course </code> lookups. A return
     *  of true does not guarantee successful authorization. A return of false
     *  indicates that it is known all methods in this session will result in
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an
     *  application that may not offer lookup operations to unauthorized
     *  users.
     *
     * @return boolean <code> false </code> if lookup methods are not
     *                        authorized, <code> true </code> otherwise
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function canLookupCourses()
    {
        return true;
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
        $this->useComparativeView();
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
        $this->usePlenaryView();
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
        $this->useFederatedView();
    }

    /**
     *  Isolates the view for methods in this session. An isolated view
     *  restricts retrievals to this course catalog only.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useIsolatedCourseCatalogView()
    {
        $this->useIsolatedView();
    }

    private static $getCourse_stmts = [];

    /**
     *  Gets the <code> Course </code> specified by its <code> Id. </code> In
     *  plenary mode, the exact <code> Id </code> is found or a <code>
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Course
     *  </code> may have a different <code> Id </code> than requested, such as
     *  the case where a duplicate <code> Id </code> was assigned to a <code>
     *  Course </code> and retained for compatibility.
     *
     *  @param object osid_id_Id $courseId the <code> Id </code> of the <code>
     *          Course </code> to rerieve
     *
     * @return object osid_course_Course the returned <code> Course </code>
     *
     * @throws osid_NotFoundException            no <code> Course </code> found with the
     *                                           given <code> Id </code>
     * @throws osid_NullArgumentException <code> courseId </code> is <code>
     *                                           null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourse(osid_id_Id $courseId)
    {
        $catalogWhere = $this->getCatalogWhereTerms();
        if (!isset(self::$getCourse_stmts[$catalogWhere])) {
            $query =
'SELECT
	SCBCRSE_SUBJ_CODE ,
	SCBCRSE_CRSE_NUMB ,
	SCBCRSE_EFF_TERM ,
	SCBCRSE_COLL_CODE ,
	SCBCRSE_DIVS_CODE ,
	SCBCRSE_DEPT_CODE ,
	SCBCRSE_CSTA_CODE ,
	SCBCRSE_TITLE ,
	SCBCRSE_CREDIT_HR_HIGH,
	SCBDESC_TEXT_NARRATIVE,
	has_alternates
FROM
	scbcrse_scbdesc_recent

WHERE
	SCBCRSE_SUBJ_CODE = :subject_code
	AND SCBCRSE_CRSE_NUMB = :course_number

	AND SCBCRSE_COLL_CODE IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			'.$this->getCatalogWhereTerms().'
	)
ORDER BY SCBCRSE_SUBJ_CODE ASC , SCBCRSE_CRSE_NUMB ASC
';
            self::$getCourse_stmts[$catalogWhere] = $this->manager->getDB()->prepare($query);
        }

        $courseIdString = $this->getDatabaseIdString($courseId, 'course.');

        $parameters = array_merge(
            [
                ':subject_code' => $this->getSubjectFromCourseId($courseId),
                ':course_number' => $this->getNumberFromCourseId($courseId),
            ],
            $this->getCatalogParameters());
        self::$getCourse_stmts[$catalogWhere]->execute($parameters);
        $row = self::$getCourse_stmts[$catalogWhere]->fetch(PDO::FETCH_ASSOC);
        self::$getCourse_stmts[$catalogWhere]->closeCursor();

        if (!$row || !($row['SCBCRSE_SUBJ_CODE'] && $row['SCBCRSE_CRSE_NUMB'])) {
            throw new osid_NotFoundException("Could not find a course matching the id-component '$courseIdString' for the catalog '".$this->getDatabaseIdString($this->getCourseCatalogId(), 'catalog.')."'.");
        }

        return new banner_course_Course(
            $this->getOsidIdFromString($row['SCBCRSE_SUBJ_CODE'].$row['SCBCRSE_CRSE_NUMB'], 'course.'),
            $row['SCBCRSE_SUBJ_CODE'].' '.$row['SCBCRSE_CRSE_NUMB'],
            (null === $row['SCBDESC_TEXT_NARRATIVE']) ? '' : $row['SCBDESC_TEXT_NARRATIVE'],	// Description
            $row['SCBCRSE_TITLE'],
            $row['SCBCRSE_CREDIT_HR_HIGH'],
            [
                $this->getOsidIdFromString($row['SCBCRSE_SUBJ_CODE'], 'topic.subject.'),
                $this->getOsidIdFromString($row['SCBCRSE_DEPT_CODE'], 'topic.department.'),
                $this->getOsidIdFromString($row['SCBCRSE_DIVS_CODE'], 'topic.division.'),
            ],
            $row['has_alternates'],
            $this);
    }

    /**
     * Answer the catalog where terms.
     *
     * @return string
     *
     * @since 4/20/09
     */
    private function getCatalogWhereTerms()
    {
        if (null === $this->catalogId || $this->catalogId->isEqual($this->getCombinedCatalogId())) {
            return 'TRUE';
        } else {
            return 'catalog_id = :catalog_id';
        }
    }

    /**
     * Answer the input parameters.
     *
     * @return array
     *
     * @since 4/17/09
     */
    private function getCatalogParameters()
    {
        $params = [];
        if (null !== $this->catalogId && !$this->catalogId->isEqual($this->getCombinedCatalogId())) {
            $params[':catalog_id'] = $this->getCatalogDatabaseId($this->catalogId);
        }

        return $params;
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
     *  @param object osid_id_IdList $courseIdList the list of <code> Ids
     *          </code> to rerieve
     *
     * @return object osid_course_CourseList the returned <code> Course list
     *                </code>
     *
     * @throws osid_NotFoundException            an <code> Id was </code> not found
     * @throws osid_NullArgumentException <code> courseIdList </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesByIds(osid_id_IdList $courseIdList)
    {
        $courses = [];

        while ($courseIdList->hasNext()) {
            try {
                $courses[] = $this->getCourse($courseIdList->getNextId());
            } catch (osid_NotFoundException $e) {
                if ($this->usesPlenaryView()) {
                    throw $e;
                }
            } catch (osid_PermissionDeniedException $e) {
                if ($this->usesPlenaryView()) {
                    throw $e;
                }
            }
        }

        return new phpkit_course_ArrayCourseList($courses);
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
     *  @param object osid_type_Type $courseGenusType a course genus type
     *
     * @return object osid_course_CourseList the returned <code> Course list
     *                </code>
     *
     * @throws osid_NullArgumentException <code> courseGenusType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesByGenusType(osid_type_Type $courseGenusType)
    {
        if ($courseGenusType->isEqual(new phpkit_type_URNInetType('urn:inet:osid.org:genera:none'))) {
            return $this->getCourses();
        } else {
            return new phpkit_EmptyList();
        }
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
     *  @param object osid_type_Type $courseGenusType a course genus type
     *
     * @return object osid_course_CourseList the returned <code> Course list
     *                </code>
     *
     * @throws osid_NullArgumentException <code> courseGenusType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesByParentGenusType(osid_type_Type $courseGenusType)
    {
        return $this->getCoursesByGenusType($courseGenusType);
    }

    /**
     *  Gets a <code> CourseList </code> containing the given course record
     *  <code> Type. </code> In plenary mode, the returned list contains all
     *  known courses or an error results. Otherwise, the returned list may
     *  contain only those courses that are accessible through this session.
     *  In both cases, the order of the set is not specified.
     *
     *  @param object osid_type_Type $courseRecordType a course record type
     *
     * @return object osid_course_CourseList the returned <code> CourseList
     *                list </code>
     *
     * @throws osid_NullArgumentException <code> courseRecordType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesByRecordType(osid_type_Type $courseRecordType)
    {
        return new phpkit_EmptyList();
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
     *  @param object osid_id_Id $topicId a topic id
     *
     * @return object osid_course_CourseList the returned <code> Course list
     *                </code>
     *
     * @throws osid_NullArgumentException <code> courseGenusType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCoursesByTopic(osid_id_Id $topicId)
    {
        return new banner_course_Course_Lookup_ByTopicList(
            $this->manager->getDB(),
            $this,
            $this->getCourseCatalogId(),
            $topicId);
    }

    /**
     *  Gets all <code> Courses. </code> In plenary mode, the returned list
     *  contains all known courses or an error results. Otherwise, the
     *  returned list may contain only those courses that are accessible
     *  through this session. In both cases, the order of the set is not
     *  specifed.
     *
     * @return object osid_course_CourseList a list of <code> Courses </code>
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     * @throws osid_IllegalStateException     this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourses()
    {
        return new banner_course_Course_Lookup_AllList(
            $this->manager->getDB(),
            $this,
            $this->getCourseCatalogId()
        );
    }

    /*********************************************************
     * Support for fetching course alternates.
     *********************************************************/

    private static $alternatesForCourse_stmt;

    /**
     * Answer the alternate course ids.
     *
     * @return PDOStatement
     *
     * @since 5/1/09
     */
    public function getAlternateIdsForCourse(osid_id_Id $courseId)
    {
        if (!isset(self::$alternatesForCourse_stmt)) {
            $query = '
SELECT
	*
FROM (

-- Give three courses, A, B, C, they may be marked equivalent in 4 columns as any of
--  1   2  3   4
--  A = B, A = C
--  A = B, B = C
--  A = B, C = B
--  A = B, C = A

	-- Columns 1 => 2 as well as 3 => 4
	SELECT
		SCREQIV_SUBJ_CODE_EQIV AS subj_code,
		SCREQIV_CRSE_NUMB_EQIV AS crse_numb,
		SCREQIV_EFF_TERM AS eff_term
	FROM
		screqiv_current
		LEFT JOIN antirequisites a ON (
			SCREQIV_SUBJ_CODE = a.subj_code
			AND SCREQIV_CRSE_NUMB = a.crse_numb
			AND SCREQIV_SUBJ_CODE_EQIV = a.subj_code_eqiv
			AND SCREQIV_CRSE_NUMB_EQIV = a.crse_numb_eqiv)
	WHERE
		SCREQIV_SUBJ_CODE = :subj_code_0
		AND SCREQIV_CRSE_NUMB = :crse_numb_0
		AND a.subj_code IS NULL

	UNION

	-- Columns 1 <= 2 as well as 3 <= 4
	SELECT
		SCREQIV_SUBJ_CODE AS subj_code,
		SCREQIV_CRSE_NUMB AS crse_numb,
		SCREQIV_EFF_TERM  AS eff_term
	FROM
		screqiv_current
		LEFT JOIN antirequisites a ON (
			SCREQIV_SUBJ_CODE = a.subj_code
			AND SCREQIV_CRSE_NUMB = a.crse_numb
			AND SCREQIV_SUBJ_CODE_EQIV = a.subj_code_eqiv
			AND SCREQIV_CRSE_NUMB_EQIV = a.crse_numb_eqiv)
	WHERE
		SCREQIV_SUBJ_CODE_EQIV = :subj_code_1
		AND SCREQIV_CRSE_NUMB_EQIV = :crse_numb_1
		AND a.subj_code IS NULL

	UNION

	-- Columns 1 => 3
	SELECT
		subj_code_3 AS subj_code,
		crse_numb_3 AS crse_numb,
		GREATEST(eff_term_a, eff_term_b) AS eff_term
	FROM
		screqiv_2way
		LEFT JOIN antirequisites a ON (
			(subj_code_1 = a.subj_code
			AND crse_numb_1 = a.crse_numb
			AND subj_code_2 = a.subj_code_eqiv
			AND crse_numb_2 = a.crse_numb_eqiv)
			OR (subj_code_3 = a.subj_code
			AND crse_numb_3 = a.crse_numb
			AND subj_code_4 = a.subj_code_eqiv
			AND crse_numb_4 = a.crse_numb_eqiv))
	WHERE
		subj_code_1 = :subj_code_2
		AND crse_numb_1 = :crse_numb_2
		AND a.subj_code IS NULL

	UNION

	-- Columns 1 => 4
	SELECT
		subj_code_4 AS subj_code,
		crse_numb_4 AS crse_numb,
		GREATEST(eff_term_a, eff_term_b) AS eff_term
	FROM
		screqiv_2way
		LEFT JOIN antirequisites a ON (
			(subj_code_1 = a.subj_code
			AND crse_numb_1 = a.crse_numb
			AND subj_code_2 = a.subj_code_eqiv
			AND crse_numb_2 = a.crse_numb_eqiv)
			OR (subj_code_3 = a.subj_code
			AND crse_numb_3 = a.crse_numb
			AND subj_code_4 = a.subj_code_eqiv
			AND crse_numb_4 = a.crse_numb_eqiv))
	WHERE
		subj_code_1 = :subj_code_3
		AND crse_numb_1 = :crse_numb_3
		AND a.subj_code IS NULL

	UNION

	-- Columns 2 => 3
	SELECT
		subj_code_3 AS subj_code,
		crse_numb_3 AS crse_numb,
		GREATEST(eff_term_a, eff_term_b) AS eff_term
	FROM
		screqiv_2way
		LEFT JOIN antirequisites a ON (
			(subj_code_1 = a.subj_code
			AND crse_numb_1 = a.crse_numb
			AND subj_code_2 = a.subj_code_eqiv
			AND crse_numb_2 = a.crse_numb_eqiv)
			OR (subj_code_3 = a.subj_code
			AND crse_numb_3 = a.crse_numb
			AND subj_code_4 = a.subj_code_eqiv
			AND crse_numb_4 = a.crse_numb_eqiv))
	WHERE
		subj_code_2 = :subj_code_4
		AND crse_numb_2 = :crse_numb_4
		AND a.subj_code IS NULL

	UNION

	-- Columns 2 => 4
	SELECT
		subj_code_4 AS subj_code,
		crse_numb_4 AS crse_numb,
		GREATEST(eff_term_a, eff_term_b) AS eff_term
	FROM
		screqiv_2way
		LEFT JOIN antirequisites a ON (
			(subj_code_1 = a.subj_code
			AND crse_numb_1 = a.crse_numb
			AND subj_code_2 = a.subj_code_eqiv
			AND crse_numb_2 = a.crse_numb_eqiv)
			OR (subj_code_3 = a.subj_code
			AND crse_numb_3 = a.crse_numb
			AND subj_code_4 = a.subj_code_eqiv
			AND crse_numb_4 = a.crse_numb_eqiv))
	WHERE
		subj_code_2 = :subj_code_5
		AND crse_numb_2 = :crse_numb_5
		AND a.subj_code IS NULL

	) as screquiv_combined

WHERE
	subj_code != :subj_code_6
	OR crse_numb != :crse_numb_6
GROUP BY
	subj_code,
	crse_numb
ORDER BY eff_term DESC
';
            self::$alternatesForCourse_stmt = $this->manager->getDB()->prepare($query);
        }
        $params = [
            ':subj_code_0' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_0' => $this->getNumberFromCourseId($courseId),
            ':subj_code_1' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_1' => $this->getNumberFromCourseId($courseId),
            ':subj_code_2' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_2' => $this->getNumberFromCourseId($courseId),
            ':subj_code_3' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_3' => $this->getNumberFromCourseId($courseId),
            ':subj_code_4' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_4' => $this->getNumberFromCourseId($courseId),
            ':subj_code_5' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_5' => $this->getNumberFromCourseId($courseId),
            ':subj_code_6' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_6' => $this->getNumberFromCourseId($courseId),
        ];

        self::$alternatesForCourse_stmt->execute($params);
        $rows = self::$alternatesForCourse_stmt->fetchAll(PDO::FETCH_ASSOC);
        self::$alternatesForCourse_stmt->closeCursor();

        $ids = [];
        foreach ($rows as $row) {
            $ids[] = $this->getCourseIdFromSubjectAndNumber($row['subj_code'], $row['crse_numb']);
        }

        return new phpkit_id_ArrayIdList($ids);
    }

    private static $alternatesForCourseInTerms_stmt;

    /**
     * Answer the alternate course ids that are effive between the given start and end terms (inclusive).
     *
     * @return PDOStatement
     *
     * @since 5/1/09
     */
    public function getAlternateIdsForCourseInTerms(osid_id_Id $courseId, osid_id_Id $startTerm, osid_id_Id $endTerm)
    {
        if (!isset(self::$alternatesForCourseInTerms_stmt)) {
            $query = '
SELECT
	*
FROM (

-- Give three courses, A, B, C, they may be marked equivalent in 4 columns as any of
--  1   2  3   4
--  A = B, A = C
--  A = B, B = C
--  A = B, C = B
--  A = B, C = A

	-- Columns 1 => 2 as well as 3 => 4
	SELECT
		SCREQIV_SUBJ_CODE_EQIV AS subj_code,
		SCREQIV_CRSE_NUMB_EQIV AS crse_numb,
		SCREQIV_EFF_TERM AS eff_term
	FROM
		SCREQIV
		LEFT JOIN antirequisites a ON (
			SCREQIV_SUBJ_CODE = a.subj_code
			AND SCREQIV_CRSE_NUMB = a.crse_numb
			AND SCREQIV_SUBJ_CODE_EQIV = a.subj_code_eqiv
			AND SCREQIV_CRSE_NUMB_EQIV = a.crse_numb_eqiv)
	WHERE
		SCREQIV_SUBJ_CODE = :subj_code_0
		AND SCREQIV_CRSE_NUMB = :crse_numb_0
		AND SCREQIV_END_TERM >= :start_term_0
		AND SCREQIV_START_TERM <= :end_term_0
		AND a.subj_code IS NULL

	UNION

	-- Columns 1 <= 2 as well as 3 <= 4
	SELECT
		SCREQIV_SUBJ_CODE AS subj_code,
		SCREQIV_CRSE_NUMB AS crse_numb,
		SCREQIV_EFF_TERM  AS eff_term
	FROM
		SCREQIV
		LEFT JOIN antirequisites a ON (
			SCREQIV_SUBJ_CODE = a.subj_code
			AND SCREQIV_CRSE_NUMB = a.crse_numb
			AND SCREQIV_SUBJ_CODE_EQIV = a.subj_code_eqiv
			AND SCREQIV_CRSE_NUMB_EQIV = a.crse_numb_eqiv)
	WHERE
		SCREQIV_SUBJ_CODE_EQIV = :subj_code_1
		AND SCREQIV_CRSE_NUMB_EQIV = :crse_numb_1
		AND SCREQIV_END_TERM >= :start_term_1
		AND SCREQIV_START_TERM <= :end_term_1
		AND a.subj_code IS NULL

	UNION

	-- Columns 1 => 3
	SELECT
		subj_code_3 AS subj_code,
		crse_numb_3 AS crse_numb,
		GREATEST(eff_term_a, eff_term_b) AS eff_term
	FROM
		screqiv_2way
		LEFT JOIN antirequisites a ON (
			(subj_code_1 = a.subj_code
			AND crse_numb_1 = a.crse_numb
			AND subj_code_2 = a.subj_code_eqiv
			AND crse_numb_2 = a.crse_numb_eqiv)
			OR (subj_code_3 = a.subj_code
			AND crse_numb_3 = a.crse_numb
			AND subj_code_4 = a.subj_code_eqiv
			AND crse_numb_4 = a.crse_numb_eqiv))
	WHERE
		subj_code_1 = :subj_code_2
		AND crse_numb_1 = :crse_numb_2
		AND end_term_a >= :start_term_2
		AND start_term_a <= :end_term_2
		AND end_term_b >= :start_term_3
		AND start_term_b <= :end_term_3
		AND a.subj_code IS NULL

	UNION

	-- Columns 1 => 4
	SELECT
		subj_code_4 AS subj_code,
		crse_numb_4 AS crse_numb,
		GREATEST(eff_term_a, eff_term_b) AS eff_term
	FROM
		screqiv_2way
		LEFT JOIN antirequisites a ON (
			(subj_code_1 = a.subj_code
			AND crse_numb_1 = a.crse_numb
			AND subj_code_2 = a.subj_code_eqiv
			AND crse_numb_2 = a.crse_numb_eqiv)
			OR (subj_code_3 = a.subj_code
			AND crse_numb_3 = a.crse_numb
			AND subj_code_4 = a.subj_code_eqiv
			AND crse_numb_4 = a.crse_numb_eqiv))
	WHERE
		subj_code_1 = :subj_code_3
		AND crse_numb_1 = :crse_numb_3
		AND end_term_a >= :start_term_4
		AND start_term_a <= :end_term_4
		AND end_term_b >= :start_term_5
		AND start_term_b <= :end_term_5
		AND a.subj_code IS NULL

	UNION

	-- Columns 2 => 3
	SELECT
		subj_code_3 AS subj_code,
		crse_numb_3 AS crse_numb,
		GREATEST(eff_term_a, eff_term_b) AS eff_term
	FROM
		screqiv_2way
		LEFT JOIN antirequisites a ON (
			(subj_code_1 = a.subj_code
			AND crse_numb_1 = a.crse_numb
			AND subj_code_2 = a.subj_code_eqiv
			AND crse_numb_2 = a.crse_numb_eqiv)
			OR (subj_code_3 = a.subj_code
			AND crse_numb_3 = a.crse_numb
			AND subj_code_4 = a.subj_code_eqiv
			AND crse_numb_4 = a.crse_numb_eqiv))
	WHERE
		subj_code_2 = :subj_code_4
		AND crse_numb_2 = :crse_numb_4
		AND end_term_a >= :start_term_6
		AND start_term_a <= :end_term_6
		AND end_term_b >= :start_term_7
		AND start_term_b <= :end_term_7
		AND a.subj_code IS NULL


	UNION

	-- Columns 2 => 4
	SELECT
		subj_code_4 AS subj_code,
		crse_numb_4 AS crse_numb,
		GREATEST(eff_term_a, eff_term_b) AS eff_term
	FROM
		screqiv_2way
		LEFT JOIN antirequisites a ON (
			(subj_code_1 = a.subj_code
			AND crse_numb_1 = a.crse_numb
			AND subj_code_2 = a.subj_code_eqiv
			AND crse_numb_2 = a.crse_numb_eqiv)
			OR (subj_code_3 = a.subj_code
			AND crse_numb_3 = a.crse_numb
			AND subj_code_4 = a.subj_code_eqiv
			AND crse_numb_4 = a.crse_numb_eqiv))
	WHERE
		subj_code_2 = :subj_code_5
		AND crse_numb_2 = :crse_numb_5
		AND end_term_a >= :start_term_8
		AND start_term_a <= :end_term_8
		AND end_term_b >= :start_term_9
		AND start_term_b <= :end_term_9
		AND a.subj_code IS NULL

	) as screquiv_combined

WHERE
	subj_code != :subj_code_6
	OR crse_numb != :crse_numb_6
GROUP BY
	subj_code,
	crse_numb
ORDER BY eff_term DESC
';
            self::$alternatesForCourseInTerms_stmt = $this->manager->getDB()->prepare($query);
        }
        $params = [
            ':subj_code_0' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_0' => $this->getNumberFromCourseId($courseId),
            ':subj_code_1' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_1' => $this->getNumberFromCourseId($courseId),
            ':subj_code_2' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_2' => $this->getNumberFromCourseId($courseId),
            ':subj_code_3' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_3' => $this->getNumberFromCourseId($courseId),
            ':subj_code_4' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_4' => $this->getNumberFromCourseId($courseId),
            ':subj_code_5' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_5' => $this->getNumberFromCourseId($courseId),
            ':subj_code_6' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb_6' => $this->getNumberFromCourseId($courseId),
            ':start_term_0' => $this->getTermCodeFromTermId($startTerm),
            ':start_term_1' => $this->getTermCodeFromTermId($startTerm),
            ':start_term_2' => $this->getTermCodeFromTermId($startTerm),
            ':start_term_3' => $this->getTermCodeFromTermId($startTerm),
            ':start_term_4' => $this->getTermCodeFromTermId($startTerm),
            ':start_term_5' => $this->getTermCodeFromTermId($startTerm),
            ':start_term_6' => $this->getTermCodeFromTermId($startTerm),
            ':start_term_7' => $this->getTermCodeFromTermId($startTerm),
            ':start_term_8' => $this->getTermCodeFromTermId($startTerm),
            ':start_term_9' => $this->getTermCodeFromTermId($startTerm),
            ':end_term_0' => $this->getTermCodeFromTermId($endTerm),
            ':end_term_1' => $this->getTermCodeFromTermId($endTerm),
            ':end_term_2' => $this->getTermCodeFromTermId($endTerm),
            ':end_term_3' => $this->getTermCodeFromTermId($endTerm),
            ':end_term_4' => $this->getTermCodeFromTermId($endTerm),
            ':end_term_5' => $this->getTermCodeFromTermId($endTerm),
            ':end_term_6' => $this->getTermCodeFromTermId($endTerm),
            ':end_term_7' => $this->getTermCodeFromTermId($endTerm),
            ':end_term_8' => $this->getTermCodeFromTermId($endTerm),
            ':end_term_9' => $this->getTermCodeFromTermId($endTerm),
        ];

        self::$alternatesForCourseInTerms_stmt->execute($params);
        $rows = self::$alternatesForCourseInTerms_stmt->fetchAll(PDO::FETCH_ASSOC);
        self::$alternatesForCourseInTerms_stmt->closeCursor();

        $ids = [];
        foreach ($rows as $row) {
            $ids[] = $this->getCourseIdFromSubjectAndNumber($row['subj_code'], $row['crse_numb']);
        }

        return new phpkit_id_ArrayIdList($ids);
    }
}
