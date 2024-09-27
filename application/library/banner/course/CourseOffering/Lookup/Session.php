<?php
/**
 * @since 4/9/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>This session defines methods for retrieving course offerings. A <code>
 *  CourseOffering </code> is a scheduled course listed in a course catalog. A
 *  <code> CourseOffering </code> is derived from a <code> Course </code> and
 *  maps to an offering time and registered students. </p>.
 *
 *  <p> This lookup session defines several views: </p>
 *
 *  <p>
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered
 *      </li>
 *      <li> plenary view: provides a complete result set or is an error
 *      condition </li>
 *      <li> isolated course catalog view: All course offering methods in this
 *      session operate, retrieve and pertain to course offerings defined
 *      explicitly in the current course catalog. Using an isolated view is
 *      useful for managing <code> CourseOfferings </code> with the <code>
 *      CourseOfferingAdminSession. </code> </li>
 *      <li> federated course catalog view: All course offering lookup methods
 *      in this session operate, retrieve and pertain to all course offerings
 *      defined in this course catalog and any other courses implicitly
 *      available in this course catalog through repository inheritence. </li>
 *  </ul>
 *  The methods <code> useFederatedCourseCatalogView() </code> and <code>
 *  useIsolatedCourseCatalogView() </code> behave as a radio group and one
 *  should be selected before invoking any lookup methods. Courses may have an
 *  additional records indicated by their respective record types. The record
 *  may not be accessed through a cast of the <code> Course. </code> </p>
 */
