<?php
/**
 * @since 4/23/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>This session provides methods to retrieve <code> CourseOffering </code>
 *  to <code> CourseCatalog </code> mappings. A <code> CourseOffering </code>
 *  may appear in multiple <code> CourseCatalog </code> objects. Each catalog
 *  may have its own authorizations governing who is allowed to look at it.
 *  </p>.
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
class banner_course_CourseOffering_Catalog_Session extends banner_course_CourseOffering_AbstractSession implements osid_course_CourseOfferingCatalogSession
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
    public function useComparativeCourseOfferingCatalogView()
    {
        $this->useComparativeView();
    }

    /**
     *  A complete view of the <code> CourseOffering </code> and <code>
     *  CourseCatalog </code> returns is desired. Methods will return what is
     *  requested or result in an error. This view is used when greater
     *  precision is desired at the expense of interoperability.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function usePlenaryCourseOfferingCatalogView()
    {
        $this->usePlenaryView();
    }

    /**
     *  Tests if this user can perform lookups of course offering/course
     *  catalog mappings. A return of true does not guarantee successful
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
    public function canLookupCourseOfferingCatalogMappings()
    {
        return true;
    }

    /**
     *  Gets the list of <code> CourseOffering Ids </code> associated with a
     *  <code> CourseCatalog. </code>.
     *
     *  @param object osid_id_Id $courseCatalogId <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_id_IdList list of related course offering <code>
     *                Ids </code>
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
    public function getCourseOfferingIdsByCatalog(osid_id_Id $courseCatalogId)
    {
        $ids = [];
        $offerings = $this->getCourseOfferingsByCatalog($courseCatalogId);
        while ($offerings->hasNext()) {
            $ids[] = $offerings->getNextCourseOffering()->getId();
        }

        return new phpkit_id_ArrayIdList($ids);
    }

    /**
     *  Gets the list of <code> CourseOfferings </code> associated with a
     *  <code> CourseCatalog. </code>.
     *
     *  @param object osid_id_Id $courseCatalogId <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_CourseOfferingList list of related course
     *                offerings
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
    public function getCourseOfferingsByCatalog(osid_id_Id $courseCatalogId)
    {
        $lookupSession = $this->manager->getCourseOfferingLookupSessionForCatalog($courseCatalogId);
        $lookupSession->useIsolatedView();
        if ($this->usesPlenaryView()) {
            $lookupSession->usePlenaryCourseOfferingView();
        } else {
            $lookupSession->useComparativeCourseOfferingView();
        }

        return $lookupSession->getCourseOfferings();
    }

    /**
     *  Gets the list of <code> CourseOffering Ids </code> corresponding to a
     *  list of <code> CourseCatalog </code> objects.
     *
     *  @param object osid_id_IdList $courseCatalogIdList list of course
     *          catalog <code> Ids </code>
     *
     * @return object osid_id_IdList list of course offering <code> Ids
     *                </code>
     *
     * @throws osid_NullArgumentException <code> courseCatalogIdList </code>
     *                                           is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingIdsByCatalogs(osid_id_IdList $courseCatalogIdList)
    {
        $idList = new phpkit_CombinedList('osid_id_IdList');
        while ($courseCatalogIdList->hasNext()) {
            try {
                $idList->addList($this->getCourseOfferingIdsByCatalog($courseCatalogIdList->getNextId()));
            } catch (osid_NotFoundException $e) {
                if ($this->usesPlenaryView()) {
                    throw $e;
                }
            }
        }

        return $idList;
    }

    /**
     *  Gets the list of <code> CourseOfferings </code> corresponding to a
     *  list of <code> CourseCatalog </code> objects.
     *
     *  @param object osid_id_IdList $courseCatalogIdList list of course
     *          catalog <code> Ids </code>
     *
     * @return object osid_course_CourseOfferingList list of course offerings
     *
     * @throws osid_NullArgumentException <code> courseCatalogIdList </code>
     *                                           is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingsByCatalogs(osid_id_IdList $courseCatalogIdList)
    {
        $courseOfferingList = new phpkit_CombinedList('osid_course_CourseOfferingList');
        while ($courseCatalogIdList->hasNext()) {
            try {
                $courseOfferingList->addList($this->getCourseOfferingsByCatalog($courseCatalogIdList->getNextId()));
            } catch (osid_NotFoundException $e) {
                if ($this->usesPlenaryView()) {
                    throw $e;
                }
            }
        }

        return $courseOfferingList;
    }

    /**
     *  Gets the <code> CourseCatalog </code> <code> Ids </code> mapped to a
     *  <code> CourseOffering. </code>.
     *
     *  @param object osid_id_Id $courseOfferingId <code> Id </code> of a
     *          <code> CourseOffering </code>
     *
     * @return object osid_id_IdList list of course catalogs
     *
     * @throws osid_NotFoundException <code>     courseOfferingId </code> is not
     *                                           found
     * @throws osid_NullArgumentException <code> courseOfferingId </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCatalogIdsByCourseOffering(osid_id_Id $courseOfferingId)
    {
        $parameters = [
            ':section_term_code' => $this->getTermCodeFromOfferingId($courseOfferingId),
            ':section_crn' => $this->getCrnFromOfferingId($courseOfferingId),
        ];
        $statement = $this->getGetCatalogsByOfferingStatement();
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
     *  CourseOffering. </code>.
     *
     *  @param object osid_id_Id $courseOfferingId <code> Id </code> of a
     *          <code> CourseOffering </code>
     *
     * @return object osid_course_CourseCatalogList list of course catalogs
     *
     * @throws osid_NotFoundException <code>     courseOfferingId </code> is not
     *                                           found
     * @throws osid_NullArgumentException <code> courseOfferingId </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCatalogsByCourseOffering(osid_id_Id $courseOfferingId)
    {
        $parameters = [
            ':section_term_code' => $this->getTermCodeFromOfferingId($courseOfferingId),
            ':section_crn' => $this->getCrnFromOfferingId($courseOfferingId),
        ];
        $statement = $this->getGetCatalogsByOfferingStatement();
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
    private function getGetCatalogsByOfferingStatement()
    {
        if (!isset(self::$getCatalogsByCourse_stmt)) {
            self::$getCatalogsByCourse_stmt = $this->manager->getDB()->prepare(
                '
SELECT
    cat.catalog_id,
    cat.catalog_title
FROM
    ssbsect_scbcrse s
    INNER JOIN course_catalog_college c ON s.SCBCRSE_COLL_CODE = c.coll_code
    INNER JOIN catalog_term t ON s.SSBSECT_TERM_CODE = t.term_code
    INNER JOIN course_catalog cat ON t.catalog_id = cat.catalog_id AND c.catalog_id = cat.catalog_id

WHERE
    SSBSECT_TERM_CODE = :section_term_code
    AND SSBSECT_CRN = :section_crn
GROUP BY cat.catalog_id
');
        }

        return self::$getCatalogsByCourse_stmt;
    }
}
