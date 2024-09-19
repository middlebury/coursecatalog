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
class banner_course_CourseOffering_Lookup_CombinedSession extends banner_course_CourseOffering_Lookup_Session implements osid_course_CourseOfferingLookupSession
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
        parent::__construct($manager, $manager->getCombinedCatalogId());
    }

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
        if ($this->usesIsolatedView()) {
            throw new osid_NotFoundException('This catalog does not directly contain any courses. Use useFederatedView() to access courses in child catalogs.');
        }

        return parent::getCourseOffering($courseOfferingId);
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
        if ($this->usesIsolatedView()) {
            if ($this->usesPlenaryView()) {
                throw new osid_NotFoundException('This catalog does not directly contain any courses. Use useFederatedView() to access courses in child catalogs.');
            } else {
                return new phpkit_EmptyList();
            }
        }

        return parent::getCourseOfferingsByIds($courseOfferingIdList);
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
        if ($this->usesIsolatedView()) {
            return new phpkit_EmptyList();
        }

        return parent::getCourseOfferingsByGenusType($courseOfferingGenusType);
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
        if ($this->usesIsolatedView()) {
            return new phpkit_EmptyList();
        }

        return parent::getCourseOfferingsByParentGenusType($courseOfferingGenusType);
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
        if ($this->usesIsolatedView()) {
            return new phpkit_EmptyList();
        }

        return parent::getCourseOfferingsByRecordType($courseOfferingRecordType);
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
        if ($this->usesIsolatedView()) {
            return new phpkit_EmptyList();
        }

        return parent::getCourseOfferingsForCourse($courseId);
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
        if ($this->usesIsolatedView()) {
            return new phpkit_EmptyList();
        }

        return parent::getCourseOfferingsByTerm($termId);
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
        if ($this->usesIsolatedView()) {
            return new phpkit_EmptyList();
        }

        return parent::getCourseOfferingsByTermForCourse($termId, $courseId);
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
        if ($this->usesIsolatedView()) {
            return new phpkit_EmptyList();
        }

        return parent::getCourseOfferingsByTopic($topicId);
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
        if ($this->usesIsolatedView()) {
            return new phpkit_EmptyList();
        }

        return parent::getCourseOfferingsByTermByTopic($termId, $topicId);
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
        if ($this->usesIsolatedView()) {
            return new phpkit_EmptyList();
        }

        return parent::getCourseOfferings();
    }
}