class banner_course_CourseOffering_Lookup_Session extends banner_course_CourseOffering_AbstractSession implements osid_course_CourseOfferingLookupSession, middlebury_course_CourseOffering_Lookup_SessionInterface
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
     *  Tests if this user can perform <code> CourseOffering </code> lookups.
     *  A return of true does not guarantee successful authorization. A return
     *  of false indicates that it is known all methods in this session will
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a
     *  hint to an application that may not offer lookup operations to
     *  unauthorized users.
     *
     * @return boolean <code> false </code> if lookup methods are not
     *                        authorized, <code> true </code> otherwise
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function canLookupCourseOfferings()
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
    public function useComparativeCourseOfferingView()
    {
        $this->useComparativeView();
    }

    /**
     *  A complete view of the <code> CourseOffering </code> returns is
     *  desired. Methods will return what is requested or result in an error.
     *  This view is used when greater precision is desired at the expense of
     *  interoperability.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function usePlenaryCourseOfferingView()
    {
        $this->usePlenaryView();
    }

    /**
     *  Federates the view for methods in this session. A federated view will
     *  include course offerings in catalogs which are children of this
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
     *  restricts retrievals to this course catalog only.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useIsolatedCourseCatalogView()
    {
        $this->useIsolatedView();
    }

    private static $getOffering_stmts = [];

    /**
     *  Gets the <code> CourseOffering </code> specified by its <code> Id.
     *  </code> In plenary mode, the exact <code> Id </code> is found or a
     *  <code> NOT_FOUND </code> results. Otherwise, the returned <code>
     *  CourseOffering </code> may have a different <code> Id </code> than
     *  requested, such as the case where a duplicate <code> Id </code> was
     *  assigned to a <code> Course </code> and retained for compatibility.
     *
     *  @param object osid_id_Id $courseOfferingId the <code> Id </code> of
     *          the <code> CourseOffering </code> to rerieve
     *
     * @return object osid_course_CourseOffering the returned <code>
     *                CourseOffering </code>
     *
     * @throws osid_NotFoundException            no <code> CourseOffering </code> found
     *                                           with the given <code> Id </code>
     * @throws osid_NullArgumentException <code> courseOfferingId </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOffering(osid_id_Id $courseOfferingId)
    {
        $catalogWhere = $this->getCatalogWhereTerms();
        if (!isset(self::$getOffering_stmts[$catalogWhere])) {
            $query =
'SELECT
	SSBSECT_TERM_CODE,
	SSBSECT_CRN,
	SSBSECT_SUBJ_CODE,
	SSBSECT_CRSE_NUMB,
	SSBSECT_SEQ_NUMB,
	SSBSECT_PTRM_CODE,
	SSBSECT_CRSE_TITLE,
	SSBSECT_MAX_ENRL,
	SSBSECT_ENRL,
	SSBSECT_SEATS_AVAIL,
	SSBSECT_LINK_IDENT,
	SSBDESC_TEXT_NARRATIVE,
	SCBCRSE_TITLE,
	SCBDESC_TEXT_NARRATIVE,
	GTVINSM_CODE,
	GTVINSM_DESC,
	SSBSECT_CAMP_CODE,
	term_display_label,
	STVTERM_START_DATE,
	STVSCHD_CODE,
	STVSCHD_DESC,
	SSRMEET_BLDG_CODE,
	SSRMEET_ROOM_CODE,
	SSRMEET_BEGIN_TIME,
	SSRMEET_END_TIME,
	SSRMEET_SUN_DAY,
	SSRMEET_MON_DAY,
	SSRMEET_TUE_DAY,
	SSRMEET_WED_DAY,
	SSRMEET_THU_DAY,
	SSRMEET_FRI_DAY,
	SSRMEET_SAT_DAY,
	SSRMEET_START_DATE,
	SSRMEET_END_DATE,
	COUNT(SSRMEET_TERM_CODE) as num_meet,
	STVCAMP_DESC,
	STVBLDG_DESC,
	SCBCRSE_EFF_TERM ,
	SCBCRSE_DEPT_CODE,
	SCBCRSE_DIVS_CODE,
	SSRXLST_XLST_GROUP
FROM
	catalog_term
	LEFT JOIN ssbsect_scbcrse_scbdesc ON term_code = SSBSECT_TERM_CODE
	LEFT JOIN GTVINSM ON SSBSECT_INSM_CODE = GTVINSM_CODE
	LEFT JOIN STVTERM ON SSBSECT_TERM_CODE = STVTERM_CODE
	LEFT JOIN SSBDESC ON (SSBSECT_TERM_CODE = SSBDESC_TERM_CODE AND SSBSECT_CRN = SSBDESC_CRN)
	LEFT JOIN SSRMEET ON (SSBSECT_TERM_CODE = SSRMEET_TERM_CODE AND SSBSECT_CRN = SSRMEET_CRN)
	LEFT JOIN STVBLDG ON SSRMEET_BLDG_CODE = STVBLDG_CODE
	LEFT JOIN STVSCHD ON SSBSECT_SCHD_CODE = STVSCHD_CODE
	LEFT JOIN STVCAMP ON SSBSECT_CAMP_CODE = STVCAMP_CODE
	LEFT JOIN SSRXLST ON (SSBSECT_TERM_CODE = SSRXLST_TERM_CODE AND SSBSECT_CRN = SSRXLST_CRN)
WHERE
	'.$this->getCatalogWhereTerms().'

	AND term_code = :section_term_code
	AND SSBSECT_CRN = :section_crn

GROUP BY SSBSECT_TERM_CODE, SSBSECT_CRN
';
            self::$getOffering_stmts[$catalogWhere] = $this->manager->getDB()->prepare($query);
        }

        $parameters = array_merge(
            [
                ':section_term_code' => $this->getTermCodeFromOfferingId($courseOfferingId),
                ':section_crn' => $this->getCrnFromOfferingId($courseOfferingId),
            ],
            $this->getCatalogParameters());
        self::$getOffering_stmts[$catalogWhere]->execute($parameters);
        $row = self::$getOffering_stmts[$catalogWhere]->fetch(PDO::FETCH_ASSOC);
        self::$getOffering_stmts[$catalogWhere]->closeCursor();

        if (!$row || !$row['SSBSECT_CRN'] || !$row['SSBSECT_TERM_CODE']) {
            throw new osid_NotFoundException('Could not find a course offering matching the term code '.$this->getTermCodeFromOfferingId($courseOfferingId).' and the crn '.$this->getCrnFromOfferingId($courseOfferingId).'.');
        }

        return new banner_course_CourseOffering($row, $this);
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
            return '
	catalog_id = :catalog_id
	AND SCBCRSE_COLL_CODE IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			catalog_id = :catalog_id2
	)	';
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
            $params[':catalog_id2'] = $this->getCatalogDatabaseId($this->catalogId);
        }

        return $params;
    }

    /**
     *  Gets a <code> CourseOfferingList </code> corresponding to the given
     *  <code> IdList. </code> In plenary mode, the returned list contains all
     *  of the course offerings specified in the <code> Id </code> list, in
     *  the order of the list, including duplicates, or an error results if an
     *  <code> Id </code> in the supplied list is not found or inaccessible.
     *  Otherwise, inaccessible <code> CourseOfferings </code> may be omitted
     *  from the list and may present the elements in any order including
     *  returning a unique set.
     *
     *  @param object osid_id_IdList $courseOfferingIdList the list of <code>
     *          Ids </code> to rerieve
     *
     * @return object osid_course_CourseOfferingList the returned <code>
     *                CourseOffering list </code>
     *
     * @throws osid_NotFoundException            an <code> Id was </code> not found
     * @throws osid_NullArgumentException <code> courseOfferingIdList </code>
     *                                           is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsByIds(osid_id_IdList $courseOfferingIdList)
    {
        $offerings = [];

        while ($courseOfferingIdList->hasNext()) {
            try {
                $offerings[] = $this->getCourseOffering($courseOfferingIdList->getNextId());
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

        return new phpkit_course_ArrayCourseOfferingList($offerings);
    }

    /**
     *  Gets a <code> CourseOfferingList </code> corresponding to the given
     *  course offering genus <code> Type </code> which does not include
     *  course offerings of types derived from the specified <code> Type.
     *  </code> In plenary mode, the returned list contains all known course
     *  offerings or an error results. Otherwise, the returned list may
     *  contain only those course offerings that are accessible through this
     *  session. In both cases, the order of the set is not specified.
     *
     *  @param object osid_type_Type $courseOfferingGenusType a course
     *          offering genus type
     *
     * @return object osid_course_CourseOfferingList the returned <code>
     *                CourseOffering list </code>
     *
     * @throws osid_NullArgumentException <code> courseOfferingGenusType
     *                                           </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsByGenusType(osid_type_Type $courseOfferingGenusType)
    {
        try {
            return new banner_course_CourseOffering_Lookup_ByGenusTypeList($this->manager->getDB(), $this, $this->getCourseCatalogId(), $courseOfferingGenusType);
        } catch (osid_NotFoundException $e) {
            return new phpkit_EmptyList();
        }
    }

    /**
     *  Gets a <code> CourseOfferingList </code> corresponding to the given
     *  course genus <code> Type </code> and include any additional course
     *  offerings with genus types derived from the specified <code> Type.
     *  </code> In plenary mode, the returned list contains all known course
     *  offerings or an error results. Otherwise, the returned list may
     *  contain only those course offerings that are accessible through this
     *  session. In both cases, the order of the set is not specified.
     *
     *  @param object osid_type_Type $courseOfferingGenusType a course
     *          offering genus type
     *
     * @return object osid_course_CourseOfferingList the returned <code>
     *                CourseOffeing list </code>
     *
     * @throws osid_NullArgumentException <code> courseOfferingGenusType
     *                                           </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsByParentGenusType(osid_type_Type $courseOfferingGenusType)
    {
        return $this->getCourseOfferingsByGenusType($courseOfferingGenusType);
    }

    /**
     *  Gets a <code> CourseOfferingList </code> containing the given course
     *  offering record <code> Type. </code> In plenary mode, the returned
     *  list contains all known courses or an error results. Otherwise, the
     *  returned list may contain only those course offerings that are
     *  accessible through this session. In both cases, the order of the set
     *  is not specified.
     *
     *  @param object osid_type_Type $courseOfferingRecordType a course
     *          offering record type
     *
     * @return object osid_course_CourseOfferingList the returned <code>
     *                CourseOfferingList list </code>
     *
     * @throws osid_NullArgumentException <code> courseOfferingRecordType
     *                                           </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsByRecordType(osid_type_Type $courseOfferingRecordType)
    {
        if ($courseOfferingRecordType->isEqual(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors'))) {
            return $this->getCourseOfferings();
        } else {
            return new phpkit_EmptyList();
        }
    }

    /**
     *  Gets all <code> CourseOfferings </code> associated with a given <code>
     *  Course. </code> In plenary mode, the returned list contains all known
     *  course offerings or an error results. Otherwise, the returned list may
     *  contain only those course offerings that are accessible through this
     *  session. In both cases, the order of the set is not specified.
     *
     *  @param object osid_id_Id $courseId a course <code> Id </code>
     *
     * @return object osid_course_CourseOfferingList a list of <code>
     *                CoursesOfferings </code>
     *
     * @throws osid_NullArgumentException <code> courseId </code> is <code>
     *                                           null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsForCourse(osid_id_Id $courseId)
    {
        return new banner_course_CourseOffering_Lookup_ForCourseList(
            $this->manager->getDB(),
            $this,
            $this->getCourseCatalogId(),
            $courseId);
    }

    /**
     *  Gets all <code> CourseOfferings </code> associated with a given <code>
     *  Term. </code> In plenary mode, the returned list contains all known
     *  course offerings or an error results. Otherwise, the returned list may
     *  contain only those course offerings that are accessible through this
     *  session. In both cases, the order of the set is not specified.
     *
     *  @param object osid_id_Id $termId a term <code> Id </code>
     *
     * @return object osid_course_CourseOfferingList a list of <code>
     *                CoursesOfferings </code>
     *
     * @throws osid_NullArgumentException <code> termId </code> is <code>
     *                                           null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsByTerm(osid_id_Id $termId)
    {
        return new banner_course_CourseOffering_Lookup_ByTermList(
            $this->manager->getDB(),
            $this,
            $this->getCourseCatalogId(),
            $termId);
    }

    /**
     *  Gets all <code> CourseOfferings </code> associated with a given <code>
     *  Term </code> and <code> Course. </code> In plenary mode, the returned
     *  list contains all known course offerings or an error results.
     *  Otherwise, the returned list may contain only those course offerings
     *  that are accessible through this session. In both cases, the order of
     *  the set is not specified.
     *
     *  @param object osid_id_Id $termId a term <code> Id </code>
     *  @param object osid_id_Id $courseId a course <code> Id </code>
     *
     * @return object osid_course_CourseOfferingList a list of <code>
     *                CoursesOfferings </code>
     *
     * @throws osid_NullArgumentException <code> termId </code> or <code>
     *                                           courseId </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsByTermForCourse(osid_id_Id $termId,
        osid_id_Id $courseId)
    {
        return new banner_course_CourseOffering_Lookup_ByTermForCourseList(
            $this->manager->getDB(),
            $this,
            $this->getCourseCatalogId(),
            $termId,
            $courseId);
    }

    /**
     *  WARNING: This method was not in the OSID trunk as of 2009-04-27. A
     *  ticket requesting the addition of this method is available at:
     *  http://oki.assembla.com/spaces/osid-dev/tickets/18-osid-course---No-way-to-map-Topics-to-Courses-or-CourseOfferings-
     *  Gets all <code> CourseOfferings </code> associated with a given <code>
     *  Topic. </code> In plenary mode, the returned list contains all known
     *  course offerings or an error results. Otherwise, the returned list may
     *  contain only those course offerings that are accessible through this
     *  session. In both cases, the order of the set is not specified.
     *
     *  @param object osid_id_Id $topicId a topic <code> Id </code>
     *
     * @return object osid_course_CourseOfferingList a list of <code>
     *                CoursesOfferings </code>
     *
     * @throws osid_NullArgumentException <code> topicId </code> is <code>
     *                                           null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsByTopic(osid_id_Id $topicId)
    {
        return new banner_course_CourseOffering_Lookup_ByTopicList(
            $this->manager->getDB(),
            $this,
            $this->getCourseCatalogId(),
            $topicId);
    }

    /**
     *  WARNING: This method was not in the OSID trunk as of 2009-04-27. A
     *  ticket requesting the addition of this method is available at:
     *  http://oki.assembla.com/spaces/osid-dev/tickets/18-osid-course---No-way-to-map-Topics-to-Courses-or-CourseOfferings-
     *  Gets all <code> CourseOfferings </code> associated with a given <code>
     *  Term </code> and a given <code> Topic </code> . In plenary mode, the
     *  returned list contains all known course offerings or an error results.
     *  Otherwise, the returned list may contain only those course offerings
     *  that are accessible through this session. In both cases, the order of
     *  the set is not specified.
     *
     *  @param object osid_id_Id $termId a term <code> Id </code>
     *  @param object osid_id_Id $topicId a topic <code> Id </code>
     *
     * @return object osid_course_CourseOfferingList a list of <code>
     *                CoursesOfferings </code>
     *
     * @throws osid_NullArgumentException <code> termId </code> or <code>
     *                                           topicId </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsByTermByTopic(osid_id_Id $termId,
        osid_id_Id $topicId)
    {
        return new banner_course_CourseOffering_Lookup_ByTermByTopicList(
            $this->manager->getDB(),
            $this,
            $this->getCourseCatalogId(),
            $termId,
            $topicId);
    }

    /**
     *  Gets all <code> CourseOfferings. </code> In plenary mode, the returned
     *  list contains all known course offerings or an error results.
     *  Otherwise, the returned list may contain only those courses that are
     *  accessible through this session. In both cases, the order of the set
     *  is not specifed.
     *
     * @return object osid_course_CourseOfferingList a list of <code>
     *                CourseOfferings </code>
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     * @throws osid_IllegalStateException     this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferings()
    {
        return new banner_course_CourseOffering_Lookup_AllList(
            $this->manager->getDB(),
            $this,
            $this->getCourseCatalogId());
    }

    /*********************************************************
     * Custom extensions from middlebury_course_CourseOffering_Lookup_SessionInterface
     *********************************************************/

    /**
     *  Gets a list of the genus types for course offerings.
     *
     * @return object osid_id_TypeList the list of course offering genus types
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getCourseOfferingGenusTypes()
    {
        return new banner_course_CourseOffering_GenusTypeList($this->manager->getDB(), $this);
    }
}
