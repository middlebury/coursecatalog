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
class banner_course_CourseOffering_Search_Session extends banner_course_CourseOffering_AbstractSession implements osid_course_CourseOfferingSearchSession
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

    /*********************************************************
     * Methods from osid_course_CourseOfferingSearchSession
     *********************************************************/

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
     * @return boolean <code> false </code> if search methods are not
     *                        authorized, <code> true </code> otherwise
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function canSearchCourseOfferings()
    {
        return true;
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
     *  Gets a course offering query interface.
     *
     * @return object osid_course_CourseOfferingQuery the course offering
     *                query interface
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingQuery()
    {
        return new banner_course_CourseOffering_Search_Query($this);
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
        return new banner_course_CourseOffering_Search_List($this->manager->getDB(), $this, $this->getCourseCatalogId(), $courseQuery, $this->getCourseOfferingSearch());
    }

    /**
     *  Gets a course offering search interface.
     *
     * @return object osid_course_CourseOfferingSearch the course offering
     *                search interface
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingSearch()
    {
        return new banner_course_CourseOffering_Search_Search($this);
    }

    /**
     *  Gets a course search order interface. The <code>
     *  CourseOfferingSearchOrder </code> is supplied to a <code>
     *  CourseOfferingSearch </code> to specify the ordering of results.
     *
     * @return object osid_course_CourseOfferingSearchOrder the course
     *                offering search order interface
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingSearchOrder()
    {
        return new banner_course_CourseOffering_Search_Order();
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
        return new banner_course_CourseOffering_Search_List($this->manager->getDB(), $this, $this->getCourseCatalogId(), $courseOfferingQuery, $courseOfferingSearch);
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
     *
     * @since 6/4/09
     */
    public function buildIndex($displayStatus = false)
    {
        harmoni_SQLUtils::runSQLfile(__DIR__.'/sql/fulltext_drop_index.sql', $this->manager->getDB());
        try {
            harmoni_SQLUtils::runSQLfile(__DIR__.'/sql/fulltext_create_index_structure.sql', $this->manager->getDB());
        } catch (PDOException $e) {
            // Ignore "Column already exists" errors.
            if ('42S21' != $e->getCode()) {
                throw $e;
            }
        }
        harmoni_SQLUtils::runSQLfile(__DIR__.'/../../../sql/create_views.sql', $this->manager->getDB());

        $lookupSession = $this->manager->getCourseOfferingLookupSession();
        $lookupSession->useFederatedCourseCatalogView();
        $lookupSession->useComparativeCourseOfferingView();
        $offerings = $lookupSession->getCourseOfferings();

        // Known topic types
        $this->subjectType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.subject');
        $this->departmentType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.department');
        $this->divisionType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.division');
        $this->requirementType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.requirement');
        $this->blockType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.block');
        $this->instructionMethodType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.instruction_method');
        // Known record types
        $this->instructorsType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');

        if ($displayStatus) {
            $total = $offerings->available();
            $numLength = strlen((string) $total);
            echo "\nBuilding Index of $total offerings:\n";
        }

        $insertStmt = $this->manager->getDB()->prepare('UPDATE SSBSECT SET SSBSECT_fulltext=:text WHERE SSBSECT_TERM_CODE = :term_code AND SSBSECT_CRN = :crn');

        $i = 0;
        while ($offerings->hasNext()) {
            try {
                $offering = $offerings->getNextCourseOffering();
                $termCode = $this->getTermCodeFromOfferingId($offering->getId());
                $crn = $this->getCrnFromOfferingId($offering->getId());
                $text = $offering->getFulltextStringForIndex();

                if (!$insertStmt->execute([':term_code' => $termCode, ':crn' => $crn, ':text' => $text])) {
                    $info = $insertStmt->errorInfo();
                    throw new osid_OperationFailedException('FullText update failed with code '.$info[0].'.'.$info[1].' - '.$info[2]);
                }
            } catch (Exception $e) {
                echo "\nError of type:\n\t".$e::class."\nwith message:\n\t".$e->getMessage()."\n";
            }

            ++$i;
            if ($displayStatus) {
                if (!($i % 100)) {
                    echo '.';
                }
                if (!($i % 10000)) {
                    echo ' ';
                    echo str_pad($i, $numLength, ' ', \STR_PAD_LEFT);
                    echo " / $total\n";
                }
            }
        }

        if ($displayStatus) {
            echo "\nData Populated, adding index to columns...";
        }

        harmoni_SQLUtils::runSQLfile(__DIR__.'/sql/fulltext_add_index.sql', $this->manager->getDB());

        if ($displayStatus) {
            echo "\nIndex Built\n";
        }
    }
}
