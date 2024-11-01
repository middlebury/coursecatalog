<?php
/**
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Controller;

use App\Paginator\CourseOfferingSearchAdaptor;
use App\Service\Osid\DataLoader;
use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use App\Service\Osid\TermHelper;
use App\Service\Osid\TypeHelper;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function __construct(
        private Runtime $osidRuntime,
        private IdMap $osidIdMap,
        private TermHelper $osidTermHelper,
        private DataLoader $osidDataLoader,
        private TypeHelper $osidTypeHelper,
    ) {
        $this->wildcardStringMatchType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:search:wildcard');
        $this->booleanStringMatchType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:search:boolean');
        $this->instructorType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
        $this->enrollmentType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:enrollment');
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
     */
    #[Route('/offerings/list/{catalogId}/{termId}', name: 'list_offerings')]
    public function listAction(\osid_id_Id $catalogId, \osid_id_Id $termId)
    {
        $data = [];
        $data['catalogId'] = $catalogId;
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSessionForCatalog($catalogId);
        $data['title'] = $lookupSession->getCourseCatalog()->getDisplayName();
        $lookupSession->useFederatedCourseCatalogView();

        // Add our parameters to the search query
        if ('CURRENT' == $this->osidIdMap->toString($termId)) {
            $termId = $this->osidTermHelper->getNextOrLatestTermId($catalogId);
        }

        $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
        $termLookupSession->useFederatedCourseCatalogView();

        $data['term'] = $termLookupSession->getTerm($termId);

        $offerings = $lookupSession->getCourseOfferingsByTerm($data['term']->getId());
        $data['offerings'] = [];
        while ($offerings->hasNext()) {
            $offering = $offerings->getNextCourseOffering();
            $data['offerings'][] = $this->osidDataLoader->getOfferingData($offering);
        }

        $data['offeringsTitle'] = 'Sections';

        // Don't do the work to display instructors if we have a very large number of
        // offerings.
        if ($offerings->available() > 200) {
            $data['hideOfferingInstructors'] = true;
        }

        return $this->render('offerings/list.html.twig', $data);
    }

    /**
     * Answer search results as an xml feed.
     *
     * @return void
     *
     * @since 10/21/09
     */
    #[Route('/offerings/searchxml/{catalogId}/{termId}', name: 'search_offerings_xml')]
    public function searchxml(Request $request, ?\osid_id_Id $catalogId = null, ?\osid_id_Id $termId = null)
    {
        [$data, $searchSession, $query, $termLookupSession] = $this->prepareSearch($request, $catalogId, $termId);
        // Actually run the query.
        $offerings = $searchSession->getCourseOfferingsByQuery($query);
        $data['offerings'] = [];
        while ($offerings->hasNext()) {
            $offering = $offerings->getNextCourseOffering();
            $offeringData = $this->osidDataLoader->getOfferingData($offering);
            $offeringData['alternates'] = $this->osidDataLoader->getOfferingAlternates($offering);
            $data['offerings'][] = $offeringData;
        }

        // Set the next and previous terms
        if (isset($data['term'])) {
            $terms = $termLookupSession->getTerms();
            while ($terms->hasNext()) {
                $term = $terms->getNextTerm();
                if ($term->getId()->isEqual($data['term']->getId())) {
                    if (isset($lastTerm)) {
                        $data['nextTerm'] = $lastTerm;
                    }
                    if ($terms->hasNext()) {
                        $data['previousTerm'] = $terms->getNextTerm();
                    }
                    break;
                }
                $lastTerm = $term;
            }
        }

        $data['title'] = 'Course Offering Results';
        $data['feedLink'] = $this->generateUrl(
            'search_courses_xml',
            $data['searchParams'],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $response = new Response($this->renderView('offerings/search.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');

        return $response;
    }

    #[Route('/offerings/search/{catalogId}', name: 'search_offerings')]
    public function search(Request $request, PaginatorInterface $paginator, ?\osid_id_Id $catalogId = null)
    {
        [$data, $searchSession, $query, $termLookupSession] = $this->prepareSearch($request, $catalogId);

        $data['form_action'] = $this->generateUrl(
            'search_offerings',
            [
                'catalogId' => $catalogId,
            ]
        );

        // Run the query if submitted.
        $data['paginator'] = null;
        if ($request->get('search')) {
            $data['searchParams']['search'] = $request->get('search');
            $data['paginator'] = $paginator->paginate(
                new CourseOfferingSearchAdaptor(
                    $searchSession,
                    $query,
                    null,
                    [$this, 'getOfferingData'],
                ),
                $request->query->getInt('page', 1), /* page number */
                10 /* limit per page */
            );
        }

        /*********************************************************
         * Data for layouts rendering.
         *********************************************************/
        $data['selectedCatalogId'] = $searchSession->getCourseCatalogId();
        $data['menuIsSearch'] = true;

        return $this->render('offerings/search.html.twig', $data);
    }

    #[Route('/offerings/view/{offeringId}', name: 'view_offering')]
    public function viewAction(\osid_id_Id $offeringId)
    {
        $data = $this->osidDataLoader->getOfferingDataById($offeringId);
        $data['alternates'] = $this->osidDataLoader->getOfferingAlternates($data['offering']);

        // Bookmarked Courses and Schedules
        $data['bookmarks_CourseId'] = $data['offering']->getCourseId();

        $data['menuIsOfferings'] = true;

        // Set the selected Catalog Id.
        $catalogSession = $this->osidRuntime->getCourseManager()->getCourseOfferingCatalogSession();
        $catalogIds = $catalogSession->getCatalogIdsByCourseOffering($data['offering']->getId());
        if ($catalogIds->hasNext()) {
            $catalogId = $catalogIds->getNextId();
            $data['catalogId'] = $catalogId;
        } else {
            $data['catalogId'] = null;
        }

        return $this->render('offerings/view.html.twig', $data);
    }

    #[Route('/offerings/viewxml/{offeringId}', name: 'view_offering_xml')]
    public function viewxmlAction(\osid_id_Id $offeringId)
    {
        $data = [];
        $offeringData = $this->osidDataLoader->getOfferingDataById($offeringId);
        $offering = $offeringData['offering'];
        $offeringData['alternates'] = $this->osidDataLoader->getOfferingAlternates($offering);
        $data['offerings'] = [$offeringData];

        // Set the selected Catalog Id.
        $catalogSession = $this->osidRuntime->getCourseManager()->getCourseOfferingCatalogSession();
        $catalogIds = $catalogSession->getCatalogIdsByCourseOffering($offering->getId());
        if ($catalogIds->hasNext()) {
            $catalogId = $catalogIds->getNextId();
            $data['catalogId'] = $catalogId;
        } else {
            $data['catalogId'] = null;
        }

        $data['title'] = $offering->getDisplayName();
        $data['feedLink'] = $this->generateUrl('view_offering', ['offeringId' => $offeringId], UrlGeneratorInterface::ABSOLUTE_URL);

        $data['previousTerm'] = null;
        $data['term'] = $offering->getTerm();
        $data['nextTerm'] = null;
        $data['terms'] = null;

        $response = new Response($this->renderView('offerings/search.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');

        return $response;
    }

    /**
     * Run a search query and provide the data suitable for templating.
     *
     * @param Request         $request
     *                                   The request that contains the search parameters
     * @param osid_id_Id|null $catalogId
     *                                   The catalog to search in or NULL for all catalogs
     *
     * @return array
     *               The parts of the search preparation in an array: data, searchSession,
     *               and query
     */
    protected function prepareSearch(Request $request, ?\osid_id_Id $catalogId = null)
    {
        $termIdString = $request->get('term');
        $data = [];
        if ($catalogId) {
            $offeringSearchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
            $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSessionForCatalog($catalogId);
            $topicSearchSession = $this->osidRuntime->getCourseManager()->getTopicSearchSessionForCatalog($catalogId);
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $resourceLookupSession = $this->osidRuntime->getCourseManager()->getResourceManager()->getResourceLookupSessionForBin($catalogId);
            $data['title'] = 'Search in '.$offeringSearchSession->getCourseCatalog()->getDisplayName();
            $data['catalogId'] = $catalogId;
        } else {
            $offeringSearchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSession();
            $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
            $topicSearchSession = $this->osidRuntime->getCourseManager()->getTopicSearchSession();
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $resourceLookupSession = $this->osidRuntime->getCourseManager()->getResourceManager()->getResourceLookupSession();
            $data['title'] = 'Search in All Catalogs';
            $data['catalogId'] = null;
        }
        $termLookupSession->useFederatedCourseCatalogView();
        $offeringSearchSession->useFederatedCourseCatalogView();

        /*********************************************************
         * Build option lists for the search form
         *********************************************************/

        // Term
        $data['term'] = null;
        $data['nextTerm'] = null;
        $data['previousTerm'] = null;
        if ('ANY' == $termIdString) {
            // Don't set a term
            $termId = null;
        } elseif (!$termIdString || 'CURRENT' == $termIdString) {
            // When accessing the "current" term via xml, use the term we are in.
            // When displaying the search interface, use the next upcoming term.
            if ('searchxml' == $request->get('action')) {
                $termId = $this->osidTermHelper->getCurrentTermId($offeringSearchSession->getCourseCatalogId());
            } else {
                $termId = $this->osidTermHelper->getNextOrLatestTermId($offeringSearchSession->getCourseCatalogId());
            }
        } else {
            $termId = $this->osidIdMap->fromString($termIdString);
        }
        if (isset($termId)) {
            $data['term'] = $termLookupSession->getTerm($termId);
        }

        // Topics
        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->departmentType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $search = $topicSearchSession->getTopicSearch();
        $order = $topicSearchSession->getTopicSearchOrder();
        $order->orderByDisplayName();
        $search->orderTopicResults($order);
        $searchResults = $topicSearchSession->getTopicsBySearch($topicQuery, $search);
        $topics = $searchResults->getTopics();
        $data['departments'] = [];
        while ($topics->hasNext()) {
            $data['departments'][] = $topics->getNextTopic();
        }

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->subjectType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $search = $topicSearchSession->getTopicSearch();
        $order = $topicSearchSession->getTopicSearchOrder();
        $order->orderByDisplayName();
        $search->orderTopicResults($order);
        $searchResults = $topicSearchSession->getTopicsBySearch($topicQuery, $search);
        $topics = $searchResults->getTopics();
        $data['subjects'] = [];
        while ($topics->hasNext()) {
            $data['subjects'][] = $topics->getNextTopic();
        }

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->divisionType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $topics = $topicSearchSession->getTopicsByQuery($topicQuery);
        $data['divisions'] = [];
        while ($topics->hasNext()) {
            $data['divisions'][] = $topics->getNextTopic();
        }

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->requirementType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $topics = $topicSearchSession->getTopicsByQuery($topicQuery);
        $data['requirements'] = [];
        while ($topics->hasNext()) {
            $data['requirements'][] = $topics->getNextTopic();
        }

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->levelType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $topics = $topicSearchSession->getTopicsByQuery($topicQuery);
        $data['levels'] = [];
        while ($topics->hasNext()) {
            $data['levels'][] = $topics->getNextTopic();
        }

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->blockType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $topics = $topicSearchSession->getTopicsByQuery($topicQuery);
        $data['blocks'] = [];
        while ($topics->hasNext()) {
            $data['blocks'][] = $topics->getNextTopic();
        }

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->instructionMethodType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $topics = $topicSearchSession->getTopicsByQuery($topicQuery);
        $data['instructionMethods'] = [];
        while ($topics->hasNext()) {
            $data['instructionMethods'][] = $topics->getNextTopic();
        }

        $genusTypes = $offeringLookupSession->getCourseOfferingGenusTypes();
        $data['genusTypes'] = [];
        while ($genusTypes->hasNext()) {
            $data['genusTypes'][] = $genusTypes->getNextType();
        }

        // Campuses -- only include if we have more than one.
        $data['campuses'] = [];
        $campuses = $resourceLookupSession->getResourcesByGenusType($this->campusType);
        if ($campuses->hasNext() && $campuses->getNextResource() && $campuses->hasNext()) {
            $campuses = $resourceLookupSession->getResourcesByGenusType($this->campusType);
            while ($campuses->hasNext()) {
                $data['campuses'][] = $campuses->getNextResource();
            }
        }

        /*********************************************************
         * Set up and run our search query.
         *********************************************************/

        $query = $offeringSearchSession->getCourseOfferingQuery();
        $search = $offeringSearchSession->getCourseOfferingSearch();
        $data['searchParams'] = [
            'catalogId' => $catalogId,
            'termId' => $termId,
        ];

        $data['terms'] = [];
        $terms = $termLookupSession->getTerms();
        $data['selectedTermId'] = null;
        while ($terms->hasNext()) {
            $term = $terms->getNextTerm();
            $data['terms'][] = $term;
        }

        // Add our parameters to the search query
        if ($termIdString) {
            $data['searchParams']['termId'] = $termId;

            if (isset($termId)) {
                $query->matchTermId($termId, true);

                $federatedTermLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
                $federatedTermLookupSession->useFederatedCourseCatalogView();
                $data['term'] = $federatedTermLookupSession->getTerm($termId);
                $data['selectedTermId'] = $termId;

                $data['title'] .= ' '.$data['term']->getDisplayName();
            }
        }

        $data['selectedDepartmentId'] = null;
        if ($request->get('department')) {
            if (is_array($request->get('department'))) {
                $departments = $request->get('department');
            } else {
                $departments = [$request->get('department')];
            }

            if (count($departments)) {
                foreach ($departments as $idString) {
                    $id = $this->osidIdMap->fromString($idString);
                    $query->matchTopicId($id, true);
                    // set the first as the selected one if multiple.
                    if (!isset($data['selectedDepartmentId'])) {
                        $data['selectedDepartmentId'] = $id;
                    }
                }
                $data['searchParams']['department'] = $departments;
            }
        }

        $data['selectedSubjectId'] = null;
        if ($request->get('subject')) {
            if (is_array($request->get('subject'))) {
                $subjects = $request->get('subject');
            } else {
                $subjects = [$request->get('subject')];
            }

            if (count($subjects)) {
                foreach ($subjects as $idString) {
                    $id = $this->osidIdMap->fromString($idString);
                    $query->matchTopicId($id, true);
                    // set the first as the selected one if multiple.
                    if (!isset($data['selectedSubjectId'])) {
                        $data['selectedSubjectId'] = $id;
                    }
                }
                $data['searchParams']['subject'] = $subjects;
            }
        }

        $data['selectedDivisionId'] = null;
        if ($request->get('division')) {
            if (is_array($request->get('division'))) {
                $divisions = $request->get('division');
            } else {
                $divisions = [$request->get('division')];
            }

            if (count($divisions)) {
                foreach ($divisions as $idString) {
                    $id = $this->osidIdMap->fromString($idString);
                    $query->matchTopicId($id, true);
                    // set the first as the selected one if multiple.
                    if (!isset($data['selectedDivisionId'])) {
                        $data['selectedDivisionId'] = $id;
                    }
                }
                $data['searchParams']['division'] = $divisions;
            }
        }

        $data['selectedRequirementIds'] = [];
        if ($request->get('requirement')) {
            if (is_array($request->get('requirement'))) {
                $requirements = $request->get('requirement');
            } else {
                $requirements = [$request->get('requirement')];
            }

            if (count($requirements)) {
                foreach ($requirements as $idString) {
                    $id = $this->osidIdMap->fromString($idString);
                    $query->matchTopicId($id, true);
                    $data['selectedRequirementIds'][] = $id;
                }
                $data['searchParams']['requirement'] = $requirements;
            }
        }

        $data['selectedLevelIds'] = [];
        if ($request->get('level')) {
            if (is_array($request->get('level'))) {
                $levels = $request->get('level');
            } else {
                $levels = [$request->get('level')];
            }

            if (count($levels)) {
                foreach ($levels as $idString) {
                    $id = $this->osidIdMap->fromString($idString);
                    $query->matchTopicId($id, true);
                    $data['selectedLevelIds'][] = $id;
                }
                $data['searchParams']['level'] = $levels;
            }
        }

        $data['selectedBlockIds'] = [];
        if ($request->get('block')) {
            if (is_array($request->get('block'))) {
                $blocks = $request->get('block');
            } else {
                $blocks = [$request->get('block')];
            }

            if (count($blocks)) {
                foreach ($blocks as $idString) {
                    $id = $this->osidIdMap->fromString($idString);
                    $query->matchTopicId($id, true);
                    $data['selectedBlockIds'][] = $id;
                }
                $data['searchParams']['block'] = $blocks;
            }
        }

        $data['selectedInstructionMethodIds'] = [];
        if ($request->get('instruction_method')) {
            if (is_array($request->get('instruction_method'))) {
                $instructionMethods = $request->get('instruction_method');
            } else {
                $instructionMethods = [$request->get('instruction_method')];
            }

            if (count($instructionMethods)) {
                foreach ($instructionMethods as $idString) {
                    $id = $this->osidIdMap->fromString($idString);
                    $query->matchTopicId($id, true);
                    $data['selectedInstructionMethodIds'][] = $id;
                }
                $data['searchParams']['instruction_method'] = $instructionMethods;
            }
        }

        $data['selectedGenusTypes'] = [];
        if ($request->get('type')) {
            if (is_array($request->get('type'))) {
                $genusTypes = $request->get('type');
            } else {
                $genusTypes = [$request->get('type')];
            }

            if (count($genusTypes)) {
                foreach ($genusTypes as $typeString) {
                    $genusType = $this->osidIdMap->typeFromString($typeString);
                    $query->matchGenusType($genusType, true);
                    $data['selectedGenusTypes'][] = $genusType;
                }
                $data['searchParams']['type'] = $genusTypes;
            }
        }

        // Campuses
        $data['selectedCampusIds'] = [];
        if ($request->get('location')) {
            if (is_array($request->get('location'))) {
                $campuses = $request->get('location');
            } else {
                $campuses = [$request->get('location')];
            }

            if (count($campuses)) {
                foreach ($campuses as $idString) {
                    $id = $this->osidIdMap->fromString($idString);
                    $query->matchLocationId($id, true);
                    $data['selectedCampusIds'][] = $id;
                }
                $data['searchParams']['location'] = $campuses;
            }
        }

        // Set the default selection to lecture/seminar if the is a new search
        if (!$request->get('search') && !count($data['selectedGenusTypes'])) {
            $data['selectedGenusTypes'] = $this->osidTypeHelper->getDefaultGenusTypes();
        }

        if ($query->hasRecordType($this->weeklyScheduleType)) {
            $queryRecord = $query->getCourseOfferingQueryRecord($this->weeklyScheduleType);

            $data['searchParams']['days'] = [];
            $data['searchParams']['days_mode'] = 'inclusive';

            if ($request->get('days')) {
                if (is_array($request->get('days'))) {
                    $days = $request->get('days');
                } else {
                    $days = [$request->get('days')];
                }

                if (count($days)) {
                    if ('exclusive' == $request->get('days_mode')) {
                        $data['searchParams']['days_mode'] = 'exclusive';

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
                        $data['searchParams']['days_mode'] = 'inclusive';

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

                    $data['searchParams']['days'] = $days;
                }
            }

            if ($request->get('time_start') || $request->get('time_end')) {
                $start = (int) $request->get('time_start');
                $end = (int) $request->get('time_end');
                if (!$end) {
                    $end = 86400;
                }
                if ($start > 0 || $end < 86400) {
                    $queryRecord->matchMeetingTime($start, $end, true);
                }

                $data['timeStart'] = $start;
                $data['timeEnd'] = $end;
                $data['searchParams']['time_start'] = $start;
                $data['searchParams']['time_end'] = $end;
            } else {
                $data['timeStart'] = 0;
                $data['timeEnd'] = 86400;
            }
        }

        if ($request->get('keywords')) {
            $query->matchKeyword($request->get('keywords'), $this->booleanStringMatchType, true);
            $data['keywords'] = $request->get('keywords');
            $data['searchParams']['keywords'] = $request->get('keywords');
        } else {
            $data['keywords'] = '';
        }

        if ($request->get('instructor')) {
            if ($query->hasRecordType($this->instructorType)) {
                $queryRecord = $query->getCourseOfferingQueryRecord($this->instructorType);
                $queryRecord->matchInstructorId($this->osidIdMap->fromString($request->get('instructor')), true);
            }
            $data['searchParams']['instructor'] = $request->get('instructor');
        }

        if ($request->get('enrollable')) {
            if ($query->hasRecordType($this->enrollmentType)) {
                $queryRecord = $query->getCourseOfferingQueryRecord($this->enrollmentType);
                $queryRecord->matchEnrollable(true);
            }
            $data['searchParams']['enrollable'] = $request->get('enrollable');
        }

        return [
            $data,
            $offeringSearchSession,
            $query,
            $termLookupSession,
        ];
    }

    /**
     * Callback to get offering data for paginated search results.
     *
     * @param \osid_course_CourseOffering $offering
     *                                              The course
     *
     * @return array
     *               An array of data about the course offering
     */
    public function getOfferingData(\osid_course_CourseOffering $offering)
    {
        $data = $this->osidDataLoader->getOfferingData($offering);
        $data['alternates'] = $this->osidDataLoader->getOfferingAlternates($offering);

        return $data;
    }
}
