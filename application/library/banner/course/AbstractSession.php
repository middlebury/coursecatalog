<?php
/**
 * @since 4/10/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This is an abstract course session that includes much of the common methods needed
 * by all course sessions in this package.
 *
 * @since 4/10/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class banner_course_AbstractSession extends banner_AbstractSession implements banner_course_SessionInterface
{
    /**
     * Answer a catalog database id string.
     *
     * @return string
     *
     * @since 4/20/09
     */
    public function getCatalogDatabaseId(osid_id_Id $id)
    {
        return $this->getDatabaseIdString($id, 'catalog.');
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
        return $this->manager->getCombinedCatalogId();
    }

    /**
     * Answer a topic lookup session.
     *
     * @return osid_course_TopicLookupSession
     *
     * @since 4/16/09
     */
    public function getTopicLookupSession()
    {
        if (!isset($this->topicLookupSession)) {
            $this->topicLookupSession = $this->manager->getTopicLookupSessionForCatalog($this->getCourseCatalogId());
            // 			$this->topicLookupSession = $this->manager->getTopicLookupSession();
            $this->topicLookupSession->useFederatedCourseCatalogView();
        }

        return $this->topicLookupSession;
    }

    /**
     * Answer the course lookup session.
     *
     * @return osid_course_CourseLookupSession
     *
     * @since 4/16/09
     */
    public function getCourseLookupSession()
    {
        if (!isset($this->courseLookupSession)) {
            $this->courseLookupSession = $this->manager->getCourseLookupSessionForCatalog($this->getCourseCatalogId());
            $this->courseLookupSession->useFederatedCourseCatalogView();
        }

        return $this->courseLookupSession;
    }

    /**
     * Answer the courseoffering lookup session.
     *
     * @return osid_course_CourseOfferingLookupSession
     *
     * @since 4/16/09
     */
    public function getCourseOfferingLookupSession()
    {
        if (!isset($this->courseOfferingLookupSession)) {
            $this->courseOfferingLookupSession = $this->manager->getCourseOfferingLookupSessionForCatalog($this->getCourseCatalogId());
            $this->courseOfferingLookupSession->useFederatedCourseCatalogView();
        }

        return $this->courseOfferingLookupSession;
    }

    /**
     * Answer the courseoffering Search session.
     *
     * @return osid_course_CourseOfferingSearchSession
     *
     * @since 4/16/09
     */
    public function getCourseOfferingSearchSession()
    {
        if (!isset($this->courseOfferingSearchSession)) {
            $this->courseOfferingSearchSession = $this->manager->getCourseOfferingSearchSessionForCatalog($this->getCourseCatalogId());
            $this->courseOfferingSearchSession->useFederatedCourseCatalogView();
        }

        return $this->courseOfferingSearchSession;
    }

    /**
     * Answer a term lookup session.
     *
     * @return osid_course_TermLookupSession
     *
     * @since 4/16/09
     */
    public function getTermLookupSession()
    {
        if (!isset($this->termLookupSession)) {
            $this->termLookupSession = $this->manager->getTermLookupSessionForCatalog($this->getCourseCatalogId());
            // 			$this->termLookupSession = $this->manager->getTermLookupSession();
            $this->termLookupSession->useFederatedCourseCatalogView();
        }

        return $this->termLookupSession;
    }

    /**
     * Answer a Resource lookup session.
     *
     * @return osid_resource_ResourceLookupSession
     *
     * @since 4/16/09
     */
    public function getResourceLookupSession()
    {
        if (!isset($this->resourceLookupSession)) {
            $this->resourceLookupSession = $this->manager->getResourceManager()->getResourceLookupSession();
        }

        return $this->resourceLookupSession;
    }

    /**
     * Answer an id object from a CRN and term-code.
     *
     * @param string $termCode
     *
     * @return osid_id_Id
     *
     * @since 4/16/09
     */
    public function getOfferingIdFromTermCodeAndCrn($termCode, $crn)
    {
        if (!strlen($termCode) || !strlen($crn)) {
            throw new osid_OperationFailedException('Both termCode and CRN must be specified.');
        }

        return $this->getOsidIdFromString($termCode.'.'.$crn, 'section.');
    }

    /**
     * Answer an id object from a subject code and course number.
     *
     * @param string $subjectCode
     *
     * @return osid_id_Id
     *
     * @since 4/16/09
     */
    public function getCourseIdFromSubjectAndNumber($subjectCode, $number)
    {
        if (!strlen($subjectCode) || !strlen($number)) {
            throw new osid_OperationFailedException('Both subjectCode and number must be specified.');
        }

        return $this->getOsidIdFromString($subjectCode.$number, 'course.');
    }

    /**
     * Answer a term code from an id.
     *
     * @return string
     *
     * @since 4/17/09
     */
    public function getTermCodeFromTermId(osid_id_Id $id)
    {
        $string = $this->getDatabaseIdString($id, 'term.');
        if (!preg_match('#^([0-9]{6})(?:\.(.{1,3}))?$#', $string, $matches)) {
            throw new osid_NotFoundException("String '$string' cannot be converted into a valid term code.");
        }

        return $matches[1];
    }

    /**
     * Answer a part-of-term code from an id.
     *
     * @return string
     *
     * @since 4/17/09
     */
    public function getPartOfTermCodeFromTermId(osid_id_Id $id)
    {
        $string = $this->getDatabaseIdString($id, 'term.');
        if (!preg_match('#^([0-9]{6})(?:\.(.{1,3}))$#', $string, $matches)) {
            throw new osid_NotFoundException("String '$string' cannot be converted into a valid part-of-term code.");
        }

        return $matches[2];
    }

    /**
     * Answer the schedule code from a genus type.
     *
     * @return string
     *
     * @since 5/27/09
     */
    public function getScheduleCodeFromGenusType(osid_type_Type $genusType)
    {
        if ('urn' != strtolower($genusType->getIdentifierNamespace())) {
            throw new osid_NotFoundException('I only know about the urn namespace');
        } elseif (strtolower($genusType->getAuthority()) != strtolower($this->getIdAuthority())) {
            throw new osid_NotFoundException("I only know about the '".$this->getIdAuthority()."' authority");
        }

        if (!preg_match('/^genera:offering\.([a-z]+)$/i', $genusType->getIdentifier(), $matches)) {
            throw new osid_NotFoundException("I only know about identifiers beginning with 'genera:offering.'");
        }

        return $matches[1];
    }

    /**
     * Answer the id authority for this session.
     *
     * @return string
     *
     * @since 4/16/09
     */
    public function getIdAuthority()
    {
        return $this->manager->getIdAuthority();
    }
}
