<?php
/**
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Controller;

use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use App\Service\Osid\TermHelper;
use App\Service\Osid\TopicHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * A controller for working with courses.
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Offerings extends AbstractController
{

    /**
     * @var \App\Service\Osid\Runtime
     */
    private $osidRuntime;

    /**
     * @var \App\Service\Osid\IdMap
     */
    private $osidIdMap;

    /**
     * @var \App\Service\Osid\TermHelper
     */
    private $osidTermHelper;

    /**
     * @var \App\Service\Osid\TopicHelper
     */
    private $osidTopicHelper;

    /**
     * Construct a new Catalogs controller.
     *
     * @param \App\Service\Osid\Runtime $osidRuntime
     *   The osid.runtime service.
     * @param \App\Service\Osid\IdMap $osidIdMap
     *   The osid.id_map service.
     * @param \App\Service\Osid\TermHelper $osidTermHelper
     *   The osid.term_helper service.
     * @param \App\Service\Osid\TopicHelper $osidTopicHelper
     *   The osid.topic_helper service.
     */
    public function __construct(Runtime $osidRuntime, IdMap $osidIdMap, TermHelper $osidTermHelper, TopicHelper $osidTopicHelper) {
        $this->osidRuntime = $osidRuntime;
        $this->osidIdMap = $osidIdMap;
        $this->osidTermHelper = $osidTermHelper;
        $this->osidTopicHelper = $osidTopicHelper;

        $this->wildcardStringMatchType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:search:wildcard');
        $this->booleanStringMatchType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:search:boolean');
        $this->instructorType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
        $this->enrollmentType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:enrollment');
        $this->locationType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:location');
        $this->alternateType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates');
        $this->weeklyScheduleType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule');

        $this->subjectType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.subject');
        $this->departmentType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.department');
        $this->divisionType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.division');
        $this->requirementType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.requirement');
        $this->levelType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.level');
        $this->blockType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.block');
        $this->instructionMethodType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.instruction_method');

        $this->termType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:terms');

        $this->campusType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:resource.place.campus');
    }

    /**
     * Print out a list of all courses.
     *
     * @return void
     *
     * @since 4/21/09
     */
    public function listAction()
    {
        if ($catalog) {
            $catalogId = $this->osidRuntimeId->fromString($catalog);
            $lookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSessionForCatalog($catalogId);
            $this->view->title = $lookupSession->getCourseCatalog()->getDisplayName();
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
            $this->view->title = 'All Catalogs';
        }
        $lookupSession->useFederatedCourseCatalogView();

        // Add our parameters to the search query
        if ($term) {
            if ('CURRENT' == $term) {
                $termId = $this->osidRuntimeTerms->getNextOrLatestTermId();
            } else {
                $termId = $this->osidRuntimeId->fromString($term);
            }

            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();

            $this->view->term = $termLookupSession->getTerm($termId);

            $this->view->offerings = $lookupSession->getCourseOfferingsByTerm($this->view->term->getId());
        } else {
            $this->view->offerings = $lookupSession->getCourseOfferings();
        }

        $this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
        $this->view->headTitle($this->view->title);

        $this->view->menuIsOfferings = true;

        $this->view->offeringsTitle = 'Sections';

        // Don't do the work to display instructors if we have a very large number of
        // offerings.
        if ($this->view->offerings->available() > 200) {
            $this->view->hideOfferingInstructors = true;
        }

        $this->render('offerings', null, true);
    }

    /**
     * Answer search results as an xml feed.
     *
     * @return void
     *
     * @since 10/21/09
     */
     #[Route('/offerings/searchxml/{catalog}/{term}', name: 'search_offerings')]
     public function searchxml(string $catalog = NULL, $term = NULL)
    {
        $this->_helper->layout->disableLayout();
        $this->getResponse()->setHeader('Content-Type', 'text/xml');

        $this->search($catalog, $term);
        $this->view->sections = $this->searchSession->getCourseOfferingsByQuery($this->query);

        // Set the next and previous terms
        if (isset($this->view->term)) {
            $terms = $this->termLookupSession->getTerms();
            while ($terms->hasNext()) {
                $term = $terms->getNextTerm();
                if ($term->getId()->isEqual($this->view->term->getId())) {
                    if (isset($lastTerm)) {
                        $this->view->nextTerm = $lastTerm;
                    }
                    if ($terms->hasNext()) {
                        $this->view->previousTerm = $terms->getNextTerm();
                    }
                    break;
                }
                $lastTerm = $term;
            }
        }
        // Reset the terms list as due to caching, we will have just wiped out the statement above.
        //
        // It would be better to fix this in the banner_course_CachingPdoQueryList, but
        // I haven't yet figured out how to determine if a result cursor
        // has been closed or not. See:
        //
        // http://stackoverflow.com/questions/1608427/how-can-i-determine-if-a-pdo-statement-cursor-is-closed
        $this->view->terms = $this->termLookupSession->getTerms();

        $this->view->feedTitle = 'Course Offering Results';
        $this->view->feedLink = $this->_helper->pathAsAbsoluteUrl($this->view->url($this->view->searchParams));
        $this->postDispatch();
    }

    #[Route('/offerings/search/{catalog}/{term}', name: 'search_offerings')]
    public function search(string $catalog = NULL, $term = NULL)
    {
        if ($catalog) {
            $catalogId = $catalogId = $this->osidIdMap->fromString($catalog);
            $offeringSearchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
            $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSessionForCatalog($catalogId);
            $topicSearchSession = $this->osidRuntime->getCourseManager()->getTopicSearchSessionForCatalog($catalogId);
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $resourceLookupSession = $this->osidRuntime->getCourseManager()->getResourceManager()->getResourceLookupSessionForBin($catalogId);
            $this->view->title = 'Search in '.$offeringSearchSession->getCourseCatalog()->getDisplayName();
        } else {
            $offeringSearchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSession();
            $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
            $topicSearchSession = $this->osidRuntime->getCourseManager()->getTopicSearchSession();
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $resourceLookupSession = $this->osidRuntime->getCourseManager()->getResourceManager()->getResourceLookupSession();
            $this->view->title = 'Search in All Catalogs';
        }
        $termLookupSession->useFederatedCourseCatalogView();
        $offeringSearchSession->useFederatedCourseCatalogView();

        /*********************************************************
         * Build option lists for the search form
         *********************************************************/

        // Term
        if ('ANY' == $term) {
            // Don't set a term
        } elseif (!$term || 'CURRENT' == $term) {
            // When accessing the "current" term via xml, use the term we are in.
            // When displaying the search interface, use the next upcoming term.
            if ('searchxml' == $this->_getParam('action')) {
                $termId = $this->osidRuntimeTerms->getCurrentTermId($offeringSearchSession->getCourseCatalogId());
            } else {
                $termId = $this->osidRuntimeTerms->getNextOrLatestTermId($offeringSearchSession->getCourseCatalogId());
            }
        } else {
            $termId = $this->osidRuntimeId->fromString($term);
        }

        // Topics
        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->departmentType, true);
        // if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
        //     $record = $topicQuery->getTopicQueryRecord($this->termType);
        //     $record->matchTermId($termId, true);
        // }
        $search = $topicSearchSession->getTopicSearch();
        $order = $topicSearchSession->getTopicSearchOrder();
        $order->orderByDisplayName();
        $search->orderTopicResults($order);
        $searchResults = $topicSearchSession->getTopicsBySearch($topicQuery, $search);
        $this->view->departments = $searchResults->getTopics();

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->subjectType, true);
        // if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
        //     $record = $topicQuery->getTopicQueryRecord($this->termType);
        //     $record->matchTermId($termId, true);
        // }
        $search = $topicSearchSession->getTopicSearch();
        $order = $topicSearchSession->getTopicSearchOrder();
        $order->orderByDisplayName();
        $search->orderTopicResults($order);
        $searchResults = $topicSearchSession->getTopicsBySearch($topicQuery, $search);
        $this->view->subjects = $searchResults->getTopics();

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->divisionType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $this->view->divisions = $topicSearchSession->getTopicsByQuery($topicQuery);

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->requirementType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $this->view->requirements = $topicSearchSession->getTopicsByQuery($topicQuery);

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->levelType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $this->view->levels = $topicSearchSession->getTopicsByQuery($topicQuery);

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->blockType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $this->view->blocks = $topicSearchSession->getTopicsByQuery($topicQuery);

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->instructionMethodType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $this->view->instructionMethods = $topicSearchSession->getTopicsByQuery($topicQuery);

        $this->view->genusTypes = $offeringLookupSession->getCourseOfferingGenusTypes();

        // Campuses -- only include if we have more than one.
        $campuses = $resourceLookupSession->getResourcesByGenusType($this->campusType);
        if ($campuses->hasNext() && $campuses->getNextResource() && $campuses->hasNext()) {
            $this->view->campuses = $resourceLookupSession->getResourcesByGenusType($this->campusType);
        }

        /*********************************************************
         * Set up and run our search query.
         *********************************************************/

        $query = $offeringSearchSession->getCourseOfferingQuery();
        $search = $offeringSearchSession->getCourseOfferingSearch();
        $this->view->searchParams = [];

        // Make our session and query available to the XML version of this action.
        $this->termLookupSession = $termLookupSession;
        $this->view->terms = $termLookupSession->getTerms();

        // Add our parameters to the search query
        if ($term) {
            $this->view->searchParams['term'] = $term;

            if (isset($termId)) {
                $query->matchTermId($termId, true);

                $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
                $termLookupSession->useFederatedCourseCatalogView();
                $this->view->term = $termLookupSession->getTerm($termId);
                $this->view->selectedTermId = $termId;

                $this->view->title .= ' '.$this->view->term->getDisplayName();
            }
        }

        if ($this->_getParam('department')) {
            if (is_array($this->_getParam('department'))) {
                $departments = $this->_getParam('department');
            } else {
                $departments = [$this->_getParam('department')];
            }

            if (count($departments)) {
                foreach ($departments as $idString) {
                    $id = $this->osidRuntimeId->fromString($idString);
                    $query->matchTopicId($id, true);
                    // set the first as the selected one if multiple.
                    if (!isset($this->view->selectedDepartmentId)) {
                        $this->view->selectedDepartmentId = $id;
                    }
                }
                $this->view->searchParams['department'] = $departments;
            }
        }

        if ($this->_getParam('subject')) {
            if (is_array($this->_getParam('subject'))) {
                $subjects = $this->_getParam('subject');
            } else {
                $subjects = [$this->_getParam('subject')];
            }

            if (count($subjects)) {
                foreach ($subjects as $idString) {
                    $id = $this->osidRuntimeId->fromString($idString);
                    $query->matchTopicId($id, true);
                    // set the first as the selected one if multiple.
                    if (!isset($this->view->selectedSubjectId)) {
                        $this->view->selectedSubjectId = $id;
                    }
                }
                $this->view->searchParams['subject'] = $subjects;
            }
        }

        if ($this->_getParam('division')) {
            if (is_array($this->_getParam('division'))) {
                $divisions = $this->_getParam('division');
            } else {
                $divisions = [$this->_getParam('division')];
            }

            if (count($divisions)) {
                foreach ($divisions as $idString) {
                    $id = $this->osidRuntimeId->fromString($idString);
                    $query->matchTopicId($id, true);
                    // set the first as the selected one if multiple.
                    if (!isset($this->view->selectedDivisionId)) {
                        $this->view->selectedDivisionId = $id;
                    }
                }
                $this->view->searchParams['division'] = $divisions;
            }
        }

        $this->view->selectedRequirementIds = [];
        if ($this->_getParam('requirement')) {
            if (is_array($this->_getParam('requirement'))) {
                $requirements = $this->_getParam('requirement');
            } else {
                $requirements = [$this->_getParam('requirement')];
            }

            if (count($requirements)) {
                foreach ($requirements as $idString) {
                    $id = $this->osidRuntimeId->fromString($idString);
                    $query->matchTopicId($id, true);
                    $this->view->selectedRequirementIds[] = $id;
                }
                $this->view->searchParams['requirement'] = $requirements;
            }
        }

        $this->view->selectedLevelIds = [];
        if ($this->_getParam('level')) {
            if (is_array($this->_getParam('level'))) {
                $levels = $this->_getParam('level');
            } else {
                $levels = [$this->_getParam('level')];
            }

            if (count($levels)) {
                foreach ($levels as $idString) {
                    $id = $this->osidRuntimeId->fromString($idString);
                    $query->matchTopicId($id, true);
                    $this->view->selectedLevelIds[] = $id;
                }
                $this->view->searchParams['level'] = $levels;
            }
        }

        $this->view->selectedBlockIds = [];
        if ($this->_getParam('block')) {
            if (is_array($this->_getParam('block'))) {
                $blocks = $this->_getParam('block');
            } else {
                $blocks = [$this->_getParam('block')];
            }

            if (count($blocks)) {
                foreach ($blocks as $idString) {
                    $id = $this->osidRuntimeId->fromString($idString);
                    $query->matchTopicId($id, true);
                    $this->view->selectedBlockIds[] = $id;
                }
                $this->view->searchParams['block'] = $blocks;
            }
        }

        $this->view->selectedInstructionMethodIds = [];
        if ($this->_getParam('instruction_method')) {
            if (is_array($this->_getParam('instruction_method'))) {
                $instructionMethods = $this->_getParam('instruction_method');
            } else {
                $instructionMethods = [$this->_getParam('instruction_method')];
            }

            if (count($instructionMethods)) {
                foreach ($instructionMethods as $idString) {
                    $id = $this->osidRuntimeId->fromString($idString);
                    $query->matchTopicId($id, true);
                    $this->view->selectedInstructionMethodIds[] = $id;
                }
                $this->view->searchParams['instruction_method'] = $instructionMethods;
            }
        }

        $this->view->selectedGenusTypes = [];
        if ($this->_getParam('type')) {
            if (is_array($this->_getParam('type'))) {
                $genusTypes = $this->_getParam('type');
            } else {
                $genusTypes = [$this->_getParam('type')];
            }

            if (count($genusTypes)) {
                foreach ($genusTypes as $typeString) {
                    $genusType = $this->osidRuntimeType->fromString($typeString);
                    $query->matchGenusType($genusType, true);
                    $this->view->selectedGenusTypes[] = $genusType;
                }
                $this->view->searchParams['type'] = $genusTypes;
            }
        }

        // Campuses
        $this->view->selectedCampusIds = [];
        if ($this->_getParam('location')) {
            if (is_array($this->_getParam('location'))) {
                $campuses = $this->_getParam('location');
            } else {
                $campuses = [$this->_getParam('location')];
            }

            if (count($campuses)) {
                foreach ($campuses as $idString) {
                    $id = $this->osidRuntimeId->fromString($idString);
                    $query->matchLocationId($id, true);
                    $this->view->selectedCampusIds[] = $id;
                }
                $this->view->searchParams['location'] = $campuses;
            }
        }

        // Set the default selection to lecture/seminar if the is a new search
        if (!$this->_getParam('search') && !count($this->view->selectedGenusTypes)) {
            $this->view->selectedGenusTypes = $this->osidRuntimeTypes->getDefaultGenusTypes();
        }

        if ($query->hasRecordType($this->weeklyScheduleType)) {
            $queryRecord = $query->getCourseOfferingQueryRecord($this->weeklyScheduleType);

            if ($this->_getParam('days')) {
                if (is_array($this->_getParam('days'))) {
                    $days = $this->_getParam('days');
                } else {
                    $days = [$this->_getParam('days')];
                }

                if (count($days)) {
                    if ('exclusive' == $this->_getParam('days_mode')) {
                        $this->view->searchParams['days_mode'] = 'exclusive';

                        if (!in_array('sunday', $days)) {
                            $queryRecord->matchMeetsSunday(false);
                        }

                        if (!in_array('monday', $days)) {
                            $queryRecord->matchMeetsMonday(false);
                        }

                        if (!in_array('tuesday', $days)) {
                            $queryRecord->matchMeetsTuesday(false);
                        }

                        if (!in_array('wednesday', $days)) {
                            $queryRecord->matchMeetsWednesday(false);
                        }

                        if (!in_array('thursday', $days)) {
                            $queryRecord->matchMeetsThursday(false);
                        }

                        if (!in_array('friday', $days)) {
                            $queryRecord->matchMeetsFriday(false);
                        }

                        if (!in_array('saturday', $days)) {
                            $queryRecord->matchMeetsSaturday(false);
                        }
                    }
                    // Inclusive search.
                    else {
                        $this->view->searchParams['days_mode'] = 'inclusive';

                        if (in_array('sunday', $days)) {
                            $queryRecord->matchMeetsSunday(true);
                        }

                        if (in_array('monday', $days)) {
                            $queryRecord->matchMeetsMonday(true);
                        }

                        if (in_array('tuesday', $days)) {
                            $queryRecord->matchMeetsTuesday(true);
                        }

                        if (in_array('wednesday', $days)) {
                            $queryRecord->matchMeetsWednesday(true);
                        }

                        if (in_array('thursday', $days)) {
                            $queryRecord->matchMeetsThursday(true);
                        }

                        if (in_array('friday', $days)) {
                            $queryRecord->matchMeetsFriday(true);
                        }

                        if (in_array('saturday', $days)) {
                            $queryRecord->matchMeetsSaturday(true);
                        }
                    }

                    $this->view->searchParams['days'] = $days;
                }
            } else {
                $this->view->searchParams['days'] = [];
            }

            if ($this->_getParam('time_start') || $this->_getParam('time_end')) {
                $start = (int) $this->_getParam('time_start');
                $end = (int) $this->_getParam('time_end');
                if (!$end) {
                    $end = 86400;
                }
                if ($start > 0 || $end < 86400) {
                    $queryRecord->matchMeetingTime($start, $end, true);
                }

                $this->view->timeStart = $start;
                $this->view->timeEnd = $end;
                $this->view->searchParams['time_start'] = $start;
                $this->view->searchParams['time_end'] = $end;
            } else {
                $this->view->timeStart = 0;
                $this->view->timeEnd = 86400;
            }
        }

        if ($this->_getParam('keywords')) {
            $query->matchKeyword($this->_getParam('keywords'), $this->booleanStringMatchType, true);
            $this->view->keywords = $this->_getParam('keywords');
            $this->view->searchParams['keywords'] = $this->_getParam('keywords');
        } else {
            $this->view->keywords = '';
        }

        if ($this->_getParam('instructor')) {
            if ($query->hasRecordType($this->instructorType)) {
                $queryRecord = $query->getCourseOfferingQueryRecord($this->instructorType);
                $queryRecord->matchInstructorId($this->osidRuntimeId->fromString($this->_getParam('instructor')), true);
            }
            $this->view->searchParams['instructor'] = $this->_getParam('instructor');
        }

        if ($this->_getParam('enrollable')) {
            if ($query->hasRecordType($this->enrollmentType)) {
                $queryRecord = $query->getCourseOfferingQueryRecord($this->enrollmentType);
                $queryRecord->matchEnrollable(true);
            }
            $this->view->searchParams['enrollable'] = $this->_getParam('enrollable');
        }

        // Make our session and query available to the XML version of this action.
        $this->searchSession = $offeringSearchSession;
        $this->query = $query;

        // Run the query if submitted.
        if ($this->_getParam('search')) {
            $this->view->searchParams['search'] = $this->_getParam('search');
            $this->view->paginator = new Zend_Paginator(new Paginator_Adaptor_CourseOfferingSearch($offeringSearchSession, $query));
            $this->view->paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        /*********************************************************
         * Options for output
         *********************************************************/

        $this->setSelectedCatalogId($offeringSearchSession->getCourseCatalogId());
        $this->view->headTitle($this->view->title);

        $this->view->menuIsSearch = true;
    }

    #[Route('/offerings/view/{id}', name: 'view_offering')]
    public function viewAction($id)
    {
        $data = $this->getOfferingDataByIdString($id);

        // Bookmarked Courses and Schedules
        $data['bookmarks_CourseId'] = $data['offering']->getCourseId();

        $data['menuIsOfferings'] = true;

        // Set the selected Catalog Id.
        $catalogSession = $this->osidRuntime->getCourseManager()->getCourseOfferingCatalogSession();
        $catalogIds = $catalogSession->getCatalogIdsByCourseOffering($data['offering']->getId());
        if ($catalogIds->hasNext()) {
            $catalogId = $catalogIds->getNextId();
            $data['menuCatalogSelectedId'] = $catalogId;
            $data['menuCatalogSelected'] = $this->osidRuntime->getCourseManager()->getCourseCatalogLookupSession()->getCourseCatalog($catalogId);
        }

        return $this->render('offerings/view.html.twig', $data);
    }

    #[Route('/offerings/viewxml/{id}', name: 'view_offering_xml')]
    public function viewxmlAction($id)
    {
        $data = [];
        $data['offerings'] = [$this->getOfferingDataByIdString($id)];
        $offering = $data['offerings'][0]['offering'];

        $data['title'] = $offering->getDisplayName();
        $data['feedLink'] = $this->generateUrl('view_offering', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL);

        $data['previousTerm'] = NULL;
        $data['term'] = $offering->getTerm();
        $data['nextTerm'] = NULL;
        $data['terms'] = NULL;

        $response = new Response($this->renderView('offerings/search.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }

    protected function getOfferingDataByIdString($idString)
    {
        $id = $this->osidIdMap->fromString($idString);
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
        $lookupSession->useFederatedCourseCatalogView();
        return $this->getOfferingData($lookupSession->getCourseOffering($id));
    }

    protected function getOfferingData(\osid_course_CourseOffering $offering) {
        $id = $offering->getId();

        // Templates can access basic getter methods on the offering itself.
        $data = ['offering' => $offering];

        // Load the topics into our view
        $data = array_merge(
            $data,
            $this->osidTopicHelper->asTypedArray($offering->getTopics())
        );

        $data['location'] = NULL;
        if ($offering->hasLocation()) {
            $data['location'] = $offering->getLocation();
        }

        $data['weekly_schedule'] = NULL;
        $weeklyScheduleType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule');
        if ($offering->hasRecordType($weeklyScheduleType)) {
            $data['weekly_schedule'] = $offering->getCourseOfferingRecord($weeklyScheduleType);
        }

        // Instructors
        $data['instructors'] = NULL;
        $instructorsType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
        if ($offering->hasRecordType($instructorsType)) {
            $instructorsRecord = $offering->getCourseOfferingRecord($instructorsType);
            $instructors = $instructorsRecord->getInstructors();
            $data['instructors'] = [];
            while ($instructors->hasNext()) {
                $data['instructors'][] = $instructors->getNextResource();
            }
        }

        // Alternates.
        $data['is_primary'] = TRUE;
        $data['alternates'] = NULL;
        if ($offering->hasRecordType($this->alternateType)) {
            $record = $offering->getCourseOfferingRecord($this->alternateType);
            $data['is_primary'] = $record->isPrimary();
            if ($record->hasAlternates()) {
                $data['alternates'] = [];
                $alternates = $record->getAlternates();
                while ($alternates->hasNext()) {
                    $alternate = $alternates->getNextCourseOffering();
                    $alternate->is_primary = FALSE;
                    if ($alternate->hasRecordType($this->alternateType)) {
                        $alternateRecord = $alternate->getCourseOfferingRecord($this->alternateType);
                        if ($alternateRecord->isPrimary()) {
                            $alternate->is_primary = TRUE;
                        }
                    }
                    $data['alternates'][] = $alternate;
                }

            }
        }

        // Availability link. @todo
        $data['availabilityLink'] = NULL;
        //$this->getAvailabilityLink($this->offering);

        $data['properties'] = [];
        $properties = $offering->getProperties();
        while ($properties->hasNext()) {
            $data['properties'][] = $properties->getNextProperty();
        }

        // Other offerings.
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
        $lookupSession->useFederatedCourseCatalogView();
        $data['offeringsTitle'] = 'All Sections';
        $data['offerings'] = $lookupSession->getCourseOfferingsByTermForCourse(
            $offering->getTermId(),
            $offering->getCourseId()
        );

        return $data;
    }

}
