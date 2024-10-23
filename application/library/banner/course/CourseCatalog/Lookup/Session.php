<?php
/**
 * @since 4/9/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This class provides support for lookups of the various catalogs listed in banner.
 * For example, Undergraduate, Breadload School of English, etc.
 *
 * <p>This session provides methods for retrieving <code> CourseCatalog
 *  </code> objects. The <code> CourseCatalog </code> represents a collection
 *  of courses, offerings and tems. </p>
 *
 *  <p> This session defines views that offer differing behaviors when
 *  retrieving multiple objects. </p>
 *
 *  <p>
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered
 *      </li>
 *      <li> plenary view: provides a complete set or is an error condition
 *      </li>
 *  </ul>
 *  Generally, the comparative view should be used for most applications as it
 *  permits operation even if there is data that cannot be accessed. For
 *  example, a browsing application may only need to examine the <code>
 *  CourseCatalogs </code> it can access, without breaking execution. However,
 *  an assessment may only be useful if all <code> CourseCatalogs </code>
 *  referenced by it are available, and a test-taking application may
 *  sacrifice some interoperability for the sake of precision. </p>
 *
 * @since 4/9/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_CourseCatalog_Lookup_Session extends banner_course_AbstractSession implements osid_course_CourseCatalogLookupSession
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
     *  A complete view of the <code> CourseCatalog </code> returns is
     *  desired. Methods will return what is requested or result in an error.
     *  This view is used when greater precision is desired at the expense of
     *  interoperability.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function usePlenaryCourseCatalogView()
    {
        $this->usePlenaryView();
    }

    /**
     *  Tests if this user can perform <code> CourseCatalog </code> lookups. A
     *  return of true does not guarantee successful authorization. A return
     *  of false indicates that it is known all methods in this session will
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a
     *  hint to an application that may opt not to offer lookup operations to
     *  unauthorized users.
     *
     * @return boolean <code> false </code> if lookup methods are not
     *                        authorized, <code> true </code> otherwise
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function canLookupCourseCatalog()
    {
        return true;
    }

    private static $getCatalogById_stmt;

    /**
     *  Gets the <code> CourseCatalog </code> specified by its <code> Id.
     *  </code> In plenary mode, the exact <code> Id </code> is found or a
     *  <code> NOT_FOUND </code> results. Otherwise, the returned <code>
     *  CourseCatalog </code> may have a different <code> Id </code> than
     *  requested, such as the case where a duplicate <code> Id </code> was
     *  assigned to a <code> CourseCatalog </code> and retained for
     *  compatibility.
     *
     *  @param object osid_id_Id $courseCatalogId <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_CourseCatalog the course catalog
     *
     * @throws osid_NotFoundException <code>     courseCatalogId </code> not
     *                                           found
     * @throws osid_NullArgumentException <code> courseCatalogId </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function getCourseCatalog(osid_id_Id $courseCatalogId)
    {
        if ($courseCatalogId->isEqual($this->getCombinedCatalogId())) {
            return new banner_course_CourseCatalog_Combined($this->getCombinedCatalogId());
        }

        if (!isset(self::$getCatalogById_stmt)) {
            self::$getCatalogById_stmt = $this->manager->getDB()->prepare(
                'SELECT
	catalog_id,
	catalog_title
FROM
	course_catalog
WHERE
	catalog_id = :catalog_id
');
        }

        self::$getCatalogById_stmt->execute([':catalog_id' => $this->getDatabaseIdString($courseCatalogId)]);

        $result = self::$getCatalogById_stmt->fetch(PDO::FETCH_ASSOC);
        self::$getCatalogById_stmt->closeCursor();

        if (!$result) {
            throw new osid_NotFoundException('Catalog id not found. ');
        }

        return new banner_course_CourseCatalog(
            $this->getOsidIdFromString($result['catalog_id']),
            $result['catalog_title']);
    }

    /**
     *  Gets a <code> CourseCatalogList </code> corresponding to the given
     *  <code> IdList. </code> In plenary mode, the returned list contains all
     *  of the repositories specified in the <code> Id </code> list, in the
     *  order of the list, including duplicates, or an error results if an
     *  <code> Id </code> in the supplied list is not found or inaccessible.
     *  Otherwise, inaccessible <code> CourseCatalogs </code> may be omitted
     *  from the list and may present the elements in any order including
     *  returning a unique set.
     *
     *  @param object osid_id_IdList $courseCatalogIdList the list of <code>
     *          Ids </code> to rerieve
     *
     * @return object osid_course_CourseCatalogList the returned <code>
     *                CourseCatalog list </code>
     *
     * @throws osid_NotFoundException            an <code> Id was </code> not found
     * @throws osid_NullArgumentException <code> courseCatalogIdList </code>
     *                                           is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseCatalogsByIds(osid_id_IdList $courseCatalogIdList)
    {
        $catalogs = [];

        while ($courseCatalogIdList->hasNext()) {
            try {
                $catalogs[] = $this->getCourseCatalog($courseCatalogIdList->getNextId());
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

        return new phpkit_course_ArrayCourseCatalogList($catalogs);
    }

    /**
     *  Gets a <code> CourseCatalogList </code> corresponding to the given
     *  course catalog genus <code> Type </code> which does not include
     *  repositories of types derived from the specified <code> Type. </code>
     *  In plenary mode, the returned list contains all known course catalogs
     *  or an error results. Otherwise, the returned list may contain only
     *  those course catalogs that are accessible through this session. In
     *  both cases, the order of the set is not specified.
     *
     *  @param object osid_type_Type $courseCatalogGenusType a course catalog
     *          genus type
     *
     * @return object osid_course_CourseCatalogList the returned <code>
     *                CourseCatalog list </code>
     *
     * @throws osid_NullArgumentException <code> courseCatalogGenusType
     *                                           </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseCatalogsByGenusType(osid_type_Type $courseCatalogGenusType)
    {
        if ($courseCatalogGenusType->isEqual(new phpkit_type_URNInetType('urn:inet:osid.org:genera:none'))) {
            return $this->getCourseCatalogs();
        } else {
            return new phpkit_course_ArrayCourseCatalogList([]);
        }
    }

    /**
     *  Gets a <code> CourseCatalogList </code> corresponding to the given
     *  course catalog genus <code> Type </code> and include any additional
     *  course catalogs with genus types derived from the specified <code>
     *  Type. </code> In plenary mode, the returned list contains all known
     *  course catalogs or an error results. Otherwise, the returned list may
     *  contain only those course catalogs that are accessible through this
     *  session. In both cases, the order of the set is not specified.
     *
     *  @param object osid_type_Type $courseCatalogGenusType a course catalog
     *          genus type
     *
     * @return object osid_course_CourseCatalogList the returned <code>
     *                Course Catalog list </code>
     *
     * @throws osid_NullArgumentException <code> courseCatalogGenusType
     *                                           </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseCatalogsByParentGenusType(osid_type_Type $courseCatalogGenusType)
    {
        return $this->getCourseCatalogsByGenusType($courseCatalogGenusType);
    }

    /**
     *  Gets a <code> CourseCatalogList </code> containing the given course
     *  catalog record <code> Type. </code> In plenary mode, the returned list
     *  contains all known course catalogs or an error results. Otherwise, the
     *  returned list may contain only those course catalogs that are
     *  accessible through this session. In both cases, the order of the set
     *  is not specified.
     *
     *  @param object osid_type_Type $courseCatalogRecordType a course catalog
     *          record type
     *
     * @return object osid_course_CourseCatalogList the returned <code>
     *                CourseCatalog list </code>
     *
     * @throws osid_NullArgumentException <code> courseCatalogRecordType
     *                                           </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseCatalogsByRecordType(osid_type_Type $courseCatalogRecordType)
    {
        return new phpkit_course_ArrayCourseCatalogList([]);
    }

    private static $getCatalogs_stmt;

    /**
     *  Gets all <code> CourseCatalogs. </code> In plenary mode, the returned
     *  list contains all known course catalogs or an error results.
     *  Otherwise, the returned list may contain only those course catalogs
     *  that are accessible through this session. In both cases, the order of
     *  the set is not specified.
     *
     * @return object osid_course_CourseCatalogList a list of <code>
     *                CourseCatalogs </code>
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     * @throws osid_IllegalStateException     this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseCatalogs()
    {
        if (!isset(self::$getCatalogs_stmt)) {
            self::$getCatalogs_stmt = $this->manager->getDB()->prepare(
                'SELECT
	catalog_id,
	catalog_title
FROM
	course_catalog
');
        }

        self::$getCatalogs_stmt->execute();

        $catalogs = [];
        //     	$catalogs[] = new banner_course_CourseCatalog_Combined($this->getCombinedCatalogId());
        while ($result = self::$getCatalogs_stmt->fetch(PDO::FETCH_ASSOC)) {
            $catalogs[] = new banner_course_CourseCatalog(
                $this->getOsidIdFromString($result['catalog_id']),
                $result['catalog_title']);
        }

        self::$getCatalogs_stmt->closeCursor();

        return new phpkit_course_ArrayCourseCatalogList($catalogs);
    }
}
