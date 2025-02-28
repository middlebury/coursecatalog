<?php

/**
 * @since 4/16/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This interface defines a few methods to allow course offering objects to get back to
 * other data from sessions such as terms and courses.
 *
 * @since 4/16/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface banner_course_CourseOffering_SessionInterface extends banner_course_SessionInterface
{
    /**
     * Answer the term code from an id object.
     *
     * @return string
     *
     * @throws an osid_NotFoundException if the id cannot match
     *
     * @since 4/16/09
     */
    public function getTermCodeFromOfferingId(osid_id_Id $id);

    /**
     * Answer the CRN from an id object.
     *
     * @return string
     *
     * @throws an osid_NotFoundException if the id cannot match
     *
     * @since 4/16/09
     */
    public function getCrnFromOfferingId(osid_id_Id $id);

    /**
     * Answer an id object from a CRN.
     *
     * @param string $termCode
     *
     * @return osid_id_Id
     *
     * @since 4/16/09
     */
    public function getOfferingIdFromTermCodeAndCrn($termCode, $crn);

    /**
     * Answer an id object from a subject code and course number.
     *
     * @param string $subjectCode
     *
     * @return osid_id_Id
     *
     * @since 4/16/09
     */
    public function getCourseIdFromSubjectAndNumber($subjectCode, $number);

    /**
     * Answer a course subject code from an id.
     *
     * @return string
     *
     * @since 4/17/09
     */
    public function getSubjectFromCourseId(osid_id_Id $id);

    /**
     * Answer a course number from an id.
     *
     * @return string
     *
     * @since 4/17/09
     */
    public function getNumberFromCourseId(osid_id_Id $id);

    /**
     * Answer a term code from an id.
     *
     * @return string
     *
     * @since 4/17/09
     */
    public function getTermCodeFromTermId(osid_id_Id $id);

    /**
     * Answer the id authority for this session.
     *
     * @return string
     *
     * @since 4/16/09
     */
    public function getIdAuthority();

    /**
     * Answer the course lookup session.
     *
     * @return osid_course_CourseLookupSession
     *
     * @since 4/16/09
     */
    public function getCourseLookupSession();

    /**
     * Answer a term lookup session.
     *
     * @return osid_course_TermLookupSession
     *
     * @since 4/16/09
     */
    public function getTermLookupSession();

    /**
     * Answer a resource lookup session.
     *
     * @return osid_resource_ResourceLookupSession
     *
     * @since 4/16/09
     */
    public function getResourceLookupSession();

    /**
     * Answer a list of instructors for the course offering id passed.
     *
     * @return osid_id_IdList
     *
     * @since 4/30/09
     */
    public function getInstructorIdsForOffering(osid_id_Id $offeringId);

    /**
     * Answer a list of instructors for the course offering id passed.
     *
     * @return osid_resource_ResourceList
     *
     * @since 4/30/09
     */
    public function getInstructorsForOffering(osid_id_Id $offeringId);
}
