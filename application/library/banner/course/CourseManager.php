<?php
/**
 * @since 4/9/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This is a CourseManager implementation that provides read-only, unauthenticated,
 * access to course information stored in Banner database tables.
 *
 * @since 4/9/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_CourseManager extends phpkit_AbstractOsidManager implements osid_course_CourseManager, banner_course_CourseManagerInterface
{
    /**
     * @var PDO;
     *
     * @since 4/9/09
     */
    private $db;

    /**
     * Set the configuration and class paths.
     *
     * @return void
     *
     * @since 4/9/09
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId(new phpkit_id_URNInetId('urn:inet:middlebury.edu:id:implementations.banner_course'));
        $this->setDisplayName('Banner Course Manager');
        $this->setDescription('This is a CourseManager implementation that provides read-only, unauthenticated, access to course information stored in Banner database tables.');
    }

    /*********************************************************
     * From banner_course_CourseManagerInterface
     *********************************************************/
    /**
     * Answer the database connection.
     *
     * @return PDO
     *
     * @since 4/13/09
     */
    public function getDB()
    {
        return $this->db;
    }

    /**
     * Answer the Identifier authority to use.
     *
     * @return string
     *
     * @since 4/13/09
     */
    public function getIdAuthority()
    {
        return $this->idAuthority;
    }

    /**
     * Answer the Id of the 'All'/'Combined' catalog.
     *
     * @return osid_id_Id
     *
     * @since 4/20/09
     */
    public function getCombinedCatalogId()
    {
        return new phpkit_id_Id($this->getIdAuthority(), 'urn', 'catalog.all');
    }

    /*********************************************************
     * From OsidManager
     *********************************************************/

    /**
     *  Initializes this manager. A manager is initialized once at the time of
     *  creation.
     *
     *  @param object osid_OsidRuntimeManager $runtime the runtime environment
     *
     * @throws osid_ConfigurationErrorException  an error with implementation
     *                                           configuration
     * @throws osid_IllegalStateException        this manager has already been
     *                                           initialized by the <code> OsidLoader </code> or this manager
     *                                           has been shut down
     * @throws osid_NullArgumentException <code> runtime </code> is <code>
     *                                           null </code>
     * @throws osid_OperationFailedException     unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  In addition to loading its runtime configuration an
     *          implementation may create shared resources such as connection
     *          pools to be shared among all sessions of this service and
     *          released when this manager is closed. Providers must
     *          thread-protect any data stored in the manager.
     *          <br/><br/>
     *          To maximize interoperability, providers should not honor a
     *          second call to <code> initialize() </code> and must set an
     *          <code> ILLEGAL_STATE </code> error.
     */
    public function initialize(osid_OsidRuntimeManager $runtime)
    {
        parent::initialize($runtime);
        $runtime = $this->impl_getRuntimeManager();

        try {
            $dsn = phpkit_configuration_ConfigUtil::getSingleValuedValue(
                $runtime->getConfiguration(),
                new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:banner_course.pdo_dsn'),
                new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/String'));

            $username = phpkit_configuration_ConfigUtil::getSingleValuedValue(
                $runtime->getConfiguration(),
                new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:banner_course.pdo_username'),
                new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/String'));

            $password = phpkit_configuration_ConfigUtil::getSingleValuedValue(
                $runtime->getConfiguration(),
                new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:banner_course.pdo_password'),
                new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/String'));
        } catch (osid_NotFoundException $e) {
            throw new osid_ConfigurationErrorException($e->getMessage(), $e->getCode(), $e);
        }

        try {
            $debug = phpkit_configuration_ConfigUtil::getSingleValuedValue(
                $runtime->getConfiguration(),
                new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:banner_course.pdo_count_queries'),
                new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/Boolean'));
        } catch (osid_ConfigurationErrorException $e) {
            $debug = false;
        }

        try {
            $driverOptions = [];
            $options = phpkit_configuration_ConfigUtil::getMultiValuedValueOfAnyType(
                $runtime->getConfiguration(),
                new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:banner_course.pdo_driver_options'));
            foreach ($options as $key => $value) {
                $option = constant($key);
                if (null === $option) {
                    throw new osid_ConfigurationErrorException("'$key' is not a valid constant.");
                }
                $driverOptions[$option] = $value;
            }
        } catch (osid_NotFoundException $e) {
            $driverOptions = [];
        }

        try {
            if ($debug) {
                $this->db = new PDODebug_PDO($dsn, $username, $password, $driverOptions);
            } else {
                $this->db = new PDO($dsn, $username, $password, $driverOptions);
            }
        } catch (PDOException $e) {
            throw new osid_ConfigurationErrorException($e->getMessage(), $e->getCode(), $e);
        }

        try {
            $this->idAuthority = phpkit_configuration_ConfigUtil::getSingleValuedValue(
                $runtime->getConfiguration(),
                new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:banner_course.id_authority'),
                new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/String'));
            if (!strlen($this->idAuthority)) {
                throw new osid_ConfigurationErrorException('urn:inet:middlebury.edu:config:banner_course.id_authority must be specified.');
            }
        } catch (osid_NotFoundException $e) {
            throw new osid_ConfigurationErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     *	Shuts down this <code>osid.OsidManager</code>.
     */
    public function shutdown()
    {
        $this->db = null;
        parent::shutdown();
    }

    /*********************************************************
     * From osid_course_CourseManager
     *********************************************************/

    /**
     *  Gets the <code> OsidSession </code> associated with the course lookup
     *  service.
     *
     * @return object osid_course_CourseLookupSession a <code>
     *                CourseLookupSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsCourseLookup()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseLookup() </code> is <code> true. </code>
     */
    public function getCourseLookupSession()
    {
        return new banner_course_Course_Lookup_CombinedSession($this,
            new phpkit_id_URNInetId('urn:inet:'.$this->idAuthority.':catalog.all'));
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course lookup
     *  service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          course catalog
     *
     * @return object osid_course_CourseLookupSession a <code>
     *                CourseLookupSession </code>
     *
     * @throws osid_NotFoundException             no <code> CousreCatalog </code> found
     *                                            by the given <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsCourseLookup()
     *                                            </code> or <code> supportsVisibleFederation() </code> is
     *                                            <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseLookup() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getCourseLookupSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        try {
            return new banner_course_Course_Lookup_Session($this, $courseCatalogId);
        } catch (osid_NotFoundException $e) {
            throw new osid_NotFoundException('Can not get a CourseLookupSession for an unknown catalog id.');
        }
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course search
     *  service.
     *
     * @return object osid_course_CourseSearchSession a <code>
     *                CourseSearchSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsCourseSearch()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseSearch() </code> is <code> true. </code>
     */
    public function getCourseSearchSession()
    {
        return new banner_course_Course_Search_Session($this,
            new phpkit_id_URNInetId('urn:inet:'.$this->idAuthority.':catalog.all'));
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course search
     *  service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_CourseSearchSession a <code>
     *                CourseSearchSession </code>
     *
     * @throws osid_NotFoundException             no course catalog found by the given
     *                                            <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsCourseSearch()
     *                                            </code> or <code> supportsVisibleFederation() </code> is
     *                                            <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseSearch() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getCourseSearchSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        try {
            return new banner_course_Course_Search_Session($this, $courseCatalogId);
        } catch (osid_NotFoundException $e) {
            throw new osid_NotFoundException('Can not get a CourseSearchSession for an unknown catalog id.');
        }
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course
     *  administration service.
     *
     * @return object osid_course_CourseAdminSession a <code>
     *                CourseAdminSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsCourseAdmin()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseAdmin() </code> is <code> true. </code>
     */
    public function getCourseAdminSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course
     *  administration service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_CourseAdminSession a <code>
     *                CourseAdminSession </code>
     *
     * @throws osid_NotFoundException             no course catalog found by the given
     *                                            <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsCourseAdmin()
     *                                            </code> or <code> supportsVisibleFederation() </code> is
     *                                            <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseAdmin() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getCourseAdminSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course
     *  notification service.
     *
     * @return object osid_course_CourseNotificationSession a <code>
     *                CourseNotificationSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseNotification() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseNotification() </code> is <code> true.
     *              </code>
     */
    public function getCourseNotificationSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course
     *  notification service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_CourseNotificationSession a <code>
     *                CourseNotificationSession </code>
     *
     * @throws osid_NotFoundException             no course catalog found by the given
     *                                            <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseNotification() </code> or <code>
     *                                            supportsVisibleFederation() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseNotification() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getCourseNotificationSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> to lookup course/catalog mappings.
     *
     * @return object osid_course_CourseCatalogSession a <code>
     *                CourseCatalogSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsCourseCatalog()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseCatalog() </code> is <code> true. </code>
     */
    public function getCourseCatalogSession()
    {
        return new banner_course_Course_Catalog_Session($this);
    }

    /**
     *  Gets the <code> OsidSession </code> associated with assigning courses
     *  to course catalogs.
     *
     * @return object osid_course_CourseCatalogAssignmentSession a <code>
     *                CourseCatalogAssignmentSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseCatalogAssignment() </code> is <code> false
     *                                            </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseCatalogAssignment() </code> is <code> true.
     *              </code>
     */
    public function getCourseCatalogAssignmentSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course
     *  offering lookup service.
     *
     * @return object osid_course_CourseOfferingLookupSession a <code>
     *                CourseOfferingSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseOfferingLookup() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseOfferingLookup() </code> is <code> true.
     *              </code>
     */
    public function getCourseOfferingLookupSession()
    {
        return new banner_course_CourseOffering_Lookup_CombinedSession($this);
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course
     *  offering lookup service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          course catalog
     *
     * @return object osid_course_CourseOfferingLookupSession a <code>
     *                CourseOfferingLookupSession </code>
     *
     * @throws osid_NotFoundException             no <code> CousreCatalog </code> found
     *                                            by the given <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseOfferingLookup() </code> or <code>
     *                                            supportsVisibleFederation() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseOfferingLookup() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getCourseOfferingLookupSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        return new banner_course_CourseOffering_Lookup_Session($this, $courseCatalogId);
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course
     *  offering search service.
     *
     * @return object osid_course_CourseOfferingSearchSession a <code>
     *                CourseOfferingSearchSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseOfferingSearch() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseOfferingSearch() </code> is <code> true.
     *              </code>
     */
    public function getCourseOfferingSearchSession()
    {
        return new banner_course_CourseOffering_Search_CombinedSession($this);
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course
     *  offering search service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_CourseOfferingSearchSession a <code>
     *                CourseOfferingSearchSession </code>
     *
     * @throws osid_NotFoundException             no course catalog found by the given
     *                                            <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseOfferingSearch() </code> or <code>
     *                                            supportsVisibleFederation() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseOfferingSearch() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getCourseOfferingSearchSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        return new banner_course_CourseOffering_Search_Session($this, $courseCatalogId);
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course
     *  offering administration service.
     *
     * @return object osid_course_CourseOfferingAdminSession a <code>
     *                CourseOfferingAdminSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseOfferingAdmin() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseOfferingAdmin() </code> is <code> true.
     *              </code>
     */
    public function getCourseOfferingAdminSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course
     *  offering administration service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_CourseOfferingAdminSession a <code>
     *                CourseOfferingAdminSession </code>
     *
     * @throws osid_NotFoundException             no course catalog found by the given
     *                                            <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseOfferingAdmin() </code> or <code>
     *                                            supportsVisibleFederation() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseOfferingAdmin() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getCourseOfferingAdminSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course
     *  offering notification service.
     *
     * @return object osid_course_CourseOfferingNotificationSession a <code>
     *                CourseOfferingNotificationSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseOfferingNotification() </code> is <code> false
     *                                            </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseOfferingNotification() </code> is <code>
     *              true. </code>
     */
    public function getCourseOfferingNotificationSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course
     *  offering notification service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_CourseOfferingNotificationSession a <code>
     *                CourseOfferingNotificationSession </code>
     *
     * @throws osid_NotFoundException             no course catalog found by the given
     *                                            <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseOfferingNotification() </code> or <code>
     *                                            supportsVisibleFederation() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseOfferingNotification() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getCourseOfferingNotificationSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the course offering hierarchy traversal session.
     *
     * @return object osid_course_CourseOfferingHierarchySession <code> a
     *                CourseOfferingHierarchySession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseOfferingHierarchy() </code> is <code> false
     *                                            </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseOfferingHierarchy() </code> is <code> true.
     *              </code>
     */
    public function getCourseOfferingHierarchySession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the course offering hierarchy design session.
     *
     * @return object osid_course_CourseOfferingHierarchyDesignSession a
     *                <code> CourseOfferingHierarchyDesignSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseOfferingHierarchyDesign() </code> is <code>
     *                                            false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseOfferingHierarchyDesign() </code> is <code>
     *              true. </code>
     */
    public function getCourseOfferingHierarchyDesignSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> to lookup course offering/catalog
     *  mappings.
     *
     * @return object osid_course_CourseOfferingCatalogSession a <code>
     *                CourseOfferingCatalogSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseOfferingCatalog() </code> is <code> false
     *                                            </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseOfferingCatalog() </code> is <code> true.
     *              </code>
     */
    public function getCourseOfferingCatalogSession()
    {
        return new banner_course_CourseOffering_Catalog_Session($this);
    }

    /**
     *  Gets the <code> OsidSession </code> associated with assigning course
     *  offerings to course catalogs.
     *
     * @return object osid_course_CourseOfferingCatalogAssignmentSession a
     *                <code> CourseOfferingCatalogAssignmentSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseOfferingCatalogAssignment() </code> is <code>
     *                                            false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseOfferingCatalogAssignment() </code> is
     *              <code> true. </code>
     */
    public function getCourseOfferingCatalogAssignmentSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the term lookup
     *  service.
     *
     * @return object osid_course_TermLookupSession a <code>
     *                TermLookupSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTermLookup()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermLookup() </code> is <code> true. </code>
     */
    public function getTermLookupSession()
    {
        return new banner_course_Term_Lookup_CombinedSession($this);
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the term lookup
     *  service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_TermLookupSession a <code>
     *                TermLookupSession </code>
     *
     * @throws osid_NotFoundException             no <code> CourseCatalog </code> found
     *                                            by the given <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTermLookup()
     *                                            </code> or <code> supportsVisibleFederation() </code> is
     *                                            <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermLookup() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getTermLookupSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        return new banner_course_Term_Lookup_Session($this, $courseCatalogId);
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the term search
     *  service.
     *
     * @return object osid_course_TermSearchSession a <code>
     *                TermSearchSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTermSearch()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermSearch() </code> is <code> true. </code>
     */
    public function getTermSearchSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the term search
     *  service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_TermSearchSession a <code>
     *                TermSearchSession </code>
     *
     * @throws osid_NotFoundException             no course catalog found by the given
     *                                            <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTermSearch()
     *                                            </code> or <code> supportsVisibleFederation() </code> is
     *                                            <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermSearch() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getTermSearchSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the term
     *  administration service.
     *
     * @return object osid_course_TermAdminSession a <code> TermAdminSession
     *                </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTermAdmin() </code>
     *                                            is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermAdmin() </code> is <code> true. </code>
     */
    public function getTermAdminSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the term
     *  administration service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_TermAdminSession a <code> TermAdminSession
     *                </code>
     *
     * @throws osid_NotFoundException             no course catalog found by the given
     *                                            <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTermAdmin() </code>
     *                                            or <code> supportsVisibleFederation() </code> is <code> false
     *                                            </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermAdmin() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getTermAdminSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the term
     *  notification service.
     *
     * @return object osid_course_TermAdminSession a <code>
     *                TermNotificationSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTermNotification()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermNotification() </code> is <code> true. </code>
     */
    public function getTermNotificationSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the term
     *  notification service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_TermNotificationSession a <code>
     *                TermNotificationSession </code>
     *
     * @throws osid_NotFoundException             no course catalog found by the given
     *                                            <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTermNotification()
     *                                            </code> or <code> supportsVisibleFederation() </code> is
     *                                            <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermNotification() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getTermNotificationSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the term hierarchy traversal session.
     *
     * @return object osid_course_TermHierarchySession <code> a
     *                TermHierarchySession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTermHierarchy()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermHierarchy() </code> is <code> true. </code>
     */
    public function getTermHierarchySession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the term hierarchy design session.
     *
     * @return object osid_course_TermHierarchyDesignSession a <code>
     *                TermHierarchyDesignSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsTermHierarchyDesign() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermHierarchyDesign() </code> is <code> true.
     *              </code>
     */
    public function getTermHierarchyDesignSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> to lookup term/catalog mappings.
     *
     * @return object osid_course_TermCatalogSession a <code>
     *                TermCatalogSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTermCatalog()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermCatalog() </code> is <code> true. </code>
     */
    public function getTermCatalogSession()
    {
        return new banner_course_Term_Catalog_Session($this);
    }

    /**
     *  Gets the <code> OsidSession </code> associated with assigning terms to
     *  course catalogs.
     *
     * @return object osid_course_TermCatalogAssignmentSession a <code>
     *                TermCatalogAssignmentSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsTermCatalogAssignment() </code> is <code> false
     *                                            </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermCatalogAssignment() </code> is <code> true.
     *              </code>
     */
    public function getTermCatalogAssignmentSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the topic lookup
     *  service.
     *
     * @return object osid_course_TopicLookupSession a <code>
     *                TopicLookupSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTopicLookup()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicLookup() </code> is <code> true. </code>
     */
    public function getTopicLookupSession()
    {
        return new banner_course_Topic_Lookup_Session($this, $this->getCombinedCatalogId());
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the topic lookup
     *  service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> courseCatalog </code>
     *
     * @return object osid_course_TopicLookupSession a <code>
     *                TopicLookupSession </code>
     *
     * @throws osid_NotFoundException             no <code> CourseCatalog </code> found
     *                                            by the given <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTopicLookup()
     *                                            </code> or <code> supportsVisibleFederation() </code> is
     *                                            <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicLookup() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getTopicLookupSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        return new banner_course_Topic_Lookup_Session($this, $courseCatalogId);
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the topic search
     *  service.
     *
     * @return object osid_course_TopicSearchSession a <code>
     *                TopicSearchSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTopicSearch()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicSearch() </code> is <code> true. </code>
     */
    public function getTopicSearchSession()
    {
        return new banner_course_Topic_Search_Session($this, $this->getCombinedCatalogId());
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the topic search
     *  service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_TopicSearchSession a <code>
     *                TopicSearchSession </code>
     *
     * @throws osid_NotFoundException             no course catalog found by the given
     *                                            <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTopicSearch()
     *                                            </code> or <code> supportsVisibleFederation() </code> is
     *                                            <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicSearch() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getTopicSearchSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        return new banner_course_Topic_Search_Session($this, $courseCatalogId);
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the topic
     *  administration service.
     *
     * @return object osid_course_TopicAdminSession a <code>
     *                TopicAdminSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTopicAdmin()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicAdmin() </code> is <code> true. </code>
     */
    public function getTopicAdminSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the topic
     *  administration service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_TopicAdminSession a <code>
     *                TopicAdminSession </code>
     *
     * @throws osid_NotFoundException             no course catalog found by the given
     *                                            <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTopicAdmin()
     *                                            </code> or <code> supportsVisibleFederation() </code> is
     *                                            <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicAdmin() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getTopicAdminSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the topic
     *  notification service.
     *
     * @return object osid_course_TopicAdminSession a <code>
     *                TopicNotificationSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTopicNotification()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicNotification() </code> is <code> true.
     *              </code>
     */
    public function getTopicNotificationSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the topic
     *  notification service for the given course catalog.
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the
     *          <code> CourseCatalog </code>
     *
     * @return object osid_course_TopicNotificationSession a <code>
     *                TopicNotificationSession </code>
     *
     * @throws osid_NotFoundException             no course catalog found by the given
     *                                            <code> Id </code>
     * @throws osid_NullArgumentException <code>  courseCatalogId </code> is
     *                                            <code> null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTopicNotification()
     *                                            </code> or <code> supportsVisibleFederation() </code> is
     *                                            <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicNotification() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true
     *              </code>
     */
    public function getTopicNotificationSessionForCatalog(osid_id_Id $courseCatalogId)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the topic hierarchy traversal session.
     *
     * @return object osid_course_TopicHierarchySession <code> a
     *                TopicHierarchySession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTopicHierarchy()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicHierarchy() </code> is <code> true. </code>
     */
    public function getTopicHierarchySession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the topic hierarchy design session.
     *
     * @return object osid_course_TopicHierarchyDesignSession a <code>
     *                TopicHierarchyDesignSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsTopicHierarchyDesign() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicHierarchyDesign() </code> is <code> true.
     *              </code>
     */
    public function getTopicHierarchyDesignSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> to lookup topic/catalog mappings.
     *
     * @return object osid_course_TopicCatalogSession a <code>
     *                TopicCatalogSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsTopicCatalog()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicCatalog() </code> is <code> true. </code>
     */
    public function getTopicCatalogSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with assigning topics
     *  to course catalogs.
     *
     * @return object osid_course_TopicCatalogAssignmentSession a <code>
     *                TopicCatalogAssignmentSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsTopicCatalogAssignment() </code> is <code> false
     *                                            </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicCatalogAssignment() </code> is <code> true.
     *              </code>
     */
    public function getTopicCatalogAssignmentSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course catalog
     *  lookup service.
     *
     * @return object osid_course_CourseCatalogLookupSession a <code>
     *                CourseCatalogLookupSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseCatalogLookup() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseCatalogLookup() </code> is <code> true.
     *              </code>
     */
    public function getCourseCatalogLookupSession()
    {
        return new banner_course_CourseCatalog_Lookup_Session($this);
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course catalog
     *  search service.
     *
     * @return object osid_course_CourseCatalogSearchSession a <code>
     *                CourseCatalogSearchSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseCatalogSearch() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseCatalogSearch() </code> is <code> true.
     *              </code>
     */
    public function getCourseCatalogSearchSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course catalog
     *  administrative service.
     *
     * @return object osid_course_CourseCatalogAdminSession a <code>
     *                CourseCatalogAdminSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseCatalogAdmin() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseCatalogAdmin() </code> is <code> true.
     *              </code>
     */
    public function getCourseCatalogAdminSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course catalog
     *  notification service.
     *
     * @return object osid_course_CourseCatalogNotificationSession a <code>
     *                CourseCatalogNotificationSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseCatalogNotification() </code> is <code> false
     *                                            </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseCatalogNotification() </code> is <code>
     *              true. </code>
     */
    public function getCourseCatalogNotificationSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course catalog
     *  hierarchy service.
     *
     * @return object osid_course_CourseCatalogHierarchySession a <code>
     *                CourseCatalogHierarchySession </code> for course catalogs
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseCatalogHierarchy() </code> is <code> false
     *                                            </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseCatalogHierarchy() </code> is <code> true.
     *              </code>
     */
    public function getCourseCatalogHierarchySession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the course catalog
     *  hierarchy design service.
     *
     * @return object osid_course_CourseCatalogHierarchyDesignSession a
     *                <code> HierarchyDesignSession </code> for course catalogs
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseCatalogHierarchyDesign() </code> is <code> false
     *                                            </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseCatalogHierarchyDesign() </code> is <code>
     *              true. </code>
     */
    public function getCourseCatalogHierarchyDesignSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the resource service for accessing resources used in course
     *  offerings.
     *
     * @return object osid_resource_ResourceManager a <code> ResourceManager
     *                </code>
     *
     * @throws osid_OperationFailedException unable to complete request
     * @throws osid_IllegalStateException    this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented <code> . </code>
     */
    public function getResourceManager()
    {
        //     	throw new osid_OperationFailedException('getResourceManager() is not yet implemented.');

        if (!isset($this->resourceManager)) {
            $this->resourceManager = $this->impl_getRuntimeManager()->getManager(osid_OSID::RESOURCE(), 'banner_resource_ResourceManager', '3.0.0');
        }

        return $this->resourceManager;
    }

    /**
     *  Gets the calendar service for accessing calendars used in course
     *  offerings.
     *
     * @return object osid_calendaring_CalendarManager a <code>
     *                CalendarManager </code>
     *
     * @throws osid_OperationFailedException unable to complete request
     * @throws osid_IllegalStateException    this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented <code> . </code>
     */
    public function getCalendarManager()
    {
        throw new osid_OperationFailedException('getCalendarManager() is not yet implemented.');
        if (!isset($this->calendarManager)) {
            $this->calendarManager = $this->impl_getRuntimeManager()->getManager(osid_OSID::CALENDAR(), 'banner_calendar_CalendarManager', '3.0.0');
        }

        return $this->calendarManager;
    }

    /**
     *  Gets the learning objective service for accessing learning objectives
     *  used in course offerings.
     *
     * @return object osid_learning_ObjectiveManager an <code>
     *                ObjectiveManager </code>
     *
     * @throws osid_OperationFailedException unable to complete request
     * @throws osid_IllegalStateException    this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented <code> . </code>
     */
    public function getLearningManager()
    {
        throw new osid_OperationFailedException('getLearningManager() is not yet implemented.');
        if (!isset($this->learningManager)) {
            $this->learningManager = $this->impl_getRuntimeManager()->getManager(osid_OSID::LEARNING(), 'banner_learning_ObjectiveManager', '3.0.0');
        }

        return $this->learningManager;
    }

    /*********************************************************
     * From osid_course_CourseProfile
     *********************************************************/

    /**
     *  Tests if any course catalog federation is exposed. Federation is
     *  exposed when a specific course catalog may be identified, selected and
     *  used to create a lookup or admin session. Federation is not exposed
     *  when a set of catalogs appears as a single catalog.
     *
     * @return boolean <code> true </code> if visible federation is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsVisibleFederation()
    {
        return true;
    }

    /**
     *  Tests if looking up courses is supported.
     *
     * @return boolean <code> true </code> if course lookup is supported,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseLookup()
    {
        return true;
    }

    /**
     *  Tests if searching courses is supported.
     *
     * @return boolean <code> true </code> if course search is supported,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseSearch()
    {
        return false;
    }

    /**
     *  Tests if course <code> </code> administrative service is supported.
     *
     * @return boolean <code> true </code> if course administration is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseAdmin()
    {
        return false;
    }

    /**
     *  Tests if a course <code> </code> notification service is supported.
     *
     * @return boolean <code> true </code> if course notification is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseNotification()
    {
        return false;
    }

    /**
     *  Tests if a course catalogging service is supported.
     *
     * @return boolean <code> true </code> if course catalogging is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseCatalog()
    {
        return true;
    }

    /**
     *  Tests if a course catalogging service is supported. A course
     *  catalogging service maps courses to catalogs.
     *
     * @return boolean <code> true </code> if course catalogging is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseCatalogAssignment()
    {
        return false;
    }

    /**
     *  Tests if looking up course offerings is supported.
     *
     * @return boolean <code> true </code> if course offering lookup is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseOfferingLookup()
    {
        return true;
    }

    /**
     *  Tests if searching course offerings is supported.
     *
     * @return boolean <code> true </code> if course offering search is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseOfferingSearch()
    {
        return true;
    }

    /**
     *  Tests if course <code> </code> offering <code> </code> administrative
     *  service is supported.
     *
     * @return boolean <code> true </code> if course offering administration
     *                        is supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseOfferingAdmin()
    {
        return false;
    }

    /**
     *  Tests if a course offering <code> </code> notification service is
     *  supported.
     *
     * @return boolean <code> true </code> if course offering notification is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseOfferingNotification()
    {
        return false;
    }

    /**
     *  Tests if course <code> </code> offering <code> </code> hierarchy
     *  traversal service is supported.
     *
     * @return boolean <code> true </code> if course offering hierarchy is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseOfferingHierarchy()
    {
        return false;
    }

    /**
     *  Tests if a course offering <code> </code> hierarchy design service is
     *  supported.
     *
     * @return boolean <code> true </code> if course offering hierarchy
     *                        design is supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseOfferingHierarchyDesign()
    {
        return false;
    }

    /**
     *  Tests if the course offering hierarchy supports node sequencing.
     *
     * @return boolean <code> true </code> if course offering hierarchy node
     *                        sequencing is supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseOfferingHierarchySequencing()
    {
        return false;
    }

    /**
     *  Tests if a course offering catalogging service is supported.
     *
     * @return boolean <code> true </code> if course offering catalog is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseOfferingCatalog()
    {
        return true;
    }

    /**
     *  Tests if a course offering catalogging service is supported. A
     *  catalogging service maps course offerings to catalogs.
     *
     * @return boolean <code> true </code> if course offering catalogging is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseOfferingCatalogAssignment()
    {
        return false;
    }

    /**
     *  Tests if looking up terms is supported.
     *
     * @return boolean <code> true </code> if term lookup is supported,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTermLookup()
    {
        return true;
    }

    /**
     *  Tests if searching terms is supported.
     *
     * @return boolean <code> true </code> if term search is supported,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTermSearch()
    {
        return false;
    }

    /**
     *  Tests if term <code> </code> administrative service is supported.
     *
     * @return boolean <code> true </code> if term administration is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTermAdmin()
    {
        return false;
    }

    /**
     *  Tests if a term <code> </code> notification service is supported.
     *
     * @return boolean <code> true </code> if term notification is supported,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTermNotification()
    {
        return false;
    }

    /**
     *  Tests if term <code> </code> hierarchy traversal service is supported.
     *
     * @return boolean <code> true </code> if term hierarchy is supported,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTermHierarchy()
    {
        return false;
    }

    /**
     *  Tests if a term <code> </code> hierarchy design service is supported.
     *
     * @return boolean <code> true </code> if term hierarchy design is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTermHierarchyDesign()
    {
        return false;
    }

    /**
     *  Tests if the term hierarchy supports node sequencing.
     *
     * @return boolean <code> true </code> if term hierarchy node sequencing
     *                        is supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTermHierarchySequencing()
    {
        return false;
    }

    /**
     *  Tests if a term catalogging service is supported.
     *
     * @return boolean <code> true </code> if term catalog is supported,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTermCatalog()
    {
        return true;
    }

    /**
     *  Tests if a term catalogging service is supported. A catalogging
     *  service maps terms to catalogs.
     *
     * @return boolean <code> true </code> if term catalogging is supported,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTermCatalogAssignment()
    {
        return false;
    }

    /**
     *  Tests if looking up topics is supported.
     *
     * @return boolean <code> true </code> if topic lookup is supported,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTopicLookup()
    {
        return true;
    }

    /**
     *  Tests if searching topics is supported.
     *
     * @return boolean <code> true </code> if topic search is supported,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTopicSearch()
    {
        return true;
    }

    /**
     *  Tests if topic <code> </code> administrative service is supported.
     *
     * @return boolean <code> true </code> if topic administration is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTopicAdmin()
    {
        return false;
    }

    /**
     *  Tests if a topic <code> </code> notification service is supported.
     *
     * @return boolean <code> true </code> if topic notification is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTopicNotification()
    {
        return false;
    }

    /**
     *  Tests if topic <code> </code> hierarchy traversal service is
     *  supported.
     *
     * @return boolean <code> true </code> if topic hierarchy is supported,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTopicHierarchy()
    {
        return false;
    }

    /**
     *  Tests if a topic <code> </code> hierarchy design service is supported.
     *
     * @return boolean <code> true </code> if topic hierarchy design is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTopicHierarchyDesign()
    {
        return false;
    }

    /**
     *  Tests if the topic hierarchy supports node sequencing.
     *
     * @return boolean <code> true </code> if topic hierarchy node sequencing
     *                        is supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTopicHierarchySequencing()
    {
        return false;
    }

    /**
     *  Tests if a topic catalogging service is supported.
     *
     * @return boolean <code> true </code> if topic catalog is supported,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTopicCatalog()
    {
        return false;
    }

    /**
     *  Tests if a topic catalogging service is supported. A catalogging
     *  service maps terms to catalogs.
     *
     * @return boolean <code> true </code> if topic catalogging is supported,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTopicCatalogAssignment()
    {
        return false;
    }

    /**
     *  Tests if looking up course catalogs is supported.
     *
     * @return boolean <code> true </code> if course catalog lookup is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseCatalogLookup()
    {
        return true;
    }

    /**
     *  Tests if searching course catalogs is supported.
     *
     * @return boolean <code> true </code> if course catalog search is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseCatalogSearch()
    {
        return false;
    }

    /**
     *  Tests if course <code> catalog </code> administrative service is
     *  supported.
     *
     * @return boolean <code> true </code> if course catalog administration
     *                        is supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseCatalogAdmin()
    {
        return false;
    }

    /**
     *  Tests if a course catalog <code> </code> notification service is
     *  supported.
     *
     * @return boolean <code> true </code> if course catalog notification is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseCatalogNotification()
    {
        return false;
    }

    /**
     *  Tests for the availability of a course catalog hierarchy traversal
     *  service.
     *
     * @return boolean <code> true </code> if course catalog hierarchy
     *                        traversal is available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented in all
     *              providers.
     */
    public function supportsCourseCatalogHierarchy()
    {
        return false;
    }

    /**
     *  Tests for the availability of a course catalog hierarchy design
     *  service.
     *
     * @return boolean <code> true </code> if course catalog hierarchy design
     *                        is available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseCatalogHierarchyDesign()
    {
        return false;
    }

    /**
     *  Tests if the course catalog hierarchy supports node sequencing.
     *
     * @return boolean <code> true </code> if course catalog hierarchy node
     *                        sequencing is supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseCatalogSequencing()
    {
        return false;
    }

    /**
     *  Gets the supported <code> Course </code> record interface types.
     *
     * @return object osid_type_TypeList a list containing the supported
     *                <code> Course </code> record types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseRecordTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if the given <code> Course </code> record interface type is
     *  supported.
     *
     *  @param object osid_type_Type $courseRecordType a <code> Type </code>
     *          indicating a <code> Course </code> record type
     *
     * @return boolean <code> true </code> if the given <code> Type </code>
     *                        is supported, <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseRecordType(osid_type_Type $courseRecordType)
    {
        return false;
    }

    /**
     *  Gets the supported <code> Course </code> search record interface
     *  types.
     *
     * @return object osid_type_TypeList a list containing the supported
     *                <code> Course </code> search record types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseSearchRecordTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if the given <code> Course </code> search record interface type
     *  is supported.
     *
     *  @param object osid_type_Type $courseSearchRecordType a <code> Type
     *          </code> indicating a <code> Course </code> search record type
     *
     * @return boolean <code> true </code> if the given search record type is
     *                        supported, <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseSearchRecordType(osid_type_Type $courseSearchRecordType)
    {
        return false;
    }

    /**
     *  Gets the supported <code> CourseOffering </code> record interface
     *  types.
     *
     * @return object osid_type_TypeList a list containing the supported
     *                <code> CourseOffering </code> record types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingRecordTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if the given <code> CourseOffering </code> record interface type
     *  is supported.
     *
     *  @param object osid_type_Type $courseOfferingRecordType a <code> Type
     *          </code> indicating an <code> CourseOffering </code> record
     *          type
     *
     * @return boolean <code> true </code> if the given record type is
     *                        supported, <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseOfferingRecordType(osid_type_Type $courseOfferingRecordType)
    {
        return false;
    }

    /**
     *  Gets the supported <code> CourseOffering </code> search types.
     *
     * @return object osid_type_TypeList a list containing the supported
     *                <code> CourseOffering </code> search types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingSearchTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if the given <code> CourseOffering </code> search type is
     *  supported.
     *
     *  @param object osid_type_Type $courseOfferingSearchRecordType a <code>
     *          Type </code> indicating an <code> CourseOffering </code>
     *          search type
     *
     * @return boolean <code> true </code> if the given <code> Type </code>
     *                        is supported, <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseOfferingSearchType(osid_type_Type $courseOfferingSearchRecordType)
    {
        return false;
    }

    /**
     *  Gets the supported <code> Term </code> record interface types.
     *
     * @return object osid_type_TypeList a list containing the supported
     *                <code> Term </code> record types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTermRecordTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if the given <code> Term </code> record interface type is
     *  supported.
     *
     *  @param object osid_type_Type $termRecordType a <code> Type </code>
     *          indicating a <code> Term </code> record type
     *
     * @return boolean <code> true </code> if the given <code> Type </code>
     *                        is supported, <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTermRecordType(osid_type_Type $termRecordType)
    {
        return false;
    }

    /**
     *  Gets the supported <code> Term </code> search record interface types.
     *
     * @return object osid_type_TypeList a list containing the supported
     *                <code> Term </code> search record types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTermSearchRecordTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if the given <code> Term </code> search record interface type is
     *  supported.
     *
     *  @param object osid_type_Type $termSearchRecordType a <code> Type
     *          </code> indicating a <code> Term </code> search record type
     *
     * @return boolean <code> true </code> if the given <code> Type </code>
     *                        is supported, <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTermSearchRecordType(osid_type_Type $termSearchRecordType)
    {
        return false;
    }

    /**
     *  Gets the supported <code> Topic </code> record interface types.
     *
     * @return object osid_type_TypeList a list containing the supported
     *                <code> Topic </code> record types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopicRecordTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if the given <code> Topic </code> record interface type is
     *  supported.
     *
     *  @param object osid_type_Type $topicRecordType a <code> Type </code>
     *          indicating a <code> Topic </code> record type
     *
     * @return boolean <code> true </code> if the given type is supported,
     *                        <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTopicRecordType(osid_type_Type $topicRecordType)
    {
        return false;
    }

    /**
     *  Gets the supported <code> Topic </code> search record interface types.
     *
     * @return object osid_type_TypeList a list containing the supported
     *                <code> Topic </code> search record types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopicSearchRecordTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if the given <code> Topic </code> search record interface type
     *  is supported.
     *
     *  @param object osid_type_Type $topicSearchRecordType a <code> Type
     *          </code> indicating a <code> Topic </code> search record type
     *
     * @return boolean <code> true </code> if the given Type is supported,
     *                        <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTopicSearchRecordType(osid_type_Type $topicSearchRecordType)
    {
        return false;
    }

    /**
     *  Gets the supported <code> CourseCatalog </code> record interface
     *  types.
     *
     * @return object osid_type_TypeList a list containing the supported
     *                <code> CourseCatalog </code> types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseCatalogRecordTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if the given <code> CourseCatalog </code> record interface type
     *  is supported.
     *
     *  @param object osid_type_Type $courseCatalogrecordType a <code> Type
     *          </code> indicating an <code> CourseCatalog </code> record type
     *
     * @return boolean <code> true </code> if the given <code> Type </code>
     *                        is supported, <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseCatalogRecordType(osid_type_Type $courseCatalogrecordType)
    {
        return false;
    }

    /**
     *  Gets the supported <code> CourseCatalog </code> search record
     *  interface types.
     *
     * @return object osid_type_TypeList a list containing the supported
     *                <code> CourseCatalog </code> search record types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseCatalogSearchRecordTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if the given <code> CourseCatalog </code> search record
     *  interface type is supported.
     *
     *  @param object osid_type_Type $courseCatalogrecordType a <code> Type
     *          </code> indicating an <code> CourseCatalog </code> search
     *          record type
     *
     * @return boolean <code> true </code> if the given <code> Type </code>
     *                        is supported, <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseCatalogSearchRecordType(osid_type_Type $courseCatalogrecordType)
    {
        return false;
    }
}
