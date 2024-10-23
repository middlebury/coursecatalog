<?php
/**
 * @since 11/28/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A controller for providing the Kurogo JSON API.
 *
 * @since 11/28/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class JsonController extends AbstractCatalogController
{
    /**
     * Initialize our view with common properties.
     *
     * @return void
     */
    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->getResponse()->setHeader('Content-Type', 'text/json');
    }

    /**
     * Print out a list of all terms.
     * Kurogo Docs: https://support.modolabs.com/support/solutions/articles/5000659578.
     *
     * @return void
     */
    public function termsAction()
    {
        if ($this->_getParam('catalog')) {
            $catalogId = $this->_helper->osidId->fromString('catalog.'.$this->_getParam('catalog'));
            $lookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $currentTermId = $this->_helper->osidTerms->getNextOrLatestTermId($catalogId);
        } else {
            $lookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
            $currentTermId = null;
        }
        $lookupSession->useFederatedCourseCatalogView();

        $terms = $lookupSession->getTerms();
        $result = ['terms' => []];
        // If we have a currentTermId, put it first.
        if ($currentTermId) {
            $term = $lookupSession->getTerm($currentTermId);
            $result['terms'][] = [
                'code' => preg_replace('/^term\./', '', $term->getId()->getIdentifier()),
                'description' => $term->getDisplayName(),
            ];
        }
        while ($terms->hasNext()) {
            $term = $terms->getNextTerm();
            // Skip the current term if it is already added to the list.
            if ($currentTermId && $currentTermId->isEqual($term->getId())) {
                continue;
            }
            $result['terms'][] = [
                'code' => preg_replace('/^term\./', '', $term->getId()->getIdentifier()),
                'description' => $term->getDisplayName(),
            ];
        }
        if (version_compare(\PHP_VERSION, '5.4.0') >= 0) {
            echo json_encode($result, \JSON_PRETTY_PRINT);
        } else {
            echo json_encode($result);
        }
    }

    /**
     * Print out a list of all "areas" which correspond to departments / programs.
     * Kurogo Docs: https://support.modolabs.com/support/solutions/articles/5000659608.
     *
     * @return void
     */
    public function areasAction()
    {
        if ($this->_getParam('catalog')) {
            $catalogId = $this->_helper->osidId->fromString('catalog.'.$this->_getParam('catalog'));
            $termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $topicSearchSession = $this->_helper->osid->getCourseManager()->getTopicSearchSessionForCatalog($catalogId);
        } else {
            $termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
            $topicSearchSession = $this->_helper->osid->getCourseManager()->getTopicSearchSession();
        }
        $termLookupSession->useFederatedCourseCatalogView();
        $topicSearchSession->useFederatedCourseCatalogView();

        $code = $this->_getParam('code');
        if (empty($code)) {
            throw new InvalidArgumentException('Missing the "code" parameter.');
        }
        $termId = $this->_helper->osidId->fromString('term.'.$code);
        $term = $termLookupSession->getTerm($termId);

        $genera = 'topic.subject';
        $generaType = $this->_helper->osidType->fromString('genera:'.$genera);
        $termType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:terms');

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($generaType, true);
        if (isset($termId) && $topicQuery->hasRecordType($termType)) {
            $record = $topicQuery->getTopicQueryRecord($termType);
            $record->matchTermId($termId, true);
        }
        $search = $topicSearchSession->getTopicSearch();
        $order = $topicSearchSession->getTopicSearchOrder();
        $order->orderByDisplayName();
        $search->orderTopicResults($order);
        $searchResults = $topicSearchSession->getTopicsBySearch($topicQuery, $search);
        $topics = $searchResults->getTopics();

        $result = ['areas' => []];
        while ($topics->hasNext()) {
            $topic = $topics->getNextTopic();
            $result['areas'][] = [
                'area' => preg_replace('/^'.str_replace('.', '\.', $genera).'\./', '', $topic->getId()->getIdentifier()),
                'name' => $topic->getDisplayName(),
            ];
        }
        if (version_compare(\PHP_VERSION, '5.4.0') >= 0) {
            echo json_encode($result, \JSON_PRETTY_PRINT);
        } else {
            echo json_encode($result);
        }
    }

    /**
     * Print out a list of all "courses" in a given "code" (term) and "area" (subject/department).
     * Kurogo Docs: https://support.modolabs.com/support/solutions/articles/5000659620.
     *
     * @return void
     */
    public function catalogAction()
    {
        if ($this->_getParam('catalog')) {
            $catalogId = $this->_helper->osidId->fromString('catalog.'.$this->_getParam('catalog'));
            $searchSession = $this->_helper->osid->getCourseManager()->getCourseSearchSessionForCatalog($catalogId);
            $termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $topicLookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
            $offeringLookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSessionForCatalog($catalogId);
        } else {
            $searchSession = $this->_helper->osid->getCourseManager()->getCourseSearchSession();
            $termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
            $topicLookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSession();
            $offeringLookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSession();
        }
        $searchSession->useFederatedCourseCatalogView();
        $termLookupSession->useFederatedCourseCatalogView();
        $topicLookupSession->useFederatedCourseCatalogView();
        $offeringLookupSession->useFederatedCourseCatalogView();

        // Validate our arguments.
        $genera = 'topic.subject';
        $area = $this->_getParam('area');
        if (empty($area)) {
            throw new InvalidArgumentException('Missing the "area" parameter.');
        }
        $topicId = $this->_helper->osidId->fromString($genera.'.'.$area);
        $topic = $topicLookupSession->getTopic($topicId);
        $code = $this->_getParam('code');
        if (empty($code)) {
            throw new InvalidArgumentException('Missing the "code" parameter.');
        }
        $termId = $this->_helper->osidId->fromString('term.'.$this->_getParam('code'));
        $term = $termLookupSession->getTerm($termId);

        // Build the query.
        $query = $searchSession->getCourseQuery();
        $topicQueryRecord = $query->getCourseQueryRecord(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:topic'));
        $topicQueryRecord->matchTopicId($topicId, true);
        $termQueryRecord = $query->getCourseQueryRecord(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:term'));
        $termQueryRecord->matchTermId($termId, true);

        $courses = $searchSession->getCoursesByQuery($query)->getCourses();
        $result = ['courses' => []];
        while ($courses->hasNext()) {
            $result['courses'][] = $this->_getCourseResult($courses->getNextCourse(), $termId, $offeringLookupSession);
        }
        if (version_compare(\PHP_VERSION, '5.4.0') >= 0) {
            echo json_encode($result, \JSON_PRETTY_PRINT);
        } else {
            echo json_encode($result);
        }
    }

    /**
     * Search for courses in a "code" (term) and "area" (subject/department).
     * Kurogo Docs: https://support.modolabs.com/support/solutions/articles/5000659620.
     *
     * @return void
     */
    public function searchAction()
    {
        if ($this->_getParam('catalog')) {
            $catalogId = $this->_helper->osidId->fromString('catalog.'.$this->_getParam('catalog'));
            $searchSession = $this->_helper->osid->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
            $termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $topicLookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
            $offeringLookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSessionForCatalog($catalogId);
        } else {
            $searchSession = $this->_helper->osid->getCourseManager()->getCourseOfferingSearchSession();
            $termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
            $topicLookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSession();
            $offeringLookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSession();
        }
        $searchSession->useFederatedCourseCatalogView();
        $termLookupSession->useFederatedCourseCatalogView();
        $topicLookupSession->useFederatedCourseCatalogView();
        $offeringLookupSession->useFederatedCourseCatalogView();

        $query = $searchSession->getCourseOfferingQuery();
        $keyword = $this->_getParam('keyword');
        if (!empty($keyword)) {
            $query->matchKeyword($keyword, new phpkit_type_URNInetType('urn:inet:middlebury.edu:search:boolean'), true);
        } else {
            // Match any keyword.
        }

        // Validate our arguments.
        $code = $this->_getParam('code');
        if (empty($code)) {
            throw new InvalidArgumentException('Missing the "code" parameter.');
        }
        $termId = $this->_helper->osidId->fromString('term.'.$this->_getParam('code'));
        $term = $termLookupSession->getTerm($termId);

        $query->matchTermId($termId, true);

        $genera = 'topic.subject';
        $area = $this->_getParam('area');
        if (!empty($area)) {
            $topicId = $this->_helper->osidId->fromString($genera.'.'.$area);
            $topic = $topicLookupSession->getTopic($topicId);
            $query->matchTopicId($topicId, true);
        }

        $offerings = $searchSession->getCourseOfferingsByQuery($query);
        $result = ['courses' => []];
        $seen = [];
        while ($offerings->hasNext()) {
            $offering = $offerings->getNextCourseOffering();
            $course = $offering->getCourse();
            $courseIdString = $course->getId()->getIdentifier();
            if (in_array($courseIdString, $seen)) {
                continue;
            }
            $seen[] = $courseIdString;
            $result['courses'][] = $this->_getCourseResult($course, $termId, $offeringLookupSession);
        }
        if (version_compare(\PHP_VERSION, '5.4.0') >= 0) {
            echo json_encode($result, \JSON_PRETTY_PRINT);
        } else {
            echo json_encode($result);
        }
    }

    protected function _getCourseResult(osid_course_Course $course, osid_id_Id $termId, osid_course_CourseOfferingLookupSession $offeringLookupSession)
    {
        preg_match('/^([A-Z]{3,4})(.+)$/', $course->getNumber(), $number_matches);
        $courseResult = [
            'title' => $course->getTitle(),
            'description' => $course->getDescription(),
            'subject' => $number_matches[1],
            'courseNumber' => $number_matches[2],
            'sections' => [],
        ];
        // Add the sections.
        $offerings = $offeringLookupSession->getCourseOfferingsByTermForCourse($termId, $course->getId());
        while ($offerings->hasNext()) {
            $offering = $offerings->getNextCourseOffering();
            $bannerIdentifiersRecord = $offering->getCourseOfferingRecord(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:banner_identifiers'));
            $sectionResult = [
                'crn' => $bannerIdentifiersRecord->getCourseReferenceNumber(),
                'courseSection' => $offering->getNumber(),
                'schedule' => $offering->getScheduleInfo(),
            ];
            // Instructors.
            $instructorsRecord = $offering->getCourseOfferingRecord(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors'));
            $instructors = $instructorsRecord->getInstructors();
            $instructorsResult = [];
            while ($instructors->hasNext()) {
                $instructor = $instructors->getNextResource();
                $instructorsResult[] = $instructor->getDisplayName();
            }
            if (!empty($instructorsResult)) {
                $sectionResult['instructor'] = implode(', ', $instructorsResult);
            }
            // Location.
            if ($offering->hasLocation()) {
                $location = $offering->getLocation();
                $locationRecord = $location->getResourceRecord(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:location'));
                $sectionResult['building'] = $locationRecord->getBuildingDisplayName();
                if ($locationRecord->getRoom()) {
                    $sectionResult['room'] = $locationRecord->getRoom();
                }
            }

            $courseResult['sections'][] = $sectionResult;
        }

        return $courseResult;
    }
}
