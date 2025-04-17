<?php

namespace Catalog\OsidImpl\SymfonyCache;

/**
 * Common methods for cacheable sessions.
 *
 * @since 8/10/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class AbstractSession extends \phpkit_AbstractOsidSession
{
    protected \osid_course_CourseManager $manager;
    private \osid_course_CourseLookupSession $courseLookupSession;
    private \osid_course_CourseOfferingLookupSession $courseOfferingLookupSession;
    private \osid_course_TermLookupSession $termLookupSession;
    private \osid_course_TopicLookupSession $topicLookupSession;
    private \osid_resource_ResourceLookupSession $resourceLookupSession;

    /**
     * Constructor.
     *
     * @return void
     *
     * @since 8/11/10
     */
    public function __construct(\osid_course_CourseManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Answer the course lookup session.
     *
     * @return \osid_course_CourseLookupSession
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
     * @return \osid_course_CourseOfferingLookupSession
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
     * Answer a term lookup session.
     *
     * @return \osid_course_TermLookupSession
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
     * @return \osid_resource_ResourceLookupSession
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
     * Answer a topic lookup session.
     *
     * @return \osid_course_TopicLookupSession
     */
    public function getTopicLookupSession()
    {
        if (!isset($this->topicLookupSession)) {
            $this->topicLookupSession = $this->manager->getTopicLookupSessionForCatalog($this->getCourseCatalogId());
            $this->topicLookupSession->useFederatedCourseCatalogView();
        }

        return $this->topicLookupSession;
    }
}
